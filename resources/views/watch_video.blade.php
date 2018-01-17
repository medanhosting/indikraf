<div class="rwd">
    <div class="video-popup-player">
       <div class="video-popup-player-ratio">
          <iframe width="640" height="360" src="{{$v->video_url."?iv_load_policy=0&showinfo=0"}}" frameborder="0" allowfullscreen></iframe>
       </div>
    </div>
    <div class="video-popup-detail">
       <h1 class="video-popup-detail-title">{{$v->title}}</h1>
       <p class="video-popup-detail-date">Diunggah {{$v->date_format()}}</p>
       <p class="video-popup-detail-desc">
         {{$v->description}}
       </p>
    </div>
</div>
