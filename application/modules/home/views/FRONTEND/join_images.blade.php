@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
<link rel="stylesheet" href="{{asset('jquery-ui/jquery-ui.min.css')}}">
@endsection
@section('content')
	<div class="wrapper">
		<div class="container">
			<form action="{{PATH_URL.'home/createFileVideo'}}" method="post" accept-charset="utf-8">
				<div class="list-image">
					<div class="image-lib col-md-6 connectedSortable" id="sortable1">
						@if($images_libs)
							@foreach($images_libs as $value)
								<div class="col-md-4"><img class='img-responsive img-sort' data-url='{{PATH_FORDER.$value->images}}' src="{{PATH_URL_PUBLIC.$value->thumbs}}" alt="Thư viện ảnh"></div>
							@endforeach
						@endif
					</div>
					<div class="image-select col-md-6 connectedSortable" id="sortable2">
						<?php $img_sort = '';?>
						@if($images_list)
							@foreach($images_list as $value)
								<div class="col-md-4"><img class='img-responsive img-sort' data-url='{{PATH_FORDER.$value}}' src="{{PATH_URL_PUBLIC.$value}}" alt="Hình ảnh của bạn và những người bạn"></div>
								<?php $img_sort .= PATH_FORDER.$value .',';?>
							@endforeach
						@endif
						<input type="hidden" name='videos' id="img-sort" value="{{$img_sort ? substr($img_sort, 0, -1): ''}}">
					</div>
				</div>
				<div class="main-nav">
					<ul>
				    	<li><button type="submit" value="Submit" class='next btn btn-primary'>Tiếp tục</button></li>
			    	</ul>
				</div>
			</form>
		</div>
	</div>
	<style type="text/css" media="screen">
		.image-lib img{height: 250px; width: 100%;}
		.image-select img{height: 250px; width: 100%;}
		.list-image [class^="col-"]{
		 	padding: 2px; margin-top: 4px;
		}	
		.list-image [class^="col-md-"] img{width: 100%; height: 206px; border: 1px solid rgba(0, 0, 0, .1); padding: 1px;}
		.list-image [class^="col-xs-"] img{width: 100%; height: 120px; border: 1px solid rgba(0, 0, 0, .1); padding: 1px;}
		.list-image [class^="col-ms-"] img{width: 100%; height: 80px; border: 1px solid rgba(0, 0, 0, .1); padding: 1px;}
		.main-nav{width: 100%; float: left;}
		.main-nav ul{list-style: none; width: 100%;}
		.main-nav ul li{width: 49%; float: left; height: 30px;}
	</style>
@endsection

@section('script')

<script type="text/javascript" src="{{asset('jquery-ui/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
	$( "#sortable1, #sortable2" ).sortable({
      	connectWith: ".connectedSortable",
      	stop: function(event, ui) {
	        var data = "";
	        $("#sortable2 .img-sort").each(function(i, el){
	            var p = $(el).attr('data-url');
	            data += p+",";
	        });
	        data = data.slice(0, -1);
	        $('#img-sort').val(data);
	    }
    }).disableSelection();

</script>
@endsection