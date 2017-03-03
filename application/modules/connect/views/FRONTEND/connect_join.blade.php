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
                        <img class="img-desktop" src="{{asset('images/home/logo.pn')}}g" alt=""/>
                        <img class="img-mobile" src="{{asset('images/home/logo-mobile.png')}}" alt="" />
                    </a>
                </div>
                <ul class="nav-menu">
                    <li><a href="{{PATH_URL}}cong-ket-noi-tieng-cuoi"><span>Trang chủ</span></a></li>
                    <li class='active'><a href="javascript:;"><span>Cổng kết nối Tiếng cười</span></a></li>
                    <li><a href="{{PATH_URL}}thu-vien-tieng-cuoi"><span>Thư viện tiếng cười</span></a></li>
                    
                    <!--<li><a href="#"><span>Trao Tiếng cười Tết</span></a></li>-->
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
            <!-- Box Content -->
            <div class="box-content form-connect">
                <div class="box-text">
                    <div class="info-text">
                        <div class="title-text animated" data-animation="fadeInDown" data-animation-delay="300">
                            <img class="text-05-desktop" src="{{asset('images/img-text/text-05.png')}}" alt="" />
                            <!--<img class="text-05-mobile" src="images/img-text/text-05-mobile.png" alt="" />-->
                        </div>
                        <p class="p-text animated" data-animation="fadeInUp" data-animation-delay="600">
                            Hãy điền thông tin của bạn và người thân phương xa để cùng nhau đón Tết tại <a href="#" target="_blank">Cổng kết nối tiếng cười </a> và giành những giải thưởng hấp dẫn.
                        </p>
                    </div>
                </div>
                <div class="box-select-form">
                    <div class="block-1 animated" data-animation="fadeInUp" data-animation-delay="900">
                        <div class="title-img">
                            <img src="{{asset('images/home/title-form-01.png')}}" alt="" />
                        </div>
                    </div>
					<div class="img-hcm-hn animated" data-animation="fadeInUp" data-animation-delay="1000">
                        <!--<img src="{{asset('images/home/img-hcm-hn.png')}}" alt="" />
						<p>CHỌN THỜI GIAN VÀ ĐỊA ĐIỂM KẾT NỐI</p>-->
                    </div>
                    <div class="block-2">
                        <div class="connect-item">
                            <!-- <div class="item animated" data-animation="fadeInLeft" data-animation-delay="300">
                                <a class="click_connect_1" href="javascript:void(0);">
                                    <img class="img-connect active" src="{{asset('images/home/connect-01.png')}}" alt="" />
									<div class="span-text">
                                        <p>Ngày 07 & 08/01/2017</p>
                                        <ul>
                                            <li><span>Aeon Mall Tân Phú<br>(HCM)</span></li>
											<li><span> - </span></li>
                                            <li><span>Aeon Mall Long Biên<br>(HN)</span></li>
                                        </ul>
                                    </div>s
                                </a>
                            </div> -->
                            <div class="item animated" data-animation="fadeInRight" data-animation-delay="600">
                                <a class="click_connect_2" href="javascript:void(0);">
                                    <img class="img-connect active" src="{{asset('images/home/connect-02.png')}}" alt="" />
									<div class="span-text">
                                        <p>Ngày 21 & 22/01/2017</p>
                                        <ul>
                                            <li><span>Vivo City<br>(HCM)</span></li>
											<li><span> - </span></li>
                                            <li><span>The Garden<br>(HN)</span></li>
                                        </ul>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="block-3">
                        <form onsubmit="return false;" id='form-regis' method="post">
                            <div class="group-form-inner clearfix">
                                <div class="item even animated" data-animation="fadeInUp" data-animation-delay="300">
                                    <div class="title-img">
                                        <img src="{{asset('images/home/title-form-02.png')}}" alt="" />
                                    </div>
                                    <div class="content-form">
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="address">Cổng kết nối<br /> của bạn*</label>
                                                </div>
                                                <div class="col-input">
                                                    <select class="selectbox" name='conn_location' id="address">
                                                        <option value="1">Hồ Chí Minh</option>
                                                        <option value="2">Hà Nội</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="first-last-name">Họ & Tên*</label>
                                                </div>
                                                <div class="col-input">
                                                    <input name="fullname" type="text" placeholder="" class="input-text" id="first-last-name" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="phone">Số điện thoại*</label>
                                                </div>
                                                <div class="col-input">
                                                    <input type="text" name="phone" class="input-text" id="phone" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="email">Email*</label>
                                                </div>
                                                <div class="col-input">
                                                    <input name="email" type="text" class="input-text" id="email" required="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item old animated" data-animation="fadeInUp" data-animation-delay="500">
                                    <div class="title-img">
                                        <img src="{{asset('images/home/title-form-03.png')}}" alt="" />
                                    </div>
                                    <div class="content-form">
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="address_1">Cổng kết nối<br /> của người thân*</label>
                                                </div>
                                                <div class="col-input">
                                                    <input type="text" disabled="disabled" placeholder="" class="input-text" id="address_1" required="" value="Hà Nội">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="first-last-name_1">Họ & Tên*</label>
                                                </div>
                                                <div class="col-input">
                                                    <input name="fr_fullname" type="text" placeholder="" class="input-text" id="first-last-name_1" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="phone_1">Số điện thoại*</label>
                                                </div>
                                                <div class="col-input">
                                                    <input type="text" name="fr_phone" class="input-text" id="phone_1" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="email_1">Email*</label>
                                                </div>
                                                <div class="col-input">
                                                    <input name="fr_email" type="text" class="input-text" id="email_1" required="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item even animated" data-animation="fadeInUp" data-animation-delay="500">
                                    <div class="title-img">
                                        <img src="{{asset('images/home/title-form-04.png')}}" alt="" />
                                    </div>
                                    <div class="content-form">
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="select_time">Ngày kết nối*</label>
                                                </div>
                                                <div class="col-input">
                                                    <select class="selectbox" name="conn_date" id='connect_date'>
                                                        <option value="0">Chọn ngày</option>
                                                        <option value="2017-01-21">21-01-2017</option>
                                                        <option value="2017-01-21">22-01-2017</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="content-form">
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="select_time">Khung thời gian*</label>
                                                </div>
                                                <div class="col-input">
                                                    <select class="selectbox" name="conn_time" id="select_time">
                                                        <option value="0">Chọn thời gian</option>
                                                        <option value="9">9 AM - 10 AM</option>
                                                        <option value="10">10 AM - 11 AM</option>
                                                        <option value="11">11 AM - 12 AM</option>
                                                        <option value="12">12 AM - 1 PM</option>
                                                        <option value="13">1 PM - 2 PM</option>
                                                        <option value="14">2 PM - 3 PM</option>
                                                        <option value="15">2 PM - 4 PM</option>
                                                        <option value="16">4 PM - 5 PM</option>
                                                        <option value="17">5 PM - 6 PM</option>
                                                        <option value="18">6 PM - 7 PM</option>
                                                        <option value="19">7 PM - 8 PM</option>
                                                        <option value="20">8 PM - 9 PM</option>
                                                        <option value="21">9 PM - 10 PM</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="item old last animated" data-animation="fadeInUp" data-animation-delay="500">
                                    <div class="title-img">
                                        <img src="{{asset('images/home/title-form-05.png')}}" alt="" />
                                    </div>
                                    <div class="content-form">
                                        <div class="input-field">
                                            <div class="col-group">
                                                <div class="col-text">
                                                    <label for="note">(Lời chúc này sẽ được in ra và trao tặng cho người thân của bạn ngay tại Cổng kết nối)</label>
                                                </div>
                                                <div class="col-input">
                                                    <textarea rows="4" cols="40" name="note" id="note"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="block-note">
                                <p class="p-text animated" data-animation="fadeInUp" data-animation-delay="300">* Thông tin bắt buộc</p>
                                <div class="link-btn animated" data-animation="fadeInUp" data-animation-delay="600">
                                    <a href="javascript:void(0);" class='btn-register'>
                                        <img src="{{asset('images/home/btn-datcho.png')}}" alt="" />
                                    </a>
                                </div>
                                <!--<div class="link-rules animated" data-animation="fadeInUp" data-animation-delay="500">
                                    <a href="#">Thể lệ và điều khoản</a>
                                </div>-->
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Smile -->
                <div class="smile-img">
                    <div class="image">
                        <img src="{{asset('images/home/img-smile.png')}}" alt="" />
                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="content-footer">     
                <div class="copyright animated" data-animation="fadeInUp" data-animation-delay="700">
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
    <!-- End Footer -->

    <!-- Modal Popup -->
    <div class="modal fade" id="form_Modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">Đóng</span>
                    </button>
                    <div class="content-inner">
                        <div class="box-text">
                            <div class="info-text">
                                <div class="title-text animated" data-animation="fadeInDown" data-animation-delay="300">
                                    <img src="{{asset('images/img-text/text-06.png')}}" alt="" />
                                </div>
                                <p class="p-text first animated" data-animation="fadeInUp" data-animation-delay="600">
                                    Cảm ơn bạn đã đăng ký tham gia Cổng kết nối Tiếng Cười cùng với người thân yêu tại<br /> <span class='localtion'>Hồ Chí Minh/ Hà Nội</span>
                                </p>
                                <p class="p-text second animated" data-animation="fadeInUp" data-animation-delay="800">
                                    Chúng tôi sẽ liên hệ với bạn và người thân để xác nhận và cập nhật thêm thông tin.
                                </p>
                            </div>
                        </div>
                        <div class="box-link">
                            <div class="image animated" data-animation="fadeInLeft" data-animation-delay="900">
                                <img src="{{asset('images/home/img-pupup.png')}}" alt="" />
                            </div>
                            <ul class="link-btn">
                                <li><a class="link-share animated" data-animation="fadeInUp" data-animation-delay="1000" href="#">Chia sẻ cùng bạn bè </a></li>
                                <li><input type="hidden" value='' class='send-dialog'><a class="link-smile animated" data-animation="fadeInUp" data-animation-delay="1100" href="#">Trao Tiếng cười Tết </a></li>
                                <li><a class="link-back animated" data-animation="fadeInUp" data-animation-delay="1200" href="{{PATH_URL}}">Quay về Trang chủ </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <div class="modal fade" id="popup-err">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true">Đóng</span></button>
                    <h4 class="modal-title">Đăng ký thất bại</h4>
                </div>
                <div class="modal-body">
                    <div class="box-err"></div>
                </div>
            </div>
        </div>
    </div>
    <?php 
    if(isset($_GET['name']) && $_GET['location'] && $_GET['date'] && $_GET['time']){
        if($_GET['name'] =! '' && $_GET['location'] != '' && $_GET['time'] != ''){
            if($_GET['date'] == '2017-01-22' || $_GET['date'] == '2017-01-21' || $_GET['date'] == '2017-01-07' || $_GET['date'] == '2017-01-08'){
                if($_GET['time'] >= 9 &&  $_GET['time'] <= 21){?>
    <div class="modal fade" id="popup-send-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true">Đóng</span></button>
                    <h4 class="modal-title">Thông báo</h4>
                </div>
                <div class="modal-body">
                    <div class="box-err">
                        <?php 
                            $location = $_GET['location'];
                            $date = $_GET['date'];
                            if($location == 1){
                                if (strtotime($date) == strtotime("2017-01-21") || strtotime($date) == strtotime("2017-01-22")){
                                    $address = "The Garden";
                                }else{
                                    $address = "Aeon Mall Long Biên";
                                }
                            }else{
                                if (strtotime($date) == strtotime("2017-01-21") || strtotime($date) == strtotime("2017-01-22")){
                                    $address = "Vivo City";
                                }else{
                                    $address = "Aeon Mall Tân Phú";
                                }
                            }
                        ?>
                        Bạn vừa nhận được lời mời "Trao Tết rộn Tiếng Cười" từ {{addslashes(htmlspecialchars($_GET['name']))}}. Hãy đến {{$address}} vào {{$_GET['time']}}h ngày {{date('d-m-Y', strtotime($_GET['date']))}} để cùng {{addslashes(htmlspecialchars($_GET['name']))}} trao Tết rộn Tiếng Cười và có cơ hội trúng chuyến Du Xuân 15.000.000đ.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }}}}?>
    <div class="modal fade" id="modal-waiting">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true">Đóng</span></button>
                    <h4 class="modal-title">THÔNG BÁO</h4>
                </div>
                <div class="modal-body">
                    <div class="box-err">
                        <p>Xin cảm ơn bạn đã đăng ký tham gia. </p>
                        <p> Nhưng thật tiếc là khung giờ bạn chọn hiện đã đầy. Bạn có muốn tiếp tục vào danh sách chờ? </p>
                        <p>Ban Tổ Chức sẽ liên lạc với bạn khi khung giờ trống trở lại!</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancer" data-dismiss="modal">Chọn khung giờ khác</button>
                    <button type="button" class="btn btn-primary btn-waiting">Vào danh sách chờ</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
