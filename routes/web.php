<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Models\Transaction;
Route::get('/cek_email',function(){
  $transaction=Transaction::where('order_id','1220170823045617')->first();
  return view('emails.invoice_email', ['transaction'=>$transaction]);
});

Route::get('/ambil_lokasi','AjaxController@ambil_lokasi');

Auth::routes();
Route::get('/register/confirm/{email}/{verification_code}','Email_verification@send_email_done');

// Admin
Route::get('/admin_login','LoginAdmin@index');
Route::post('/admin_login','LoginAdmin@login')->name('admin_login');

Route::get('/admin','Admin@index');
Route::get('/admin/analisys','Admin@analisys');
Route::get('/admin/chart_data','AjaxController@chart_data');
Route::get('/admin/profile','Admin@profile');
Route::post('/admin/profile','Admin@profile_setting');
Route::post('/admin/user_profile','Admin@user_profile_setting');

Route::get('/admin/transaction','Admin@transaction');
Route::get('/admin/transaction/{order_id}','Admin@detail_transaction');
Route::get('/admin/transaction_detail_print/{order_id}','Admin@detail_transaction_print');
Route::post('/admin/change_status_transaction/','Admin@change_status_transaction');
Route::get('/admin/cancel_transaction/{order_id}','Admin@cancel_transaction');

Route::get('/admin/users','Admin@users');
Route::get('/admin/users/{user_id}','Admin@user_detail');
Route::post('admin/makeUserAdmin','Admin@makeAdmin');

Route::get('/admin/selling_product','Admin@selling_product');
Route::get('/admin/product_detail/{product_id}','Admin@product_detail');
Route::get('/admin/ajax_ed','AjaxController@ed_produk');
Route::post('/admin/add_product','Admin@add_product');
Route::post('/admin/edit_product','Admin@edit_product');
Route::get('/admin/delete_product/{product_id}','Admin@delete_product');
Route::post('/admin/add_product_image','Admin@add_product_image');
Route::get('/admin/delete_product_image/{product_image_id}','Admin@delete_product_image');

Route::get('/admin/product_category','Admin@product_category');
Route::get('/admin/ajax_ed_category','AjaxController@ed_kategori');
Route::post('/admin/add_product_category','Admin@add_product_category');
Route::post('/admin/edit_product_category','Admin@edit_product_category');
Route::get('/admin/delete_product_category/{category_id}','Admin@delete_product_category');


Route::get('/admin/store','Admin@store');
Route::post('/admin/store','Admin@store');
Route::get('/admin/store_products/{store_id}','Admin@store_products');
Route::post('/admin/add_store','Admin@add_store');
Route::get('/admin/ajax_ed_store','AjaxController@ed_store');
Route::post('/admin/edit_store','Admin@edit_store');
Route::get('/admin/delete_store/{id}','Admin@delete_store');


Route::get('/admin/submit_product','Admin@submit_product');
Route::get('/admin/delete_submit_product/{id}','Admin@delete_submit_product');

Route::get('/admin/posting','Admin@article');
Route::post('/admin/image_posting','Admin@add_image_article');
Route::get('/admin/post/{post_id}','Admin@article_detail');
Route::post('/admin/posting','Admin@post_article');
Route::get('/admin/delete_post/{post_id}','Admin@delete_article');
Route::get('/admin/update_article/{post_id}','Admin@update_article');
Route::post('/admin/update_article','Admin@edit_article');
Route::post('/admin/comment','Admin@comment');
Route::get('/admin/approve_comment/{comment_id}','Admin@approve_comment');
Route::get('/admin/delete_comment/{comment_id}','Admin@delete_comment');

Route::get('/admin/gallery','Admin@gallery');
Route::post('/admin/add_gallery','Admin@add_gallery');
Route::get('/admin/ajax_ed_image','AjaxController@ed_image');
Route::post('/admin/edit_gallery','Admin@edit_gallery');
Route::get('/admin/delete_gallery/{image_id}','Admin@delete_gallery');

Route::get('/admin/image_category','Admin@image_category');
Route::get('/admin/add_image_category','Admin@add_image_category');
Route::post('/admin/add_image_category','Admin@add_image_category');
Route::get('/admin/ajax_ed_image_category','AjaxController@ed_image_category');
Route::post('/admin/edit_image_category','Admin@edit_image_category');
Route::get('/admin/delete_image_category/{category_id}','Admin@delete_image_category');

