<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Embed;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTException;
use Session;
use Cart;
use RajaOngkir;
use App\Notifications\Message;

// Models
use DB;
use App\User;
use App\Models\Product;
use App\Models\ProductRequest;
use App\Models\Category;
use App\Models\Cart as WebCart;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Video;
use App\Models\Faq;
use App\Models\Information;
use App\Models\Email;
use App\Models\Province;
use App\Models\City;
use App\Models\Address;
use App\Models\Store;

class HomeController extends Controller
{
    protected $user_id,$user,$cart=null;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if($request->header('api-key') && $request->header('api-key')=='sbaLrebyC71027001milasigerikzir'){
              return $next($request);
            }else {
              return response()->json(['error'=>'Nyari apa gan? web ini ga pake api'],500);
            }
        });

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

    public function get_cart()
    {
        return response()->json(['cart_value' => count($this->cart)]);
    }

    public function index()
    {
        $articles=Post::orderBy('updated_at','desc')->take(3)->get();

        foreach ($articles as $a) {
          $a->default_image=asset('uploads/gambar_artikel/'.$a->writer->user_id."_".$a->writer->profile->first_name."/artikel".$a->post_id."/".$a->default_image);
          $a->thumbnail=asset('uploads/gambar_artikel/'.$a->writer->user_id."_".$a->writer->profile->first_name."/artikel".$a->post_id."/".$a->thumbnail);
          $a->date=$a->date_format();
          $a->writer_name=$a->writer->profile->first_name." ".$a->writer->profile->last_name;
        }

        $newest_products=Product::where('stock','!=','0')->orderBy('created_at','desc')->take(4)->get();

        foreach ($newest_products as $p) {

          $p->path=asset('uploads/gambar_produk/'.$p->seller->user_id."_".$p->seller->profile->first_name);


          foreach ($p->product_images as $pi) {
            $pi->product_image_name=$p->path."/produk".$p->product_id."/".$pi->product_image_name;
          }

          $p->store;
          $p->rating=$p->rating();
        }

        $best_selling_products=Product::join('carts','carts.product_id','products.product_id')
                                ->where( DB::raw('MONTH(carts.created_at)'), '=', date('n') )
                                ->groupBy('products.product_id')
                                ->orderBy(DB::raw('SUM(amount)'),'desc')
                                ->get()->take(10);

        foreach ($best_selling_products as $p) {

          $p->path=asset('uploads/gambar_produk/'.$p->seller->user_id."_".$p->seller->profile->first_name);

          foreach ($p->product_images as $pi) {
            $pi->product_image_name=$p->path."/produk".$p->product_id."/".$pi->product_image_name;
          }

          $p->store;
          $p->rating=$p->rating();
        }

        $videos=Video::orderBy('updated_at','desc')->get();
        $embed=array();
        $x=0;
        foreach ($videos as $v) {
          if($x==2){
            break;
          }else {
            if(Embed::make($v->video_url)->parseUrl()==false){
              continue;
            }else {
              $video=explode("=",$v->video_url);
              // $embed[$x++]="https://www.youtube.com/embed/".$video[1];
              $embed[$x++]=array(
                'link'        => $video[1],
                'title'       => $v->title,
                'date'        => $v->date_format(),
                'description' => $v->description
              );
            }
          }
        }

        if ($this->token['code']=='200') {
          $status_profile=count($this->user->address)>0?1:0;
        }

        if ($this->token['code']=='200') {
          $data = [
                    'token'=>$this->token,
                    'newest_products'=>$newest_products,
                    'best_selling_products'=>$best_selling_products,
                    'articles'=>$articles,
                    'videos'=>$embed,
                    'profile_complete'=>$status_profile
                  ];
        }elseif($this->token['code']=='000') {
          $data = [
                    'token'=>$this->token,
                    'newest_products'=>$newest_products,
                    'best_selling_products'=>$best_selling_products,
                    'articles'=>$articles,
                    'videos'=>$embed
                  ];
        }

        return response()->json(compact('data'));
    }

    public function products(){
        $products=Product::where('stock','!=','0')->orderBy('created_at','desc')->paginate(10);
        foreach ($products as $p) {

          $p->path=asset('uploads/gambar_produk/'.$p->seller->user_id."_".$p->seller->profile->first_name);


          foreach ($p->product_images as $pi) {
            $pi->product_image_name=$p->path."/produk".$p->product_id."/".$pi->product_image_name;
            $p->rating=$p->rating();
          }

          $p->store;
        }
        $categories=Category::all();

        $data = [
                  'token'=>$this->token,
                  'products'=>$products,
                  'categories'=>$categories
                ];

        return response()->json(compact('data'));
    }

    public function search(Request $r){
        $search=preg_replace("#[^0-9a-z]#i"," ",$r->s);
        if ($r->has('category')) {
          if ($r->has('category')){
            if ($r->sort=="cheap") {
              $products=Product::where(function ($query) use ($search) {
                                    $query->where('product_name', 'LIKE', "%$search%")
                                          ->orWhere('description', 'LIKE', "%$search%");
                                })->where('category_id',$r->category)->orderBy('price')->paginate(10);
            }else {
              $products=Product::where(function ($query) use ($search) {
                                    $query->where('product_name', 'LIKE', "%$search%")
                                          ->orWhere('description', 'LIKE', "%$search%");
                                })->where('category_id',$r->category)->orderBy('price','desc')->paginate(10);
              $products->appends($r->only('s','category','sort'))->links();
            }
          }else {
            $products=Product::where(function ($query) use ($search) {
                                  $query->where('product_name', 'LIKE', "%$search%")
                                        ->orWhere('description', 'LIKE', "%$search%");
                              })->where('category_id',$r->category)->paginate(10);
            $products->appends($r->only('s','category'))->links();
          }
        }
        else if ($r->has('sort')) {
          if ($r->sort=="cheap") {
            $products=Product::where(function ($query) use ($search) {
                                  $query->where('product_name', 'LIKE', "%$search%")
                                        ->orWhere('description', 'LIKE', "%$search%");
                              })->orderBy('price')->paginate(10);
          }else {
            $products=Product::where(function ($query) use ($search) {
                                  $query->where('product_name', 'LIKE', "%$search%")
                                        ->orWhere('description', 'LIKE', "%$search%");
                              })->orderBy('price','desc')->paginate(10);
          }

          $products->appends($r->only('s','sort'))->links();
        }
        else {
          $products=Product::where(function ($query) use ($search) {
                                $query->where('product_name', 'LIKE', "%$search%")
                                      ->orWhere('description', 'LIKE', "%$search%");
                            })->paginate(10);

          $products->appends($r->only('s'))->links();
        }

        foreach ($products as $p) {

          $p->path=asset('uploads/gambar_produk/'.$p->seller->user_id."_".$p->seller->profile->first_name);


          foreach ($p->product_images as $pi) {
            $pi->product_image_name=$p->path."/produk".$p->product_id."/".$pi->product_image_name;
          }

          $p->store;
          $p->rating=$p->rating();
        }

        $data = [
                  'token'=>$this->token,
                  'products'=>$products,
                ];

        return response()->json(compact('data'));
    }

    public function getcast()
    {
        $categories=Category::all();
        $store_cities=Product::join('stores','products.store_id','stores.store_id')
                              ->groupBy('stores.store_city')
                              ->get();

        $cat = array(array());
        $i=0;
        foreach ($categories as $c) {
          $cat[$i]['category_id']=','.$c->category_id;
          $cat[$i]['category_name']=$c->category_name;
          $i++;
        }

        $cities=array(array());
        $i=0;
        foreach ($store_cities as $sc) {
          $cities[$i]['city_id']=','.$sc->store_city;
          $cities[$i]['city_name']=City::find($sc->store_city)->city;
          $i++;
        }

        $data = [
                  'token'=>$this->token,
                  'categories'=>$cat,
                  'store_cities'=>$cities
                ];

        return response()->json(compact('data'));
    }

    public function filter_products(Request $r)
    {
        $keyword=$r->keyword;
        $category_list=$r->category_list;
        $city_list=$r->city_list;
        $rating_list=$r->rating_list;
        $minimum_price=$r->minimum_price;
        $maximum_price=$r->maximum_price;

        if ($category_list!=NULL) {
            if ($city_list!=NULL) {
                if ($rating_list!=NULL) {
                    if ($keyword!=NULL) {
                      // echo "ada semua";
                      $products=Product::where(function ($query) use ($keyword) {
                                          $query->where('product_name', 'LIKE', "%$keyword%")
                                                ->orWhere('description', 'LIKE', "%$keyword%");
                                       })
                                       ->whereRaw('category_id in ('.$category_list.')')
                                       ->join('stores','stores.store_id','products.store_id')
                                       ->whereRaw('stores.store_city IN ('.$city_list.')')
                                       ->join('ratings','ratings.product_id','products.product_id')
                                       ->groupBy('ratings.product_id')
                                       ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                      // $products->appends($r->only('keyword','category_list','city_list','rating_list'))->links();
                    }else {
                      // echo "ada semua kecuali keyword";
                      $products=Product::whereRaw('category_id in ('.$category_list.')')
                                       ->join('stores','stores.store_id','products.store_id')
                                       ->whereRaw('stores.store_city IN ('.$city_list.')')
                                       ->join('ratings','ratings.product_id','products.product_id')
                                       ->groupBy('ratings.product_id')
                                       ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                    }
                }
                else{
                    if ($keyword!=NULL) {
                      // echo "ada keyword, kategori dan kota";
                      $products=Product::where(function ($query) use ($keyword) {
                                          $query->where('product_name', 'LIKE', "%$keyword%")
                                                ->orWhere('description', 'LIKE', "%$keyword%");
                                       })
                                       ->whereBetween('price',[$minimum_price,$maximum_price])
                                       ->whereRaw('category_id in ('.$category_list.')')
                                       ->join('stores','products.store_id','stores.store_id')
                                       ->whereRaw('stores.store_city IN ('.$city_list.')')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                    }
                    else {
                      // echo "ada kategori dan kota";
                      $products=Product::whereBetween('price',[$minimum_price,$maximum_price])
                                       ->whereRaw('category_id in ('.$category_list.')')
                                       ->join('stores','products.store_id','stores.store_id')
                                       ->whereRaw('stores.store_city IN ('.$city_list.')')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                    }
                }
            }
            else {
              if ($rating_list!=NULL) {
                  if ($keyword!=NULL) {
                    // echo "ada keyword, kategori dan rating";
                    $products=Product::where(function ($query) use ($keyword) {
                                        $query->where('product_name', 'LIKE', "%$keyword%")
                                              ->orWhere('description', 'LIKE', "%$keyword%");
                                     })
                                     ->whereBetween('price',[$minimum_price,$maximum_price])
                                     ->whereRaw('category_id in ('.$category_list.')')
                                     ->join('ratings','ratings.product_id','products.product_id')
                                     ->groupBy('ratings.product_id')
                                     ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                  }else {
                    // echo "ada kategori dan rating";
                    $products=Product::where(function ($query) use ($keyword) {
                                        $query->where('product_name', 'LIKE', "%$keyword%")
                                              ->orWhere('description', 'LIKE', "%$keyword%");
                                     })
                                     ->whereBetween('price',[$minimum_price,$maximum_price])
                                     ->whereRaw('category_id in ('.$category_list.')')
                                     ->join('ratings','ratings.product_id','products.product_id')
                                     ->groupBy('ratings.product_id')
                                     ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                  }
              }
              else{
                  if ($keyword!=NULL) {
                    // echo "ada keyword dan kategori";
                    $products=Product::where(function ($query) use ($keyword) {
                                        $query->where('product_name', 'LIKE', "%$keyword%")
                                              ->orWhere('description', 'LIKE', "%$keyword%");
                                     })
                                     ->whereBetween('price',[$minimum_price,$maximum_price])
                                     ->whereRaw('category_id in ('.$category_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                  }
                  else {
                    // echo "cuma ada kategori";
                    $products=Product::whereBetween('price',[$minimum_price,$maximum_price])
                                     ->whereRaw('category_id in ('.$category_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                  }
              }
            }
        }
        else {
          if ($city_list!=NULL) {
              if ($rating_list!=NULL) {
                  if ($keyword!=NULL) {
                    // echo "ada keyword, kota dan rating";
                    $products=Product::where(function ($query) use ($keyword) {
                                        $query->where('product_name', 'LIKE', "%$keyword%")
                                              ->orWhere('description', 'LIKE', "%$keyword%");
                                     })
                                     ->whereBetween('price', [$minimum_price, $maximum_price])
                                     ->join('stores','products.store_id','stores.store_id')
                                     ->whereRaw('stores.store_city IN ('.$city_list.')')
                                     ->join('ratings','ratings.product_id','products.product_id')
                                     ->groupBy('ratings.product_id')
                                     ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                  }else {
                    // echo "ada kota dan rating";
                    $products=Product::whereBetween('price', [$minimum_price, $maximum_price])
                                     ->join('stores','products.store_id','stores.store_id')
                                     ->whereRaw('stores.store_city IN ('.$city_list.')')
                                     ->join('ratings','ratings.product_id','products.product_id')
                                     ->groupBy('ratings.product_id')
                                     ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                  }
              }
              else{
                  if ($keyword!=NULL) {
                    // echo "cuma ada keyword dan kota";
                    $products=Product::where(function ($query) use ($keyword) {
                                        $query->where('product_name', 'LIKE', "%$keyword%")
                                              ->orWhere('description', 'LIKE', "%$keyword%");
                                     })
                                     ->whereBetween('price',[$minimum_price,$maximum_price])
                                     ->join('stores','products.store_id','stores.store_id')
                                     ->whereRaw('stores.store_city IN ('.$city_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                  }
                  else {
                    // echo "cuma ada kota";
                    $products=Product::whereBetween('price',[$minimum_price,$maximum_price])
                                     ->join('stores','products.store_id','stores.store_id')
                                     ->whereRaw('stores.store_city IN ('.$city_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                  }
              }
          }
          else {
            if ($rating_list!=NULL) {
                if ($keyword!=NULL) {
                  // echo "ada rating dan keyword";
                  $products=Product::whereBetween('price', [$minimum_price, $maximum_price])
                                   ->join('ratings','ratings.product_id','products.product_id')
                                   ->groupBy('ratings.product_id')
                                   ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                   ->orderBy('products.created_at','desc')
                                   ->paginate(12);
                }else {
                  // echo "cuma ada rating";
                  $products=Product::whereBetween('price', [$minimum_price, $maximum_price])
                                   ->join('ratings','ratings.product_id','products.product_id')
                                   ->groupBy('ratings.product_id')
                                   ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                   ->orderBy('products.created_at','desc')
                                   ->paginate(12);
                }
            }
            else{
                if ($keyword!=NULL) {
                  // echo "hapus filter tapi masih ada keyword";
                  $products=Product::where(function ($query) use ($keyword) {
                                      $query->where('product_name', 'LIKE', "%$keyword%")
                                            ->orWhere('description', 'LIKE', "%$keyword%");
                                   })
                                   ->whereBetween('price', [$minimum_price, $maximum_price])
                                   ->orderBy('created_at','desc')
                                   ->paginate(12);
                }
                else {
                  // echo "hapus filter";
                  $products=Product::whereBetween('price', [$minimum_price, $maximum_price])
                                   ->orderBy('created_at','desc')
                                   ->where('stock','<',0)
                                   ->paginate(12);
                }
            }
          }
        }

        if (count($products)) {
          $categories=Category::all();
          foreach ($products as $p) {
              $p->path=asset('uploads/gambar_produk/'.$p->seller->user_id."_".$p->seller->profile->first_name);


              foreach ($p->product_images as $pi) {
                $pi->product_image_name=$p->path."/produk".$p->product_id."/".$pi->product_image_name;
              }

              $p->store;
              $p->rating=$p->rating();
          }
          $data = [
                    'token'=>$this->token,
                    'products'=>$products
                  ];
        }else {
          $data = [
                    'token'=>$this->token,
                    'products'=>$products
                  ];
        }

        return response()->json(compact('data'));
    }

    public function detail_product($product_id)
    {
        $detail_product=Product::find($product_id);
        $related_product=Product::where([['category_id',$detail_product->category_id],['product_id','!=',$detail_product->product_id]])->inRandomOrder()->take(5)->get();

        $detail_product->path=asset('uploads/gambar_produk/'.$detail_product->seller->user_id."_".$detail_product->seller->profile->first_name);

        foreach ($detail_product->product_images as $pi) {
          $pi->product_image_name=$detail_product->path."/produk".$detail_product->product_id."/".$pi->product_image_name;
        }

        $detail_product->store;
        $detail_product->review;

        foreach ($detail_product->review as $r) {
          $r->name=$r->user->profile->first_name." ".$r->user->profile->last_name;
          $r->user=null;
        }

        $detail_product->rating=$detail_product->rating();
        $detail_product->store->city=City::find($detail_product->store->store_city);
        $detail_product->store->city->province;

        foreach ($related_product as $p) {
          $p->path=asset('uploads/gambar_produk/'.$p->seller->user_id."_".$p->seller->profile->first_name);

          foreach ($p->product_images as $pi) {
            $pi->product_image_name=$p->path."/produk".$p->product_id."/".$pi->product_image_name;
          }

          $p->store;
          $p->rating=$p->rating();
        }

        $data = [
                  'token'=>$this->token,
                  'detail_product'=>$detail_product,
                  'product_id'=>$product_id,
                  'related_product'=>$related_product
                ];


      return response()->json(compact('data'));
    }

    public function add_to_cart(Request $r)
    {
        $product=Product::find($r->product_id);

        if($this->token['code']=="200"){
          $check_item=WebCart::where([['product_id',$r->product_id],['buyer_id',$this->user_id],['status',0]])->first();

          $price=$product->price;
          $total_price=$price*$r->amount;

          if(count($check_item)==0)
          {
            $cart=WebCart::create([
              'buyer_id'=>$this->user_id,
              'product_id'=>$r->product_id,
              'price'=>$price,
              'amount'=>$r->amount,
              'total_price'=>$total_price,
            ]);
          }else{
            $cart_id=$check_item->cart_id;
            $cart=WebCart::find($cart_id);
            $cart->amount+=$r->amount;
            $cart->total_price+=$total_price;
            $cart->update();
          }
        }else if($this->token['code']=="000"){
          // if(count(Cart::get($r->product_id))>0){
          //   $cart=Cart::update($r->product_id, array(
          //     'quantity' => $r->amount,
          //   ));
          // }else {
          //   $cart=Cart::add(array(
          //           'id' => $r->product_id,
          //           'name' => $product->product_name,
          //           'price' => $product->price,
          //           'quantity' => $r->amount,
          //           'attributes' => array()
          //         ));
          // }
          return response()->json(['status'=>'simpan data keranjang di database android']);
        }else {
          return response()->json(['error'=>'ada masalah dengan token']);
        }

        $data = [
                  'token'=>$this->token,
                  'cart'=>$this->cart,
                ];

        return response()->json(compact('data'));
    }

    public function shopping_cart(){
        $products=Product::take(4)->get();
        foreach ($products as $p) {

          $p->path=asset('uploads/gambar_produk/'.$p->seller->user_id."_".$p->seller->profile->first_name);


          foreach ($p->product_images as $pi) {
            $pi->product_image_name=$p->path."/produk".$p->product_id."/".$pi->product_image_name;
          }

          $p->store;
        }

        $cart=WebCart::join('products','products.product_id','carts.product_id')
                  ->where([['buyer_id',$this->user_id],['status','0']])
                  ->orderBy('products.store_id')
                  ->get();

        if (count($cart)) {
          $store_id=array();
          $cart2=array(array());
          $msa=1;
          $ar=0;
          foreach ($cart as $p) {
              if (array_search($p->store_id,$store_id)==false) {
                $store_id[$msa]=$p->store_id;
                $cart2[$ar]['store_id']=$p->store_id;
                $cart2[$ar]['store_name']=$p->product->store->store_name;
                $cart2[$ar]['store_city']=$p->product->store->city->city;
                $cart2[$ar]['total_product']=$cart->where('store_id',$p->store_id)->count('product_id');
                $cart2[$ar]['total_price']=$cart->where('store_id',$p->store_id)->sum('total_price');
                $ar++;
                $msa++;
              }
          }

          $data = [
                    'token'=>$this->token,
                    'cart'=>$cart2
                  ];
        }else {
          $data = [
                    'token'=>$this->token,
                    'cart'=>[],
                    'products'=>$products
                  ];
        }

        return response()->json(compact('data'));
    }

    public function detail_cart($store_id)
    {
      if ($this->token['code']=="200") {
        $cart=WebCart::join('products','products.product_id','carts.product_id')
                  ->where([['buyer_id',$this->user_id],['status','0'],['store_id',$store_id]])
                  ->orderBy('products.store_id')
                  ->get();
      }else {
        return response()->json(['error'=>'user belum login atau ada masalah di token']);
      }

      foreach ($cart as $p) {
          $p->product->path=asset('uploads/gambar_produk/'.$p->product->seller->user_id."_".$p->product->seller->profile->first_name);

          foreach ($p->product->product_images as $pi) {
            $pi->product_image_name=$p->product->path."/produk".$p->product->product_id."/".$pi->product_image_name;
          }

          $p->total_weight=$p->amount*$p->product->weight;
      }

      $data = [
                'token'=>$this->token,
                'cart'=>$cart
              ];
      return response()->json(compact('data'));
    }

    public function update_cart(Request $r){
        if($this->token['code']=="200")
        {
          $cart=WebCart::join('products','products.product_id','carts.product_id')
                    ->where([['buyer_id',$this->user_id],['status','0']])
                    ->orderBy('products.store_id')
                    ->get();

          foreach ($cart as $c) {
            if ($c->product->product_id==$r->product_id) {
              if ($r->quantity<=$c->product->stock) {
                if ($r->quantity==0) {
                  $c->delete();
                }else {
                  $c->amount=$r->quantity;
                  $c->total_price=$c->price*$c->amount;
                  $c->update();
                }
              }else {
                $error['code']='uc000';
                $error['desc']='stock tidak cukup';
                return response()->json(compact('error'));
              }
            }
            // if(array_key_exists($c->product->product_id, $r->quantity))
            // {
            //
            //   if($r->quantity[$c->product->product_id]<=$product=$c->product->stock){
            //     if ($r->quantity[$c->product->product_id]==0) {
            //       $c->delete();
            //     }else {
            //       $c->amount=$r->quantity[$c->product->product_id];
            //       $c->total_price=$c->price*$c->amount;
            //       $c->update();
            //     }
            //   }
            // }
          }
        }else if($this->token['code']=="000"){
          return response()->json(['error'=>'pengaturan cart ada di database android']);
        }else {
          return response()->json(['error'=>'ada masalah dengan token']);
        }

        $data = [
                  'token'=>$this->token,
                  'cart'=>$this->cart
                ];

        return response()->json(compact('data'));
    }

    public function checkout($store_id)
    {
        if ($this->token['code']=="200") {
          $cart=WebCart::join('products','products.product_id','carts.product_id')
                    ->where([['buyer_id',$this->user_id],['status','0'],['store_id',$store_id]])
                    ->orderBy('products.store_id')
                    ->get();

          $address=Address::where('user_id',$this->user_id)->first();

          if (count($address)) {
            $address->city=City::find($address->city_id);
            $address->city->province;
          }else {
            $address=null;
          }

          $store=Store::find($store_id);
        }else {
          return response()->json(['error'=>'user belum login atau ada masalah di token']);
        }

        foreach ($cart as $p) {
            $p->product->path=asset('uploads/gambar_produk/'.$p->product->seller->user_id."_".$p->product->seller->profile->first_name);

            foreach ($p->product->product_images as $pi) {
              $pi->product_image_name=$p->product->path."/produk".$p->product->product_id."/".$pi->product_image_name;
            }

            $p->total_weight=$p->amount*$p->product->weight;
        }

        $data = [
                  'token'=>$this->token,
                  'cart'=>$cart,
                  'address'=>$address,
                  'store'=>$store
                ];

        return response()->json(compact('data'));
    }

    public function getProvince()
    {
        $province=Province::all();
        $data = [
                  'provincelists'=>$province
                ];
        return response()->json(compact('data'));
    }

    public function add_address(Request $r)
    {
        $address=Address::create([
          'user_id'=>$this->user_id,
          'first_name'=>$r->first_name,
          'last_name'=>$r->last_name,
          'address'=>$r->address,
          'city_id'=>$r->city,
          'postal_code'=>$r->postal_code,
          'phone'=>$r->phone,
        ]);

        $data = [
                  'token'=>$this->token,
                  'address'=>$address
                ];

        return response()->json(compact('data'));
    }

    public function register_store(Request $r){
        if($r->type=="Image"){
          $file=$r->file('file');
          $product_image_name=time().'.'.$file->getClientOriginalExtension();
          $lokasi=public_path('/uploads/gambar_request_produk/');
          $file->move($lokasi, $product_image_name);
          $url='/uploads/gambar_request_produk/'.$product_image_name;
        }else {
          $url=$r->url;
        }
        $product_request=ProductRequest::create([
            'email'=>$r->email,
            'phone'=>$r->phone,
            'type'=>$r->type,
            'url'=>$url,
        ]);

        $data = [
                  'token'=>$this->token,
                  'product_request'=>$product_request
                ];
        return response()->json(compact('data'));
    }

    public function articles(){
        $articles=Post::orderBy('updated_at','desc')->paginate(5);
        foreach ($articles as $a) {
          $a->default_image=asset('uploads/gambar_artikel/'.$a->writer->user_id."_".$a->writer->profile->first_name."/artikel".$a->post_id."/".$a->default_image);
          $a->thumbnail=asset('uploads/gambar_artikel/'.$a->writer->user_id."_".$a->writer->profile->first_name."/artikel".$a->post_id."/".$a->thumbnail);
          $a->date=$a->date_format();
          $a->post=str_limit(strip_tags($a->post), $limit = 100, $end = '...');
          $a->writer_name=$a->writer->profile->first_name." ".$a->writer->profile->last_name;
        }

        $data = [
                  'token'=>$this->token,
                  'articles'=>$articles
                ];

        return response()->json(compact('data'));
    }

    public function load_articles(Request $r){
        $articles=Post::orderBy('updated_at','desc')->paginate(5);

        foreach ($articles as $a) {
          $a->default_image=asset('uploads/gambar_artikel/'.$a->writer->user_id."_".$a->writer->profile->first_name."/artikel".$a->post_id."/".$a->default_image);
        }

        $data = [
                  'token'=>$this->token,
                  'articles'=>$articles
                ];

        return response()->json(compact('data'));
    }

    public function article_details($article_id){
        $article=Post::find($article_id);

        $article->default_image=asset('uploads/gambar_artikel/'.$article->writer->user_id."_".$article->writer->profile->first_name."/artikel".$article->post_id."/".$article->default_image);
        $article->thumbnail=asset('uploads/gambar_artikel/'.$article->writer->user_id."_".$article->writer->profile->first_name."/artikel".$article->post_id."/".$article->thumbnail);
        $article->date=$article->date_format();
        $article->writer_name=$article->writer->profile->first_name." ".$article->writer->profile->last_name;
        $related_article=Post::take(4)->get();

        foreach ($related_article as $a) {
          $a->default_image=asset('uploads/gambar_artikel/'.$a->writer->user_id."_".$a->writer->profile->first_name."/artikel".$a->post_id."/".$a->default_image);
          $a->thumbnail=asset('uploads/gambar_artikel/'.$a->writer->user_id."_".$a->writer->profile->first_name."/artikel".$a->post_id."/".$a->thumbnail);
          $a->date=$a->date_format();
		      $a->post=str_limit(strip_tags($a->post), $limit = 100, $end = '...');
          $a->writer_name=$a->writer->profile->first_name." ".$a->writer->profile->last_name;
        }

        $comments=Comment::where([['post_id',$article_id],['status','1']])->orderBy('created_at','desc')->paginate(1);
        foreach ($comments as $a) {
          $a->date=$a->date_format();
        }

        $data = [
                  'token'=>$this->token,
                  'article'=>$article,
                  'comments'=>$comments,
                  'related_article'=>$related_article
                ];

        return response()->json(compact('data'));
    }

    public function comment(Request $r){
        $comment=Comment::create([
          'post_id'=>$r->post_id,
          'name'=>$r->name,
          'email'=>$r->email,
          'comment'=>$r->comment
        ]);

        $data = [
                  'token'=>$this->token,
                  'comment'=>$comment,
                ];
        return response()->json(compact('data'));
    }

    public function gallery(){
        $images=Image::paginate(12);

        foreach ($images as $i) {
          $i->image_path=asset('uploads/gallery/'.$i->category->image_category_id."_".$i->category->image_category_name."/".$i->image_path);
        }

        $data = [
                  'token'=>$this->token,
                  'images'=>$images
                ];

        return response()->json(compact('data'));
    }

    public function load_gallery(Request $r){
        $images=Image::paginate(8);

        foreach ($images as $i) {
          $i->image_path=asset('uploads/gallery/'.$i->category->image_category_id."_".$i->category->image_category_name."/".$i->image_path);
        }

        $data = [
                  'token'=>$this->token,
                  'images'=>$images,
                ];

        return response()->json(compact('data'));
    }

    public function video(){
        $getvideos=Video::paginate(6);
        $videos=array();
        $x=0;

        foreach ($getvideos as $v) {
          if(Embed::make($v->video_url)->parseUrl()==false){
            continue;
          }else {
            $video=explode("=",$v->video_url);
            // $videos[$x++]="https://www.youtube.com/embed/".$video[1];
            $videos[$x++]=array(
              'link'        => $video[1],
              'title'       => $v->title,
              'date'        => $v->date_format(),
              'description' => $v->description
            );
          }
        }

        $data = [
                  'token'=>$this->token,
                  'videos'=>$videos
                ];

        return response()->json(compact('data'));
    }

    public function faq()
    {
        $faq=Faq::all();

        $data = [
                  'token'=>$this->token,
                  'faq'=>$faq
                ];

        return response()->json(compact('data'));
    }

    public function about(){
        $information=Information::find(1);

        $data = [
                  'token'=>$this->token,
                  'information'=>$information
                ];

        return response()->json(compact('data'));
    }

    public function contact(Request $r){
        $name=$r->name;
        $email=$r->email;
        $title=$r->title;
        $messagetoAdmin=$r->message;

        $user=User::where('role_id',1)->get();
        foreach ($user as $u) {
          $u->notify(new Message($name,$email,$title,$messagetoAdmin));

          \Mail::send('emails.blast_email', ['user'=>$u,'text'=>$messagetoAdmin], function ($message) use ($title,$email,$u){
              $message->from($email);
              $message->subject($title);
              $message->to($u->email);
          });
        }

        $data = [
                  'token'=>$this->token,
                  'status_message'=>'success'
                ];

        return response()->json(compact('data'));
    }

    public function subscribe(Request $r)
    {
        $email=Email::create([
          'name'=>$r->name,
          'email'=>$r->email,
        ]);
        $data = [
                  'token'=>$this->token,
                  'email'=>$email
                ];

        return response()->json(compact('data'));
    }

    public function getCity($province_id)
    {
        $cities=City::where('province_id',$province_id)->get();
        $data = [
                  'cities'=>$cities
                ];
        return response()->json(compact('data'));
    }

    public function getCourier(Request $r)
    {
        $origin=$r->origin;
        $destination=$r->destination;
        $weight=$r->weight;
        $couier=$r->layanan;

        $courier_data = RajaOngkir::Cost([
          'origin'        => $origin, // id kota asal
          'destination'   => $destination, // id kota tujuan
          'weight'        => $weight, // berat satuan gram
          'courier'       => $courier, // kode kurir pengantar ( jne / tiki / pos )
        ])->get();

        $data = [
                  'courier_data'=>$courier_data
                ];
        return response()->json(compact('data'));
    }

    public function getShippingPrice(Request $r)
    {
        $origin=$r->origin;
        $destination=$r->destination;
        $weight=$r->weight;
        $couier=$r->layanan;

        $courier_data = RajaOngkir::Cost([
          'origin'        => $origin, // id kota asal
          'destination'   => $destination, // id kota tujuan
          'weight'        => $weight, // berat satuan gram
          'courier'       => $courier, // kode kurir pengantar ( jne / tiki / pos )
        ])->get();

        $price=(ceil($courier_data[0]['costs'][$r->tipe]['cost'][0]['value']/1000))*1000;

        $data = [
                  'price'=>$price
                ];

        return response()->json(compact('data'));
    }
}
