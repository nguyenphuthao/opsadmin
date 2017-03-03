<!DOCTYPE html>
<html lang="en">
<head>
	<title>Material Admin - Login</title>

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
	<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/formvalidation/formvalidation.io.css')}}" />
	<!-- END STYLESHEETS -->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script type="text/javascript" src="{{asset('admin/assets/js/libs/utils/html5shiv.js')}}"></script>
	<script type="text/javascript" src="{{asset('admin/assets/js/libs/utils/respond.min.js')}}"></script>
	<![endif]-->
	<script>
		var root = "<?php echo PATH_URL;?>";
	</script>
</head>
<body class="menubar-hoverable header-fixed ">
	<!-- BEGIN LOGIN SECTION -->
	<section class="section-account">
		<div class="spacer"></div>
		<div class="card contain-sm style-transparent">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-6">
						<br/>
						<span class="text-lg text-bold text-primary">BELTET ADMIN</span>
						<br/><br/>
						<form id='loginForm' class="form floating-label" action="{{PATH_URL}}admincp/login" accept-charset="utf-8" method="post">
							<div class="form-group">
								<input type="text" class="form-control" id="username" name="username">
								<label for="username">Username</label>
							</div>
							<div class="form-group">
								<input type="password" class="form-control" id="password" name="password">
								<label for="password">Password</label>
								<p class="help-block"><a href="#">Forgotten?</a></p>
							</div>
							<div class="alert alert-danger fade in show-err" style='display: none'>
							    Wrong username or password
							</div>
							<br/>
							<div class="row">
								<div class="col-xs-6 text-left">
								</div><!--end .col -->
								<div class="col-xs-6 text-right">
									<button class="btn btn-primary btn-raised" type="submit">Login</button>
								</div><!--end .col -->
							</div><!--end .row -->
						</form>
					</div><!--end .col -->
				</div><!--end .card -->
			</section>
			<!-- END LOGIN SECTION -->

			<!-- BEGIN JAVASCRIPT -->
			<script src="{{asset('admin/assets/js/libs/jquery/jquery-1.11.2.min.js')}}"></script>
			<script src="{{asset('admin/assets/js/libs/bootstrap/bootstrap.min.js')}}"></script>
			<script src="{{asset('admin/assets/js/core/source/App.js')}}"></script>
			<script src="{{asset('admin/assets/js/core/source/AppForm.js')}}"></script>
			<script src="{{asset('admin/assets/formvalidation/formvalidation.io.js')}}"></script>
			<script src="{{asset('admin/assets/js/form/jquery.form.min.js')}}"></script>
			<script>
				$(document).ready(function() {
				    $('#loginForm').formValidation({
				        framework: 'bootstrap',
				        icon: {
				            valid: 'glyphicon glyphicon-ok',
				            invalid: 'glyphicon glyphicon-remove',
				            validating: 'glyphicon glyphicon-refresh'
				        },
				        fields: {
				            username: {
				                validators: {
				                    notEmpty: {
				                        message: 'The username is required'
				                    },
				                    regexp: {
				                        regexp: /^[a-zA-Z0-9_\.]+$/,
				                        message: 'The username can only consist of alphabetical, number, dot and underscore'
				                    }
				                }
				            },
				            password: {
				                validators: {
				                    notEmpty: {
				                        message: 'The password is required'
				                    }
				                }
				            }
				        }
				    }).on('success.form.fv', function(e){
			            e.preventDefault();
			            var $form = $(e.target);
			            $form.ajaxSubmit({
			                url: $form.attr('action'),
			                dataType: 'text',
			                success: function(responseText, statusText, xhr, $form) {
			                	if(responseText == 1) location.href=root+"admincp/connect";
			                	else $(".show-err").show();
			                }
			            });
			        });;
				});
			</script>
			<!-- END JAVASCRIPT -->

		</body>
	</html>
