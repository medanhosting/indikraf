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
					<h1>{!! trans('front/about.title') !!}</h1>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="section">
				<div class="col-1 hide-sm"></div>
				<div class="col-10">
					<div class="about-text">
						{!!$information->post!!}
					</div>
					<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;
				</div>
			</div>
		</div>
@endsection
