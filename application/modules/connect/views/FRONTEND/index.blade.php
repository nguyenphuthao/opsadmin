@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/animate.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/reset.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css?1')}}">
@endsection
@section('content')
	
    <!-- Begin Header -->
    <header class="header">
        <div class="container">
            <!-- Nav top menu -->
            <div class="nav-top-menu">
                <div class="logo">
                    <a href="{{PATH_URL_PUBLIC}}">
                        <img class="img-desktop" src="{{asset('images/home/logo.png')}}" alt=""/>
                        <img class="img-mobile" src="{{asset('images/home/logo-mobile.png')}}" alt=""/>
                    </a>
                </div>
                <ul class="nav-menu">
                    <li class="active"><a href="{{PATH_URL_PUBLIC}}"><span>Trang chủ</span></a></li>
                    <li><a href="{{PATH_URL}}tham-gia-ket-noi-tieng-cuoi"><span>Cổng kết nối Tiếng cười</span></a></li>
                    <li><a href="{{PATH_URL}}thu-vien-tieng-cuoi"><span>Thư viện tiếng cười</span></a></li>
                    <!--<li><a href="javascript:;"><span>Trao Tiếng cười Tết</span></a></li>-->
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
            <div class="banner">
                <div class="image">
                    <img src="{{asset('images/banner/01.jpg"')}}  alt=""/>
                    <div class="link-video animated" data-animation="fadeIn" data-animation-delay="300">
                        <a href="javascrip:void(0)" data-video="https://www.youtube.com/embed/dW3PnlM8-pk" data-toggle="modal" data-target="#video_Modal">
                            <img src="{{asset('images/home/link-video.png')}}" alt=""/>
                        </a>
                    </div>
                </div>

            </div>
            <!-- Box Content -->
            <div class="box-content bg-01">
                <div class="box-text first">
                    <div class="info-text">
                        <div class="title-text animated" data-animation="fadeInDown" data-animation-delay="300">
                            <img src="{{asset('images/img-text/text-01.png')}}" alt="" />
                        </div>
                        <p class="p-text animated" data-animation="fadeInUp" data-animation-delay="600">
                            Dù có thế nào, dù ở nơi đâu - khi chúng ta trao nhau tiếng cười, dành cho nhau sự quan tâm chia sẻ, đó cũng chính là lúc ta thực sự có Tết.
                        </p>
                        <p class="p-text animated" data-animation="fadeInUp" data-animation-delay="600">
                            Hãy cùng Con Bò Cười lan tỏa tiếng Cười để Tết này thêm rộn ràng, vui tươi.
                        </p>
                    </div>
                </div>
                <div class="box-geography">
                    <div class="col-group clearfix">
                        <div class="col-left">
                            <div class="box-text">
                                <div class="info-text">
                                    <div class="title-text animated" data-animation="fadeInDown" data-animation-delay="300">
                                        <img src="{{asset('images/img-text/text-07.png')}}" alt="" />
                                    </div>
                                    <p class="p-text animated" data-animation="fadeInUp" data-animation-delay="600">
                                        Cùng người thân phương xa trải nghiệm đón Tết bên nhau, xóa nhòa khoảng cách địa lý, tại 2 Cổng kết nối Tiếng cười<br> <span>Hà Nội và TP.HCM </span> và có cơ hội giành hai chuyến Du Xuân rộn tiếng cười cùng nhau trị giá <span> 15.000.000 VNĐ </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-right">
                            <div class="image animated" data-animation="fadeInRight" data-animation-delay="600">
                                <img src="{{asset('images/home/img-vn.png')}}" alt="" />
                            </div>
                        </div>
                    </div>
					
					<div class="experience">
                        <h3 class="title animated" data-animation="fadeInUp" data-animation-delay="900">Trải nghiệm Vui Tết bên nhau, xóa nhòa khoảng cách địa lý</h3>
                        <ul class="list-item">
                            <li>
                                <div class="item first animated" data-animation="fadeInLeft" data-animation-delay="300">
                                    <div class="image">
                                        <a href="javascript:void(0)">
                                            <img src="{{asset('images/home/avatar-01.png')}}" alt="" />
                                        </a>
                                    </div>
                                    <div class="info-text">
                                        <a href="javascript:void(0)">Cùng vượt thử thách vui nhộn và nhận <span> quà hấp dẫn</span></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item second animated" data-animation="fadeInRight" data-animation-delay="500">
                                    <div class="image">
                                        <a href="javascript:void(0)">
                                            <img src="{{asset('images/home/avatar-02.png')}}" alt="" />
                                        </a>
                                    </div>
                                    <div class="info-text">
                                        <a href="javascript:void(0)">Lưu giữ kỉ niệm với ảnh Tết rộn tiếng cười</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item thirst animated" data-animation="fadeInLeft" data-animation-delay="700">
                                    <div class="image">
                                        <a href="javascript:void(0)">
                                            <img src="{{asset('images/home/avatar-03.png')}}" alt="" />
                                        </a>
                                    </div>
                                    <div class="info-text">
                                        <a href="javascript:void(0)">Cơ hội giành một chuyến Du Xuân trị giá <span>15.000.000 VNĐ</span></a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-02">
        <div class="container">
            <div class="schedule bg-02">
                <div class="info-text">
                    <h3 class="animated" data-animation="fadeInDown" data-animation-delay="300">Lịch trình</h3>
                    <div class="title-text animated" data-animation="fadeInUp" data-animation-delay="300">
                        <img src="{{asset('images/img-text/text-02.png')}}" alt="" />
                    </div>
                </div>
                <div class="box-address">
                    <div class="item animated" data-animation="fadeInLeft" data-animation-delay="600">
                        <h3>Hồ Chí Minh – Hà Nội</h3>
                        <p class="p-day">Ngày 07 & 08/01/2017</p>
                        <p class="p-address">Tại AEON Mall Tân Phú – <br />AEON Mall Long Biên</p>
                    </div>
                    <div class="item animated" data-animation="fadeInRight" data-animation-delay="600">
                        <h3>Hồ Chí Minh – Hà Nội</h3>
                        <p class="p-day">Ngày 21 & 22/01/2017</p>
                        <p class="p-address">Tại Vivo City – <br />The Garden</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-03">
        <div class="container">
            <!-- Box Content -->
            <div class="box-content bg-03">
                <!-- box-taking -->
                <div class="box-taking">
                    <div class="info-text">
                        <div class="title-text animated" data-animation="fadeInDown" data-animation-delay="300">
                            <img src="{{asset('images/img-text/text-03.png')}}" alt="" />
                        </div>
                    </div>
                    <ul class="block-step">
                        <li class="step-1 animated" data-animation="fadeInLeft" data-animation-delay="500">
                            <a href="#"><span>Điền thông tin của </span><span> bạn và người thân </span></a>
                        </li>
                        <li class="step-2 animated" data-animation="fadeInLeft" data-animation-delay="700">
                            <a href="#"><span>Nhận xác nhận </span><span>tham dự từ </span><span> ban tổ chức </span></a>
                        </li>
                        <li class="step-3 animated" data-animation="fadeInLeft" data-animation-delay="900">
                            <a href="#"><span>Đến Cổng kết nối để </span><span>cùng nhau đón Tết </span><span> và nhận quà hấp dẫn </span></a>
                        </li>
                    </ul>
                    <div class="link-button animated" data-animation="fadeInUp" data-animation-delay="1000">
                        <a href="{{PATH_URL}}tham-gia-ket-noi-tieng-cuoi">
                            <img src="{{asset('images/home/btn-thamgia.png')}}" alt="" />
                        </a>
                    </div>
                    <!--<div class="link-rules animated" data-animation="fadeInUp" data-animation-delay="1100">
                        <a href="#">Thể lệ và điều khoản</a>
                    </div>-->

                </div>
            </div>
        </div>
    </section>

    <section class="section-04">
        <div class="container">
            <div class="box-number">
                <ul>
                    <li>
                        <div class="image animated" data-animation="fadeInLeft" data-animation-delay="300">
                            <img src="{{asset('images/home/img-01.png')}}" />
                        </div>
                    </li>
                    <li>
                        <div class="imge-count animated" data-animation="fadeInLeft" data-animation-delay="600">
                            <img src="{{asset('images/home/img-count.png')}}" />
                            <span class="span-count" data-to="{{$num}}" data-speed="2000">{{$num}}</span>
                        </div>
                    </li>
                    <li>
                        <p class="p-text animated" data-animation="fadeInRight" data-animation-delay="900">
                            người đã trao tiếng cười Tết đến người thân phương xa.<br />
                            Còn bạn thì sao?
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="content-footer">
                <div class="copyright animated" data-animation="fadeInUp" data-animation-delay="1100">
                    <h3>Công ty TNHH BEL Việt Nam</h3>
                    <p>
                        Địa chỉ: Lầu 10 Habour View Tower,<span> 35 Nguyễn Huệ, phường Bến Nghé, quận 1,</span> <span> thành phố, Hồ Chí Minh<br /></span>
                        <span>Email: customerservice-vn@group-bel.com<br /> </span>
                    </p>
                    <p>
                        © 2016 Copyright by Bel Vietnam.<span> All right reserved. Designed and developed</span> <span class="span-by"> by Y&R Vietnam</span>
                    </p>
                </div>

            </div>
        </div>
    </footer>
     <!-- Modal Popup -->
    <div class="modal fade" id="video_Modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">Đóng</span>
                    </button>
                    <iframe width="100%" height="488" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Popup -->
@endsection
@section('script')

<script type="text/javascript" src="{{asset('js/jquery.appear.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.countTo.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.viewportchecker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/main.js')}}"></script>
<script type="text/javascript">
	// $('.btn-register').click(function () {
	// 	$.ajax({
	// 		type:"POST",
	// 		dataType: "text",
	// 		url: root+"connect/processConnect",
	// 		data: $('#register').serialize(),
	// 		success:function(result){
	// 			console.log(result);
	// 		}
	// 	});
	// })
	
	//$('.friend-check[name="friends"]:checked').map(function() {
	// 	    friend_list.push(this.value);
	// 	});
</script>
@endsection

