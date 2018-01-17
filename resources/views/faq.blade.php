@extends('layouts.layout_indikraf')

@section('title')
	{{$meta->title}}
@endsection

@section('keywords')
	{{$meta->keyword}}
@endsection

@section('description')
	{{$meta->description}}
@endsection

@section('content')
		<div class="container container--slider">
			<div class="page-head">
				<div class="page-head__title">
					<h1>Frequently Asked Question</h1>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="section">
				<div class="col-1 hide-sm"></div>
				<div class="col-10">
					<div class="faq">
						<div class="faq-body">
							<h2 class="faq-body-title">FAQ</h2>
							<div class="faq-description">
								<p class="faq-description-body no-accordion">
									{!! trans('front/faq.faq') !!}
								</p>
							</div>
						</div>
						<div class="faq-body">
							<h2 class="faq-body-title">Daftar FAQ</h2>
							@foreach ($faq as $f)
								<div class="faq-description">
									<h3 accordion-target="#faq-{{$f->faq_id}}" class="faq-description-title">{{$f->question}}??</h3>
									<div id="faq-{{$f->faq_id}}" class="faq-description-body">
										{!!$f->answer!!}
									</div>
								</div>
							@endforeach
						</div>
					</div>
					<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;
				</div>
			</div>
		</div>
@endsection
