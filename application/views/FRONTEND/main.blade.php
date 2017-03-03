<!DOCTYPE html>
<html>
<head>
<title>@yield('title')</title>
<link rel="shortcut icon" href="{{asset('images/home/favicon.ico')}}" type="image/x-icon">
<!-- BOOTSTRAP CSS -->
<link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap-theme.min.css')}}">
<!-- END BOOTSTRAP CSS -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if($this->uri->segment(1) != 'chinh-sua-clip' && $this->uri->segment(1) != 'chinh-sua-clip-buoc-3'){?>
	<?php if($this->uri->segment(1) == 'tham-gia-ket-noi-tieng-cuoi' && $this->uri->segment(1) == 'cong-ket-noi-tieng-cuoi'){?>
		<meta property="og:title" content="Cùng Con Bò Cười trao nhau Tiếng Cười Tết - nhận Quà hấp dẫn"/>
		<meta property="og:description" content="Đăng ký ngay cuộc hẹn với người thân phương xa để trao nhau Tiếng Cười Tết và có cơ hội trúng chuyến Du Xuân trị giá 15.000.000đ."/>
		<meta property="og:type" content="website"/>
		<meta property="og:image" content="{{PATH_URL_PUBLIC}}public/images/share/share.jpg"/>
		<meta property="og:url" content="{{PATH_URL}}"/>
		<meta property="og:site_name" content="Cổng kết nối tiếng cười"/>
		<meta property="og:locale" content="vi_VN"/>
	<?php }else{?>
		<meta property="fb:app_id" content="{{FB_CLIENT_ID}}" />
		<meta property="og:title" content="Cùng Con Bò Cười Tạo Clip 'Trao Nhau Tiếng Cười Tết'"/>
		<meta property="og:description" content="Tạo clip Tiếng Cười ngay để cùng xem bạn và người thân yêu đã có bao nhiêu khoảnh khắc đầy ấp tiếng cười trong năm qua!"/>
		<meta property="og:type" content="website"/>
		<meta property="og:image" content="{{PATH_URL_PUBLIC}}public/images/share/share.png"/>
		<meta property="og:url" content="{{PATH_URL}}thu-vien-tieng-cuoi"/>
		<meta property="og:site_name" content="Cổng kết nối tiếng cười"/>
		<meta property="og:locale" content="vi_VN"/>
	<?php }?>
<?php }?>
@yield('stylesheet')
</head>

<body>
@yield('content')
<!-- JQUERY -->
<script src="{{asset('js/jquery.min.js')}}" type="text/javascript"></script>
<!-- JQUERY -->
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript">
	var root_public = "{{PATH_URL_PUBLIC}}";
	var root = "{{PATH_URL}}";
	var root_1 = "{{PATH_URL_1}}";
	var root_2 = "{{PATH_URL_2}}";
	var root_3 = "{{PATH_URL_3}}";
	var root_4 = "{{PATH_URL_4}}";
	var root_5 = "{{PATH_URL_5}}";
	var root_6 = "{{PATH_URL_6}}";
	var root_7 = "{{PATH_URL_7}}";
	var root_8 = "{{PATH_URL_8}}";
</script>

@yield('script')
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-85184317-3', 'auto');
  ga('send', 'pageview');

</script>
<!-- <img src="//vn-gmtdmp.mookie1.com/t/v2/activity?tagid=V2_138347&src.rand=[timestamp]" style="display:none;"/> -->
</body>

</html>