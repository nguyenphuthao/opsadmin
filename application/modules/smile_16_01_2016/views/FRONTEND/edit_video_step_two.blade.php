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
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.jscrollpane.css')}}" />

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
                        <div class="content-clip clip-detail">                            
                            <div class="block-clip-video">
                                <div class="list-bg-video">
                                    @for($i=1; $i <= 6; $i++)
                                    <div class="item" id="frame{{$i}}" onclick="choose_image(this);" data-id="{{$i}}" data-name="show{{$i}}">
                                        <a href="javascript:void(0);">
                                            <img src="{{PATH_URL_PUBLIC.'public/images/lib/'.$i.'/thumb.png'}}" alt=""/>
                                        </a>
                                        <img src="{{asset('images/smile/close.png')}}" class='show-close' style="width: 20px; height: 20px; position: absolute; top: 4px; right: 4px; display: none;">
                                    </div>
                                    @endfor                                    
                                </div>
                                <div class="video-edit-content">
                                    <div class="video-inner">
                                    @for($i=1; $i <= 6; $i++)
                                        <img class='img-replate<?php if($i == 1) echo " an";?>' id='show{{$i}}' data-name={{$i}} src="{{PATH_URL_PUBLIC.'public/images/lib/'.$i.'/1.png'}}" alt="" />
                                    @endfor 
                                    </div>
                                    <div class="image-append"></div>
                                </div>
                                <div class="list-text-video">
                                    <div class="scroll-text">
                                        @for($i=1; $i <= 10; $i++)
                                        <div class="item" id="water{{$i}}" onclick="image_append(this)" data-id='{{$i}}' data-name="{{PATH_URL_PUBLIC.'public/images/water/items_'.$i.'.png'}}">
                                            <a href="javascript:void(0);">
                                                <img src="{{PATH_URL_PUBLIC.'public/images/water/items_'.$i.'.png'}}" alt=""/>
                                            </a>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                                <input type="hidden" class='frame-select' value='1'>
                                <input type="hidden" class='water-select' value='0'>
                            </div>

                        </div>
                        <!-- <div class="link-btn">
                            <a href="javascript:;" class='btn-access'><img src="{{asset('images/smile/btn-access.png')}}" alt=""></a>
                        </div> -->
                    </div>
                    
                    <div class="link-btn">
                        <a class="link-prev animated" data-animation="fadeInLeft" data-animation-delay="300" href="javascript:;">
                            <img src="{{asset('images/home/btn-tro-lai.png')}}" alt="" />
                        </a>
                        <a class="link-next" href="{{PATH_URL.'chinh-sua-clip-buoc-3/'.$idInsert}}">
                            <img src="{{asset('images/home/btn-tiep-tuc.png')}}" alt="" />
                        </a>
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
    <style type="text/css" media="screen">
        .video-edit-content{position: relative;}    
        .image-append{position: absolute; z-index: 1; top: 10px; left: 10px;}
        .img-replate.an{display: block}
        .img-replate{display: none}
    </style>

    <!-- Modal -->
    <div class="modal fade" id="confirm-acceess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style='background: url({{asset("/images/home/bg-step-clip.png")}}) repeat top center; box-shadow: none;    border: none; color: yellow'>
                <div class="modal-header">
                    Thông báo
                </div>
                <div class="modal-body">
                    Bạn có thật sự muốn chọn .. cho khung hình này?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default chose-again" data-dismiss="modal">Chọn lại</button>
                    <a class="btn btn-primary btn-access">Đồng Ý</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-static fade" id="processing-modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style='background: url({{asset("/images/home/bg-step-clip.png")}}) repeat top center; box-shadow: none;    border: none;'>
                <div class="modal-body">
                    <div class="text-center">
                        <div id="fountainG" style='width: 100%'>
                            <div class="text" style='text-align: center'>Đang cập nhật những khoảnh khắc của bạn và người thân yêu…</div><img src="{{asset("images/home/load.gif")}}" alt="Loading" width="32" style="margin-top: 17%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="messenger" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style='background: url(../images/home/bg-step-clip.png) no-repeat top center; box-shadow: none;    border: none; color: yellow'>
                <div class="modal-header">
                    Thông báo
                </div>
                <div class="modal-body text-center">
                    Khung ảnh này bạn đã chọn xử lý. Vui lòng chọn khung ảnh khác
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/jquery.appear.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.countTo.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.viewportchecker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/main.js')}}"></script>

