<?php

namespace App\Http\Controllers;

use Auth;
use Cart;
use Embed;
use Session;
use RajaOngkir;
use Illuminate\Http\Request;
use App\Notifications\CommentArticle;
use App\Notifications\NewStoreRegister;
use App\Notifications\Message;

// Models
use DB;
use App\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart as WebCart;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Video;
use App\Models\Faq;
use App\Models\Information;
use App\Models\Email;
use App\Models\ProductRequest;
use App\Models\Province;
use App\Models\Meta;
use App\Models\Message as MessageModel;

class HomeController extends Controller
{
    protected $user_id,$user,$cart=null;
    public function __construct()
    {
        $this->middleware(function($request,$next){
          app()->setLocale(Session::get('locale'));
          if(Auth::check()){
              if(Auth::user()->role->role_name=="Admin"){
                return redirect('/admin');
              }else {
                $this->user_id=Auth::user()->user_id;
                $this->user=Auth::user();
                $this->user->profile=$this->user->profile;
                $this->cart=$this->user->cart->where('status','0');
              }
          }else {
            $this->user_id=Session::getId();
            $this->cart=Cart::getContent();
          }
          return $next($request);
        });
        if (count(DB::table('visitlogs')->where('ip',\Request::ip())->whereDate('created_at',\Carbon\Carbon::today()->toDateString())->get())<1) {
          \VisitLog::save();
        }
    }

    public function index()
    {
        $articles=Post::orderBy('updated_at','desc')->take(3)->get();

        $newest_products=Product::where('stock','!=','0')->orderBy('created_at','desc')->take(8)->get();

        $best_selling_products=Product::join('carts','carts.product_id','products.product_id')
                                ->where('status','1')
                                ->whereYear('carts.updated_at', '=', date('Y'))
                                ->whereMonth('carts.updated_at', '=', date('m'))
                                ->groupBy('products.product_id')
                                ->orderBy(DB::raw('SUM(amount)'),'desc')
                                ->get()->take(10);

        $videos=Video::orderBy('created_at','desc')->take(2)->get();
        $skip=array();
        $x=0;

        foreach ($videos as $v) {
          if(Embed::make($v->video_url)->parseUrl()==false){
            $skip[$x++]=$v->video_id;
          }else {
            $video=explode("=",$v->video_url);
            $v->video_url="https://www.youtube.com/embed/".$video[1];
            $v->thumbnail="https://img.youtube.com/vi/".$video[1]."/mqdefault.jpg";
          }
        }

        $meta=Meta::find(1);
        if(Auth::check()){
          $data=['user'=>$this->user,'newest_products'=>$newest_products,'best_selling_products'=>$best_selling_products,'cart'=>$this->cart,'articles'=>$articles,'videos'=>$videos,'skip'=>$skip,'meta'=>$meta];
        }else{
          $data=['newest_products'=>$newest_products,'best_selling_products'=>$best_selling_products,'cart'=>$this->cart,'articles'=>$articles,'videos'=>$videos,'skip'=>$skip,'meta'=>$meta];
        }

        return view('index',$data);
    }

    public function language_chooser(Request $r)
    {
        Session::put('locale',$r->lang);
        return redirect(url()->previous());
    }

    public function products(){
        $products=Product::where('stock','!=','0')->orderBy('created_at','desc')->paginate(12);
        $categories=Category::all();

        $store_cities=Product::join('stores','products.store_id','stores.store_id')
                              ->groupBy('stores.store_city')
                              ->get();

        $meta=Meta::find(2);

        if(Auth::check()){
          $data=['user'=>$this->user,'products'=>$products,'cart'=>$this->cart,'categories'=>$categories,'stores'=>$store_cities,'meta'=>$meta];
        }else{
          $data=['products'=>$products,'cart'=>$this->cart,'categories'=>$categories,'stores'=>$store_cities,'meta'=>$meta];
        }

        return view('products',$data);
    }

