@extends('layouts.master')

@section('title',"")



@section('body')

<h1>{{ $post[0]->title }}</h1>

<article class="blogPost">
{{-- http://iswwwup.com/t/7eff94350b1d/php-laravel-5-0-pagination-render-method-return-html-text.html --}}
{!! $post[0]->content !!}
</article>
@endSection
