@php
  $quantity=0;
  $total_weight=0;
  $total_price=0;
@endphp
<div class="col-8 border-right no-pad">
  <div class="rwd">
    <div class="col-12 border-bottom pad">
      <div class="product-highlight-wrapper" style="max-height:160px; overflow-y:auto;">

        <?php foreach ($transaction as $t): ?>
          <div class="product-highlight">
            <div class="product-image">
              <div class="product-ratio">
                <?php $path="uploads/gambar_produk/".$t->product->seller->user_id."_".$t->product->seller->profile->first_name."/produk".$t->product->product_id?>
                <img src="<?php echo asset($path."/".$t->product->product_images[0]->product_image_name)?>">
              </div>
            </div>
            <div class="product-attributes">
              <h4><?php echo $t->product->product_name?></h4>
              <p>
                Rp <?php echo number_format($t->price)?><br>
                <?php echo $t->amount." buah"?><br>
                <?php echo $t->product->description?>.
              </p>
            </div>
          </div>
        <?php
          $status=$t->transaction->status;

          $date=$t->transaction->date_format();
          $time=$t->transaction->time_format();
          $quantity+=$t->amount;
          $total_weight+=$t->product->weight;
          $total_price=$t->transaction->cart->sum('total_price');
          $shipping_price=$t->transaction->shipping_price;
          $payment_method=$t->transaction->payment_method;
          $courier=$t->transaction->courier;
          $courier_type=$t->transaction->courier_type;
          $resi=$t->transaction->tracking_number;

          $name=$t->transaction->shipping_address->address->first_name." ".$t->transaction->shipping_address->address->last_name;
          $address=$t->transaction->shipping_address->address;
          $city=$t->transaction->shipping_address->address->city;
          endforeach;
        ?>
      </div>
    </div>
    <div class="col-12 border-bottom pad">
      <table class="table">
        <tr>
          <td class="text-left">{!! trans('member/transaction.date') !!}</td>
          <td class="text-right" id="transaction_date"><?php echo $date." ".$time?></td>
        </tr>
        <tr>
          <td class="text-left">{!! trans('member/transaction.quantity') !!}</td>
          <td class="text-right" id="quantity"><?php echo $quantity?></td>
        </tr>
        <tr>
          <td class="text-left">{!! trans('member/transaction.total_weight') !!}</td>
          <td class="text-right" id="weight"><?php echo number_format($total_weight)?> gr</td>
        </tr>
        <tr>
          <td class="text-left">{!! trans('member/transaction.total_price') !!}</td>
          <td class="text-right" id="total_price"><?php echo number_format($total_price)?></td>
        </tr>
      </table>
    </div>
    <div class="col-12 pad">
      <table class="table">
        <tr>
          <td class="text-left">{!! trans('member/transaction.shipping_cost') !!}</td>
          <td class="text-right" id="shipping_price"><?php echo number_format($shipping_price)?></td>
        </tr>
        <tr>
          <td class="text-left">{!! trans('member/transaction.total_payment') !!}</td>
          <td class="text-right text-bold" id="total_price_2"><?php echo number_format($total_price+$shipping_price)?></td>
        </tr>
        <tr class="separator"></tr>
        <tr>
          <td class="text-left">{!! trans('member/transaction.total_price') !!}</td>
          <td class="text-right" id="total_price_3"><?php echo number_format($total_price+$shipping_price)?></td>
        </tr>
        <tr>
          <td class="text-left">{!! trans('member/transaction.payment_method') !!}</td>
          <td class="text-right" id="payment_method"><?php echo $payment_method?></td>
        </tr>
        <tr>
          <td class="text-left">{!! trans('member/transaction.courier') !!}</td>
          <td class="text-right" id="courier"><?php echo $courier?></td>
        </tr>
        <tr>
          <td class="text-left">{!! trans('member/transaction.courier_type') !!}</td>
          <td class="text-right" id="courier_type"><?php echo $courier_type?></td>
        </tr>
        <tr>
          <td class="text-left">{!! trans('member/transaction.tracking_number') !!}</td>
          <td class="text-right">
            <button type="button" class="text-link" style="border:none; background:none;" data-clipboard-action="copy" data-clipboard-target="#resi">{!! trans('member/transaction.copy') !!}</button>
            <input type="text" id="resi" name="resi" readonly="" value="<?php echo $resi==NULL?'Belum ada resi':$resi?>" class="form-field" style="max-width: 140px">
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="col-4 no-pad">
  <div class="col-12 border-bottom pad">
    <h3 class="text-info">{!! trans('member/transaction.destination') !!}</h3>
    <p class="wider">{!! trans('member/transaction.d_name') !!} <br class="hide-sm"><span id="name"><?php echo $name?></span></p>
    <p class="wider">{!! trans('member/transaction.d_address') !!} <br class="hide-sm"><?php echo $address->address?>.<?php echo $city->city.", ".$city->province->province.". ".$address->postal_code?></p>
    <p class="wider">{!! trans('member/transaction.phone') !!} <br clear="hide-sm"><span id="phone"><?php echo $address->phone?></span></p>
  </div>
  <?php
    $array_status = array(
                  1=>"Menunggu Pembayaran",
                  2=>"Pembayaran Diterima",
                  3=>"Barang Diproses",
                  4=>"Barang Dikirim",
                  5=>"Selesai"
              );

    $index=array_search($status,$array_status);
  ?>
  <div class="col-12 pad">
    <h3 class="text-info">{!! trans('member/transaction.transaction_status') !!}</h3>
    <table class="table">
      <?php if ($index!=false){
        for ($i = 1; $i <= $index; $i++){
        ?>
          <tr>
            <td class="no-pad"><?php echo $array_status[$i]?></td>
            <td class="text-success text-right"><i class="fa fa-check-circle-o"></i></td>
          </tr>
        <?php
          }
          for ($i = $index+1; $i <= 6-$index; $i++){
            ?>
            <tr>
              <td class="no-pad"><?php echo $array_status[$i]?></td>
              <td class="text-default text-right"><i class="fa fa-check-circle-o"></i></td>
            </tr>
            <?php
          }
        }else{
        ?>
        <tr>
          <td class="no-pad"><font color="red"><?php echo $status?></font></td>
          <td class="text-right"><font color="red"><i class="fa fa-close"></i></font></td>
        </tr>
      <?php
      }?>
    </table>
  </div>
</div>
