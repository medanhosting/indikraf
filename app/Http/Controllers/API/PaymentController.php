<?php

namespace App\Http\Controllers\API;

use Auth;
use App\Http\Controllers\Controller;
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
  protected $user_id,$user,$cart=null;
  public function __construct()
  {
      if(JWTAuth::getToken()==false){
        $this->user_id=Session::getId();
        $this->cart=Cart::getContent();
        $this->token['code']='000';
        $this->token['token_status']='token is not exist';
      }else {
          try {
              JWTAuth::parseToken()->authenticate();
          }catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $refreshedToken['code']='123';
            $refreshedToken['token_status']='expired';
            $refreshedToken['new_token']=$this->token();
            return $this->token=$refreshedToken;
          } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
             $this->token['code']='321';
             $this->token['token_status']='invalid';
             return $this->token;
          } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
             return $this->token['token_status']='blacklisted';
          } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
             return response()->json($e);
          }

          $user=json_decode(JWTAuth::toUser(JWTAuth::getToken()));
          $this->user_id=$user->user_id;
          $this->user=User::find($this->user_id);
          $this->user->profile=$this->user->profile;
          $this->cart=$this->user->cart->where('status','0');
          $this->token['code']='200';
          $this->token['token_status']='token is valid';
        }
    }

    public function token(){
        $token=JWTAuth::getToken();

        if(!$token){
          return response()->json(['error'=>"Token is invalid"]);
        }

        try {
          $refreshedToken=JWTAuth::refresh($token);
        } catch (JWTException $e) {
          return response()->json(['error'=>"Something went wrong"]);
        }

        return $refreshedToken;
    }

    public function index(Request $r)
    {
        $address=Address::find($r->selected_address);

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
          'order_id'=>$order_id,
          'buyer_id'=>$this->user_id,
          'amount'=>$total_quantity,
          'payment_method'=>$payment_method,
          'courier'=>$r->courier,
          'courier_type'=>$r->courier_type,
          'shipping_price'=>$r->shipping_cost,
          'status'=>$status
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
        $shipping_address=Shipping_address::create([
          'order_id'=>$order_id,
          'address_id'=>$address_id,
        ]);

        $shipping_address->address;

        Mail::to($this->user->email)->queue(new Invoice($this->user,$transaction));
        $seller->notify(new NewOrder($transaction));

        $data = [
                  'transaction'=>$transaction,
                  'shipping_address'=>$shipping_address
                ];
        return response()->json(compact('data'));
    }
}
