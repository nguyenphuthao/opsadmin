@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
<!-- <link rel="stylesheet" href="{{asset('jquery-ui/jquery-ui.min.css')}}"> -->
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
	        FB.getLoginStatus(function(response) {
	            if (response.status === 'connected') {
                getPhoto(user_id);
                window.location.replace(root+"home/clientGetFriendsList");
	            }
	        });
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
	<div class="container">
	  	
	</div>
@endsection

@section('script')
<script type="text/javascript">
  function getPhoto(user_id) {
    FB.api(
        '/me/',
        'GET',
        {"fields":"friends.limit(5000){name}"},
        function(response) {
            var friend = {
                'oauth_uid': tags[j].id,
                'name': tags[j].name
            };
            friends_list.push(friend);
        }
    );
    $.ajax({
        type: "POST",
        dataType: "text",
        url: root + "home/clientAjaxSetFriendsList",
        data: {
            friends: JSON.stringify(friends_list),
            oauth_uid: oauth_uid,
            user_id: user_id,
        },
        success: function(result) {
        }
    });
  }  
</script>
@endsection