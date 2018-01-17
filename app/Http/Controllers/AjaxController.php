<?php
namespace App\Http\Controllers;

use DB;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductRequest;
use App\Models\Category;
use App\Models\Image;
use App\Models\Video;
use App\Models\Image_category;
use App\Models\Information;
use App\Models\Faq;
use App\Models\Cart;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\City;
use App\Models\Meta;

use Illuminate\Http\Request;
use RajaOngkir;

class AjaxController extends Controller
{
    public function ambil_lokasi(Request $r)
    {
        $jenis=$r->jenis;
        $prov=$r->prov;

        switch ($jenis)
        {
          case 'provinsi':
                $data=City::where('province_id',$prov)->get();
                ?>
                <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                <?php
                foreach ($data as $d) {
                    ?>
                    <option value="<?php echo $d->city_id?>"><?php echo $d->city?></option>
                    <?php
                }
            break;

          case 'ekspedisi':
                $origin=$r->origin;
                $destination=$r->destination;
                $weight=$r->weight;
                $data = RajaOngkir::Cost([
                  'origin'         => $origin, // id kota asal
                  'destination'    => $destination, // id kota tujuan
                  'weight'         => $weight, // berat satuan gram
                  'courier'        => $prov, // kode kurir pengantar ( jne / tiki / pos )
                ])->get();
                ?>
                <option value="" disabled selected>Pilih Tipe Pengiriman</option>
                <?php
                $n=0;
                foreach ($data[0]['costs'] as $d) {
                    ?>
            			<option value="<?php echo $n++?>"><?php echo $d['description']." (".$d['cost'][0]['etd'].($prov=='jne'?" hari":'').")"?></option>
                  <?php
                }
            break;

          case 'tipe_kirim':
                $origin=$r->origin;
                $destination=$r->destination;
                $weight=$r->weight;
                $data = RajaOngkir::Cost([
                  'origin'         => $origin, // id kota asal
                  'destination'    => $destination, // id kota tujuan
                  'weight'         => $weight, // berat satuan gram
                  'courier'        => $prov, // kode kurir pengantar ( jne / tiki / pos )
                ])->get();

                $price=(ceil($data[0]['costs'][$r->tipe]['cost'][0]['value']/1000))*1000;
                echo $price;
            break;
          default:
            # code...
            break;
        }
    }

    public function ed_produk(Request $r)
    {
      return Product::find($r->id)->toJson();
    }

    public function ed_kategori(Request $r)
    {
      return Category::find($r->id)->toJson();
    }

    public function ed_produk_m(Request $r)
    {
      return ProductRequest::find($r->id)->toJson();
    }

    public function ed_store(Request $r)
    {
      return Store::find($r->id)->toJson();
    }

    public function ed_faq(Request $r)
    {
      return Faq::find($r->id)->toJson();
    }

    public function get_information(Request $r)
    {
      return Information::find($r->id)->toJson();
    }

    public function detail_transaction(Request $r)
    {
      $transaction=Transaction::where('order_id',$r->id)->first()->cart;
      return view('Member.modal_transaction',['transaction'=>$transaction]);
      // $cart=Cart::find($r->id);
      //
      // $cart->user->profile=$cart->user->profile;
      // $cart->product=$cart->product;
      // $cart->product->product_images=$cart->product->product_images;
      // $cart->product->category=$cart->product->category;
      // $cart->product->seller=$cart->product->seller;
      // $cart->product->seller->profile=$cart->product->seller->profile;
      //
      // $cart->transaction=$cart->transaction;
      // $cart->transaction->shipping_address->address=$cart->transaction->shipping_address->address;
      //
      // $cart->city=RajaOngkir::Kota()->find($cart->transaction->shipping_address->address->city);
      //
      // return $cart->toJson();
    }

    public function ajax_rating(Request $r){
        $rating=Rating::where([['product_id',$r->id],['user_id',$r->user_id]])->first();
        $product=Product::find($r->id);
        if (count($rating)!=0) {
          return json_encode($json=array(['rating'=>$rating,'product'=>$product]));
        }else {
          return json_encode($product);
        }
    }

    public function ed_image(Request $r)
    {
        return Image::find($r->id)->toJson();
    }

