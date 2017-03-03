@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/reset.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/smile.css')}}">
    <!-- animate -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/animate.min.css')}}" />
    

@endsection
@section('content')
	
    <!-- Begin Header -->
    <header class="header">
        <div class="container">
            <!-- Nav top menu -->
            <div class="nav-top-menu">
                <div class="logo">
                    <a href="index.html">
                        <img class="img-desktop" src="{{asset('images/smile/logo.png')}}" alt="" />
                        <img class="img-mobile" src="{{asset('images/smile/logo-mobile.png')}}" alt="" />
                    </a>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.html"><span>Trang chủ</span></a></li>
                    <li><a href="tham-gia.html"><span>Tham gia</span></a></li>
                    <li class="active"><a href="thu-vien-tieng-cuoi.html"><span>Thư viện tiếng cười</span></a></li>
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
            <div class="banner library-banner">
               <div class="content-banner">
                   <div class="box-text">
                       <div class="info-text">
                           <div class="title-text animated" data-animation="fadeInDown" data-animation-delay="300">
                               <img src="{{asset('images/img-text/text-08.png')}}" alt="" />
                           </div>
                           <div class="block-count animated" data-animation="fadeInUp" data-animation-delay="600">
                               <p class="p-numb">Đã có <span class="span-count" data-to="{{$total}}" data-speed="3000">{{$total}}</span> người </p>
                               <p>Tham gia tạo clip</p>
                               <p>“Trao nhau tiếng cười Tết”</p>
                           </div>
                           <div class="link-log-face animated" data-animation="fadeInUp" data-animation-delay="900">
                               <a class="log-face" href="{{$this->session->userdata('userData') ? PATH_URL.'clip-trao-nhau-tieng-cuoi-tet' :$loginUrl}}">
                                   <img src="{{asset('images/home/btn-log-face.png')}}" alt="" />
                               </a>
                           </div>
                           <div class="link-rules animated" data-animation="fadeInUp" data-animation-delay="1200">
                               <a href="#">Thể lệ và điều khoản</a>
                           </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </section>

    <section class="section-02">
        <div class="container">
            <!-- Participants -->
            <div class="participants library-participants">
                <div class="title-text animated" data-animation="fadeInUp" data-animation-delay="1500">
                    <p>Những người đã tham gia</p>
                </div>
                <div class="box-list">
                    @if($pageLink)
                    <div class="animated" data-animation="fadeInUp" data-animation-delay="300">
                        <div class="paging">
                            <a class="first" href="#">Trước</a>
                            <a class="active" href="#">1</a>
                            <a href="#">2</a>
                            <a href="#">3</a>
                            <a href="#">4</a>
                            <a href="#">5</a>
                            <a href="#">6</a>
                            <a href="#">7</a>
                            <a href="#">8</a>
                            <a class="dot-last">...</a>
                            <a class="last" href="#">Xem tất cả</a>
                        </div>
                    </div>
                    @endif
                    <div class="list-show clearfix">
                    @if($result)
                    @foreach($result as $value)
                        <div class="item">
                            <a href="#">
                                <div class="image">
                                    <img src="{{PATH_URL_PUBLIC.$value->avatar_image}}" alt="" />
                                </div>
                                <div class="caption-text">
                                    <span>{{$value->name}}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                    @endif
                    </div>
                    <!-- pagelink -->
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
@endsection

