@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('content')
  <script type='text/javascript'> 
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '{{FB_CLIENT_ID}}',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.8' // use graph api version 2.8
  });
};
  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
  
</script>
<div class="container">
  <a href="javascript:;" onclick="login();">Click me</a>
</div>
@endsection

@section('script')
<script type="text/javascript">
  function login(){
    FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        // FB.api(
        //   '/me',
        //   'GET',
        //   {"fields":"id,name,first_name,last_name,email,gender,locale,picture"},
        //   function(response) {
        //     $.ajax({
        //       type:"POST",
        //       dataType: "text",
        //       url: root+"home/ajaxLogin",
        //       data: response,
        //       success:function(result){
        //         console.log(result);
        //       }
        //     });
        //   }
        // );
        getPhoto('', response.authResponse.userID);
      }else{
        alert("Connect Facebook Error");
      }
    });    
  }
  function getPhoto(after, id) {
    var fbjs = {pretty: "0", fields: "name_tags,can_tag,tags", limit: "200"};
    if(after) fbjs.after=after;
    FB.api(
      '/me/photos',
      'GET',
      fbjs,
      function(response) {
        friends_list = [];
        var data = response.data;
        for(var i = 0; i < data.length; i++){
          var tags = data[i].tags.data;
          for(var j = 0; j < tags.length; j++){
            var friend = {'oauth_uid': tags[j].id, 'name': tags[j].name};
            if(tags[j].id != id) friends_list.push(friend);
          }
          imageID = data[i].id;
        }
        // INSERT FRIEND LIST.
        $.ajax({
          type:"POST",
          dataType: "text",
          url: root+"home/ajaxGetFriendsList",
          data: {friends : JSON.stringify(friends_list), oauth_uid: id},
          success:function(result){
            console.log(result);
          }
        });

      }
    );
  }
</script>
@endsection
