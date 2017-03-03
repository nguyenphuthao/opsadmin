@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
    @include('FRONTEND\stylesheet_smile')
    <style type="text/css" media="screen">
      .box-list .pager{margin: 0;}
      .box-list .pagination{margin: 0;}
      .box-list .pagination li{margin-left: 8px; float: left;}
      .box-list .pagination li a{background: none; border: none; color: #fff; font-size: 18px; font-weight: bold; padding: 3px 11px; border-radius: 15px;}
      .box-list .pagination li a:hover, .box-list .pagination li.active a{background: #ffff00; color: #343434;}
    </style>
@endsection
@section('content')
	
    @include('FRONTEND\header_smile')

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
                               <a class="log-face" onclick="ga('send', 'event', 'Clip', 'FBLogin', '');" href="{{$this->session->userdata('userData') ? PATH_URL.'clip-trao-nhau-tieng-cuoi-tet' :$loginUrl}}">
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
                      {{$pageLink}}
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
    @include('FRONTEND/footer_smile')
@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/jquery.appear.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.countTo.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.viewportchecker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/main.js')}}"></script>
@endsection