    public function search(Request $r)
    {
      $search=preg_replace("#[^0-9a-z]#i"," ",$r->s);
      $products=Product::where(function ($query) use ($search) {
                            $query->where('product_name', 'LIKE', "%$search%")
                                  ->orWhere('description', 'LIKE', "%$search%");
                        })->paginate(4);

      $articles=Post::where(function ($query) use ($search) {
                            $query->where('title', 'LIKE', "%$search%")
                                  ->orWhere('post', 'LIKE', "%$search%");
                        })->paginate(2);

      $images=Image::where(function ($query) use ($search) {
                            $query->where('image_name', 'LIKE', "%$search%")
                                  ->orWhere('description', 'LIKE', "%$search%")
                                  ->orWhere('tooltip', 'LIKE', "%$search%");
                        })->paginate(4);

      $videos=Video::where(function ($query) use ($search) {
                            $query->where('title', 'LIKE', "%$search%")
                                  ->orWhere('description', 'LIKE', "%$search%");
                        })->paginate(2);

      $skip=array();
      $x=0;

      foreach ($videos as $v) {
        if(Embed::make($v->video_url)->parseUrl()==false){
          $skip[$x++]=$v->video_id;
        }else {
          $video=explode("=",$v->video_url);
          $v->video_url="https://www.youtube.com/embed/".$video[1];
          $v->thumbnail="https://img.youtube.com/vi/".$video[1]."/mqdefault.jpg";
        }
      }

      $meta=Meta::find(1);
      if(Auth::check()){
        $data = [
                  'user'    => $this->user,
                  'products'=> $products,
                  'articles'=> $articles,
                  'images'  => $images,
                  'videos'  => $videos,
                  'skip'    => $skip,
                  'cart'    => $this->cart,
                  'meta'    => $meta
                ];
      }else{
        $data = [
                  'products'=> $products,
                  'articles'=> $articles,
                  'images'  => $images,
                  'videos'  => $videos,
                  'skip'    => $skip,
                  'cart'    => $this->cart,
                  'meta'    => $meta
                ];
      }

      return view('search',$data);
    }

