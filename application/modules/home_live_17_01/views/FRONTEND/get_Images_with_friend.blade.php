@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('content')
	<div class="wrapper">
		<div class="container">
			<form action="{{PATH_URL.'home/saveImages'}}" method="post" accept-charset="utf-8" onsubmit="return validateForm();">
				<div class="list-image">
					@foreach ($friendsList as $friend)
						<?php $oauth_uid[$friend->oauth_uid] = $friend->name;?>
					@endforeach
					@foreach ($imagesTags as $valueImage)
						@foreach($valueImage as $image)
					    <div class="col-md-3 col-lg-3 col-xs-4 col-ms-4 items" onclick="choose_images(this)">
					    	<input type="checkbox" class='images-check' name='images[]' value="{{$image->images_large}}" />
					      	<img src="{{$image->images_large}}" alt="" class="img-responsive">
					      	<div class="check"><img src="{{asset('images/check.png')}}" alt="checkbox"></div>
					    </div>
					    @endforeach
					@endforeach
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
		.list-image [class^="col-"]{
		 	padding: 2px; margin-top: 4px;
		}	

		.list-image [class^="col-md-"] img{width: 100%; height: 206px; border: 1px solid rgba(0, 0, 0, .1); padding: 1px;}
		.list-image [class^="col-xs-"] img{width: 100%; height: 120px; border: 1px solid rgba(0, 0, 0, .1); padding: 1px;}
		.list-image [class^="col-ms-"] img{width: 100%; height: 80px; border: 1px solid rgba(0, 0, 0, .1); padding: 1px;}

		.images-check{display: none;}
		.items .check{display: none; position: absolute; top: 0; right: 0}
		.list-image .items.active .check img{width: 27px; height: 24px; border: none; outline: none;}
		.items.active .check{display: block;}
		.list-image .items.active img{border: 1px solid red;}
		.main-nav{width: 100%; float: left;}
		.main-nav ul{list-style: none; width: 100%;}
		.main-nav ul li{width: 49%; float: left; height: 30px;}
	</style>
@endsection

@section('script')
<script type="text/javascript">
	var num_image=0;
	function choose_images(element) {		
		if($(element).children(':checkbox').is(":checked")){
			$(element).removeClass('active');
			$(element).children(':checkbox').prop('checked', false);
			if(num_image) num_image--;
		}else{
			$(element).addClass('active');
			$(element).children(':checkbox').prop('checked', true);
			num_image++;
		}
	}
	function validateForm() {
		if(!num_image) {alert('Vui lòng chọn hình'); return false;}
		if(num_image > 10) {alert('Số lượng hình quá nhiều'); return false;}
	}
</script>
@endsection