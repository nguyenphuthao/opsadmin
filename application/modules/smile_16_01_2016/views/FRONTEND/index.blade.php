@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/reset.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/smile.css')}}">
    <!-- animate -->
    <!-- <link rel="stylesheet" type="text/css" href="{{asset('css/animate.min.css')}}" /> -->
   

@endsection
@section('content')
	
    <!-- Begin Header -->
    <header class="header">
        <div class="container">
            <!-- Nav top menu -->
            <div class="nav-top-menu">
                <div class="logo">
                    <a href="index.html">
                        <img class="img-desktop" src="{{asset('images/smile/logo.png')}}" alt=""/>
                        <img class="img-mobile" src="{{asset('images/smile/logo-mobile.png')}}" alt="" />
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
                <div class="img-left animated" data-animation="fadeInLeft" data-animation-delay="300">
                    <img src="{{asset('images/smile/img-left.png')}}" alt="" />
                </div>
                <div class="img-right animated" data-animation="fadeInRight" data-animation-delay="600">
                    <img src="{{asset('images/smile/img-right.png')}}" alt="" />
                </div>

                <div class="participation-content">
                    <div class="step-head animated" data-animation="fadeInDown" data-animation-delay="900">
                        <span>Bước 1: Chọn bạn</span>
                    </div>
                    <!-- Box inner -->
                    <div class="box-inner animated" data-animation="fadeInDown" data-animation-delay="1100">
                        <div style="text-align:center;" class='step-to-step'>
                            <form method="post" id='create-video' onsubmit='return false;'>
                                <div class="row" style='color: white'>
                                    <div class="col-md-6 col-ms-6 col-lg-6 col-xs-6">                
                                        <div class="row">
                                            <div class="col-md-6 col-ms-10 col-lg-6 col-xs-12 pull-right">
                                                <p>Người bạn được đề cử</p>
                                                <input type="text" class="form-control" placeholder="Tìm kiếm" id='search'/>
                                                <div class="list-group list-friend">
                                                    @if($friends)
                                                        @foreach($friends as $item)
                                                            <li class='list-group-item' >{{$item->name}} - {{$item->appear_count}}<a href="javascript:;" onclick='choose_friend(this);' data-id='{{$item->oauth_uid}}' data-name='{{$item->name}}'><span class="glyphicon glyphicon-plus" style='color: #8f96ba; float: right'></span></a></li>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-ms-6 col-lg-6 col-xs-6">               
                                        <div class="col-md-6 col-ms-10 col-lg-6 col-xs-12 pull-left">
                                            <p>Bạn được chọn</p>                     
                                            <ul class="list-group friend-selected">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-md-12 center-block">
                                    <button type="submit" class="btn-viewclip center-block"></button>
                                </div>
                                </div>               
                            </form>
                        </div>
                        <div style="text-align:center;" class="step-process">
                                              
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

<script type="text/javascript">
    $('#search').keyup(function () {
        var text = $(this).val();
        if(text.length > 1){
            $.ajax({
                type:"POST",
                dataType: "text",
                url: root+"smile/ajaxGetFriendSearch",
                data: { name: text },
                success:function(result){
                    if(result == -1) window.location = root;
                    else $('.list-friend').html(result);
                }
            });
        }
    });
    var autoWaiting='';
    var step_1 = '<div id="fountainG"><div class="text">Đang cập nhật những khoảnh khắc của bạn và người thân yêu…</div><img src="{{asset("images/home/load.gif")}}" alt="Loading" width="32" style="margin-top: 17%;"></div>';
    var step_2 = '<div id="fountainG"><div class="text">Xin đợi trong giây lát …. <br>Clip “Tiếng Cười” đang được tạo.</div><img src="{{asset("images/home/load.gif")}}" alt="Loading" width="32" style="margin-top: 17%;"></div>';
    var processImage = [];
    $('.btn-viewclip').click(function () {
        $('.step-to-step').hide();
        $('.step-process').html(step_1);
        $.ajax({
            type:"POST",
            dataType: "json",
            url:root+"smile/createVideoDemo",
            data: $("#create-video").serialize(),
            success:function(result){
                if(result.status == 1){
                    var list = result.result; list = JSON.parse(list); var arrayAvatar = result.arrayAvatar;
                    var image1 = createImageStep(list[0].images_large, list[0].created_time, 1, result.idInsert);
                    var image2 = createImageStep(list[1].images_large, list[1].created_time, 2, result.idInsert);
                    var image3 = createImageStep(list[2].images_large, list[2].created_time, 3, result.idInsert);
                    var image4 = createImageStep(list[3].images_large, list[3].created_time, 4, result.idInsert);
                    var image5 = createImageStep(list[4].images_large, list[4].created_time, 5, result.idInsert);
                    var image6 = createImageStep(list[5].images_large, list[5].created_time, 6, result.idInsert);
                    var image7 = createImageStep(arrayAvatar, "1", 7, result.idInsert);
                    autoWaiting = setInterval(function () {
                        checkProcess(result.path, result.idInsert, result.oauth_uid);                      
                    },1000);
                }else{
                    $('.step-process').html("<p>"+result.msg+"</p>");
                }
            }
        });
    });
    function  checkProcess(path, idInsert, oauth_uid) {
        if(processImage[1] == 1 && processImage[2] == 2 && processImage[3] == 3 && processImage[4] == 4 && processImage[5] == 5 && processImage[6] == 6 && processImage[7] == 7){
            clearInterval(autoWaiting);
            $('.step-process').html(step_2);
            $.post(root+"smile/getVideoFinal",{path: path, id: idInsert, oauth_uid: oauth_uid},
                function(res){
                    if(res > 0) window.location = root+'chinh-sua-clip/'+res;
                }
            ); 
        }
    }
    function createImageStep(sourcefile, created_time, frame, idInsert) {        
        switch(frame) {
            case 1:
                url = "http://beltet1.opsgreat.vn/index.php/smile/createImageFrame";
                break;
            case 2:
                url = "http://beltet2.opsgreat.vn/index.php/smile/createImageFrame";
                break;
            case 2:
                url = "http://beltet3.opsgreat.vn/index.php/smile/createImageFrame";
                break;
            case 4:
                url = "http://beltet4.opsgreat.vn/index.php/smile/createImageFrame";
                break;
            case 5:
                url = "http://beltet5.opsgreat.vn/index.php/smile/createImageFrame";
                break;
            case 6:
                url = "http://beltet6.opsgreat.vn/index.php/smile/createImageFrame";
                break;
            default:
                var url = root+"smile/createImageFrame";
        }
        $.post(url,{sourcefile: sourcefile, created_time: created_time, frame: frame, idInsert: idInsert},
            function(res){
                processImage[frame] = res;
                // return res;
            }
        );
    }
    function choose_friend(element) {
        var name = $(element).data('name');
        var id = $(element).data('id');
        var num = $('.item-select').length;
        if(num == 0){
            $('.friend-selected').append('<li class="list-group-item item-select">'+name+' <input type="checkbox" class="hidden friend-check" checked name="friends[]" value="'+id+'"><a href="javascript:;" onclick="remove_friend(this);" style="float: right"><span class="glyphicon glyphicon-remove" style="color: #8f96ba;"></span></a></li>');
        }else{alert('Số lượng bạn quá lớn');}
    }
    function  remove_friend(element) {
        $(element).parent().remove();
    }
</script>
@endsection

