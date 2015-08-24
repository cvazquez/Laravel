@extends('layouts.master')

@section('title',"Fitness and Health")

@section('body')
	<h1>{{ $categoryName }}</h1>

	@include('blog.shared.posts')
@endSection