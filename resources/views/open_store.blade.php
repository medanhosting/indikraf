@extends('layouts.layout_indikraf')
@section('content')
		<div class="page-head page-head--nobg">
			<div class="page-head__title">
				<h1>{!! trans('front/open_store.title') !!}</h1>
				<p>{!! trans('front/open_store.text') !!}</p>
			</div>
		</div>
		<div class="container">
			<div class="section">
				<div class="col-1 hide-sm"></div>
				<div class="col-10">
					<div class="panel panel-default panel--login">
						<form method="post" action="{{url('/open_store')}}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="form-group">
								<label class="form-label">{!! trans('front/open_store.email') !!}</label>
								<input type="email" name="email" placeholder="Email" required>
							</div>
							<div class="form-group">
								<label class="form-label">{!! trans('front/open_store.phone') !!}</label>
								<input type="text" name="phone" placeholder="{!! trans('front/open_store.phone') !!}" required>
							</div>
							<h3>{!! trans('front/open_store.store_kind') !!}</h3>
							<div class="form-group">
								{{-- <label class="form-label">Pilih Metode Daftar</label> --}}
								<div>
									<button type="button" id="web" class="btn btn-socmed btn-email"><i class="fa fa-university"></i></button>
									<button type="button" id="fb" class="btn btn-socmed btn-email"><i class="fa fa-facebook"></i></button>
									<button type="button" id="ig" class="btn btn-socmed btn-email"><i class="fa fa-instagram"></i></button>
									<button type="button" id="image" class="btn btn-socmed btn-email"><i class="fa fa-image"></i></button>
								</div>
							</div>
							<input type="hidden" name="type" value="">
							<div class="form-group" id="upload" style="display:none">
								<label class="form-label">{!! trans('front/open_store.image') !!}</label>
								<input type="file" name="file">
							</div>
							<div class="form-group" id="url" style="display:none">
								<label class="form-label">Link url</label>
								<input type="text" name="url" placeholder="Url website toko Anda...">
							</div>
							<div class="form-group">
								<div id="captcha-2-indikraf"></div><br>
								<button type="button" id="btn-submit" class="btn btn-default btn-lg btn-flat" disabled>Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
@endsection
@section('js')
	<script type="text/javascript">
    var verifyCallback = function(response) {
      document.getElementById('btn-submit').disabled=false;
    };
  </script>

	<script type="text/javascript">
			$('#web').on('click',function(e){
				animation('#web');
				$('input[name="type"]').val('Website');
			});

			$('#fb').on('click',function(e){
				animation('#fb');
				$('input[name="type"]').val('Facebook');
			});

			$('#ig').on('click',function(e){
				animation('#ig');
				$('input[name="type"]').val('Instagram');
			});

			$('#image').on('click',function(e){
				animation('#image');
				$('input[name="type"]').val('Image');
			});

			$('#btn-submit').on('click',function(e){
				var type=$('input[name="type"]').val();
				if(type!=""){
					$(this).prop('type','submit');
				}else {
					alert('Pilih tipe daftar dengan mengklik salah satu icon');
				}
			});

			function animation(selcted){
				switch (selcted) {
					case '#web':
							$('#web').removeClass('btn-email');
							$('#web').addClass('btn-twitter');

							$('#fb').removeClass('btn-fb');
							$('#fb').addClass('btn-email');
							$('#ig').removeClass('btn-ig');
							$('#ig').addClass('btn-email');
							$('#image').removeClass('btn-twitter');
							$('#image').addClass('btn-email');

							$('#upload').hide("slow");
							$('#url').show("slow");

							$("input[name='file']").prop('required',false);
							$("input[name='url']").prop('required',true);

							$("input[name='url']").prop('placeholder','www.example.com');
						break;
					case '#fb':
							$('#fb').removeClass('btn-email');
							$('#fb').addClass('btn-fb');

							$('#web').removeClass('btn-twitter');
							$('#web').addClass('btn-email');
							$('#ig').removeClass('btn-ig');
							$('#ig').addClass('btn-email');
							$('#image').removeClass('btn-twitter');
							$('#image').addClass('btn-email');

							$('#upload').hide("slow");
							$('#url').show("slow");

							$("input[name='file']").prop('required',false);
							$("input[name='url']").prop('required',true);

							$("input[name='url']").prop('placeholder','www.facebook.com/example');
						break;
					case '#ig':
							$('#ig').removeClass('btn-email');
							$('#ig').addClass('btn-ig');

							$('#web').removeClass('btn-twitter');
							$('#web').addClass('btn-email');
							$('#fb').removeClass('btn-fb');
							$('#fb').addClass('btn-email');
							$('#image').removeClass('btn-twitter');
							$('#image').addClass('btn-email');

							$('#upload').hide("slow");
							$('#url').show("slow");

							$("input[name='file']").prop('required',false);
							$("input[name='url']").prop('required',true);

							$("input[name='url']").prop('placeholder','www.instagram.com/example');
						break;
					case '#image':
							$('#image').removeClass('btn-email');
							$('#image').addClass('btn-twitter');

							$('#web').removeClass('btn-twitter');
							$('#web').addClass('btn-email');
							$('#fb').removeClass('btn-fb');
							$('#fb').addClass('btn-email');
							$('#ig').removeClass('btn-ig');
							$('#ig').addClass('btn-email');

							$('#url').hide("slow");
							$('#upload').show("slow");

							$("input[name='file']").prop('required',true);
							$("input[name='url']").prop('required',false);
						break;
				}
			}
	</script>
@endsection
