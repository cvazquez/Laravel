@extends('layouts.master')

@section('title',"Categories")

@section('body')
	<h1>All Your Categories Belong to Us</h1>

    {{-- Display all active blog categories ---}}
    <ul id="categories">
    @foreach ($categories as $category)
    	<li><a href="/category/{{ $category->URL }}">{{ $category->name }}</a> <span class="categoryEntryCount">~{{ $category->entryCount }}~</span></li>
    @endforeach
    </ul>        

@endsection