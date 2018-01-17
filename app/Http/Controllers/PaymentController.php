<?php

namespace App\Http\Controllers;

use Auth;
use RajaOngkir;
use Illuminate\Http\Request;
use App\Mail\Invoice;

use Session;
use Mail;
use App\Notifications\NewOrder;

// Models
use App\Models\Cart;
use App\Models\Product;
use App\Models\Address;
use App\Models\Transaction;
use App\Models\Shipping_address;

class PaymentController extends Controller
{
    protected $user_id,$user;
    public function __construct()
    {
        $this->middleware(function($request,$next){
          if(Auth::check()){
              if(Auth::user()->role->role_name=="Admin"){
                return redirect('/admin');
              }else {
                $this->user_id=Auth::user()->user_id;
                $this->user=Auth::user();
                $this->user->profile=$this->user->profile;
              }
          }else {
            return redirect('/');
          }
          return $next($request);
        });

        // Veritrans::$serverKey = 'VT-server-VDNwHZpuBPJQ_YhlikY_3Nkq';
        // Veritrans::$isProduction = false;
    }

    public function index(Request $r)
    {
        if ($r->selected_address=="new") {
          $address=Address::create([
            'user_id'     => $this->user_id,
            'first_name'  => $r->first_name,
            'last_name'   => $r->last_name,
            'address'     => $r->address,
            'city_id'     => $r->city,
            'postal_code' => $r->postal_code,
            'phone'       => $r->phone,
          ]);
        }else {
          $address=Address::find($r->selected_address);
        }

        $total_quantity=0;
        $total_weight=0;
        $total_price=0;

        $cart=Cart::join('products','products.product_id','carts.product_id')
                  ->where([['buyer_id',$this->user_id],['status','0'],['store_id',$r->store_id]])
                  ->orderBy('products.store_id')
                  ->get();

        $er_lin = array();
        foreach ($cart as $c) {
          if($c->product->stock<$c->amount){
            if ($c->product->stock==0) {
              $c->delete();
            }else {
              $c->amount=$c->product->stock;
              $c->total_price=$c->amount*$c->product->price;
              $c->update();
            }
            $er_lin[$c->product_id]='Stok produk '.$c->product->product_name.' kurang dari permintaan Anda, kami sudah mengurangi keranjang Anda';
          }
        }

        if (count($er_lin)) {
          Session::flash('er_lin',$er_lin);
          return redirect(redirect()->getUrlGenerator()->previous());
        }

        foreach ($cart as $c) {
          $total_quantity+=$c->amount;
          $total_weight+=$c->product->weight;
          $total_price+=$c->product->price*$c->amount;
        }

        $order_id=$this->user_id.date("Ymdhis");
        $payment_method="COD";
        $status='Menunggu Pembayaran';

        $transaction=Transaction::create([
          'order_id'        => $order_id,
          'buyer_id'        => $this->user_id,
          'amount'          => $total_quantity,
          'payment_method'  => $payment_method,
          'courier'         => $r->courier,
          'courier_type'    => $r->courier_type,
          'shipping_price'  => $r->shipping_cost,
          'status'          => $status
        ]);

        foreach ($cart as $c) {
          $c->transaction_id=$transaction->transaction_id;
          $c->status=1;
          $c->update();

          $c->product->stock-=$c->amount;
          $c->product->update();

          $seller=$c->product->seller;
        }

        $address_id=$address->address_id;
        Shipping_address::create([
          'order_id'   => $order_id,
          'address_id' => $address_id,
        ]);

        Mail::to($this->user->email)->queue(new Invoice($this->user,$transaction));
        $seller->notify(new NewOrder($transaction));

        return redirect(url('/member/transaction_detail/'.$order_id));
    }
}