    public function ed_image_category(Request $r)
    {
        return Image_category::find($r->id)->toJson();
    }

    public function ed_video(Request $r)
    {
        return Video::find($r->id)->toJson();
    }

    public function ed_meta(Request $r)
    {
        return Meta::find($r->id)->toJson();
    }

    public function search_products(Request $r)
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
                      $rating_list=implode(',',$rating_list);
                      if ($keyword!=NULL) {
                        // echo "ada semua";
                        $products=Product::where(function ($query) use ($keyword) {
                                            $query->where('product_name', 'LIKE', "%$keyword%")
                                                  ->orWhere('description', 'LIKE', "%$keyword%");
                                         })
                                         ->whereIn('category_id',$category_list)
                                         ->join('stores','stores.store_id','products.store_id')
                                         ->whereIn('stores.store_city',$city_list)
                                         ->join('ratings','ratings.product_id','products.product_id')
                                         ->where('stock','>','0')
                                         ->groupBy('ratings.product_id')
                                         ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                         ->orderBy('products.created_at','desc')
                                         ->paginate(12);
                        // $products->appends($r->only('keyword','category_list','city_list','rating_list'))->links();
                        $products->appends(['keyword'=>$keyword,'rating_list'=>$rating_list,'city_list'=>implode(',',$city_list),'category_list'=>implode(',',$category_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                      }else {
                        // echo "ada semua kecuali keyword";
                        $products=Product::whereIn('category_id',$category_list)
                                         ->join('stores','stores.store_id','products.store_id')
                                         ->whereIn('stores.store_city',$city_list)
                                         ->join('ratings','ratings.product_id','products.product_id')
                                         ->where('stock','>','0')
                                         ->groupBy('ratings.product_id')
                                         ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                         ->orderBy('products.created_at','desc')
                                         ->paginate(12);
                        $products->appends(['rating_list'=>$rating_list,'city_list'=>implode(',',$city_list),'category_list'=>implode(',',$category_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
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
                                         ->whereIn('category_id',$category_list)
                                         ->join('stores','products.store_id','stores.store_id')
                                         ->whereIn('store_city',$city_list)
                                         ->where('stock','>','0')
                                         ->orderBy('products.created_at','desc')
                                         ->paginate(12);
                        $products->appends(['keyword'=>$keyword,'city_list'=>implode(',',$city_list),'category_list'=>implode(',',$category_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                      }
                      else {
                        // echo "ada kategori dan kota";
                        $products=Product::whereBetween('price',[$minimum_price,$maximum_price])
                                         ->whereIn('category_id',$category_list)
                                         ->join('stores','products.store_id','stores.store_id')
                                         ->whereIn('store_city',$city_list)
                                         ->where('stock','>','0')
                                         ->orderBy('products.created_at','desc')
                                         ->paginate(12);
                        $products->appends(['city_list'=>implode(',',$city_list),'category_list'=>implode(',',$category_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                      }
                  }
              }
              else {
                if ($rating_list!=NULL) {
                    $rating_list=implode(',',$rating_list);
                    if ($keyword!=NULL) {
                      // echo "ada keyword, kategori dan rating";
                      $products=Product::where(function ($query) use ($keyword) {
                                          $query->where('product_name', 'LIKE', "%$keyword%")
                                                ->orWhere('description', 'LIKE', "%$keyword%");
                                       })
                                       ->whereBetween('price',[$minimum_price,$maximum_price])
                                       ->whereIn('category_id',$category_list)
                                       ->join('ratings','ratings.product_id','products.product_id')
                                       ->where('stock','>','0')
                                       ->groupBy('ratings.product_id')
                                       ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                      $products->appends(['keyword'=>$keyword,'rating_list'=>$rating_list,'category_list'=>implode(',',$category_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                    }else {
                      // echo "ada kategori dan rating";
                      $products=Product::where(function ($query) use ($keyword) {
                                          $query->where('product_name', 'LIKE', "%$keyword%")
                                                ->orWhere('description', 'LIKE', "%$keyword%");
                                       })
                                       ->whereBetween('price',[$minimum_price,$maximum_price])
                                       ->whereIn('category_id',$category_list)
                                       ->join('ratings','ratings.product_id','products.product_id')
                                       ->where('stock','>','0')
                                       ->groupBy('ratings.product_id')
                                       ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                      $products->appends(['rating_list'=>$rating_list,'category_list'=>implode(',',$category_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
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
                                       ->whereIn('category_id',$category_list)
                                       ->where('stock','>','0')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                      $products->appends(['keyword'=>$keyword,'category_list'=>implode(',',$category_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                    }
                    else {
                      // echo "cuma ada kategori";
                      $products=Product::whereBetween('price',[$minimum_price,$maximum_price])
                                       ->whereIn('category_id',$category_list)
                                       ->where('stock','>','0')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);
                      $products->appends(['category_list'=>implode(',',$category_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                    }
                }
              }
          }
          else {
            if ($city_list!=NULL) {
                if ($rating_list!=NULL) {
                    $rating_list=implode(',',$rating_list);
                    if ($keyword!=NULL) {
                      // echo "ada keyword, kota dan rating";
                      $products=Product::where(function ($query) use ($keyword) {
                                          $query->where('product_name', 'LIKE', "%$keyword%")
                                                ->orWhere('description', 'LIKE', "%$keyword%");
                                       })
                                       ->whereBetween('price', [$minimum_price, $maximum_price])
                                       ->join('stores','products.store_id','stores.store_id')
                                       ->whereIn('store_city',$city_list)
                                       ->join('ratings','ratings.product_id','products.product_id')
                                       ->where('stock','>','0')
                                       ->groupBy('ratings.product_id')
                                       ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);

                      $products->appends(['keyword'=>$keyword,'rating_list'=>$rating_list,'city_list'=>implode(',',$city_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                    }else {
                      // echo "ada kota dan rating";
                      $products=Product::whereBetween('price', [$minimum_price, $maximum_price])
                                       ->join('stores','products.store_id','stores.store_id')
                                       ->whereIn('store_city',$city_list)
                                       ->join('ratings','ratings.product_id','products.product_id')
                                       ->where('stock','>','0')
                                       ->groupBy('ratings.product_id')
                                       ->havingRaw('CEIL(AVG(ratings.rating)) in ('.$rating_list.')')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);

                      $products->appends(['rating_list'=>$rating_list,'city_list'=>implode(',',$city_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
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
                                       ->whereIn('stores.store_city',$city_list)
                                       ->where('stock','>','0')
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);

                      $products->appends(['keyword'=>$keyword,'city_list'=>implode(',',$city_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                    }
                    else {
                      // echo "cuma ada kota";
                      $products=Product::whereBetween('price',[$minimum_price,$maximum_price])
                                       ->join('stores','products.store_id','stores.store_id')
                                       ->where('stock','>','0')
                                       ->whereIn('stores.store_city',$city_list)
                                       ->orderBy('products.created_at','desc')
                                       ->paginate(12);

                      $products->appends(['city_list'=>implode(',',$city_list),'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                    }
                }
            }
            else {
              if ($rating_list!=NULL) {
                  $rating_list=implode(',',$rating_list);
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
                    $products->appends(['rating_list'=>$rating_list,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                  }
              }
              else{
                  if ($keyword!=NULL) {
                    // echo "cuma ada keyword";
                    $products=Product::where(function ($query) use ($keyword) {
                                        $query->where('product_name', 'LIKE', "%$keyword%")
                                              ->orWhere('description', 'LIKE', "%$keyword%");
                                     })
                                     ->where('stock','>','0')
                                     ->whereBetween('price', [$minimum_price, $maximum_price])
                                     ->orderBy('created_at','desc')
                                     ->paginate(12);

                    $products->appends(['keyword'=>$keyword,'minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                  }
                  else {
                    // echo "hapus filter";
                    $products=Product::where('stock','>','0')
                                     ->orderBy('created_at','desc')
                                     ->paginate(12);
  				  $products->appends(['minimum_price'=>$minimum_price,'maximum_price'=>$maximum_price])->links();
                  }
              }
            }
          }
          $products->setPath('search_product');

          return view('filter_product',['products'=>$products]);
      }
}

?>
