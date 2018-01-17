<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


$api=app('Dingo\Api\Routing\Router');

$api->version('v1',['middleware'=>'jwt.auth','namespace' => 'App\Http\Controllers\Api'],function($api){
  $api->get('/admin','Admin@index');
  $api->get('/admin/selling_product','Admin@selling_product');
  $api->post('/admin/add_product','Admin@add_product');
  $api->post('/admin/edit_product','Admin@edit_product');
  $api->get('/admin/delete_product/{product_id}','Admin@delete_product');
  $api->post('/admin/add_product_image','Admin@add_product_image');

  $api->get('/admin/submit_product','Admin@submit_product');

  $api->get('/admin/posting','Admin@article');
  $api->post('/admin/posting','Admin@post_article');
  $api->get('/admin/gallery','Admin@gallery');
  $api->post('/admin/add_image_category','Admin@add_image_category');
  $api->post('/admin/add_gallery','Admin@add_gallery');
  $api->get('/admin/video','Admin@video');
  $api->post('/admin/add_videos','Admin@add_videos');
  $api->get('/admin/delete_video/{video_id}','Admin@delete_video');

  $api->get('/admin/information','Admin@information');

  $api->get('/admin/email','Admin@email');
  $api->post('/admin/email/send_email','Admin@send_email');

  $api->get('/admin/faq','Admin@faq');
  $api->post('/admin/add_faq','Admin@add_faq');
  $api->post('/admin/edit_faq/','Admin@edit_faq');
  $api->get('/admin/delete_faq/{id}','Admin@delete_faq');
});

$api->version('v1',['middleware'=>'jwt.auth','namespace' => 'App\Http\Controllers\Api'],function($api){
  $api->get('/member','Member@index');
  $api->post('/member/profile', 'Member@profile_setting');
  $api->post('/member/profile_image', 'Member@update_profile_image');
  $api->get('/member/transaction', 'Member@transaction');
  $api->post('/member/search_transaction','Member@search_transaction');
  $api->get('/member/transaction_detail/{order_id}','Member@transaction_detail');
  $api->get('/member/review','Member@review');
  $api->post('/member/rating','Member@rating');
});

$api->version('v1',['namespace' => 'App\Http\Controllers\Api'],function($api){
  $api->get('/get_cart','HomeController@get_cart');
  $api->get('/','HomeController@index');
  $api->get('/products','HomeController@products');
  $api->post('/search','HomeController@search');
  $api->get('/getcast','HomeController@getcast');
  $api->post('/filter_products','HomeController@filter_products');
  $api->get('/products/detail_product/{product_id}','HomeController@detail_product');
  $api->post('/add_to_cart','HomeController@add_to_cart');
  $api->get('/shopping_cart','HomeController@shopping_cart');
  $api->get('/shopping_cart/{store_id}','HomeController@detail_cart');
  $api->post('/update_cart','HomeController@update_cart');
  $api->get('/checkout/{store_id}','HomeController@checkout');
  $api->post('/add_address','HomeController@add_address');

  $api->get('/open_store','HomeController@open_store');
  $api->post('/open_store','HomeController@register_store');

  $api->get('/articles','HomeController@articles');
  $api->get('/articles/load_more','HomeController@load_articles');
  $api->get('/article_details/{article_id}','HomeController@article_details');
  $api->post('/comment','HomeController@comment');
  $api->get('/gallery','HomeController@gallery');
  $api->get('/gallery/load_more','HomeController@load_gallery');
  $api->get('/video','HomeController@video');
  $api->get('/faq','HomeController@faq');
  $api->get('/about','HomeController@about');
  $api->get('/contact','HomeController@contact');
  $api->post('/subscribe','HomeController@subscribe');
  $api->get('/getprovince','HomeController@getProvince');
  $api->get('/getcities/{province_id}','HomeController@getCity');
  $api->post('/getcourier/','HomeController@getCourier');
  $api->post('/getshippingprice/','HomeController@getShippingPrice');

  // Auth
  $api->post('/login','AuthController@authenticate');
  $api->post('/register','AuthController@register');
  $api->get('/refresh','AuthController@token');
  $api->post('/password/reset','AuthController@requestPassword');
  $api->post('/password/reset/set','AuthController@setPassword');
  // End Auth
});