    public function search_product(Request $r){
        $keyword=$r->keyword;
        $category_list=$r->category_list;
        $city_list=$r->city_list;
        $rating_list=$r->rating_list;
        $minimum_price=isset($r->minimum_price)?$r->minimum_price:0;
        $maximum_price=isset($r->maximum_price)?$r->maximum_price:1000000;

        if ($category_list!=NULL) {
            if ($city_list!=NULL) {
                if ($rating_list!=NULL) {
                    if ($keyword!=NULL) {
                      // echo "ada semua";
                      $products=Product::where(function ($query) use ($keyword) {
                                          $query->where('product_name', 'LIKE', "%$keyword%")
                                                ->orWhere('description', 'LIKE', "%$keyword%");
                                       })
                                       ->whereRaw('category_id IN ('.$category_list.')')
                                       ->join('stores','stores.store_id','products.store_id')
                                       ->whereRaw('stores.store_city IN ('.$city_list.')')
                                       ->join('ratings','ratings.product_id','products.product_id')
                                       ->where('stock','>','0')
                                       ->groupBy('ratings.product_id')
                                       ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                      // $products->appends($r->only('keyword','category_list','city_list','rating_list'))->links();
                      $products->appends(['keyword'=>$keyword,'rating_list'=>$rating_list,'city_list'=>$city_list,'category_list'=>$category_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                    }else {
                      // echo "ada semua kecuali keyword";
                      $products=Product::whereRaw('category_id IN ('.$category_list.')')
                                       ->join('stores','stores.store_id','products.store_id')
                                       ->whereRaw('stores.store_city IN ('.$city_list.')')
                                       ->join('ratings','ratings.product_id','products.product_id')
                                       ->where('stock','>','0')
                                       ->groupBy('ratings.product_id')
                                       ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                      $products->appends(['rating_list'=>$rating_list,'city_list'=>$city_list,'category_list'=>$category_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
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
                                       ->whereRaw('category_id IN ('.$category_list.')')
                                       ->join('stores','products.store_id','stores.store_id')
                                       ->whereRaw('stores.store_city IN ('.$city_list.')')
                                       ->where('stock','>','0')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                      $products->appends(['keyword'=>$keyword,'city_list'=>$city_list,'category_list'=>$category_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                    }
                    else {
                      // echo "ada kategori dan kota";
                      $products=Product::whereBetween('price',[$minimum_price,$maximum_price])
                                       ->whereRaw('category_id IN ('.$category_list.')')
                                       ->join('stores','products.store_id','stores.store_id')
                                       ->whereRaw('stores.store_city IN ('.$city_list.')')
                                       ->where('stock','>','0')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                      $products->appends(['city_list'=>$city_list,'category_list'=>$category_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
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
                                     ->whereRaw('category_id IN ('.$category_list.')')
                                     ->join('ratings','ratings.product_id','products.product_id')
                                     ->where('stock','>','0')
                                     ->groupBy('ratings.product_id')
                                     ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                    $products->appends(['keyword'=>$keyword,'rating_list'=>$rating_list,'category_list'=>$category_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                  }else {
                    // echo "ada kategori dan rating";
                    $products=Product::where(function ($query) use ($keyword) {
                                        $query->where('product_name', 'LIKE', "%$keyword%")
                                              ->orWhere('description', 'LIKE', "%$keyword%");
                                     })
                                     ->whereBetween('price',[$minimum_price,$maximum_price])
                                     ->whereRaw('category_id IN ('.$category_list.')')
                                     ->join('ratings','ratings.product_id','products.product_id')
                                     ->where('stock','>','0')
                                     ->groupBy('ratings.product_id')
                                     ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                    $products->appends(['rating_list'=>$rating_list,'category_list'=>$category_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
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
                                     ->whereRaw('category_id IN ('.$category_list.')')
                                     ->where('stock','>','0')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                    $products->appends(['keyword'=>$keyword,'category_list'=>$category_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                  }
                  else {
                    // echo "cuma ada kategori";
                    $products=Product::whereBetween('price',[$minimum_price,$maximum_price])
                                     ->whereRaw('category_id IN ('.$category_list.')')
                                     ->where('stock','>','0')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                    $products->appends(['category_list'=>$category_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
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
                                     ->where('stock','>','0')
                                     ->groupBy('ratings.product_id')
                                     ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);

                    $products->appends(['keyword'=>$keyword,'rating_list'=>$rating_list,'city_list'=>$city_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                  }else {
                    // echo "ada kota dan rating";
                    $products=Product::whereBetween('price', [$minimum_price, $maximum_price])
                                     ->join('stores','products.store_id','stores.store_id')
                                     ->whereRaw('stores.store_city IN ('.$city_list.')')
                                     ->join('ratings','ratings.product_id','products.product_id')
                                     ->where('stock','>','0')
                                     ->groupBy('ratings.product_id')
                                     ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);

                    $products->appends(['rating_list'=>$rating_list,'city_list'=>$city_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
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
                                     ->where('stock','>','0')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                    $products->appends(['keyword'=>$keyword,'city_list'=>$city_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                  }
                  else {
                    // echo "cuma ada kota";
                    $products=Product::whereBetween('price',[$minimum_price,$maximum_price])
                                     ->join('stores','products.store_id','stores.store_id')
                                     ->whereRaw('stores.store_city IN ('.$city_list.')')
                                     ->where('stock','>','0')
                                     ->orderBy('products.created_at','desc')
                                     ->paginate(12);
                    $products->appends(['city_list'=>$city_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                  }
              }
          }
          else {
            if ($rating_list!=NULL) {
                if ($keyword!=NULL) {
                  // echo "ada rating dan keyword";
                  $products=Product::whereBetween('price', [$minimum_price, $maximum_price])
                                   ->join('ratings','ratings.product_id','products.product_id')
                                   ->where('stock','>','0')
                                   ->groupBy('ratings.product_id')
                                   ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                   ->orderBy('products.created_at','desc')
                                   ->paginate(12);

                  $products->appends(['rating_list'=>$rating_list,'keyword'=>$keyword,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                }else {
                  // echo "cuma ada rating";
                  $products=Product::whereBetween('price', [$minimum_price, $maximum_price])
                                   ->join('ratings','ratings.product_id','products.product_id')
                                   ->where('stock','>','0')
                                   ->groupBy('ratings.product_id')
                                   ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                   ->orderBy('products.created_at','desc')
                                   ->paginate(12);

                  $products->appends(['rating_list'=>$rating_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price]);
                }
            }
            else{
                if ($keyword!=NULL) {
                  // echo "cuma ada keyword";
                  $products=Product::where(function ($query) use ($keyword) {
                                      $query->where('product_name', 'LIKE', "%$keyword%")
                                            ->orWhere('description', 'LIKE', "%$keyword%");
                                   })
                                   ->whereBetween('price', [$minimum_price, $maximum_price])
                                   ->where('stock','>','0')
                                   ->orderBy('created_at','desc')
                                   ->paginate(12);

                  $products->appends(['keyword'=>$keyword,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                }
                else {
                  // echo "hapus filter";
                  $products=Product::where('stock','>','0')
                                   ->orderBy('created_at','desc')
                                   ->paginate(12);
                }
            }
          }
        }

        $categories=Category::all();

        $store_cities=Product::join('stores','products.store_id','stores.store_id')
                              ->groupBy('stores.store_city')
                              ->get();

        $meta=Meta::find(1);
        if(Auth::check()){
          $data=['user'=>$this->user,'products'=>$products,'cart'=>$this->cart,'categories'=>$categories,'stores'=>$store_cities,'meta'=>$meta];
        }else{
          $data=['products'=>$products,'cart'=>$this->cart,'categories'=>$categories,'stores'=>$store_cities,'meta'=>$meta];
        }

        return view('products',$data);
    }

    public function search_article(Request $r)
    {
        $search=preg_replace("#[^0-9a-z]#i"," ",$r->s);
        $articles=Post::where(function ($query) use ($search) {
                              $query->where('title', 'LIKE', "%$search%")
                                    ->orWhere('post', 'LIKE', "%$search%");
                          })->paginate(5);

        $articles->appends($r->only('s'))->links();

        $meta=Meta::find(3);

        if(Auth::check()){
          $data=['user'=>$this->user,'articles'=>$articles,'cart'=>$this->cart,'meta'=>$meta];
        }else{
          $data=['articles'=>$articles,'cart'=>$this->cart,'meta'=>$meta];
        }

        return view('articles',$data);
    }

    public function search_gallery(Request $r)
    {
        $search=preg_replace("#[^0-9a-z]#i"," ",$r->s);
        $images=Image::where(function ($query) use ($search) {
                              $query->where('image_name', 'LIKE', "%$search%")
                                    ->orWhere('description', 'LIKE', "%$search%")
                                    ->orWhere('tooltip', 'LIKE', "%$search%");
                          })->paginate(8);

        $images->appends($r->only('s'))->links();

        $meta=Meta::find(4);

        if(Auth::check()){
          $data=['user'=>$this->user,'images'=>$images,'cart'=>$this->cart,'meta'=>$meta];
        }else{
          $data=['images'=>$images,'cart'=>$this->cart,'meta'=>$meta];
        }

        return view('gallery',$data);
    }

    public function search_videos(Request $r)
    {
        $search=preg_replace("#[^0-9a-z]#i"," ",$r->s);
        $videos=Video::orderBy('created_at','desc')->paginate(2);
        $skip=array();
        $x=0;

        foreach ($videos as $v) {
          if(Embed::make($v->video_url)->parseUrl()==false){
            $skip[$x++]=$v->video_id;
          }else {
            $video=explode("=",$v->video_url);
            $v->video_url="https://www.youtube.com/embed/".$video[1];
            $v->thumbnail="https://img.youtube.com/vi/".$video[1]."/mqdefault.jpg";
          }
        }

        $videos->appends($r->only('s'))->links();

        $meta=Meta::find(5);
        if(Auth::check()){
          $data=['user'=>$this->user,'cart'=>$this->cart,'videos'=>$videos,'skip'=>$skip,'meta'=>$meta];
        }else{
          $data=['cart'=>$this->cart,'videos'=>$videos,'skip'=>$skip,'meta'=>$meta];
        }

        return view('video',$data);
    }

    public function detail_product($product_id)
    {
        $pd=explode('-',$product_id);
        $id=$pd[count($pd)-1];
        $product_id=substr($id,3,strlen($id)-3);

        $detail_product=Product::findOrFail($product_id);
        $related_product=Product::where([['category_id',$detail_product->category_id],['product_id','!=',$detail_product->product_id]])->inRandomOrder()->take(8)->get();

        $meta=Meta::find(2);
        if(Auth::check())
        {
            $data=['user'=>$this->user,'detail_product'=>$detail_product,'cart'=>$this->cart,'product_id'=>$product_id,'related_product'=>$related_product,'meta'=>$meta];
        }else{
            $data=['detail_product'=>$detail_product,'cart'=>$this->cart,'product_id'=>$product_id,'related_product'=>$related_product,'meta'=>$meta];
        }

        return view('detail_product',$data);
    }

    public function add_to_cart(Request $r)
    {
        $product=Product::find($r->product_id);
        if(Auth::check()){
        $check_item=WebCart::where([['product_id',$r->product_id],['buyer_id',$this->user_id],['status',0]])->first();

        $price=$product->price;
        $total_price=$price*$r->amount;

        if(count($check_item)==0)
        {
          WebCart::create([
            'buyer_id'    => $this->user_id,
            'product_id'  => $r->product_id,
            'price'       => $price,
            'amount'      => $r->amount,
            'total_price' => $total_price,
          ]);
        }else{
          $cart_id=$check_item->cart_id;
          $c=WebCart::find($cart_id);
          $c->amount+=$r->amount;
          $c->total_price+=$total_price;
          $c->update();
        }
        }else {
          if(count(Cart::get($r->product_id))>0){
            Cart::update($r->product_id, array(
              'quantity' => $r->amount,
            ));
          }else {
            Cart::add(array(
                    'id'          => $r->product_id,
                    'name'        => $product->product_name,
                    'price'       => $product->price,
                    'quantity'    => $r->amount,
                    'attributes'  => array()
                  ));
          }
        }
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function shopping_cart(){
        $products=Product::take(4)->get();

        // $cart=WebCart::where([['buyer_id',$this->user_id],['status','0']])
        //               ->get()
        //               ->sortBy(function($q){
        //                 return $q->product->store_id;
        //               },1);

        $cart=WebCart::join('products','products.product_id','carts.product_id')
                  ->where([['buyer_id',$this->user_id],['status','0']])
                  ->orderBy('products.store_id')
                  ->get();

        // dd($cart,$cart2);

        $meta=Meta::find(1);
        if(Auth::check())
        {
          $data=['user'=>$this->user,'cart'=>$cart,'products'=>$products,'meta'=>$meta];
        }else {
          $data=['cart'=>$this->cart,'products'=>$products,'meta'=>$meta];
        }
        return view('shopping_cart',$data);
    }

    public function update_cart(Request $r){
        if(Auth::check())
        {
          $cart=WebCart::join('products','products.product_id','carts.product_id')
                    ->where([['buyer_id',$this->user_id],['status','0']])
                    ->orderBy('products.store_id')
                    ->get();

          foreach ($cart as $c) {
            if(array_key_exists($c->product->product_id, $r->quantity))
            {
              if($r->quantity[$c->product->product_id]<=$product=$c->product->stock){
                if ($r->quantity[$c->product->product_id]==0) {
                  $c->delete();
                }else {
                  $c->amount=$r->quantity[$c->product->product_id];
                  $c->total_price=$c->price*$c->amount;
                  $c->update();
                }
              }
            }
          }
        }else {
          foreach ($this->cart as $c) {
            $p=Product::find($c->id);
            if ($r->quantity[$p->product_id]==0) {
              Cart::remove($c->id);
            }else {
              $plus=$r->quantity[$p->product_id]-$c->quantity;
              if($r->quantity[$p->product_id]<=$p->stock){
                Cart::update($c->id, array(
                  'quantity' => $plus,
              ));
              }
            }
          }
        }
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function checkout($store_id){
        if(Auth::check())
        {
          $cart=WebCart::join('products','products.product_id','carts.product_id')
                    ->where([['buyer_id',$this->user_id],['status','0'],['store_id',$store_id]])
                    ->orderBy('products.store_id')
                    ->get();
          $province=Province::all();
          $meta=Meta::find(1);
          $data=['user'=>$this->user,'cart'=>$cart,'province'=>$province,'meta'=>$meta];
        }else {
          return redirect('/register');
        }

        return view('checkout',$data);
    }

    public function open_store(){
        $meta=Meta::find(1);
        return view('open_store',['user'=>$this->user,'cart'=>$this->cart,'meta'=>$meta]);
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
            'email' => $r->email,
            'phone' => $r->phone,
            'type'  => $r->type,
            'url'   => $url,
        ]);

        $user=User::where('role_id','1')->get();
        foreach ($user as $u) {
          $u->notify(new NewStoreRegister($product_request));
        }

        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function articles(){
        $articles=Post::orderBy('updated_at','desc')->paginate(5);

        $meta=Meta::find(3);
        if(Auth::check()){
          $data=['user'=>$this->user,'cart'=>$this->cart,'articles'=>$articles,'meta'=>$meta];
        }else{
          $data=['cart'=>$this->cart,'articles'=>$articles,'meta'=>$meta];
        }

        return view('articles',$data);
    }

    public function load_articles(Request $r){
        $articles=Post::orderBy('updated_at','desc')->paginate(5);
        if($r->page-1==$articles->lastPage()){
          echo "0";
        }else {
          foreach ($articles as $a) {
            ?>
            <div class="news-wrap news--page load-item">
              <div class="news--page__padding rwd">
                <div class="news-wrap__cover">
                  <?php
                    $path="uploads/gambar_artikel/".$a->writer->user_id."_".$a->writer->profile->first_name."/artikel".$a->post_id."/".$a->default_image;
                  ?>
                  <a href="<?php echo url('/article_details/'.$a->slug)?>"><img src="<?php echo $path?>"></a>
                </div>
                <div class="news-wrap__body">
                  <a href="<?php echo url('/article_details/'.$a->slug)?>"><h3 class="title"><?php echo $a->title?></h3></a>
                  <p class="date"><?php echo $a->date_format()?>, <?php echo $a->time_format()?></p>
                  <p class="main">
                    <?php echo str_limit(strip_tags($a->post), $limit = 50, $end = '...')?>
                  </p>
                  <a href="<?php echo url('/article_details/'.$a->slug)?>" class="btn btn-less">Read More</a>
                </div>
              </div>
            </div>
            <?php
          }
        }
    }

    public function article_details($year,$month,$article_id){
        $pd=explode('-',$article_id);
        $id=$pd[count($pd)-1];
        $article_id=substr($id,3,strlen($id)-3);

        $article=Post::findOrFail($article_id);
        $related_article=Post::where('post_id','!=',$article_id)->inRandomOrder()->take(4)->get();
        $comments=Comment::where([['post_id',$article_id],['status','1']])->orderBy('created_at','desc')->paginate(10);

        $meta=Meta::find(3);
        if(Auth::check()){
          $data=['user'=>$this->user,'cart'=>$this->cart,'article'=>$article,'comments'=>$comments,'related_article'=>$related_article,'meta'=>$meta];
        }else{
          $data=['cart'=>$this->cart,'article'=>$article,'comments'=>$comments,'related_article'=>$related_article,'meta'=>$meta];
        }

        return view('article_details',$data);
    }

    public function comment(Request $r){
        $comment=Comment::create([
          'post_id' => $r->post_id,
          'name'    => $r->name,
          'email'   => $r->email,
          'comment' => $r->comment
        ]);

        $post=Post::find($r->post_id);
        $post->writer->notify(new CommentArticle($comment));

        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function gallery(){
        $images=Image::orderBy('created_at','desc')->paginate(8);
        $meta=Meta::find(4);
        if(Auth::check()){
          $data=['user'=>$this->user,'cart'=>$this->cart,'images'=>$images,'meta'=>$meta];
        }else{
          $data=['cart'=>$this->cart,'images'=>$images,'meta'=>$meta];
        }

        return view('gallery',$data);
    }

    public function load_gallery(Request $r){
        $images=Image::orderBy('created_at','desc')->paginate(8);
        if($r->page-1==$images->lastPage()){
          error;
        }else {
          foreach ($images as $i) {
            $path="/uploads/gallery/".$i->category->image_category_id."_".$i->category->image_category_name;
            ?>
            <div class="gallery" style="display:none">
              <div class="gallery__ratio">
                <a href="<?php echo $path."/".$i->image_path?>" data-lightbox="gallery" class="no-smoothstate" data-title="<?php echo $i->description?>">
                  <img src="<?php echo $path."/".$i->image_path?>">
                  <div class="hover">
                    <i class="fa fa-search"></i>
                  </div>
                </a>
              </div>
            </div>
            <?php
          }
        }
    }

    public function video(){
        $videos=Video::orderBy('created_at','desc')->paginate(2);
        $skip=array();
        $x=0;

        foreach ($videos as $v) {
          if(Embed::make($v->video_url)->parseUrl()==false){
            $skip[$x++]=$v->video_id;
          }else {
            $video=explode("=",$v->video_url);
            $v->video_url="https://www.youtube.com/embed/".$video[1];
            $v->thumbnail="https://img.youtube.com/vi/".$video[1]."/mqdefault.jpg";
          }
        }

        $meta=Meta::find(5);
        if(Auth::check()){
          $data=['user'=>$this->user,'cart'=>$this->cart,'videos'=>$videos,'skip'=>$skip,'meta'=>$meta];
        }else{
          $data=['cart'=>$this->cart,'videos'=>$videos,'skip'=>$skip,'meta'=>$meta];
        }

        return view('video',$data);
    }

    public function watch_video($video_id)
    {
        $v=Video::find($video_id);
        $salim=explode('=',$v->video_url);
        $v->video_url="https://youtube.com/embed/".$salim[1];
        return view('watch_video',['v'=>$v]);
    }

    public function load_video(Request $r){
        $videos=Video::orderBy('created_at','desc')->paginate(2);
        $skip=array();
        $x=0;

        foreach ($videos as $v) {
          if(Embed::make($v->video_url)->parseUrl()==false){
            $skip[$x++]=$v->video_id;
          }else {
            $video=explode("=",$v->video_url);
            $v->video_url="https://www.youtube.com/embed/".$video[1];
            $v->thumbnail="https://img.youtube.com/vi/".$video[1]."/mqdefault.jpg";
          }
        }

        if($r->page-1==$videos->lastPage()){
          error;
        }else {
          foreach ($videos as $v) {
            if (array_search($v->video_id,$skip)!=false){
              # code...
            }else {
            ?>
                <div class="video">
									<a href="<?php echo url('/watch/'.$v->video_id)?>" data-featherlight="ajax" data-featherlight-variant="featherlight-video" class="no-smoothstate video-trigger">
										<div class="video-embed">
											<div class="video-ratio">
												<img src="<?php echo $v->thumbnail?>" alt="Gambar Thumbnail" class="video-ratio-thumbs">
												<div class="video-ratio-helper">
													<img src="assets/images/play-button.png" alt="Play Button">
												</div>
											</div>
										</div>
										<div class="video-desc">
											<h2 class="video-desc-title"><?php echo $v->title==NULL?'Video Indikraf':$v->title?></h2>
											<p class="video-desc-date">Diunggah <?php echo $v->date_format()?></p>
											<p class="video-desc-detail">
                        <?php
												if ($v->description!=NULL){
													echo $v->description;
                        }
												else{
                        ?>
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        <?php } ?>
											</p>
										</div>
									</a>
								</div>
              <?php
            }
          }
        }
    }

    public function faq()
    {
      $faq=Faq::all();
      $meta=Meta::find(6);
      if(Auth::check()){
        $data=['user'=>$this->user,'cart'=>$this->cart,'faq'=>$faq,'meta'=>$meta];
      }else{
        $data=['cart'=>$this->cart,'faq'=>$faq,'meta'=>$meta];
      }

      return view('faq',$data);
    }

    public function about(){
        $information=Information::find(1);
        $meta=Meta::find(7);
        if(Auth::check()){
          $data=['user'=>$this->user,'cart'=>$this->cart,'information'=>$information,'meta'=>$meta];
        }else{
          $data=['cart'=>$this->cart,'information'=>$information,'meta'=>$meta];
        }

        return view('about',$data);
    }

    public function contact(){
        $information=Information::find(2);
        $meta=Meta::find(7);
        if(Auth::check()){
          $data=['user'=>$this->user,'cart'=>$this->cart,'information'=>$information,'meta'=>$meta];
        }else{
          $data=['cart'=>$this->cart,'information'=>$information,'meta'=>$meta];
        }

        return view('contact',$data);
    }

    public function send_message(Request $r)
    {
      $name=$r->name;
      $email=$r->email;
      $title=$r->title;
      $messagetoAdmin=$r->message;

      $user=User::where('role_id',1)->get();
      foreach ($user as $u) {
        $u->notify(new Message($name,$email,$title,$messagetoAdmin));

        MessageModel::create([
            'type'     => 'Receive',
            'sender'   => $email,
            'receiver' => $u->user_id,
            'subject'  => $title,
            'body'     => $messagetoAdmin
        ]);

        \Mail::send('emails.blast_email', ['user'=>$u,'text'=>$messagetoAdmin], function ($message) use ($title,$email,$u){
            $message->from($email);
            $message->subject($title);
            $message->to($u->email);
        });
      }

      $r->session()->flash('status','sent');
      return redirect(url()->previous());
    }

    public function subscribe(Request $r)
    {
        if (!Email::where('email',$r->email)->first()) {
          Email::create([
            'name'=>$r->name,
            'email'=>$r->email,
          ]);
        }

        $r->session()->flash('subscribed','true');
        return redirect(redirect()->getUrlGenerator()->previous());
    }
}
