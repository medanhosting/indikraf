<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Mail;
use Session;
use App\Mail\Blast_email;
use App\Mail\Invoice;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Notifications\NewOrder;
use App\Jobs\Send_email_blast;
use App\Notifications\Message;
use App\Http\Requests\ValidateProduct;

// Models
use DB;
use App\User;
use App\Models\Profile;
use App\Models\Address;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Product_image;
use App\Models\Store;
use App\Models\ProductRequest;
use App\Models\Product_image_request;
use App\Models\Image;
use App\Models\Image_category;
use App\Models\Video;
use App\Models\Information;
use App\Models\Faq;
use App\Models\Email;
use App\Models\Province;
use App\Models\Page_header;
use App\Models\Meta;
use App\Models\Message as MessageModel;

class Admin extends Controller
{
    protected $user_id;
    protected $user;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role->role_name!="Admin") {
                return redirect('/');
            } else {
                $this->user_id=Auth::user()->user_id;
                $this->user=Auth::user();
                $this->user->profile=Auth::user()->profile;
                return $next($request);
            }
        });
    }

    public function index()
    {
        $transactions=Transaction::all();
        $member=User::where('role_id','2')->get();
        $products=Product::all();
        $data=$this->chart_data();
        $visitor_data=$this->chart_data_visitor();
        // dd($data);
        return view('Admin.index', ['user'=>$this->user,'transactions'=>$transactions,'member'=>$member,'products'=>$products,'data'=>$data,'visitor_data'=>$visitor_data]);
    }

    public function analisys()
    {
        $products=Product::all();
        $visitor_data=$this->chart_data_visitor();
        $chart_data_register=$this->chart_data_register();
        return view('Admin.analisys', ['user'=>$this->user,'data'=>$chart_data_register,'visitor_data'=>$visitor_data]);
    }

    public function chart_data()
    {
        $transactions=DB::select( DB::raw(" SELECT SUM(total_price)+t.shipping_price AS value,(CONCAT_WS('-',YEAR(t.paid_date),LPAD(MONTH(t.paid_date), 2, '0'),DAY(t.paid_date))) AS date FROM
                                            transactions AS t,carts AS c
                                            WHERE t.transaction_id=c.transaction_id AND t.status NOT IN ('Dibatalkan','Menunggu Pembayaran')
                                            GROUP BY date ORDER BY t.paid_date") );

        return $transactions;
    }

    public function chart_data_visitor()
    {
        $visitors=DB::select( DB::raw(" SELECT COUNT(id) AS value,(CONCAT_WS('-',YEAR(created_at),LPAD(MONTH(created_at), 2, '0'),DAY(created_at))) AS date FROM
                                        visitlogs
                                        GROUP BY date ORDER BY created_at") );

        return $visitors;
    }

    public function chart_data_register()
    {
        $register=DB::select( DB::raw(" SELECT COUNT(user_id) AS value,(CONCAT_WS('-',YEAR(created_at),LPAD(MONTH(created_at), 2, '0'),DAY(created_at))) AS date FROM
                                        users
                                        GROUP BY date ORDER BY created_at") );

        return $register;
    }

    public function profile()
    {
        $province=Province::all();
        return view('Admin.profile',['user'=>$this->user,'province'=>$province]);
    }

    public function profile_setting(Request $r)
    {
        if ($r->file('profile_image')!=NULL) {

            $this->validate($r, [
                'profile_image' => 'image',
            ]);

            $profile=Profile::where('user_id',$this->user_id)->first();

            $lokasi=public_path('/uploads/foto_profil/'.$profile->profile_image);
            $deleteFile=File::delete($lokasi);

            $file=$r->file('profile_image');
            $image_profile=sha1($profile->user_id).'.'.$file->getClientOriginalExtension();
            $lokasi=public_path('/uploads/foto_profil/');
            $file->move($lokasi,$image_profile);

            $profile->profile_image=$image_profile;
            $profile->update();
        }

        if ($r->password!=NULL) {
            $this->validate($r, [
                'password' => 'min:6|confirmed',
            ]);

            $u=User::find($this->user_id);
            $u->password=bcrypt($r->password);
            $u->update();
        }

        $this->validate($r, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|numeric|digits_between:10,20',
            'address' => 'required|min:6',
            'province' => 'required|numeric',
            'city' => 'required|numeric',
            'postal_code' => 'required|numeric|digits_between:5,6'
        ]);

        $profile=Profile::where('user_id',$this->user_id)->first();
        if ($profile->first_name!=$r->first_name) {
          if(count($this->user->products)){
            $folder=public_path('uploads/gambar_produk/'.$this->user_id."_".$profile->first_name);
            $rename=public_path('uploads/gambar_produk/'.$this->user_id."_".$r->first_name);
            rename($folder,$rename);
          }
          if (count($this->user->articles)) {
            $folder=public_path('uploads/gambar_artikel/'.$this->user_id."_".$profile->first_name);
            $rename=public_path('uploads/gambar_artikel/'.$this->user_id."_".$r->first_name);
            rename($folder,$rename);
          }
        }

        $profile->update([
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
          Address::create([
            'user_id'=>$this->user_id,
            'first_name'=>$r->first_name,
            'last_name'=>$r->last_name,
            'address'=>$r->address,
            'city_id'=>$r->city,
            'postal_code'=>$r->postal_code,
            'phone'=>$r->phone
          ]);
        }

        $r->session()->flash('status_profile','Edit profil berhasil dilakukan');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function user_profile_setting(Request $r)
    {
        if ($r->file('profile_image')!=NULL) {
            $this->validate($r, [
                'profile_image' => 'image',
            ]);

            $profile=Profile::where('user_id',$r->user_id)->first();

            $lokasi=public_path('/uploads/foto_profil/'.$profile->profile_image);
            $deleteFile=File::delete($lokasi);

            $file=$r->file('profile_image');
            $image_profile=sha1($profile->user_id).'.'.$file->getClientOriginalExtension();
            $lokasi=public_path('/uploads/foto_profil/');
            $file->move($lokasi,$image_profile);

            $profile->profile_image=$image_profile;
            $profile->update();
        }

        if ($r->password!=NULL) {
            $this->validate($r, [
                'password' => 'min:6|confirmed',
            ]);

            $u=User::find($r->user_id);
            $u->password=bcrypt($r->password);
            $u->update();
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

        $profile=Profile::where('user_id',$r->user_id)->first();

        $profile->update([
            'first_name'  => $r->first_name,
            'last_name'   => $r->last_name,
            'gender'      => $r->gender
        ]);

        $address=Address::where('user_id',$r->user_id)->first();
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
            'user_id'     => $r->user_id,
            'first_name'  => $r->first_name,
            'last_name'   => $r->last_name,
            'address'     => $r->address,
            'city_id'     => $r->city,
            'postal_code' => $r->postal_code,
            'phone'       => $r->phone
          ]);
        }
        $r->session()->flash('status_message','Update Profil berhasil');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function transaction()
    {
        $transactions=Transaction::groupBy('order_id')->orderBy('created_at','desc')->get();
        $no=$this->user->unreadNotifications->where('type','App\Notifications\NewOrder');

        $new_order=array();
        if ($no) {
          foreach ($no as $n) {
            $new_order[$n->id]=$n->data['order_id'];
          }
        }

        return view('Admin.transaction', ['user'=>$this->user,'transactions'=>$transactions,'new_order'=>$new_order]);
    }

    public function detail_transaction($order_id)
    {
        foreach ($this->user->unreadNotifications as $n) {
          if ($n->type=="App\Notifications\NewOrder" && $n->data['order_id']==$order_id) {
            $n->markAsRead();
          }
        }

        $transaction=Transaction::where('order_id',$order_id)->first();

        return view('Admin.transaction_detail', ['user'=>$this->user,'transaction'=>$transaction]);
    }

    public function change_status_transaction(Request $r)
    {
        $transaction=Transaction::where('order_id',$r->order_id)->first();
        Mail::to($transaction->buyer->email)->queue(new Invoice($transaction->buyer,$transaction));
        if (count($transaction)<1) {
          return redirect(redirect()->getUrlGenerator()->previous());
        }else {
          $status = array(
                        1 => "Menunggu Pembayaran",
                        2 => "Pembayaran Diterima",
                        3 => "Barang Diproses",
                        4 => "Barang Dikirim",
                        5 => "Selesai"
                    );

          $index=array_search($transaction->status,$status);
          if ($index!=false) {
            if ($index==1) {
              $transaction->paid_date=\Carbon\Carbon::now();
              $transaction->update();
            }
            if ($r->tracking_number!=NULL) {
              $transaction->tracking_number=$r->tracking_number;
              $transaction->update();
            }
            $transaction->status=$status[$index+1];
            $transaction->update();
          }
        }
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function cancel_transaction($order_id)
    {
        $transaction=Transaction::where('order_id',$order_id)->first();
        if (count($transaction)<1) {
          return redirect(redirect()->getUrlGenerator()->previous());
        }else {
          $transaction->status="Dibatalkan";
          $transaction->update();
        }
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function detail_transaction_print($order_id)
    {
        $transaction=Transaction::where('order_id',$order_id)->first();

        return view('Admin.transaction_detail_print', ['user'=>$this->user,'transaction'=>$transaction]);
    }

    public function users()
    {
        $users=User::where('role_id','2')->orderBy('created_at','desc')->get();
        $no=$this->user->unreadNotifications->where('type','App\Notifications\NewUserRegistration');
        $new_users=array();
        if ($no) {
          foreach ($no as $n) {
            $new_users[$n->id]=$n->data['user_id'];
          }
        }
        return view('Admin.users',['user'=>$this->user,'users'=>$users,'new_users'=>$new_users]);
    }

    public function user_detail($user_id)
    {
        $u=User::find($user_id);
        $transactions=Transaction::where('buyer_id',$u->user_id)->groupBy('order_id')->orderBy('created_at','desc')->get();
        $province=Province::all();

        foreach ($this->user->unreadNotifications as $n) {
          if ($n->type=="App\Notifications\NewUserRegistration" && $n->data['user_id']==$user_id) {
            $n->markAsRead();
          }
        }

        return view('Admin.user_detail',['user'=>$this->user,'u'=>$u,'transactions'=>$transactions,'province'=>$province]);
    }

    public function makeAdmin(Request $r)
    {
      User::find($r->user_id)->update([
        'role_id' => 1
      ]);
      return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function selling_product()
    {
        $products=Product::where('seller_id', $this->user_id)->orderBy('created_at','desc')->get();
        $category=Category::all();
        $store=Store::all();
        return view('Admin.sell', ['user'=>$this->user,'products'=>$products,'category'=>$category,'store'=>$store]);
    }

    public function product_detail($product_id)
    {
        $product=Product::find($product_id);

        return view('Admin.product_detail',['user'=>$this->user,'product'=>$product]);
    }

    public function add_product(ValidateProduct $r)
    {
        if ($r->first_price) {
          $first_price=$r->first_price;
        }else {
          $first_price=$r->price;
        }

        $products=Product::create([
          'seller_id'         => $this->user_id,
          'category_id'       => $r->category_id,
          'store_id'          => $r->store_id,
          'product_name'      => $r->product_name,
          'description'       => $r->description,
          'stock'             => $r->stock,
          'first_price'       => $first_price,
          'price'             => $r->price,
          'weight'            => $r->weight,
          'meta_keyword'      => $r->meta_keyword,
          'meta_description'  => $r->meta_description
        ]);

        $product_id=$products->product_id;

        $file=$r->file('file');
        $product_image_name=time().'.'.$file->getClientOriginalExtension();
        $lokasi_khusus=$this->user_id."_".$this->user->profile->first_name."/produk".$product_id;
        $lokasi=public_path('/uploads/gambar_produk/'.$lokasi_khusus);
        $file->move($lokasi, $product_image_name);

        Product_image::create([
          'product_id'=>$product_id,
          'product_image_name'=>$product_image_name
        ]);

        $r->session()->flash('status_input_product','Input Produk Berhasil');
        return redirect('/admin/selling_product');
    }

    public function edit_product(ValidateProduct $r)
    {
        $products=Product::find($r->product_id)->update([
          'category_id'       => $r->category_id,
          'store_id'          => $r->store_id,
          'product_name'      => $r->product_name,
          'description'       => $r->description,
          'stock'             => $r->stock,
          'first_price'       => $r->first_price,
          'price'             => $r->price,
          'weight'            => $r->weight,
          'meta_keyword'      => $r->meta_keyword,
          'meta_description'  => $r->meta_description
        ]);

        $r->session()->flash('status_edit_product','Edit Produk Berhasil');
        $r->session()->flash('product_id',$r->product_id);
        return redirect('/admin/selling_product');
    }

    public function delete_product($product_id)
    {
        $products=Product::find($product_id)->delete();
        //
        // $lokasi_khusus=$this->user_id."_".$this->user->profile->first_name."/produk".$product_id;
        // $lokasi=public_path('/uploads/gambar_produk/'.$lokasi_khusus);
        // $deleteFile=File::deleteDirectory($lokasi);

        Session::flash('status_delete_product','Produk berhasil dihapus');
        return redirect('/admin/selling_product');
    }

    public function add_product_image(Request $r)
    {
        $input = Input::all();
        $file  = Input::file('file');

        $extension = $file->getClientOriginalExtension();

        $lokasi_khusus        = $this->user_id."_".$this->user->profile->first_name."/produk".$r->product_id;
        $lokasi               = public_path('/uploads/gambar_produk/'.$lokasi_khusus);
        $product_image_name   = sha1($file->getClientOriginalName()).time().".{$extension}";

        $upload_success = $file->move($lokasi, $product_image_name);

        $product_id=$r->product_id;
        if ($upload_success) {
            Product_image::create([
            'product_id'        => $product_id,
            'product_image_name'=> $product_image_name
          ]);
        }
    }

    public function delete_product_image($product_image_id)
    {
        $product_image=Product_image::find($product_image_id);
        $product=Product::find($product_image->product_id);
        $lokasi_khusus=$product->seller->user_id."_".$product->seller->profile->first_name."/produk".$product->product_id."/";
        $lokasi=public_path('/uploads/gambar_produk/'.$lokasi_khusus."/".$product_image->product_image_name);
        $deleteFile=File::delete($lokasi);

        $product_image->delete();

        Session::flash('status_delete','Gambar produk berhasil dihapus');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function product_category()
    {
        $categories=Category::all();
        return view('Admin.categories', ['user'=>$this->user,'categories'=>$categories]);
    }

    public function add_product_category(Request $r)
    {
        $c=new Category;
        $c->category_name=$r->category_name;
        $c->save();

        $r->session()->flash('status_add_category','Tambah kategori berhasil');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function edit_product_category(Request $r)
    {
        $c=Category::find($r->category_id);
        $c->category_name=$r->category_name;
        $c->update();

        $r->session()->flash('status_edit_category','Kategori berhasil diedit');
        $r->session()->flash('category_id',$r->category_id);
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function delete_product_category($category_id)
    {
        $product=Product::where('category_id',$category_id)->get();
        foreach($product as $p){
            $p->delete();
        }
        $c=Category::find($category_id)->delete();

        Session::flash('status_delete_category','Kategori berhasil dihapus');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function store(Request $r)
    {
        $province=Province::all();
        $store=Store::all();
        if ($r->email && $r->phone) {
          $email=$r->email;
          $phone=$r->phone;
        }else {
          $email=NULL;
          $phone=NULL;
        }
        return view('Admin.store', ['user'=>$this->user,'store'=>$store,'province'=>$province,'email'=>$email,'phone'=>$phone]);
    }

    public function store_products($store_id)
    {
        $store=Store::find($store_id);
        $products=$store->products;
        return view('Admin.store_products',['user'=>$this->user,'store'=>$store,'products'=>$products]);
    }

    public function add_store(Request $r)
    {
        Store::create([
          'store_name'        => $r->store_name,
          'store_address'     => $r->store_address,
          'store_city'        => $r->store_city,
          'store_postal_code' => $r->store_postal_code,
          'store_email'       => $r->store_email,
          'store_phone'       => $r->store_phone,
        ]);

        $r->session()->flash('status_add_store','Toko berhasil ditambah');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function edit_store(Request $r)
    {
        Store::find($r->store_id)->update([
          'store_name'        => $r->store_name,
          'store_address'     => $r->store_address,
          'store_city'        => $r->store_city,
          'store_postal_code' => $r->store_postal_code,
          'store_email'       => $r->store_email,
          'store_phone'       => $r->store_phone,
        ]);

        $r->session()->flash('status_edit_store','Toko berhasil diedit');
        $r->session()->flash('store_id',$r->store_id);
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function delete_store($id)
    {
        Store::find($id)->delete();

        Session::flash('status_delete_store','Toko berhasil dihapus');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function submit_product()
    {
        $products=ProductRequest::orderBy('created_at','desc')->get();
        $no=$this->user->unreadNotifications->where('type','App\Notifications\NewStoreRegister');
        $new_store=array();
        if ($no) {
          foreach ($no as $n) {
            $new_store[$n->id]=$n->data['request_id'];
          }
        }
        return view('Admin.product_request', ['user'=>$this->user,'products'=>$products,'new_store'=>$new_store]);
    }

    public function delete_submit_product($id)
    {
        ProductRequest::find($id)->delete();
        Session::flash('status_delete','Produk dari user berhasil dihapus');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function article()
    {
        $post=Post::where('writer_id',$this->user_id)->orderBy('created_at','desc')->get();
        return view('Admin.article', ['user'=>$this->user,'post'=>$post]);
    }

    public function add_image_article(Request $r)
    {
        $image=$r->file('image');
        dd($image);
    }

    public function article_detail($post_id)
    {
        $no=$this->user->unreadNotifications->where('type','App\Notifications\CommentArticle');
        if ($no) {
          foreach ($no as $n) {
            if ($n->data['post_id']==$post_id) {
              $n->markAsRead();
            }
          }
        }

        $post=Post::find($post_id);
        return view('Admin.article_detail',['user'=>$this->user,'post'=>$post]);
    }

    public function post_article(Request $r)
    {
        $validator=$this->validate($r, [
            'title'             => 'required',
            'meta_keyword'      => 'required',
            'meta_description'  => 'required',
            'post'              => 'required',
            'file'              => 'required|image|dimensions:max_width=500,max_height=500',
            'thumbnail'         => 'required|image|dimensions:max_width=200,max_height=200'
        ]);

        $post=Post::create([
          'writer_id'        => $this->user_id,
          'title'            => $r->title,
          'post'             => $r->post,
          'meta_keyword'     => $r->meta_keyword,
          'meta_description' => $r->meta_description,
        ]);

        $file=$r->file('file');
        $image_name=time().'.'.$file->getClientOriginalExtension();
        $lokasi_khusus=$this->user_id."_".$this->user->profile->first_name."/artikel".$post->post_id;
        $lokasi=public_path('/uploads/gambar_artikel/'.$lokasi_khusus);
        $file->move($lokasi, $image_name);

        $post->default_image=$image_name;
        $post->update();

        $file=$r->file('thumbnail');
        $image_name='thumbnail_'.time().'.'.$file->getClientOriginalExtension();
        $lokasi_khusus=$this->user_id."_".$this->user->profile->first_name."/artikel".$post->post_id;
        $lokasi=public_path('/uploads/gambar_artikel/'.$lokasi_khusus);
        $file->move($lokasi, $image_name);

        $post->thumbnail=$image_name;
        $post->update();

        $r->session()->flash('status_article','Artikel berhasil dibuat');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function update_article($post_id)
    {
        $post=Post::find($post_id);
        return view('Admin.update_article',['user'=>$this->user,'post'=>$post]);
    }

    public function edit_article(Request $r)
    {
        $validator=$this->validate($r, [
            'title'             => 'required',
            'meta_keyword'      => 'required',
            'meta_description'  => 'required',
            'post'              => 'required',
            'file'              => 'required|image|dimensions:max_width=500,max_height=500',
            'thumbnail'         => 'required|image|dimensions:max_width=200,max_height=200'
        ]);

        $post=Post::find($r->post_id);
        $post->update([
          'title'            => $r->title,
          'post'             => $r->post,
          'meta_keyword'     => $r->meta_keyword,
          'meta_description' => $r->meta_description,
        ]);

        if ($r->file('file')!=NULL) {
          $lokasi_khusus=$post->writer->user_id."_".$post->writer->profile->first_name."/artikel".$post->post_id;
          $lokasi=public_path('/uploads/gambar_artikel/'.$lokasi_khusus."/".$post->default_image);
          $deleteFile=File::delete($lokasi);

          $file=$r->file('file');
          $image_name=time().'.'.$file->getClientOriginalExtension();
          $lokasi_khusus=$this->user_id."_".$this->user->profile->first_name."/artikel".$post->post_id;
          $lokasi=public_path('/uploads/gambar_artikel/'.$lokasi_khusus);
          $file->move($lokasi, $image_name);

          $post->default_image=$image_name;
          $post->update();

          $lokasi_khusus=$post->writer->user_id."_".$post->writer->profile->first_name."/artikel".$post->post_id;
          $lokasi=public_path('/uploads/gambar_artikel/'.$lokasi_khusus."/".$post->thumbnail);
          $deleteFile=File::delete($lokasi);

          $file=$r->file('thumbnail');
          $image_name='thumbnail_'.time().'.'.$file->getClientOriginalExtension();
          $lokasi_khusus=$this->user_id."_".$this->user->profile->first_name."/artikel".$post->post_id;
          $lokasi=public_path('/uploads/gambar_artikel/'.$lokasi_khusus);
          $file->move($lokasi, $image_name);

          $post->thumbnail=$image_name;
          $post->update();
        }
        $r->session()->flash('status_article','Artikel berhasil diedit');
        return redirect('/admin/posting/');
    }

    public function delete_article($post_id)
    {
        $post=Post::find($post_id);

        $lokasi_khusus=$post->writer->user_id."_".$post->writer->profile->first_name."/artikel".$post->post_id;
        $lokasi=public_path('/uploads/gambar_artikel/'.$lokasi_khusus);
        $deleteFile=File::deleteDirectory($lokasi);

        $post->delete();
        Session::flash('status_article','Artikel berhasil dihapus');
        return redirect('/admin/posting/');
    }

    public function comment(Request $r)
    {
        $name=$this->user->profile->first_name." ".$this->user->profile->last_name;
        Comment::create([
          'post_id' => $r->post_id,
          'name'    => $name,
          'email'   => $this->user->email,
          'comment' => $r->comment
        ]);

        $r->session()->flash('comment','Komentar berhasil ditambahkan');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function approve_comment($comment_id)
    {
        $update=Comment::find($comment_id)->update([
          'status' => '1'
        ]);

        Session::flash('comment','Komentar berhasil ditampilkan');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function delete_comment($comment_id)
    {
        Comment::find($comment_id)->delete();

        Session::flash('comment','Komentar berhasil dihapus');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function gallery(Request $r)
    {
        $image_category=Image_category::all();
        $images=Image::all();
        return view('Admin.gallery', ['user'=>$this->user,'image_category'=>$image_category,'images'=>$images]);
    }

    public function add_gallery(Request $r)
    {
        $validator=$this->validate($r, [
            'image'              => 'required|image|dimensions:max_width=1000,max_height=1000',
            'description'        => 'required|string',
            'image_category_id'  => 'required',
            'tooltip'            => 'required'
        ]);

        $input = Input::all();
        $file  = Input::file('image');

        $salim=Image_category::find($r->image_category_id);

        $extension    = $file->getClientOriginalExtension();
        $lokasi_khusus= $r->image_category_id."_".$salim->image_category_name."/";
        $lokasi       = public_path('/uploads/gallery/'.$lokasi_khusus);
        $image_path   = sha1($file->getClientOriginalName()).time().".{$extension}";

        $upload_success = $file->move($lokasi, $image_path);

        if ($upload_success) {
            Image::create([
            'image_category_id' => $r->image_category_id,
            'image_name'        => $file->getClientOriginalName(),
            'image_path'        => $image_path,
            'description'       => $r->description,
            'tooltip'           => $r->tooltip
          ]);
        }
        return  redirect(redirect()->getUrlGenerator()->previous());
    }

    public function edit_gallery(Request $r)
    {
        $validator=$this->validate($r, [
            'image_name'      => 'required|string',
            'description'     => 'required|string',
            'image_category'  => 'required',
            'tooltip'         => 'required'
        ]);

        $i=Image::find($r->image_id);
        if ($r->image_category!=$i->image_category_id) {
          $salim=Image_category::find($r->image_category);
          $lokasi_khusus = $r->image_category."_".$salim->image_category_name."/";
          $lokasi        = public_path('/uploads/gallery/'.$lokasi_khusus);
          $old_path      = public_path('/uploads/gallery/'.$i->category->image_category_id."_".$i->category->image_category_name."/".$i->image_path);
          // dd($lokasi,$old_path);
          if(!File::exists($lokasi)) {
              File::makeDirectory($lokasi);
          }
          $new_path=$lokasi."/".$i->image_path;
          File::move($old_path,$new_path);
        }
        $i->update([
          'image_category_id' => $r->image_category,
          'image_name'        => $r->image_name,
          'description'       => $r->description,
          'tooltip'           => $r->tooltip,
        ]);

        $r->session()->flash('edit_gallery','Gambar berhasil diedit');
        $r->session()->flash('image_id',$r->image_id);
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function delete_gallery($image_id)
    {
        $image=Image::find($image_id);

        $lokasi_khusus=$image->category->image_category_id."_".$image->category->image_category_name."/".$image->image_path;
        $lokasi=public_path('/uploads/gallery/'.$lokasi_khusus);
        $deleteFile=File::delete($lokasi);

        Session::flash('delete_gallery','Gambar berhasil dihapus');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function image_category()
    {
        $image_category=Image_category::all();
        return view('Admin.image_categories',['user'=>$this->user,'image_category'=>$image_category]);
    }

    public function add_image_category(Request $r)
    {
        Image_category::create([
          'image_category_name' => $r->category_name
        ]);

        $image_category=Image_category::all();
        if (redirect()->getUrlGenerator()->previous()!=url('/admin/image_category')) {
          return json_encode($image_category);
        }else {
          $r->session()->flash('status_image_category','Kategori galeri berhasil ditambah');
          return redirect(redirect()->getUrlGenerator()->previous());
        }
    }

    public function edit_image_category(Request $r)
    {
        $image_category=Image_category::find($r->category_id);
        if (count($image_category->images)) {
          $folder=public_path('uploads/gallery/'.$image_category->image_category_id."_".$image_category->image_category_name);
          $rename=public_path('uploads/gallery/'.$image_category->image_category_id."_".$r->category_name);
          rename($folder,$rename);
        }

        $image_category->update([
          'image_category_name'=>$r->category_name,
        ]);

        $r->session()->flash('status_edit_category','Kategori galeri berhasil diedit');
        $r->session()->flash('category_id',$r->category_id);
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function delete_image_category($category_id)
    {
        $image_category=Image_category::find($category_id);

        $lokasi_khusus=$image_category->image_category_id."_".$image_category->image_category_name;
        $lokasi=public_path('/uploads/gallery/'.$lokasi_khusus);
        $deleteFile=File::deleteDirectory($lokasi);

        $image_category->delete();

        Session::flash('status_image_category','Kategori galeri berhasil dihapus');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function video()
    {
        $videos=Video::all();
        return view('Admin.video', ['user'=>$this->user,'videos'=>$videos]);
    }

    public function add_videos(Request $r)
    {
        Video::create([
          'video_url'   => $r->video_url,
          'title'       => $r->title,
          'description' => $r->description
        ]);

        $r->session()->flash('status_video','Video berhasil ditambahkan');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function edit_video(Request $r)
    {
        Video::find($r->video_id)->update([
          'video_url' => $r->video_url,
        ]);

        $r->session()->flash('status_edit_video','Video berhasil diedit');
        $r->session()->flash('video_id',$r->video_id);
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function delete_video($video_id)
    {
        Video::find($video_id)->delete();

        Session::flash('status_video','Video berhasil dihapus');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function information()
    {
        $informations=Information::all();
        return view('Admin.information', ['user'=>$this->user,'informations'=>$informations]);
    }

    public function control_information(Request $r)
    {
        $i=Information::find($r->kind);
        $i->post = $r->post;
        $i->update();

        $kind=$r->kind==1?'Tentang Kami':'Kontak Kami';
        $r->session()->flash('status_information',$kind." berhasil diedit");

        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function email()
    {
        $email=Email::all();
        return view('Admin.email',['user'=>$this->user,'email'=>$email]);
    }

    public function send_email(Request $r)
    {
        $us=Email::all();
        $text=$r->text;
        $subject=$r->subject;

        foreach ($us as $u) {
          // Mail::to($u->email)->queue(new Blast_email($u,$text,$subject));
          $sendEmailJob = new Send_email_blast($u,$text,$subject);
          $this->dispatch($sendEmailJob);
        }

        $r->session()->flash('status_email','Email akan dikirim di background process');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function faq()
    {
        $faqs=Faq::all();
        return view('Admin.faq',['user'=>$this->user,'faqs'=>$faqs]);
    }

    public function add_faq(Request $r)
    {
        Faq::create([
          'question'  => $r->question,
          'answer'    => $r->answer
        ]);

        $r->session()->flash('status_faq','FAQ berhasil ditambahkan');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function edit_faq(Request $r)
    {
        Faq::find($r->faq_id)->update([
          'question'  => $r->question,
          'answer'    => $r->answer
        ]);

        $r->session()->flash('status_edit_faq','FAQ berhasil diedit');
        $r->session()->flash('faq_id',$r->faq_id);
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function delete_faq($id)
    {
        Faq::find($id)->delete();
        Session::flash('status_faq','FAQ berhasil dihapus');
        return redirect(redirect()->getUrlGenerator()->previous());
    }

    public function meta()
    {
        $meta=Meta::all();
        return view('Admin.meta',['user'=>$this->user,'meta'=>$meta]);
    }

    public function edit_meta(Request $r)
    {
        $meta=Meta::find($r->page_id)->update([
          'title'       => $r->title,
          'keyword'     => $r->keyword,
          'description' => $r->description
        ]);
        return redirect(url()->previous());
    }

    public function message()
    {
        $messages=MessageModel::where([
                                        ['type','Receive'],
                                        ['receiver',$this->user_id]
                                    ])
                                    ->orderBy('created_at','desc')
                                    ->paginate(10);
        $no=$this->user->unreadNotifications->where('type','App\Notifications\Message');
        $new_message=array();
        if ($no) {
          foreach ($no as $n) {
            $new_message[$n->id]=$n->data['message'];
          }
        }
        return view('Admin.message',['user'=>$this->user,'messages'=>$messages,'new_message'=>$new_message]);
    }

    public function read_message($message_id)
    {
        $message=MessageModel::find($message_id);
        foreach ($this->user->unreadNotifications as $n) {
          if ($n->type=="App\Notifications\Message" && $n->data['message']==$message->body) {
            $n->markAsRead();
          }
        }

        return view('Admin.read_message',['user'=>$this->user,'message'=>$message]);
    }

    public function delete_message($message_id)
    {
        $message=MessageModel::find($message_id)->delete();
        return redirect(url('admin/message'));
    }
}
