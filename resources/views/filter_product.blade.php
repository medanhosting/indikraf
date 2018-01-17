<div class="pagination">
  <div class="pagination__body rwd">
    @if (count($products))
        @foreach ($products as $p)
        <div class="product-wrap-rating">
          <a href="products/detail_product/{{str_slug($p->product_name)."-999".$p->product_id}}">
            <div class="product-wrap-rating--padding">
              <div class="product-wrap-rating__ratio">
                @if (count($p->product_images)!=0)
                  @php($path="uploads/gambar_produk/".$p->seller->user_id."_".$p->seller->profile->first_name."/produk".$p->product_id)
                    <img src="{{$path."/".$p->product_images[0]->product_image_name}}">
                @else
                  <img src="http://placehold.it/320x150" alt="">
                @endif
              </div>
              <div class="product-wrap-rating__attributes">
                <h3 class="title">{{str_limit($p->product_name,27,'...')}}</h3>
                <p class="author">{{$p->store->store_name}}</p>
                <p class="rwd">
                  <span class="price">Rp. {{number_format($p->price)}}</span>
                  <span class="rating">
                    @php($salim=0)
                    @for ($i = 0; $i < $p->rating(); $i++)
                      @php
                        $salim++
                      @endphp
                      <img src="assets/images/star-on.png" class="rating__star">
                    @endfor
                    @php
                      $arizi=5-$salim;
                    @endphp
                    @for ($i = 0; $i < $arizi; $i++)
                      <img src="assets/images/star-off.png" class="rating__star">
                    @endfor
                  </span>
                </p>
              </div>
            </div>
          </a>
        </div>
        @endforeach
    @else
      <h2>Maaf produk yang anda cari tidak ditemukan</h2>
    @endif
  </div>
  <div class="pagination__foot">
    <ul class="pagination-list">
      @php($link_limit = 10)
      @if ($products->onFirstPage())
      @else
        <li><a href="{{$products->previousPageUrl()}}">&laquo; Prev</a></li>
      @endif

      @if ($products->hasMorePages())
        @for ($i = 1; $i <= $products->lastPage(); $i++)
            @php
            $half_total_links = floor($link_limit / 2);
            $from = $products->currentPage() - $half_total_links;
            $to = $products->currentPage() + $half_total_links;
            if ($products->currentPage() < $half_total_links) {
               $to += $half_total_links - $products->currentPage();
            }
            if ($products->lastPage() - $products->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($products->lastPage() - $products->currentPage()) - 1;
            }
            @endphp
            @if ($from < $i && $i < $to)
                <li class="{{ ($products->currentPage() == $i) ? ' active' : '' }}">
                    <a href="{{ $products->url($i) }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
      @endif

      @if ($products->hasMorePages())
        <li><a href="{{$products->nextPageUrl()}}">Next &raquo;</a></li>
      @else
      @endif
    </ul>
  </div>
</div>