Route::get('/admin/video','Admin@video');
Route::get('/admin/ajax_ed_video','AjaxController@ed_video');
Route::post('/admin/add_videos','Admin@add_videos');
Route::post('/admin/edit_video','Admin@edit_video');
Route::get('/admin/delete_video/{video_id}','Admin@delete_video');

Route::get('/admin/information','Admin@information');
Route::get('/admin/get_information','AjaxController@get_information');
Route::post('/admin/control_information','Admin@control_information');
Route::get('/admin/email','Admin@email');
Route::post('/admin/email/send_email','Admin@send_email');
Route::get('/admin/faq','Admin@faq');
Route::post('/admin/add_faq','Admin@add_faq');
Route::get('/admin/ajax_ed_faq','AjaxController@ed_faq');
Route::get('/admin/delete_faq/{id}','Admin@delete_faq');
Route::post('/admin/edit_faq/','Admin@edit_faq');
Route::get('/admin/meta/','Admin@meta');
Route::get('/admin/message','Admin@message');
Route::get('/admin/read_message/{message_id}','Admin@read_message');
Route::get('/admin/delete_message/{message_id}','Admin@delete_message');
Route::get('/admin/ajax_ed_meta','AjaxController@ed_meta');
Route::post('/admin/edit_meta/','Admin@edit_meta');


// Member
Route::get('/member', 'Member@index');
Route::get('/member/transaction', 'Member@transaction');
Route::get('/member/ajax_detail_transaction','AjaxController@detail_transaction');
Route::get('/member/transaction_detail/{order_id}','Member@transaction_detail');
Route::get('/member/search_transaction', 'Member@search_transaction');
Route::post('/member/profile', 'Member@profile_setting');
Route::get('/member/review','Member@review');
Route::get('/member/ajax_rating','AjaxController@ajax_rating');
Route::post('/member/rating','Member@rating');


// Front Page
Route::get('/','HomeController@index');
Route::post('/language-chooser','HomeController@language_chooser');

Route::get('/products','HomeController@products');
Route::get('/search','HomeController@search');
Route::get('/search_product','HomeController@search_product');
Route::get('/search_products','AjaxController@search_products');
Route::get('/search_article','HomeController@search_article');
Route::get('/search_gallery','HomeController@search_gallery');
Route::get('/search_videos','HomeController@search_videos');
Route::get('/products/detail_product/{product_id}','HomeController@detail_product');
Route::post('/add_to_cart','HomeController@add_to_cart');
Route::get('/shopping_cart','HomeController@shopping_cart');
Route::post('/update_cart','HomeController@update_cart');
Route::get('/checkout/{store_id}','HomeController@checkout');

Route::get('/open_store','HomeController@open_store');
Route::post('/open_store','HomeController@register_store');

Route::post('/payment','PaymentController@index');

Route::get('/articles','HomeController@articles');
Route::get('/articles/load_more','HomeController@load_articles');
Route::get('/article_details/{year}/{month}/{article_id}','HomeController@article_details');
Route::post('/comment','HomeController@comment');
Route::get('/gallery','HomeController@gallery');
Route::get('/gallery/load_more','HomeController@load_gallery');
Route::get('/video','HomeController@video');
Route::get('/watch/{video_id}','HomeController@watch_video');
Route::get('/video/load_more','HomeController@load_video');
Route::get('/faq','HomeController@faq');
Route::get('/about','HomeController@about');
Route::get('/contact','HomeController@contact');
Route::post('/send_message','HomeController@send_message');
Route::post('/subscribe','HomeController@subscribe');

// Payment Gateway
Route::get('/vtweb', 'VtwebController@vtweb');

Route::get('/vtdirect', 'VtdirectController@vtdirect');
Route::post('/vtdirect', 'VtdirectController@checkout_process');

Route::get('/vt_transaction', 'TransactionController@transaction');
Route::post('/vt_transaction', 'TransactionController@transaction_process');

Route::post('/vt_notif', 'VtwebController@notification');

Route::get('/snap', 'SnapController@snap');
Route::get('/snaptoken', 'SnapController@token');
Route::post('/snapfinish', 'SnapController@finish');
