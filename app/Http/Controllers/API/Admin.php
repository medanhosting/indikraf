<?php

namespace App\Http\Controllers\API;

use File;
use Mail;
use RajaOngkir;
use App\Mail\Blast_email;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

// Models
use App\User;
use App\Models\Post;
use App\Models\Product;
use App\Models\Category;
use App\Models\Product_image;
use App\Models\ProductRequest;
use App\Models\Product_image_request;
use App\Models\Image;
use App\Models\Image_category;
use App\Models\Video;
use App\Models\Information;
use App\Models\Faq;
use App\Models\Email;

class Admin extends Controller
{
    protected $user,$user_id,$cart,$token;
    public function __construct()
    {
      try {
            JWTAuth::parseToken()->authenticate();
      }catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        $refreshedToken['token_status']='expired';
        $refreshedToken['new_token']=$this->token();
        return $this->token=$refreshedToken;
      } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
         return $this->token['token_status']='invalid';
      } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
         return $this->token['token_status']='blacklisted';
      } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
         return response()->json($e);
      }

      $this->user = JWTAuth::toUser(JWTAuth::getToken());
      $this->middleware(function($request,$next){
        if($this->user->role_id!=1){
          return redirect('/api');
        }else {
          $this->user_id=$this->user->user_id;
          $this->user->profile=$this->user->profile;
        }
        return $next($request);
      });
    }

    function token(){
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


    public function index()
    {
        $data = [
                  'user'=>$this->user
                ];
        return response()->json(compact('data'));
    }

    public function selling_product()
    {
        $products=Product::where('seller_id', $this->user_id)->get();
        $category=Category::all();
        $data = [
                  'products'=>$products,
                  'category'=>$category
                ];
        return response()->json(compact('data'));
    }

    public function add_product(Request $r)
    {
        $products=Product::create([
          'seller_id'=>$this->user_id,
          'category_id'=>$r->category_id,
          'product_name'=>$r->product_name,
          'description'=>$r->description,
          'stock'=>$r->stock,
          'price'=>$r->price,
          'weight'=>$r->weight
        ]);
        $product_id=$products->product_id;

        $file=$r->file('file');
        $product_image_name=time().'.'.$file->getClientOriginalExtension();
        $lokasi_khusus=$this->user_id."_".$this->user->profile->first_name."/produk".$product_id;
        $lokasi=public_path('/uploads/gambar_produk/'.$lokasi_khusus);
        $file->move($lokasi, $product_image_name);

        $product_image=Product_image::create([
          'product_id'=>$product_id,
          'product_image_name'=>$product_image_name
        ]);

        $data = [
                  'product'=>$products,
                  'product_image'=>$product_image,
                ];
        return response()->json(compact('data'));
    }

    public function edit_product(Request $r)
    {
        $products=Product::find($r->product_id)->update([
        'category_id'=>$r->category_id,
        'product_name'=>$r->product_name,
        'description'=>$r->description,
        'stock'=>$r->stock,
        'price'=>$r->price,
        'weight'=>$r->weight
      ]);
      $data = [
                'product'=>$products,
              ];
      return response()->json(compact('data'));
    }

    public function delete_product($product_id)
    {
        $products=Product::find($product_id)->delete();

        $lokasi_khusus=$this->user_id."_".$this->user->profile->first_name."/produk".$product_id;
        $lokasi=public_path('/uploads/gambar_produk/'.$lokasi_khusus);
        $deleteFile=File::deleteDirectory($lokasi);

        $data = [
                  'status_delete'=>$products,
                  'status_delet_image'=>$deleteFile
                ];
        return response()->json(compact('data'));
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
            'product_id'=>$product_id,
            'product_image_name'=>$product_image_name
          ]);
        }

        $data = [
                  'product_id'=>$product_id,
                  'upload_success'=>$upload_success,
                  'product_image_name'=>$product_image_name
                ];
        return response()->json(compact('data'));
    }

    public function submit_product()
    {
        $products=ProductRequest::all();
        $data = [
                  'products'=>$products
                ];
        return response()->json(compact('data'));
    }

    public function article()
    {
        $post=Post::all();
        $data = [
                  'post'=>$post
                ];
        return response()->json(compact('data'));
    }

    public function post_article(Request $r)
    {
        $post=Post::create([
          'writer_id'=>$this->user_id,
          'title'=>$r->title,
          'post'=>$r->post
        ]);

        $file=$r->file('file');
        $image_name=time().'.'.$file->getClientOriginalExtension();
        $lokasi_khusus=$this->user_id."_".$this->user->profile->first_name."/artikel".$post->post_id;
        $lokasi=public_path('/uploads/gambar_artikel/'.$lokasi_khusus);
        $file->move($lokasi, $image_name);

        $post->default_image=$image_name;
        $post->update();

        $data = [
                  'post'=>$post
                ];
        return response()->json(compact('data'));
    }

    public function gallery(Request $r)
    {
        $image_category=Image_category::all();
        $images=Image::all();
        $data = [
                  'image_category'=>$image_category,
                  'images'=>$images
                ];
        return response()->json(compact('data'));
    }

    public function add_image_category(Request $r)
    {
        Image_category::create([
          'image_category_name'=>$r->category_name
        ]);

        $image_category=Image_category::all();
        $data = [
                  'image_category'=>$image_category
                ];
        return response()->json(compact('data'));
    }

    public function add_gallery(Request $r)
    {
        $input = Input::all();
        $file  = Input::file('file');

        $salim=Image_category::find($r->image_category);

        $extension    = $file->getClientOriginalExtension();
        $lokasi_khusus= $r->image_category."_".$salim->image_category_name."/";
        $lokasi       = public_path('/uploads/gallery/'.$lokasi_khusus);
        $image_path   = sha1($file->getClientOriginalName()).time().".{$extension}";

        $upload_success = $file->move($lokasi, $image_path);

        if ($upload_success) {
            $image=Image::create([
            'image_category_id'=>$r->image_category,
            'image_name'=>$file->getClientOriginalName(),
            'image_path'=>$image_path,
            'description'=>$r->description
          ]);
        }

        $data = [
                  'image'=>$image
                ];
        return response()->json(compact('data'));
    }

    public function video()
    {
        $videos=Video::all();
        $data = [
                  'videos'=>$videos
                ];
        return response()->json(compact('data'));
    }

    public function add_videos(Request $r)
    {
        $videos=$r->videos;
        $videos=explode(',',$videos);

        $vi= array();
        $salim=0;
        foreach ($videos as $v) {
          $vid=new Video;
          $vid->video_url=$v;
          $vid->save();
          $vi[$salim]=$v;
          $salim++;
        }
        $data = [
                  'videos'=>$vi
                ];
        return response()->json(compact('data'));
    }

    public function delete_video($video_id)
    {
        $status_delete=Video::find($video_id)->delete();
        $data = [
                  'status_delete'=>$status_delete
                ];
        return response()->json(compact('data'));
    }

    public function information()
    {
        $informations=Information::all();
        $data = [
                  'informations'=>$informations
                ];
        return response()->json(compact('data'));
    }

    public function email()
    {
        $email=User::where('role_id','2')->get();
        $data = [
                  'email'=>$email
                ];
        return response()->json(compact('data'));
    }

    public function send_email(Request $r)
    {
        $u=Email::find(1);
        $text=$r->text;
        $subject=$r->subject;

        $mail=Mail::to($u->email)->send(new Blast_email($u,$text,$subject));

        $data = [
                  'mail'=>$mail
                ];
        return response()->json(compact('data'));
    }

    public function faq()
    {
        $faqs=Faq::all();
        $data = [
                  'faqs'=>$faqs
                ];
        return response()->json(compact('data'));
    }

    public function add_faq(Request $r)
    {
        Faq::create([
          'question'=>$r->question,
          'answer'=>$r->answer
        ]);
        $faqs=Faq::all();
        $data = [
                  'faqs'=>$faqs
                ];
        return response()->json(compact('data'));
    }

    public function edit_faq(Request $r)
    {
        $status_edit=Faq::find($r->faq_id)->update([
          'question'=>$r->question,
          'answer'=>$r->answer
        ]);

        $faqs=Faq::all();
        $data = [
                  'status_edit'=>$status_edit,
                  'faqs'=>$faqs
                ];
        return response()->json(compact('data'));
    }

    public function delete_faq($id)
    {
        $status_delete=Faq::find($id)->delete();
        $faqs=Faq::all();
        $data = [
                  'status_delete'=>$status_delete,
                  'faqs'=>$faqs
                ];
        return response()->json(compact('data'));
    }
}
