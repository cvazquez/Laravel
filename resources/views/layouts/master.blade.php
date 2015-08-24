<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>@yield('title') By Carlos Vazquez</title>

	 <!--[if lt IE 9]>
	  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	  <![endif]-->

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link href="{{asset('css/all.css') }}" media="all" rel="stylesheet" type="text/css" />


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>

<body>
<div class="container-fluid">

	<header>	
		<div id="title"><a href="/">Carlos Vazquez</a></div>
		<div id="titleCaption">Casually blogging about Website Development, Travel, Fitness, Health and random stuff</div>

		
		<nav id="headerNav">
		<ul id="headerList">
			@foreach ($navCategories as $navCategory)
				<li><a href="/category/{{ $navCategory->categoryURL }}">{{ $navCategory->categoryName }}</a></li>
			@endforeach
			<li><a href="/categories">More Categories</a>
			
		</ul>		      
		</nav>
	</header>
	

	<div id="body">
		@yield('carousel')

		<div id="content">
			@yield('body')
		</div>

		<footer></footer>

	</div> {{-- End id=body --}}
</div>
</body>
</html>