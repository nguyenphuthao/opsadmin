@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
    <meta property="og:title" content="Cùng Con Bò Cười Tạo Clip 'Trao Nhau Tiếng Cười Tết'" />
    <meta property="og:url" content="{{PATH_URL.uri_string()}}" />
    <meta property="fb:app_id" content="{{FB_CLIENT_ID}}" />
    <meta property="og:type" content="video.other" /> 
    <meta property="og:video" content="{{PATH_URL_PUBLIC.$result->video_file}}" />
    <meta property="og:video:secure_url" content="{{PATH_URL_SECURE.$result->video_file}}" />
    <meta property="og:video:type" content="video/mp4" /> 
    <meta property="og:video:width" content="500" /> 
    <meta property="og:video:height" content="500" /> 
    <meta property="og:description" content="Tạo clip Tiếng Cười ngay để cùng xem bạn và người thân yêu đã có bao nhiêu khoảnh khắc đầy ấp tiếng cười trong năm qua!" />
    <meta property="og:image" content="{{asset('images/share/video/share-bk4.jpg')}}" />
    <meta property="og:image:width" content="500" /> 
    <meta property="og:image:height" content="500" />
    @include('FRONTEND\stylesheet_smile')
    <style>
        #player{width: 100%;}
    </style>
@endsection
@section('content')
    
    @include('FRONTEND\header_smile')

    <section class="section-01">
        <div class="container">
            <!-- Banner -->
            <div class="banner participation-banner">
                <div class="content-banner">
                    <div class="box-text">
                        <div class="info-text">
                            <div class="title-text animated" data-animation="fadeInDown" data-animation-delay="300">
                                <img src="{{asset('images/img-text/text-08.png')}}" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-02">
        <div class="container">
            <!-- participation -->
            <div class="participation page-create-clip">
                <div class="img-left animated" data-animation="fadeInLeft" data-animation-delay="300">
                        <img src="{{asset('images/smile/img-left.png')}}" alt="" />
                </div>
                <div class="img-right animated" data-animation="fadeInRight" data-animation-delay="600">
                    <img src="{{asset('images/smile/img-right.png')}}" alt="" />
                </div>            
                <div class="participation-content">
                    <div class="box-create-clip">
                        <?php if($userData && $userData['oauth_uid'] == $this->uri->segment(2)){?>
                        <div class="step-head">
                            <span>Bước 2: Tạo clip </span>
                        </div>
                        <?php }?>
                        <div class="content-create-clip">
                            <video id="player" controls poster="{{$poster}}">
                                <source src="{{PATH_URL_PUBLIC.$result->video_file}}" type="video/mp4">
                              Your browser does not support HTML5 video.
                            </video>
                        </div>
                    </div>
                    <div class="link-btn">
                        <?php if($userData && $userData['oauth_uid'] == $this->uri->segment(2)){?>
                        <a class="link-prev animated" data-animation="fadeInLeft" data-animation-delay="300" href="{{PATH_URL.'chinh-sua-clip-buoc-1/'.$this->uri->segment(3)}}" onclick="ga('send', 'event', 'Clip', 'ChinhSua', '{{$this->uri->segment(3)}}');">
                            <img src="{{asset('images/home/btn-edit-clip.png')}}" alt="" />
                        </a>
                        <?php }else{?>
                            <a class="link-prev animated" data-animation="fadeInLeft" data-animation-delay="300" href="{{PATH_URL.'thu-vien-tieng-cuoi'}}">
                                <img src="{{asset('images/home/btn-create-clip.png')}}" alt="" />
                            </a>
                        <?php }?>
                        <a class="link-prev animated" data-animation="fadeInLeft" data-animation-delay="300" href="{{PATH_URL_PUBLIC.$result->video_file}}" download onclick="ga('send', 'event', 'Clip', 'TaiClip', '{{$this->uri->segment(2)}}');">
                            <img src="{{asset('images/home/btn-downclip.png')}}" alt="">
                        </a>
                        <a class="link-next animated btn-share" data-animation="fadeInRight" data-animation-delay="500" href="javascript:;">
                            <img src="{{asset('images/home/btn-share-frend.png')}}" alt="" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('FRONTEND/footer_smile')
@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/jquery.appear.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.countTo.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.viewportchecker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/main.js')}}"></script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '{{FB_CLIENT_ID}}',
      xfbml      : true,
      version    : 'v2.1'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<script>
$(document).ready(function() {
    $('.btn-share').click(function() {
        ga('send', 'event', 'Clip', 'ChiaSe', '{{$this->uri->segment(3)}}');
        FB.ui({
          method: 'feed',
          description: 'Tạo clip Tiếng Cười ngay để cùng xem bạn và người thân yêu đã có bao nhiêu khoảnh khắc đầy ấp tiếng cười trong năm qua!',
          link: '{{PATH_URL.uri_string()}}',
          caption: 'Tạo clip trao nhau tiếng cười',
        }, function(response){
            if(response !== 'undefined' && response.post_id !== 'undefined'){
                var oauth_uid = "{{$this->uri->segment(2)}}";
                var video_id = "{{$this->uri->segment(3)}}";
                var link = "{{PATH_URL.uri_string()}}";
                $.ajax({
                    type:"POST",
                    dataType: "text",
                    url: root+"smile/trackingShare",
                    data: {oauth_uid: oauth_uid, video_id: video_id, link: link},
                    success:function(result){
                        console.log(result);
                    }
                });
            }
        });
    });
});
</script> 
@endsection

