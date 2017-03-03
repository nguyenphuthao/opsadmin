@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
    <meta property="og:url" content="{{base_url(uri_string())}}" />
    <meta property="fb:app_id" content="{{FB_CLIENT_ID}}" />
    <meta property="og:type" content="video.other" /> 
    <meta property="og:video" content="{{PATH_URL_PUBLIC.$result->video_file}}" />
    <meta property="og:video:secure_url" content="{{$poster}}" /> 
    <meta property="og:video:type" content="video/mp4" /> 
    <meta property="og:video:width" content="500" /> 
    <meta property="og:video:height" content="500" /> 
    <meta property="og:title" content="Cùng Con Bò Cười Tạo Clip 'Trao Nhau Tiếng Cười Tết'" />
    <meta property="og:description" content="Tạo clip Tiếng Cười ngay để cùng xem bạn và người thân yêu đã có bao nhiêu khoảnh khắc đầy ấp tiếng cười trong năm qua!" />
    <meta property="og:image" content="{{asset('images/share/share.jpg')}}" />

    <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/reset.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/smile.css')}}">
    <!-- animate -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/animate.min.css')}}" />
    <style>
        #player{margin: 0 auto;}
    </style>

@endsection
@section('content')
    
    <!-- Begin Header -->
    <header class="header">
        <div class="container">
            <!-- Nav top menu -->
            <div class="nav-top-menu">
                <div class="logo">
                    <a href="index.html">
                        <img class="img-desktop" src="{{asset('images/home/logo.png')}}" alt=""/>
                        <img class="img-mobile" src="{{asset('images/home/logo-mobile.png')}}" alt="" />
                    </a>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.html"><span>Trang chủ</span></a></li>
                    <li class="active"><a href="tham-gia.html"><span>Tham gia</span></a></li>
                    <li><a href="thu-vien-tieng-cuoi.html"><span>Thư viện tiếng cười</span></a></li>
                </ul>
                <!-- Menu-mobile -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-content" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
        </div>
        <!-- End Nav top menu-->
    </header>
    <!-- End Header -->

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
            <div class="participation">
                <div class="participation-content">
                    <div class="img-left animated" data-animation="fadeInLeft" data-animation-delay="300">
                        <img src="{{asset('images/smile/img-left.png')}}" alt="" />
                    </div>
                    <div class="img-right animated" data-animation="fadeInRight" data-animation-delay="600">
                        <img src="{{asset('images/smile/img-right.png')}}" alt="" />
                    </div>             
                    <!-- Box Edit Clip -->
                    <div class="box-edit-clip">
                        <div class="content-clip clip-detail">                            
                            <div class="block-clip-video">
                                <video id="player" controls poster="{{$poster}}">
                                    <source src="{{PATH_URL_PUBLIC.$result->video_file}}" type="video/mp4">
                                  Your browser does not support HTML5 video.
                                </video>
                            </div>
                            <div class="bt" style='width: 502px; margin: 8px auto'>
                                <?php $segment = $this->uri->segment(2);?>
                                <ul>
                                    <li style='float: left; width: 240px'><a href="{{PATH_URL.'chinh-sua-clip-buoc-1/'.$segment}}" ><img src="{{asset('images/home/btn-edit-clip.png')}}" alt=""></a></li>
                                    <li style='float: left; width: 240px'><a href="javascript:;" class=' btn-share'><img src="{{asset('images/home/btn-share-frend.png')}}" alt=""></a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="bg-footer">
                <div class="img-footer"></div>             
            </div>
        </div>
    </footer>
    <!-- End Footer -->
@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/jquery.appear.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.countTo.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.viewportchecker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/main.js')}}"></script>
<script>
var myVideo = document.getElementById("player");
var windowWidth = $(window).width();
$(window).resize(function(){
    windowWidth = $(window).width();
    scale(windowWidth);
});
function scale (windowWidth) {
    if(windowWidth >= 1024) myVideo.width = 500;
    if(windowWidth < 1024 && windowWidth >= 980) myVideo.width = 500;
    if(windowWidth < 980 && windowWidth >= 800) myVideo.width = 500;
    if(windowWidth < 800 && windowWidth >= 780) myVideo.width = 500;
    if(windowWidth < 780 && windowWidth >= 768) myVideo.width = 500;
    if(windowWidth < 768 && windowWidth >= 720) myVideo.width = 500;
    if(windowWidth < 720 && windowWidth >= 640) myVideo.width = 500;
    if(windowWidth < 640 && windowWidth >= 600) myVideo.width = 500;
    if(windowWidth < 600 && windowWidth >= 568) myVideo.width = 500;
    if(windowWidth < 568 && windowWidth >= 563) myVideo.width = 500;
    if(windowWidth < 563 && windowWidth >= 549) myVideo.width = 500;
    if(windowWidth < 549 && windowWidth >= 534) myVideo.width = 500;
    if(windowWidth < 534 && windowWidth >= 480) myVideo.width = 460;
    if(windowWidth < 480 && windowWidth >= 360) myVideo.width = 360;
    if(windowWidth < 360 && windowWidth >= 338) myVideo.width = 320;
    if(windowWidth < 338 && windowWidth >= 330) myVideo.width = 320;
    if(windowWidth <= 320) myVideo.width = 320;
}
$(document).ready(function(){
    scale(windowWidth);
})
</script> 
@endsection