<script>
    var login = 0;
    window.fbAsyncInit = function() {
        FB.init({
            appId: '{{FB_CLIENT_ID}}',
            cookie: true, // enable cookies to allow the server to access 
            // the session
            xfbml: true, // parse social plugins on this page
            version: 'v2.8' // use graph api version 2.8
        });
        $(document).trigger('fbload');
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
<script type="text/javascript" src="{{asset('js/jquery.appear.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.countTo.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.viewportchecker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/main.js?1')}}"></script>
<script type="text/javascript">
    $(document).on(
        'fbload', 
        function(){
            var img = root_public + 'public/images/share/share.jpg';
            $('.link-share').click(function () {
                FB.ui({
                    method: 'feed',
                    mobile_iframe: true,
                    picture: img,
                    description: "Đăng ký ngay cuộc hẹn với người thân phương xa để trao nhau Tiếng Cười Tết và có cơ hội trúng chuyến Du Xuân trị giá 15.000.000đ.",
                    message: 'Cùng Con Bò Cười trao nhau Tiếng Cười Tết - nhận Quà hấp dẫn',
                    link: root+'tham-gia-ket-noi-tieng-cuoi',
                }, function(response){});
				
				ga('send', 'event', 'Facebook', 'share', '');
            });
            $('.link-smile').click(function () {
                var send_link = root + $('.send-dialog').val();
                send_link  = encodeURI(send_link);
                FB.ui({
                    method: 'send',
                    link: send_link,
                });   

				ga('send', 'event', 'Facebook', 'inbox', '');
            })
        }
    );   

    function validateEmail(email) {
        var reg_mail = /^[A-Za-z0-9]+([_\.\-]?[A-Za-z0-9])*@[A-Za-z0-9]+([\.\-]?[A-Za-z0-9]+)*(\.[A-Za-z]+)+$/;
        if(reg_mail.test(email) == false) return false;
        else return true;
    }
    function validatePhone(phone) {        
        var filter = /^[0-9-+]+$/;
        if(phone.length < 9 || phone.lenght > 13) return false;
        if (filter.test(phone)) {
            return true;
        }
        else {
            return false;
        }
    }
    $('#connect_date').change(function () {
        var conn_date = $(this).val();
        $.ajax({
            type: "POST",
            dataType: "text",
            url: root+"connect/processConnectDate",
            data: {conn_date: conn_date},            
            success: function(result) {
                $('#select_time').html(result);
            },
        });

    })
    $('#address').change(function () {
        var add = $(this).val();
        if(add == 1) $('#address_1').val('Hà Nội');
        if(add == 2) $('#address_1').val('Hồ Chí Minh');
		
		ga('send', 'event', 'RegiserPage', 'click', 'change_location');
    })
	$('.btn-register').click(function () {
		ga('send', 'event', 'RegiserPage', 'click', 'register');
        var username = $('#first-last-name').val();
        if(username == '' || username == null) {            
            $('.box-err').html('Vui lòng nhập họ và tên của bạn');
            $('#popup-err').modal({
                show: 'false'
            });
            $('#first-last-name').focus();
            return false;
        }
        var phone = $('#phone').val();
        if(!validatePhone(phone)) {
            $('.box-err').html('Định dạng số điện thoại không chính xác');
            $('#popup-err').modal({
                show: 'false'
            });
            $('#phone').focus(); return false;
        }
        var email = $('#email').val();
        if(!validateEmail(email)) {
            $('.box-err').html('Định dạng email không chính xác');
            $('#popup-err').modal({
                show: 'false'
            });
            $('#email').focus(); return false;
        }
        var connect_date = $('#connect_date').val();
        if(connect_date == 0) {
            $('.box-err').html('Vui lòng chọn ngày kết nối');
            $('#popup-err').modal({
                show: 'false'
            });
            $('#connect_date').focus(); return false;
        }
        var select_time = $('#select_time').val();
        if(select_time == 0) {
            $('.box-err').html('Vui lòng chọn thời gian tham gia');
            $('#popup-err').modal({
                show: 'false'
            });
            $('#select_time').focus(); return false;
        }
        var frusername = $('#first-last-name_1').val();
        if(frusername == '' || frusername == null) {
            $('.box-err').html('Vui lòng nhập họ và tên người thân của bạn');
            $('#popup-err').modal({
                show: 'false'
            });
            $('#first-last-name_1').focus(); return false;
        }
        var frphone = $('#phone_1').val();
        if(!validatePhone(frphone)) {
            $('.box-err').html('Định dạng số điện thoại của người thân không chính xác');
            $('#popup-err').modal({
                show: 'false'
            });
            $('#phone_1').focus(); return false;
        }
        var fremail = $('#email_1').val();
        if(!validateEmail(fremail)) {
            $('.box-err').html('Định dạng email của người thân không chính xác');
            $('#popup-err').modal({
                show: 'false'
            });
            $('#email_1').focus(); return false;
        }
        var conn_location = $('#address').val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: root+"connect/processConnect",
            data: $('#form-regis').serialize(),            
            success: function(result) {
                if(result.status == 0) {
                    $('.box-err').html(result.msg)
                    $('#popup-err').modal({
                        show: 'false'
                    });
                }
                else if(result.status == -1){
                    $('.box-err').html(result.msg);                    
                    $('#modal-waiting').modal({
                        show: 'false'
                    });
                }
                else {
                    $('.send-dialog').val(result.msg);
                    $('#form-regis').find("input[type=text], textarea").val("");
                    if(conn_location == 1) {$('#form_Modal .localtion').html('Hồ Chí Minh / Hà Nội'); $('#address_1').val("Hà Nội")}
                    else {$('#form_Modal .localtion').html('Hà Nội/ Hồ Chí Minh'); $('#address_1').val("Hồ Chí Minh")}
                    $('#form_Modal').modal({
                        show: 'false'
                    });                    
                }
            },
        });
    });
    $('.btn-waiting').on('click', function () {
        postWaiting();
    })
    function postWaiting() {
        $('#modal-waiting').modal('hide');
        var conn_location = $('#address').val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: root+"connect/processConnect",
            data: $('#form-regis').serialize()+"&waiting=1",            
            success: function(result) {
                if(result.status == 0) {
                    $('.box-err').html(result.msg)
                    $('#popup-err').modal({
                        show: 'false'
                    });                    
                }
                else {
                    $('.send-dialog').val(result.msg);
                    $('#form-regis').find("input[type=text], textarea").val("");
                    if(conn_location == 1) {$('#form_Modal .localtion').html('Hồ Chí Minh / Hà Nội'); $('#address_1').val("Hà Nội")}
                    else {$('#form_Modal .localtion').html('Hà Nội/ Hồ Chí Minh'); $('#address_1').val("Hồ Chí Minh")}
                    $('#form_Modal').modal({
                        show: 'false'
                    }); 
                }
            },
        });
    }
    $('.link-back').click(function () {
		ga('send', 'event', 'RegiserPage', 'click', 'back_to_home');
        $('#form_Modal').modal('hide');
        window.location.href = root_public;
    });
    if($("#popup-send-dialog").length > 0){
        $('#popup-send-dialog').modal({
            show: 'false'
        });
    }
</script>
@endsection