<script type="text/javascript">
    var processImage = [];
    var imagesave = '{{$imagesave}}';
    var idInsert = '{{$idInsert}}';
    var total = '{{$total_like}}';
    var dataName = '{{$dataName}}';
    $(document).ready(function () {
        /* ScrollPane */
        $('.scroll-text').jScrollPane();
        $('.scroll-text').on({
            'mousewheel': function (e) {
                if (!UnderElement(".scroll-text", e)) {
                    return;
                }
                else {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }
        });

        if(imagesave){
            $.ajax({
                type:"POST",
                dataType: "json",
                url:root+"smile/ajaxSaveImageEditStepTwo",
                data: {imagesave: imagesave, idInsert: idInsert},
                success:function(result){                    
                    // CREATED TEXT 1
                    if(total){
                        $.ajax({
                            type:"POST",
                            dataType: "text",
                            url:root+"smile/ajaxCreateFrameTextOne",
                            data: {total: total, idInsert: idInsert},
                            success:function(result){                   
                                processImage[14] = result;
                            }
                        });
                    }
                    if(dataName){
                        $.ajax({
                            type:"POST",
                            dataType: "text",
                            url:"http://beltet1.opsgreat.vn/index.php/smile/ajaxCreateFrameTextTwo",
                            data: {dataName: dataName, idInsert: idInsert},
                            success:function(result){                   
                                processImage[15] = result;
                            }
                        });   
                    }
                    for(var i = 0; i < result.length; i++){
                        if(result[i].images_large != 0) createImageStep(result[i].images_large, result[i].created_time, i+1, idInsert);
                    }
                }
            });
        }
        setTimeout(function () {
            var arrayAvatar = '{{$arrayAvatar}}';
            console.log(arrayAvatar);
            var idInsert = {{$idInsert}};
            var image7 = createImageStep(arrayAvatar, "1", 7, idInsert);
        },800);
        processImage[8] = 8; processImage[9] = 9; processImage[10] = 10; processImage[11] = 11; processImage[12] = 12; processImage[13] = 13;
        autoWaiting = setInterval(function () {
            checkProcess();                      
        },3000);

        $('.link-next').click(function () {
            var frame_sl = $('.frame-select').val();
            var water_sl = $('.water-select').val();
            if(frame_sl == 0 || water_sl == 0){
                if(!checkProcess()) {
                    $('#processing-modal').modal({
                        show: 'false'
                    });
                    return false;
                }else{
                    var url = $('.link-next').attr('href');
                    window.location.replace(url);
                }
            }else{
                $('#confirm-acceess').modal({
                    show: 'false'
                });
                $('.btn-access').on('click', function () {
                    $(this).hide();
                    $('#confirm-acceess').modal('hide');
                    var frame = $(".frame-select").val();
                    var water = $(".water-select").val();
                    // $('.link-next').hide();
                    var fr = parseInt(frame) + 7;
                    processImage[fr] = 0;
                    $('#frame'+frame).removeAttr("onclick");
                    $('#frame'+frame+ " .show-close").show();
                    switch(parseInt(frame)) {
                        case 1:
                            url = "http://beltet1.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                            break;
                        case 2:
                            url = "http://beltet2.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                            break;
                        case 2:
                            url = "http://beltet3.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                            break;
                        case 4:
                            url = "http://beltet4.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                            break;
                        case 5:
                            url = "http://beltet5.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                            break;
                        case 6:
                            url = "http://beltet6.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                            break;
                        default:
                            var url = root+"smile/createImageFrame";
                    }
                    $.ajax({
                        type:"POST",
                        dataType: "text",
                        url: url,
                        data: {frame: frame, water: water, idInsert: idInsert},
                        success:function(result){                   
                            processImage[fr] = result;
                        }
                    });
                    callBackW();
                    $('#processing-modal').modal({
                        show: 'false'
                    });
                });
                $('.chose-again').click(function () {
                    return false;
                });
            }
        });
    });
    function callBackW() {
        autoWaiting = setInterval(function () {
            checkProcess();                      
        },3000);
    }

    
    function  checkProcess() {
        console.log(processImage);
        if(processImage[1] == 1 && processImage[2] == 2 && processImage[3] == 3 && processImage[4] == 4 && processImage[5] == 5 && processImage[6] == 6 && processImage[7] == 7 && processImage[8] == 8 && processImage[9] == 9 && processImage[10] == 10 && processImage[11] == 11 && processImage[12] == 12 && processImage[13] == 13){
            clearInterval(autoWaiting);
            $("#processing-modal").modal("hide");           
            return true;
        }else{

            return false;
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
            }
        );
    }
    var image_id = 1;
    function  choose_image(element) {
        var frame_sl = $('.frame-select').val();
        var water_sl = $('.water-select').val();
        var src = "#"+$(element).data('name');
        image_id = $(element).data('id');
        if(frame_sl == 0 || water_sl == 0){
            $('.item').removeClass('active');
            $('.btn-access').show();
            $(element).addClass('active');           
            $('.img-replate').removeClass('an');
            $(src).addClass('an');        
            $('.frame-select').val(image_id);
            $('.image-append').html('');
        }else{
            $('#confirm-acceess').modal({
                show: 'false'
            });
            $('.btn-access').on('click', function () {
                $(this).hide();
                $('#confirm-acceess').modal('hide');
                var frame = $(".frame-select").val();
                var water = $(".water-select").val();
                // $('.link-next').hide();
                var fr = parseInt(frame) + 7;
                processImage[fr] = 0;
                $('#frame'+frame).removeAttr("onclick");
                $('#frame'+frame+ " .show-close").show();
                switch(parseInt(frame)) {
                    case 1:
                        url = "http://beltet1.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                        break;
                    case 2:
                        url = "http://beltet2.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                        break;
                    case 2:
                        url = "http://beltet3.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                        break;
                    case 4:
                        url = "http://beltet4.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                        break;
                    case 5:
                        url = "http://beltet5.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                        break;
                    case 6:
                        url = "http://beltet6.opsgreat.vn/index.php/smile/ajaxEditImageByWater";
                        break;
                    default:
                        var url = root+"smile/createImageFrame";
                }
                $.ajax({
                    type:"POST",
                    dataType: "text",
                    url: url,
                    data: {frame: frame, water: water, idInsert: idInsert},
                    success:function(result){                   
                        processImage[fr] = result;
                    }
                });
                callBackW();

                setTimeout(function () {
                    $('.item').removeClass('active');
                    $('.btn-access').show();
                    $(element).addClass('active');           
                    $('.img-replate').removeClass('an');
                    $(src).addClass('an');        
                    $('.frame-select').val(image_id);
                    $('.water-select').val(0);
                    $('.image-append').html('');
                },800);
            });
        }
    }
    function  image_append(element) {
        var top = "0px"; var left= "0px";
        var src = $(element).data('name');
        var item = $(element).data('id');
        $('.water-select').val(item);
        if(image_id == 6) {top = "0"; left = "0px";}
        else if(image_id == 2) {top = "300px"; left = "200px";}
        else if(image_id == 3) {top = "0px"; left = "0px";}
        else if(image_id == 4) {top = "400px"; left = "400px";}
        else if(image_id == 5) {top = "400"; left = "0px";}
        else if(image_id == 1) {top =  "150px"; left = "350px";}
        $('.image-append').css({"top": top, "left": left});
        $('.image-append').html('<img src="'+src+'">');
        // var frSl = "#frame"+$('.img-replate.an').data('name');
        // console.log(frSl);
        // var ckSl = $(frSl).attr('onclick');
        // console.log(ckSl);

        // if (typeof ckSl !== typeof undefined && ckSl !== false) {
        //     setTimeout(function () {
        //         $('#confirm-acceess').modal({
        //             show: 'false'
        //         }); 
        //     },500);
        // }
        // else{
        //     $('#messenger').modal({
        //         show: 'false'
        //     });           
        // }        
    }
</script>

@endsection

