@extends('layouts.layout_indikraf')
@section('css')
<style media="screen">
  form .error {
    color: #ff0000;
  }
</style>
@endsection
@section('content')
    <div class="page-head page-head--nobg">
      <div class="page-head__title">
        <h1>{!! trans('front/shopping_cart.title') !!}</h1>
      </div>
    </div>
    <div class="container container--gray">
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10 no-pad">
          @if (Session::has('er_lin'))
            @foreach (Session::get('er_lin') as $e)
              <div class="cart-wrapper clearfix">
                <div class="cart-section--8">
                  <div class="panel panel-default panel-fullscreen panel--cart">
                    <div class="panel-body">
                      <big><i class="fa fa-info-circle"></i></big> {{$e}}
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          @endif
          <div class="cart-wrapper clearfix">
            <div class="cart-section--8">
              <div class="panel panel-default panel-fullscreen panel--cart">
                <div class="panel-body no-pad">
                  <table class="table table--cart table--large">
                    <thead class="thead-hide-sm">
                      <th>{!! trans('front/shopping_cart.col_product') !!}</th>
                      <th></th>
                      <th width="100px">{!! trans('front/shopping_cart.col_price') !!} @</th>
                      <th>{!! trans('front/shopping_cart.col_amount') !!}</th>
                    </thead>
                    <tbody>
                      @php
                        $total_quantity=0;
                        $total_price=0;
                        $total_weight=0;
                      @endphp
                        @foreach ($cart as $c)
                          @php
                            $origin=$c->product->store->store_city;
                            $store_id=$c->product->store->store_id;
                          @endphp
                          <tr class="text-center">
                            <td class="table-cart-produk">
                              @if (count($c->product->product_images)!=0)
                                @php($path="uploads/gambar_produk/".$c->product->seller->user_id."_".$c->product->seller->profile->first_name."/produk".$c->product->product_id)
                                  <img class="img-responsive" src="{{asset($path."/".$c->product->product_images[0]->product_image_name)}}" style="max-height:118px">
                              @else
                                <img src="http://placehold.it/320x150" alt="">
                              @endif
                            </td>
                            <td class="table-cart-name">
                              <h3>{{$c->product->product_name}}</h3>
                              <span class="hide-sm">{{str_limit($c->product->description, $limit = 50, $end = '...')}}</span>
                              <h3 class="price">Rp {{number_format($c->product->price)}}</h3>
                            </td>
                            <td class="table-cart-price">Rp {{number_format($c->product->price)}}</td>
                            <td class="table-cart-quantity">
                              <p class="cancel"><button class="btn btn-close"><i class="fa fa-close"></i></button></p>
                              <br>
                              <p class="label">{!! trans('front/shopping_cart.col_amount') !!}</p>
                              <div class="quantity">
                                {{$c->amount}}x
                              </div>
                            </td>
                          </tr>
                          @php
                            $total_quantity+=$c->amount;
                            $total_weight+=($c->product->weight*$c->amount);
                            $total_price+=$c->product->price*$c->amount;
                          @endphp
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="panel panel-default panel-fullscreen panel--cart">
                <div class="panel-head panel--no-border">{!! trans('front/shopping_cart.destination') !!}</div>
                <div class="panel-body">
                  <!-- Show this if address already inputed -->
                  <form action="{{url('/payment')}}" method="post" id="checkout-form">
                    {{csrf_field()}}
                    @if (count($user->address)>0)
                      @php($salim=1)
                      @foreach ($user->address->slice(0, 1) as $a)
                        <div class="radio-group">
                          <div class="form-group group--radio group--header">
                            <input type="hidden" name="destination" id="destination_{{$a->address_id}}" value="{{$a->city_id}}">
                            <input type="radio" name="address" value="{{$a->address_id}}"> {!! trans('front/shopping_cart.my_address') !!}
                          </div>
                          <div class="div_address" id="address_{{$a->address_id}}" style="display:none">
                            <div class="form-group">
                              <label>{!! trans('front/shopping_cart.name') !!} :</label>
                              <p class="form-field-static">{{$a->first_name." ".$a->last_name}}</p>
                            </div>
                            <div class="form-group">
                              <label>{!! trans('front/shopping_cart.phone') !!} :</label>
                              <p class="form-field-static">{{$a->phone}}</p>
                            </div>
                            <div class="form-group">
                              <label>{!! trans('front/shopping_cart.address') !!} :</label>
                              <p class="form-field-static">{{$a->address}}</p>
                            </div>
                            @php
                              $aa=$a->city;
                            @endphp
                            <div class="form-group">
                              <label>{!! trans('front/shopping_cart.province') !!} :</label>
                              <p class="form-field-static">{{$aa->province->province}}</p>
                            </div>
                            <div class="form-group">
                              <label>{!! trans('front/shopping_cart.city') !!} :</label>
                              <p class="form-field-static">{{$aa->city}}</p>
                            </div>
                            <div class="form-group">
                              <label>{!! trans('front/shopping_cart.postal') !!} :</label>
                              <p class="form-field-static">{{$a->postal_code}}</p>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    @endif
                    <div class="form-group group--radio group--header">
                      <input type="radio" name="address" value="new"> {!! trans('front/shopping_cart.new_address') !!}
                    </div>
                    <div id="form-new" style="display:none">
                        <div class="form-group group--medium">
                          <label>{!! trans('front/shopping_cart.first_name') !!}</label>
                          <input type="text" name="first_name" class="form-field" required>
                        </div>
                        <div class="form-group group--medium">
                          <label>{!! trans('front/shopping_cart.last_name') !!}</label>
                          <input type="text" name="last_name" class="form-field" required>
                        </div>
                        <div class="form-group group--medium">
                          <label>{!! trans('front/shopping_cart.phone') !!}</label>
                          <input type="text" name="phone" class="form-field" required>
                        </div>
                        <div class="form-group group--medium">
                          <label>{!! trans('front/shopping_cart.address') !!}</label>
                          <textarea name="address" class="form-field" required></textarea>
                        </div>
                        <div class="form-group group--medium">
                          <label>{!! trans('front/shopping_cart.province') !!}</label>
                          <select class="form-field" id="province" name="province" required>
                            <option disabled selected>{!! trans('front/shopping_cart.c_province') !!}</option>
                            @foreach ($province as $p)
                              <option value="{{$p['province_id']}}">{{$p['province']}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group group--medium">
                          <label>{!! trans('front/shopping_cart.city') !!}</label>
                          <select class="form-field" id="city" name="city" required>
                            <option disabled selected>{!! trans('front/shopping_cart.c_province') !!}</option>
                          </select>
                        </div>
                        <div class="form-group group--small">
                          <label>{!! trans('front/shopping_cart.postal') !!}</label>
                          <input type="text" name="postal_code" class="form-field" required>
                        </div>
                    </div>
                  <br>&nbsp;
                  <button type="button" class="btn btn-primary btn-panel-submit" id="submit_address">Submit {!! trans('front/shopping_cart.address') !!}</button>
                </div>
              </div>
              <div class="panel panel-default panel-fullscreen panel--cart" id="shipping_panel" style="display:none">
                <div class="panel-head panel--no-border">{!! trans('front/shopping_cart.c_courier') !!}</div>
                <div class="panel-body">
                    <div class="form-group group--medium">
                      <label>{!! trans('front/shopping_cart.c_courier2') !!}</label>
                      <select class="form-field" id="ekspedisi" name="ekspedisi">
                        <option disabled selected>{!! trans('front/shopping_cart.c_courier3') !!}</option>
                        <option value="jne">JNE</option>
                        <option value="pos">POS</option>
                      </select>
                    </div>
                    <div class="form-group group--medium">
                      <label>{!! trans('front/shopping_cart.c_courier4') !!}</label>
                      <select class="form-field" name="tipe" id="tipe">
                        <option value="" disabled selected>{!! trans('front/shopping_cart.c_courier5') !!}</option>
                      </select>
                    </div>
                  <br>&nbsp;
                </div>
              </div>
            </div>
            <div class="cart-section--4">
              <div class="panel panel-default panel-fullscreen panel--cart">
                <div class="panel-body">
                  <div class="summary">
                    <div class="summary-item">
                      <span class="summary-item-left">{!! trans('front/shopping_cart.product_total') !!}</span>
                      <span class="summary-item-right">{{$total_quantity}}</span>
                    </div>
                    <div class="summary-item">
                      <span class="summary-item-left">{!! trans('front/shopping_cart.weight_total') !!}</span>
                      <span class="summary-item-right">{{number_format($total_weight)}} gr</span>
                    </div>
                    <div class="summary-item">
                      <span class="summary-item-left">{!! trans('front/shopping_cart.price_total') !!}</span>
                      <span class="summary-item-right">Rp {{number_format($total_price)}}</span>
                    </div>
                    <br>&nbsp;
                    <div class="summary-item">
                      <span class="summary-item-left">{!! trans('front/shopping_cart.shipping_cost') !!}</span>
                      <span class="summary-item-right shipping_cost"></span>
                    </div>
                    <div class="summary-item">
                      <span class="summary-item-left">{!! trans('front/shopping_cart.payment_total') !!}</span>
                      <span class="summary-item-right text-bold total_payment"></span>
                    </div>
                  </div>
                </div>
                <div class="panel-footer panel--no-border">
                  <div class="text-center">
                    <input type="hidden" name="origin" value="{{$origin}}">
                    <input type="hidden" name="store_id" value="{{$store_id}}">
                    <input type="hidden" name="selected_address" value="">
                    <input type="hidden" name="courier" value="">
                    <input type="hidden" name="courier_type" value="">
                    <input type="hidden" name="shipping_cost" class="shipping_cost" value="">
                    <input type="hidden" name="total_payment" class="total_payment" value="">
                    <button type="submit" id="btn-pay" style="display:none" class="btn btn-primary btn-panel-submit"
                    onclick="event.preventDefault(); document.getElementById('checkout-form').submit();">{!! trans('front/shopping_cart.pay') !!}</button>
                  </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>&nbsp;<br>&nbsp;
    </div>
@endsection
@section('js')
  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
  <script type="text/javascript">
  $(function() {
// Initialize form validation on the registration form.
// It has the name attribute "registration"
    $("#checkout-form").validate({
    // Specify validation rules
    rules: {
          // The key name on the left side is the name attribute
          // of an input field. Validation rules are defined
          // on the right side
          firstname: "required",
          lastname: "required",
          phone: {
            required: true,
            // Specify that email should be validated
            // by the built-in "email" rule
            number: true
          },
          postal_code: {
            required: true,
            minlength: 5
          }
        },
        // Specify validation error messages
        messages: {
          firstname: "Please enter your firstname",
          lastname: "Please enter your lastname",
          postal_code: {
            required: "Please provide a postal code",
            minlength: "Your postal code must be at least 5 characters long"
          },
          phone: "Please enter a valid phone number"
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
      });

      $('#submit_address').on('click',function(e){

          selected_address=$('input[name=address]:checked').val();
          if(selected_address!='new'){
            destination=$('#destination_'+selected_address).val();
            $('#shipping_panel').slideDown();
          }else {
            destination=$('#city').val();
            if ($("#checkout-form").valid()) {
                $('#shipping_panel').slideDown();
            }
          }

          $('input[name=selected_address]').val(selected_address);


          $('input[name=address]').attr('disabled',true);

      });
  });
  </script>
  <script type="text/javascript">

    $(document).ready(function() {
      $('input[name=address]').change(function() {
          if ($(this).val()=="new") {
            $('#form-new').slideDown('slow');
            $('.div_address').fadeOut();
          }else {
            $('#form-new').fadeOut('slow');
            $('.div_address').fadeOut();
            $('#address_'+$(this).val()).slideDown('slow');
          }
      });
    });

    var selected_address;
    var origin={{$origin}};
    var destination;
    var weight={{$total_weight}};
    var total_price={{$total_price}};

    $('#province').on('change',function(e){
      ambil_ajax('provinsi','#city',$(this).val());
    });

    $('select[name="ekspedisi"]').on('change',function(e){
      ambil_ajax('ekspedisi','#tipe',$(this).val(),weight,origin,destination)
    });

    $('select[name="tipe"]').on('change',function(e){
      ambil_ajax('tipe_kirim','.shipping_cost',$('select[name="ekspedisi"]').val(),weight,origin,destination,$(this).val());
    });

    function ambil_ajax(j,k,prov,weight,origin,destination,tipe){
      $.ajax({
        type:"get",
        url:"/ambil_lokasi",
        data:{jenis:j,prov:prov,weight:weight,origin:origin,destination:destination,tipe:tipe},
        success:function(e){
          if(j=='tipe_kirim'){
            $(k).val(e);
            $(k).html("Rp "+$.number(e));
            var total_payment=parseInt(e)+parseInt(total_price);
            $('.total_payment').val(total_payment);
            $('.total_payment').html("Rp "+$.number(total_payment));
            $('input[name=courier]').val($('select[name=ekspedisi] option:selected').text());
            $('input[name=courier_type]').val($('select[name=tipe] option:selected').text());
          }else {
            $(k).html(e);
          }
        },
        complete:function(e){
          $('#btn-pay').fadeIn();
        },
        error:function(e){}
      });
    }
  </script>
@endsection
