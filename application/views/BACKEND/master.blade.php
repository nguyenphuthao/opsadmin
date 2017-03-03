<!DOCTYPE html>
<html lang="en">
	<head>
		<title>@yield('title')</title>

		<!-- BEGIN META -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="your,keywords">
		<meta name="description" content="Short explanation about this website">
		<!-- END META -->
		<!-- BEGIN STYLESHEETS -->
		<link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
		<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/css/theme-default/bootstrap.css')}}" />
		<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/css/theme-default/materialadmin.css')}}" />
		<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/css/theme-default/font-awesome.min.css')}}" />
		<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/css/theme-default/material-design-iconic-font.min.css')}}" />
		<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/css/theme-default/libs/rickshaw/rickshaw.css')}}" />
		<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/css/theme-default/libs/morris/morris.core.css')}}" />
		@yield('stylesheets')
		<!-- END STYLESHEETS -->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="../../assets/js/libs/utils/html5shiv.js?1403934957"></script>
		<script type="text/javascript" src="../../assets/js/libs/utils/respond.min.js?1403934956"></script>
		<![endif]-->
	</head>
	<body class="menubar-hoverable header-fixed ">

		<!-- BEGIN HEADER-->
		<header id="header" >
			<div class="headerbar">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="headerbar-left">
					<ul class="header-nav header-nav-options">
						<li class="header-nav-brand" >
							<div class="brand-holder">
								<a href="../../html/dashboards/dashboard.html">
									<span class="text-lg text-bold text-primary">BELTET ADMIN</span>
								</a>
							</div>
						</li>
						<li>
							<a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
								<i class="fa fa-bars"></i>
							</a>
						</li>
					</ul>
				</div>
				
			</div>
		</header>
		<!-- END HEADER-->

		<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN OFFCANVAS LEFT -->
			<div class="offcanvas">
			</div><!--end .offcanvas-->
			<!-- END OFFCANVAS LEFT -->

			<!-- BEGIN CONTENT-->
			@yield('content')
			<!-- END CONTENT -->

			<!-- BEGIN MENUBAR-->
			<div id="menubar" class="menubar-inverse ">
				<div class="menubar-fixed-panel">
					<div>
						<a class="btn btn-icon-toggle btn-default menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
							<i class="fa fa-bars"></i>
						</a>
					</div>
					<div class="expanded">
						<a href="../../html/dashboards/dashboard.html">
							<span class="text-lg text-bold text-primary ">BELTET ADMIN</span>
						</a>
					</div>
				</div>
				<div class="menubar-scroll-panel">

					<!-- BEGIN MAIN MENU -->
					<ul id="main-menu" class="gui-controls">

						<!-- BEGIN DASHBOARD -->
						<li>
							<a href="javascript:;" class="active">
								<div class="gui-icon"><i class="md md-home"></i></div>
								<span class="title">Dashboard</span>
							</a>
						</li><!--end /menu-li -->

						<!-- END DASHBOARD -->
						<li>
							<a href="{{PATH_URL.'admincp/connect/'}}" >
								<div class="gui-icon"><i class="md md-web"></i></div>
								<span class="title">Danh sách kết nối</span>
							</a>
						</li><!--end /menu-li -->
						<li>
							<a href="{{PATH_URL.'admincp/users/'}}" >
								<div class="gui-icon"><i class="md md-web"></i></div>
								<span class="title">Danh sách tạo clip</span>
							</a>
						</li><!--end /menu-li -->
						<li>
							<a href="{{PATH_URL.'admincp/share/'}}" >
								<div class="gui-icon"><i class="md md-web"></i></div>
								<span class="title">Danh sách share clip</span>
							</a>
						</li><!--end /menu-li -->
					</ul><!--end .main-menu -->
					<!-- END MAIN MENU -->

					<div class="menubar-foot-panel">
						<small class="no-linebreak hidden-folded">
							<span class="opacity-75">Copyright &copy; 2014</span> <strong>CodeCovers</strong>
						</small>
					</div>
				</div><!--end .menubar-scroll-panel-->
			</div><!--end #menubar-->
			<!-- END MENUBAR -->
		</div><!--end #base-->
		<!-- END BASE -->

		<!-- BEGIN JAVASCRIPT -->
		<script src="{{asset('admin/assets/js/libs/jquery/jquery-1.11.2.min.js')}}"></script>
		<script src="{{asset('admin/assets/js/libs/jquery/jquery-migrate-1.2.1.min.js')}}"></script>
		<script src="{{asset('admin/assets/js/libs/bootstrap/bootstrap.min.js')}}"></script>
		<script src="{{asset('admin/assets/js/libs/spin.js/spin.min.js')}}"></script>
		<script src="{{asset('admin/assets/js/libs/autosize/jquery.autosize.min.js')}}"></script>
		<script src="{{asset('admin/assets/js/libs/nanoscroller/jquery.nanoscroller.min.js')}}"></script>
		<script src="{{asset('admin/assets/js/core/source/App.js')}}"></script>
		<script src="{{asset('admin/assets/js/core/source/AppNavigation.js')}}"></script>
		<script src="{{asset('admin/assets/js/core/source/AppOffcanvas.js')}}"></script>
		<script src="{{asset('admin/assets/js/core/source/AppCard.js')}}"></script>
		<script src="{{asset('admin/assets/js/core/source/AppForm.js')}}"></script>
		<script src="{{asset('admin/assets/js/core/source/AppNavSearch.js')}}"></script>
		<script src="{{asset('admin/assets/js/core/source/AppVendor.js')}}"></script>
		<script src="{{asset('admin/assets/js/core/demo/Demo.js')}}"></script>
		@yield('script')
		<!-- END JAVASCRIPT -->

	</body>
</html>
