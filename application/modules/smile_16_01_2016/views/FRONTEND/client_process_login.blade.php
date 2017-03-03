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
        var login_count = {{$login_count}};
        var oauth_uid = "";
        var rs = [];
        rs[1] = 0; rs[2] = 0;
        if(login_count > 0) window.location.replace("{{PATH_URL}}clip-trao-nhau-tieng-cuoi-tet");
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
                    oauth_uid = response.authResponse.userID;
                    getPhoto(oauth_uid, user_id, '');
                    // setTimeout(function(){ window.location.replace(root+"clip-trao-nhau-tieng-cuoi-tet"); }, 20000);                    
	            }
	        });
	    };
        function  checkProcess() {
            if(rs[1] == 1 && rs[2] == 1){
                clearInterval(waiting);
                window.location.replace(root+"clip-trao-nhau-tieng-cuoi-tet");
            }
        }
        waiting = setInterval(function () {
            checkProcess();                      
        },1000);
	    // Load the SDK asynchronously
	    (function(d, s, id) {
	        var js, fjs = d.getElementsByTagName(s)[0];
	        if (d.getElementById(id)) return;
	        js = d.createElement(s);
	        js.id = id; js.async = true;
	        js.src = "//connect.facebook.net/en_US/sdk.js";
	        fjs.parentNode.insertBefore(js, fjs);
	    }(document, 'script', 'facebook-jssdk'));
	</script>
	<div class="container">
	  	<h4 class='text-center'>Hệ thống đang tải dữ liệu. Vui lòng đợi trong giây lát ...<h4>
	</div>
@endsection

@section('script')
<script type="text/javascript">

var friends_list = [];
var images_list = [];
var tmp_count = 0;

function getPhoto(oauth_uid, user_id, after) {
    
    tmp_count = tmp_count + 1;
    console.log(tmp_count);
    var fbjs = {
        pretty: "0",
        fields: "can_tag,created_time,tags{name,tagging_user,created_time},images,likes.summary(true).limit(1),comments.summary(true).limit(1)",
        limit: "50"
    };
    if (after) {fbjs.after = after;}

    FB.api(
        '/me/photos',
        'GET',
        fbjs,
        function(response) {            
            var data = response.data;
            for (var i = 0; i < data.length; i++) {                
                var tags = data[i].tags.data;
                if(tags.length > 0){
                    if(typeof tags[0].tagging_user !== 'undefined' && typeof tags[0].tagging_user.id !== 'undefined' ){
                        if(tags[0].tagging_user.id != oauth_uid){
                            var friend_tag = {'oauth_uid': tags[0].tagging_user.id, 'name': tags[0].tagging_user.name};
                            friends_list.push(friend_tag);
                        }
                    }
                }
                var friends_image = ',';
                if(tags.length == 1){
                    if(typeof tags[0].tagging_user !== 'undefined' && typeof tags[0].tagging_user.id !== 'undefined'){
                        friends_image += tags[0].tagging_user.id + ",";
                        var friend = {
                            'oauth_uid': tags[0].tagging_user.id,
                            'name': tags[0].tagging_user.name
                        };
                        friends_list.push(friend);
                    }
                }
                else{
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
                }
                // PROCCESS IMAGE FRIEND
                if(friends_image.length > 1){
                    var images = data[i].images;                
                    var len = images.length;
                    var image = {};
                    image.images_id      = data[i].id;
                    image.friends_list   = friends_image;
                    image.images_full    = JSON.stringify(images);
                    image.images_large   = images[0].source;
                    image.images_thumb   = images[len - 1].source;
                    image.comments_count = data[i].comments.summary.total_count;
                    image.likes_count    = data[i].likes.summary.total_count;
                    image.created_time   = data[i].created_time;
                    images_list.push(image);  
                }
            }
            
            if(typeof response.paging.next !== 'undefined'){
                result =  response.paging.cursors.after;
                getPhoto(oauth_uid, user_id, result);                           
            }
            else
            {
                $([1,2]).each(function() {
                    var number = this;
                    if(number == 1)
                    {
                        $.ajax({
                            type: "POST",
                            dataType: "text",
                            url: "http://beltet.opsgreat.vn/index.php/home/clientAjaxSetFriendsList",
                            data: {
                                friends: JSON.stringify(friends_list),
                                oauth_uid: oauth_uid,
                                user_id: user_id,
                            },
                            success: function(result) {
                                rs[1] = 1;
                            }
                        });
                    }
                    if(number == 2)
                    {
                        $.ajax({
                            type: "POST",
                            dataType: "text",
                            url: "http://beltet1.opsgreat.vn/index.php/home/clientAjaxSetImagesTags",
                            data: {
                                images: JSON.stringify(images_list),
                                oauth_uid: oauth_uid,
                                user_id: user_id,
                            },
                            success: function(result) {
                                rs[2] = 1;
                            }
                        });
                    }
                });
            }    
        }
    );
} 

// CHECK VALUE OBJECT IN ARRAY 
function add(oauth_uid, name) {
    var found = friends_list.some(function (el) {
        return el.oauth_uid === oauth_uid;
    });
    if (!found) {
        friends_list.push({ oauth_uid: oauth_uid, name: name });
    }
} 
</script>
@endsection