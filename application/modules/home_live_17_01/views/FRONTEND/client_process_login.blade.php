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
                getPhoto(response.authResponse.userID, user_id, '');
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
  function getPhoto(oauth_uid, user_id, after) {
    var result = 0;
    var fbjs = {
        pretty: "0",
        fields: "name_tags,can_tag,tags",
        limit: "200"
    };
    if (after) fbjs.after = after;
    FB.api(
        '/me/photos',
        'GET',
        fbjs,
        function(response) {
            var friends_list = [];
            var images_list = [];
            var data = response.data;
            for (var i = 0; i < data.length; i++) {
                var tags = data[i].tags.data;
                var friends_image = '';
                for (var j = 0; j < tags.length; j++) {
                    var friend = {
                        'oauth_uid': tags[j].id,
                        'name': tags[j].name
                    };
                    if (tags[j].id != oauth_uid) {
                        friends_list.push(friend);
                        friends_image += tags[j].id + ',';
                    }
                }
                if (friends_image.length > 0) {
                    imageID = data[i].id;                    
                    FB.api(
                      imageID,
                      'GET', {
                          "fields": "images,likes.summary(true),comments.summary(true)"
                      },
                      function(res) {
                        var image = {};
                        var len                 = res.images.length;
                        image.images_id      = res.id;
                        image.images_full    = JSON.stringify(res.images);
                        image.images_large   = res.images[0].source;
                        image.images_thumb   = res.images[len - 1].source;
                        image.comments_count = res.comments.summary.total_count;
                        image.likes_count    = res.likes.summary.total_count;
                        $.ajax({
                            type: "POST",
                            dataType: "text",
                            url: root + "home/clientAjaxSetImagesTags",
                            data: {
                                images: JSON.stringify(image),
                                oauth_uid: oauth_uid,
                                user_id: user_id,
                            },
                            success: function(result) {
                            }
                        });
                      }
                    );                                       
                }
            }
            // INSERT FRIEND LIST.
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
            
            if(typeof response.paging.next !== 'undefined'){
              result =  response.paging.cursors.after;
              getPhoto(oauth_uid, user_id, result);              
            }
        }
    );    
  }  
</script>
@endsection