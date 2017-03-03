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
        var oauth_uid = "";
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
                    getPhoto(oauth_uid, '');
	            }
	        });
	    };
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
        <div class="row">
            <div class="content"></div>
        </div>
        <!-- <div class="text-center">
            <a href="javascript:;" class='btn-loadmore'>Loadmore</a>    
        </div>  -->       
	</div>
@endsection

@section('script')
<script type="text/javascript">
var next = '';

function getPhoto(oauth_uid, after) {    
    var next = '';
    var fbjs = {
        pretty: "0",
        fields: "images",
        limit: "50"
    };
    if (after) {fbjs.after = after;}

    FB.api(
        '/me/photos',
        'GET',
        fbjs,
        function(response) {            
            var data = response.data;
            for(var i = 0; i < data.length; i++){
                var h = '<div class="col-md-3"><div class="panel row list"><div class="panel-body"><img src="'+data[i].images[0].source+'" alt="" class="img-responsive"></div></div></div>';
                $('.content').append(h);
            }
            console.log(response);
            if(typeof response.paging.next !== 'undefined'){
                result =  response.paging.cursors.after;
                getPhoto(oauth_uid, result);                           
            }
        }
    );
} 

</script>
@endsection