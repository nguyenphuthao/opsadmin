@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
<link rel="stylesheet" href="{{asset('fonts\fontawesome\css\font-awesome.min.css')}}">
<style type="text/css" media="screen">
	/* enable absolute positioning */
	.inner-addon {
	  position: relative;
	}
	/* style glyph */
	.inner-addon .glyphicon {
	  position: absolute;
	  padding: 10px;
	  pointer-events: none;
	}

	/* align glyph */
	.left-addon .glyphicon  { left:  0px;}
	.right-addon .glyphicon { right: 0px;}
</style>
@endsection
@section('content')
<div id="fb-root"></div>
	<script>
		var user_id = {{$user_id}};
    	window.fbAsyncInit = function() {
	        FB.init({
	            appId: '{{FB_CLIENT_ID}}',
	            cookie: true, // enable cookies to allow the server to access 
	            // the session
	            xfbml: true, // parse social plugins on this page
	            version: 'v2.8' // use graph api version 2.8
	        });
	        $(document).trigger('fbload');
	    };

	    // Load the SDK asynchronously
	    (function(d, s, id) {
	        var js, fjs = d.getElementsByTagName(s)[0];
	        if (d.getElementById(id)) return;
	        js = d.createElement(s);
	        js.id = id; js.async = false;
	        js.src = "//connect.facebook.net/en_US/sdk.js";
	        fjs.parentNode.insertBefore(js, fjs);
	    }(document, 'script', 'facebook-jssdk'));
	</script>
	<div class="wrapper">
		<div class="container">
			<h2>Vui lòng chọn bạn</h2>
			
			<div class="col-md-12 form-group">
				<div class="inner-addon right-addon">
			      	<i class="glyphicon glyphicon-search"></i>
			      	<input type="text" class="form-control" placeholder="Tìm kiếm" id='search'/>
			    </div>
		    </div>
			<form action="{{PATH_URL.'home/getImagesWithFriend'}}" method="post" onsubmit="return validateForm()">
				<div class="list-friend">
					@foreach($friends as $value)
					<div class="col-md-4 col-lg-4 col-ms-12 items" onclick='choose_friend(this);'>
						<input type="checkbox" class='friend-check' name='friends[]' value="{{$value->oauth_uid}}" />
						<div class="panel row list">
					      	<div class="panel-body">
					      		<div class="col-md-4 col-ms-4 col-xs-4"><img src="{{$value->picture_url ? $value->picture_url : asset('images/user.jpg')}}" alt="{{$value->name}}"></div>
					      		<div class="col-md-8 col-ms-8 col-xs-8 fullname">{{$value->name}} ({{$value->appear_count}} lượt)</div>
					      	</div>			      	
					    </div>
					    <div class="check"><img src="{{asset('images/check.png')}}" alt="checkbox"></div>
				    </div>
				    @endforeach
				</div>
				<input type="hidden" class='page' value='1'>
			    <div class="main-nav">
			    	<ul>
				    	<li><a href="javascript:;" class='loadmore btn btn-primary'>Tải thêm</a></li>
				    	<!-- <li><a href="javascript:;" class='next btn btn-primary'>Tiếp tục</a></li> -->
				    	<li><button type="submit" value="Submit" class='next btn btn-primary'>Tiếp tục</button></li>
			    	</ul>
			    </div>
		    </form>
	    </div>
	</div>
	<style type="text/css" media="screen">

		.active .list{border: 1px solid #009e55;}
		.list{border: 1px solid #e9ebee; border-radius: 0;}
		.list {[class*="col-"]{padding: 0;}}
		.list [class^="col-"]{
		 	padding: 0;
		}
		.items{position: relative;}
		.list-friend [class^="col-"].items{width: 32%; margin-left: 1%;}
		/*.list-friend .col-ms-12.items{width: 100%;}*/
		.list .panel-body{display: table; width: 100%; padding: 0;}
		.panel img{width: 100px; height: 100px;}
		.fullname{vertical-align: middle; display: table-cell; color: #365899; font-family: 'Helvetica, Arial, sans-serif'; font-weight: bold; font-size: 14px; float: none;}
		.friend-check{display: none;}
		.items .check{display: none; position: absolute; top: 0; right: 0}
		.items.active .check{display: block;}
		.main-nav{width: 100%; float: left;}
		.main-nav ul{list-style: none; width: 100%;}
		.main-nav ul li{width: 49%; float: left; height: 30px;}
	</style>
@endsection
@section('script')
<script type="text/javascript">
	$('.loadmore').click(function () {
		var numfriend = {{$num_friend}};
		var page      = $('.page').val();
		var record    = {{$record}};
		var totalpage = Math.ceil(numfriend/record);
		if(parseInt(totalpage) > parseInt(page)){
			$.ajax({
				type:"POST",
				dataType: "text",
				url: root+"home/ajaxGetFriendsListMore",
				data: { record: record, start: page},
				success:function(result){
					$('.page').val(parseInt(page)+1);
					$('.list-friend').append(result);
				}
			});
		}
	});
	var num_friend = 0;
	function choose_friend(element) {		
		if($(element).children(':checkbox').is(":checked")){
			$(element).removeClass('active');
			$(element).children(':checkbox').prop('checked', false);
			if(num_friend) num_friend--;
		}else{
			$(element).addClass('active');
			$(element).children(':checkbox').prop('checked', true);
			num_friend++;
		}
	}
	function  validateForm() {
		if(!num_friend) {alert('Vui lòng chọn bạn bè'); return false;}
		if(num_friend > 5) {alert('Số lượng bạn bè quá lớn'); return false;}
	}
	$('#search').keyup(function () {
		var text = $(this).val();
		$.ajax({
			type:"POST",
			dataType: "text",
			url: root+"home/ajaxGetFriendSearch",
			data: { name: text },
			success:function(result){
				$('.list-friend').html(result);
			}
		});
	});
	$(document).on(
	    'fbload', 
	    function(){
	    	var allFriends = '{{json_encode($arr_friends)}}';
			allFriends = JSON.parse(allFriends);
	        FB.getLoginStatus(function(res){
	            if( res.status === "connected" ){
	                getAvataUser(allFriends);
	            }
	        });

	    }
	);	
	function getAvataUser(friends) {
		for(var i = 0; i < friends.length; i++){
			FB.api(
				'/'+friends[i],
				'GET',
				{"fields":"picture.width(100)","picture.height(100)":null},
				function(response) {
					if(typeof typeof response.error !== 'undefined'){
						var pic_u = {'oauth_uid' : response.id, 'picture_url' : response.picture.data.url};
						$.ajax({
							type:"POST",
							dataType: "text",
							url: root+"home/clientAjaxUpdateAvatar",
							data: { pic_u: JSON.stringify(pic_u) },
							success:function(result){
								console.log(result);
							}
						});
					}
				}
			);	
		}
	}
	//$('.friend-check[name="friends"]:checked').map(function() {
	// 	    friend_list.push(this.value);
	// 	});
</script>
@endsection

