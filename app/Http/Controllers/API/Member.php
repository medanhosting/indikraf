<?php

namespace App\Http\Controllers\API;

use File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

// Models
use App\Models\Profile;
use App\Models\Address;
use App\Models\Province;
use App\Models\City;
use App\Models\ProductRequest;
use App\Models\Product_image_request;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Rating;
use App\User;

class Member extends Controller
{
    protected $user,$user_id,$cart,$token;
    public function __construct()
    {
      try {
            JWTAuth::parseToken()->authenticate();
      }catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
          return response()->json(['errors'=> $e]);
      } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
         return $this->token['token_status']='invalid';
         return response()->json($this->token);
      } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
         return $this->token['token_status']='blacklisted';
      } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
         return response()->json($e);
      }

      $this->user = JWTAuth::toUser(JWTAuth::getToken());
      $this->middleware(function($request,$next){
        if($this->user->role_id!=2){
          return redirect('/api');
        }else {
          $this->user_id=$this->user->user_id;
          $this->user->profile=$this->user->profile;
        }
        return $next($request);
      });
    }

    public function index()
    {
        $province=Province::all();
        $address=Address::where('user_id',$this->user_id)->first();
        if (count($address)) {
          $mycity=City::find($address->city_id);
          $mycity->province;
        }else {
          $mycity=null;
        }

        $data = [
                  'user'=>$this->user,
                  'address'=>$address,
                  'mycity'=>$mycity,
                  'provincelists'=>$province
                ];
        return response()->json(compact('data'));
    }

    public function transaction()
    {
        $transaction=Cart::where([['status','1'],['buyer_id',$this->user_id]])->groupBy('transaction_id')->orderBy('created_at','desc')->paginate(10);
        foreach ($transaction as $t) {
          $t->transaction->shipping_address;
          $t->tanggal=$t->short_date_format();
        }
        $data = [
                  'transaction'=>$transaction
                ];
        return response()->json(compact('data'));
    }

    public function search_transaction(Request $r)
    {
        $order_id=$r->order_id;
        $status=$r->status;

        if ($status!="Semua Status") {
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
            $transactions=Cart::where([['status','1'],['buyer_id',$this->user_id]])
                                ->groupBy('transaction_id')
                                ->orderBy('created_at','desc')
                                ->paginate(10);
            $transactions->appends($r->only('status'))->links();
          }
        }

        if (count($transactions)!=0) {
          foreach ($transactions as $t) {
            $t->transaction->shipping_address;
            $t->tanggal=$t->short_date_format();
          }
        }

        $data = [
                  'transaction'=>$transactions
                ];

        return response()->json(compact('data'));
    }

    public function transaction_detail($order_id)
    {
        $transaction=Transaction::where('order_id',$order_id)->first();
        $transaction->tanggal=$transaction->short_date_format()." ".$transaction->time_format();
        $cart=Transaction::where('order_id',$order_id)->first()->cart;

        $shipping_address=Transaction::where('order_id',$order_id)->first()->shipping_address->address;
        $shipping_address->city=City::find($shipping_address->city_id);
        $shipping_address->city->province;

        foreach ($cart as $t) {
          $t->product->path=asset('uploads/gambar_produk/'.$t->product->seller->user_id."_".$t->product->seller->profile->first_name);

          foreach ($t->product->product_images as $pi) {
            $pi->product_image_name=$t->product->path."/produk".$t->product->product_id."/".$pi->product_image_name;
          }

          $t->total_weight=$t->amount*$t->product->weight;
        }
        $data = [
                  'transaction'=>$transaction,
                  'cart'=>$cart,
                  'shipping_address'=>$shipping_address
                ];
        return response()->json(compact('data'));
    }

    public function review()
    {
        $products=Cart::where([['status','1'],['buyer_id',$this->user_id]])->groupBy('product_id')->orderBy('created_at','desc')->paginate(10);
        foreach ($products as $p) {
            $p->product->path=asset('uploads/gambar_produk/'.$p->product->seller->user_id."_".$p->product->seller->profile->first_name);

            foreach ($p->product->product_images as $pi) {
              $pi->product_image_name=$p->product->path."/produk".$p->product->product_id."/".$pi->product_image_name;
            }

            if ($p->product->my_rating($this->user->user_id)=="0") {
              $p->is_rated="0";
              $p->my_rating=null;
            }else {
              $p->is_rated="1";
              $p->my_rating=$p->product->my_rating($this->user->user_id);
            }
        }
        $data = [
                  'products'=>$products
                ];
        return response()->json(compact('data'));
    }

    public function rating(Request $r)
    {
        $check=Rating::where([['product_id',$r->product_id],['user_id',$this->user_id]])->first();

        if ($check) {
          return response()->json(['error'=>'user sudah memberi rating untuk produk ini']);
        }else {
          $rating=Rating::create([
            'product_id'=>$r->product_id,
            'user_id'=>$this->user_id,
            'rating'=>$r->rating,
            'comments'=>$r->comments,
          ]);
          $data = [
                    'rating'=>$rating
                  ];
          return response()->json(compact('data'));
        }
    }

    public function profile_setting(Request $r)
    {
        if ($r->password!=NULL) {
          $validator=Validator::make($r->all(), [
              'password' => 'min:6|confirmed',
          ]);

          if ($validator->fails()){
            $errors=$validator->errors();
            return response()->json(['errors'=>$errors],400);
          }

          $u=User::find($this->user_id);
          $u->password=bcrypt($r->password);
          $u->update();
        }

        $profile=Profile::where('user_id',$this->user_id)->first();

        $validator=Validator::make($r->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'required|numeric|digits_between:10,20',
            'address' => 'required|min:6',
            'province' => 'nullable|numeric',
            'city' => 'nullable|numeric',
            'postal_code' => 'required|numeric|digits_between:5,6'
        ]);

        if ($validator->fails()){
           $errors=$validator->errors();
           return response()->json(['errors'=>$errors],400);
        }

        Profile::where('user_id',$this->user_id)->update([
          'first_name'=>$r->first_name,
          'last_name'=>$r->last_name,
          'gender'=>$r->gender
        ]);

        $address=Address::where('user_id',$this->user_id)->first();
        $check=count($address);
        if($check>0){
          $address->update([
            'first_name'=>$r->first_name,
            'last_name'=>$r->last_name,
            'address'=>$r->address,
            'city_id'=>$r->city,
            'postal_code'=>$r->postal_code,
            'phone'=>$r->phone
          ]);
        }else {
          $address=Address::create([
            'user_id'=>$this->user_id,
            'first_name'=>$r->first_name,
            'last_name'=>$r->last_name,
            'address'=>$r->address,
            'city_id'=>$r->city,
            'postal_code'=>$r->postal_code,
            'phone'=>$r->phone
          ]);
        }

        $profile->email=$this->user->email;
        $data = [
                  'profile'=>$profile,
                  'address'=>$address,
                ];

        return response()->json(compact('data'));
    }

    public function update_profile_image(Request $r)
    {
        $profile=Profile::where('user_id',$this->user_id)->first();

        $validator=Validator::make($r->all(), [
            'profile_image' => 'required|image',
        ]);

        if ($validator->fails()){
          $errors=$validator->errors();
          return response()->json(['errors'=>$errors],400);
        }

        $lokasi=public_path('/uploads/foto_profil/'.$profile->profile_image);
        $deleteFile=File::delete($lokasi);

        $file=$r->file('profile_image');
        $image_profile=sha1($profile->user_id).'.'.$file->getClientOriginalExtension();
        $lokasi=public_path('/uploads/foto_profil/');
        $file->move($lokasi,$image_profile);

        $profile->profile_image=$image_profile;
        $profile->update();

        $data = [
                  'profile'=>$profile,
                ];

        return response()->json(compact('data'));

    }
}
