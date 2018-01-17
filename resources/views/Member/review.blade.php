@extends('layouts.layout_indikraf')

@section('content')
  <main class="main-section">
    @include('Member.header_member')
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <div class="panel panel-default panel-fullscreen">
            <table class="table table--review table--large">
              <thead class="text-primary">
                <th width="80px">#</th>
                <th width="120px">{!! trans('member/review.c_product') !!}</th>
                <th class="table-hide-sm">{!! trans('member/review.c_review') !!}</th>
                <th width="160px">{!! trans('member/review.m_rate') !!}</th>
                <th width="160px">{!! trans('member/review.action') !!}</th>
              </thead>
              <tbody class="text-center">
                @php($n=1)
                @foreach ($products as $p)
                  <tr>
                    <th>{{$n++}}</th>
                    <td>
                      @if (count($p->product->product_images)!=0)
  			                @php($path="uploads/gambar_produk/".$p->product->seller->user_id."_".$p->product->seller->profile->first_name."/produk".$p->product->product_id)
  												<img src="{{asset($path."/".$p->product->product_images[0]->product_image_name)}}" class="img-responsive">
  			              @else
  			                <img src="http://placehold.it/320x150" class="img-responsive">
  			              @endif
                    </td>
                    <td class="text-left table-hide-sm">
                      @if ($p->product->my_rating($user->user_id)!="0")
                        {{$p->product->my_rating($user->user_id)->comments}}
                      @else
                        {!! trans('member/review.mn_rate') !!}
                      @endif
                    </td>
                    <td>
                        @if ($p->product->my_rating($user->user_id)!="0")
                          @php
                            $rating=$p->product->my_rating($user->user_id)->rating;
                            $salim=0;
                          @endphp
                          @for ($i = 0; $i < $rating; $i++)
                            @php
                              $salim++
                            @endphp
                            <img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
                          @endfor
                          @php
                            $arizi=5-$salim;
                          @endphp
                          @for ($i = 0; $i < $arizi; $i++)
                            <img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
                          @endfor
                        @else
                          {!! trans('member/review.not_rated') !!}
                        @endif
                    </td>
                    <td>
                      @if ($p->product->my_rating($user->user_id)=="0")
                        <button class="btn btn-primary review" onclick="ajax_rating({{$p->product_id}})">Review</button>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="table-footer table-footer--bordered">
              {{$products->links()}}
              <span class="record-track pull-right">{!! trans('member/review.show') !!} {{count($products)}} {!! trans('member/review.from') !!} {{$products->total()}}</span>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
      <br>&nbsp;<br>&nbsp;
    </div>
  </main>
@endsection
@section('js')
  <script type="text/javascript">
    function ajax_rating(id){
      $.ajax({
        type:'get',
        url:'/member/ajax_rating',
        data:{id:id,user_id:{{$user->user_id}}},
        success:function(salim){
            var arizi=JSON.parse(salim);
            var product_name=arizi['product_name'];
            $('#modal_review #product_name').text(product_name);
            $('#modal_review input[name=product_id]').val(id);
        },
        error:function(e){}
      });
    }

    $("#send-review").click(function() {
      if($(this).text()=="Kirim Ulasan"){
        alert('Mohon periksa review Anda, karena review ini tidak bisa diulangi/dihapus. Anda yakin dengan review Anda?')
        $(this).text('Saya Yakin');
      }else {
        $(this).attr('type','submit');
      }
    });
  </script>
@endsection
