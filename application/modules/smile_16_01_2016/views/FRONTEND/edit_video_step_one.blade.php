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
                        <img class="img-desktop" src="{{asset('images/home/logo.png')}}" alt="" />
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
                    <!-- Box Edit Clip -->
                    <div class="box-edit-clip">
                        <div class="step-head">
                            <span>Chỉnh sửa clip</span>
                        </div>
                        <form action="{{PATH_URL.'chinh-sua-clip-buoc-2/'.$id}}" method="post" accept-charset="utf-8" onsubmit="return validateForm();">
                            <input type="hidden" name='friends_list' value={{$friends_list}}>
                            <input type="hidden" name='total_like' value="{{$total_like}}">
                            <div class="content-clip">
                                <div class="scrol_clip_1">
                                    <div class="gallery-img clearfix">
                                        @if(count($result) < 6) <p style='color: red'> Số lượng hình ảnh của bạn không đủ để thực hiện clip </p>;
                                        @else
                                        @foreach($result as $key => $item)
                                        <div class="item" onclick="choose_images(this)">
                                            <input type="checkbox" class='images-check' name='images[]' value="{{$item->images_id}}" />
                                            <a href="javascript:;" >
                                                <div class="image">
                                                    <img src="{{$item->images_thumb}}" alt="" />
                                                </div>
                                                <span class="numbers"></span>
                                            </a>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Link -->
                            <div class="link-btn animated" data-animation="fadeInUp" data-animation-delay="300">
                                <button type="submit" value="Submit" class="next"><img src="{{asset('images/home/btn-tiep-tuc.png')}}" alt="" /></button>
                            </div>
                        </form>
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
 <script type="text/javascript" src="{{asset('js/jquery.slimscroll.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.scrol_clip_1').slimScroll({
            height: '476px',
            alwaysVisible: false,
            wheelStep: 2,
            touchScrollStep: 50,
        });
    })
    var num_image=1;
    function choose_images(element) {
        if($(element).children(':checkbox').is(":checked")){
            if(num_image > 0){
                $(element).removeClass('highlight');
                $(element).find('.numbers').html('');
                $(element).removeClass('active');
                $(element).children(':checkbox').prop('checked', false);
                if(num_image) num_image--;
            }
        }else{
            if(num_image <= 6){
                $(element).addClass('highlight');
                $(element).find('.numbers').html(num_image);
                $(element).addClass('active');
                $(element).children(':checkbox').prop('checked', true);
                num_image++;
            }
        }
    }
    function validateForm() {
        if(num_image != 7){
            alert("Số lượng hình ảnh bạn cần chọn là 6 hình"); 
            return false;
        }
    }
</script>
@endsection

