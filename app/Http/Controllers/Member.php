<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


// Models
use App\User;
use App\Models\Profile;
use App\Models\Address;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Rating;
use App\Models\Province;

class Member extends Controller
{
    protected $user_id;
    protected $user;
    protected $cart;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function($request,$next){
          app()->setLocale(Session::get('locale'));
          if(Auth::user()->role->role_name!="Member"){
            return redirect('/');
          }else{
            $this->user_id=Auth::user()->user_id;
            $this->user=Auth::user();
            $this->user->profile=Auth::user()->profile;
            $this->user->address=Auth::user()->address;
            $this->cart=$this->user->cart->where('status','0');
            return $next($request);
          }
        });
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $province=Province::all();
        return view('Member.index',['user'=>$this->user,'province'=>$province,'cart'=>$this->cart]);
    }

    public function transaction()
    {
        $transactions=Cart::where([['status','1'],['buyer_id',$this->user_id]])->groupBy('transaction_id')->orderBy('created_at','desc')->paginate(10);

        foreach ($transactions as $t) {
          $t->transaction->shipping_address;
		      $t->tanggal=$t->date_format();
        }

        return view('Member.transaction',['user'=>$this->user,'cart'=>$this->cart,'transactions'=>$transactions]);
    }

    public function search_transaction(Request $r)
    {
        $order_id=$r->order_id;
        $status=$r->status;
        $english_status=array(
          1 => 'Pending',
          2 => 'Payment Received',
          3 => 'Processed',
          4 => 'Shipped',
          5 => 'Success',
          6 => 'Canceled',
        );

        $indo_status=array(
          1 => 'Menunggu Pembayaran',
          2 => 'Pembayaran Diterima',
          3 => 'Barang Diproses',
          4 => 'Barang Dikirim',
          5 => 'Selesai',
          6 => 'Dibatalkan',
        );

        if (array_search($status,$english_status)) {
          $status=$indo_status[array_search($status,$english_status)];
        }

        if ($status!="0") {
          if ($order_id) {
            $transactions=Cart::join('transactions','carts.transaction_id', '=', 'transactions.transaction_id')
                                ->groupBy('transactions.order_id')
                                ->where([
                                    ['transactions.buyer_id',$this->user_id],
                                    ['order_id', '=', $order_id],
                                    ['transactions.status',$status],
                                ])
                                ->orderBy('transactions.created_at','desc')
                                ->paginate(10);
            $transactions->appends($r->only('order_id','status'))->links();
          }else {
            $transactions=Cart::join('transactions','carts.transaction_id', '=', 'transactions.transaction_id')
                                ->groupBy('transactions.order_id')
                                ->where([
                                  ['transactions.buyer_id',$this->user_id],
                                  ['transactions.status',$status]
                                ])
                                ->orderBy('transactions.created_at','desc')
                                ->paginate(10);
            $transactions->appends($r->only('order_id','status'))->links();
          }
        }else {
          if ($order_id) {
            $transactions=Cart::join('transactions','carts.transaction_id', '=', 'transactions.transaction_id')
                                ->groupBy('transactions.order_id')
                                ->where([
                                  ['transactions.buyer_id',$this->user_id],
                                  ['order_id', '=', $order_id]
                                ])
                                ->orderBy('transactions.created_at','desc')
                                ->paginate(10);
            $transactions->appends($r->only('order_id','status'))->links();
          }else {
            // $transactions=Cart::where([['status','1'],['buyer_id',$this->user_id]])
            //                     ->groupBy('transaction_id')
            //                     ->orderBy('created_at','desc')
            //                     ->paginate(10);
            // $transactions->appends($r->only('status'))->links();
            return redirect('/member/transaction');
          }
        }

        return view('Member.transaction',['user'=>$this->user,'cart'=>$this->cart,'transactions'=>$transactions]);
    }

    public function transaction_detail($order_id)
    {
        $transaction=Transaction::where('order_id',$order_id)->first()->cart;

        return view('Member.transaction_detail',['user'=>$this->user,'cart'=>$this->cart,'transaction'=>$transaction]);
    }

    public function review()
    {
        $products=Cart::where([['status','1'],['buyer_id',$this->user_id]])->groupBy('product_id')->orderBy('created_at','desc')->paginate(10);
        return view('Member.review',['user'=>$this->user,'cart'=>$this->cart,'products'=>$products]);
    }

    public function rating(Request $r)
    {
      $check=Rating::where([['product_id',$r->product_id],['user_id',$this->user_id]])->first();
      if ($check) {
        return redirect(redirect()->getUrlGenerator()->previous());
      }else {
        Rating::create([
          'product_id'=> $r->product_id,
          'user_id'   => $this->user_id,
          'rating'    => $r->rating,
          'comments'  => $r->comments,
        ]);

        return redirect(redirect()->getUrlGenerator()->previous());
      }
    }

    public function profile_setting(Request $r)
    {
      if ($r->password!=NULL) {
        $this->validate($r, [
            'password' => 'min:6|confirmed',
        ]);

        $u=User::find($this->user_id);
        $u->password=bcrypt($r->password);
        $u->update();
      }

      $profile=Profile::where('user_id',$this->user_id)->first();

      if ($r->file('profile_image')!=NULL) {
        $this->validate($r, [
            'profile_image' => 'image',
        ]);

        $lokasi=public_path('/uploads/foto_profil/'.$profile->profile_image);
        $deleteFile=File::delete($lokasi);

        $file=$r->file('profile_image');
        $image_profile=sha1($profile->user_id).'.'.$file->getClientOriginalExtension();
        $lokasi=public_path('/uploads/foto_profil/');
        $file->move($lokasi,$image_profile);

        $profile->profile_image=$image_profile;
        $profile->update();
      }

      $this->validate($r, [
        'first_name'  => 'required|string|max:255',
        'last_name'   => 'required|string|max:255',
        'gender'      => 'required|string',
        'email'       => 'required|string|email|max:255',
        'phone'       => 'required|numeric|digits_between:10,20',
        'address'     => 'required|min:6',
        'province'    => 'required|numeric',
        'city'        => 'required|numeric',
        'postal_code' => 'required|numeric|digits_between:5,6'
      ]);

      Profile::where('user_id',$this->user_id)->update([
        'first_name'  => $r->first_name,
        'last_name'   => $r->last_name,
        'gender'      => $r->gender
      ]);
      $address=Address::where('user_id',$this->user_id)->first();
      $check=count($address);
      if($check>0){
        $address->update([
          'first_name'  => $r->first_name,
          'last_name'   => $r->last_name,
          'address'     => $r->address,
          'city_id'     => $r->city,
          'postal_code' => $r->postal_code,
          'phone'       => $r->phone
        ]);
      }else {
        Address::create([
          'user_id'     => $this->user_id,
		      'first_name'  => $r->first_name,
          'last_name'   => $r->last_name,
          'address'     => $r->address,
          'city_id'     => $r->city,
          'postal_code' => $r->postal_code,
          'phone'       => $r->phone
        ]);
      }

      return redirect(redirect()->getUrlGenerator()->previous());
    }
}
