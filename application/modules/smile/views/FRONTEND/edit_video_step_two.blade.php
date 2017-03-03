@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
    @include('FRONTEND\stylesheet_smile')
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.jscrollpane.css')}}" />

@endsection
@section('content')
    
    @include('FRONTEND\header_smile')
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
                                    <!-- onclick="choose_image(this);" -->
                                    @for($i=1; $i <= 8; $i++)
                                    <div class="item" id="frame{{$i}}" data-id="{{$i}}" data-name="show{{$i}}">
                                        <a href="javascript:void(0);">
                                            <img src="{{PATH_URL_PUBLIC.'public/images/lib/'.$i.'/thumb.png'}}" alt=""/>
                                        </a>
                                        <img src="{{asset('images/smile/close.png')}}" class='show-close' style="width: 20px; height: 20px; position: absolute; top: 4px; right: 4px; display: none;">
                                    </div>
                                    @endfor                                    
                                </div>
                                <div class="video-edit-content">
                                    <div class="video-inner">
                                    @for($i=1; $i <= 8; $i++)
                                        <img class='img-replate<?php if($i == 1) echo " an";?>' id='show{{$i}}' data-name={{$i}} src="{{PATH_URL_PUBLIC.'public/_data/_delete/'.$idInsert.'/frame/'.$i.'.png'}}" alt="" />
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
                    </div>
                    
                    <div class="link-btn">
                        <a class="link-prev animated" data-animation="fadeInLeft" data-animation-delay="300" href="{{PATH_URL}}thu-vien-tieng-cuoi" onclick="ga('send', 'event', 'Clip', 'QuayLai', '');">
                            <img src="{{asset('images/home/btn-tro-lai.png')}}" alt="" />
                        </a>
                        <a class="link-next" href="{{PATH_URL.'chinh-sua-clip-buoc-3/'.$oauth_uid.'/'.$idInsert}}">
                            <img src="{{asset('images/home/btn-tiep-tuc.png')}}"  alt="" />
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>
    @include('FRONTEND/footer_smile')
    <style type="text/css" media="screen">
        .video-edit-content{position: relative;}    
        .image-append{position: absolute; z-index: 1;}
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
                    Bạn muốn lưu chỉnh sửa cho khung hình này?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default chose-again" data-dismiss="modal">Chọn lại</button>
                    <a class="btn btn-primary btn-access" id='btn-dongy'>Đồng Ý</a>
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
    var flag = 0;
    var arrayAvatar = '{{$arrayAvatar}}';
    var statusSaveImage = 0;
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
        if(total){
            $.ajax({
                type:"GET",
                dataType: "text",
                url:root+"smile/ajaxCreateFrameTextOne",
                data: {total: total, idInsert: idInsert},
                success:function(result){                   
                    processImage[18] = parseInt(result);
                }
            });
        }
        if(dataName){
            $.ajax({
                type:"GET",
                dataType: "text",
                url: root_1+"smile/ajaxCreateFrameTextTwo",
                data: {dataName: dataName, idInsert: idInsert},
                success:function(result){                   
                    processImage[19] = parseInt(result);
                }
            });   
        }
        for(var i = 0; i < 8; i++){
            createImageStep(1, i+1, idInsert);
        }
        setTimeout(function () {
            var image7 = createImageStep(arrayAvatar, 9, idInsert);
        },800);

        processImage[10] = 10;
        processImage[11] = 11; 
        processImage[12] = 12;
        processImage[13] = 13;
        processImage[14] = 14;
        processImage[15] = 15;
        processImage[16] = 16;
        processImage[17] = 17;
        autoWaiting = setInterval(function () {
            checkProcess(0);                      
        },3000);

        $('.link-next').click(function (event) {
            event.preventDefault();
            var frame_sl = $('.frame-select').val();
            var water_sl = $('.water-select').val();
            if(frame_sl == 0 || water_sl == 0){
                if(!checkProcess(0)) {
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
                $('#btn-dongy').unbind().on('click', function () {
                    $(this).hide();
                    $('.frame-select').val(0);
                    $('.water-select').val(0);
                    $('#confirm-acceess').modal('hide');
                    var fr = parseInt(frame_sl) + 9;
                    processImage[fr] = 0;
                    $('#frame'+frame_sl).removeAttr("onclick");
                    $('#frame'+frame_sl+ " .show-close").show();
                    switch(parseInt(frame_sl)) {
                        case 1:
                            url = root_1;
                            break;
                        case 2:
                            url = root_2;
                            break;
                        case 3:
                            url = root_3;
                            break;
                        case 4:
                            url = root_4;
                            break;
                        case 5:
                            url = root_5;
                            break;
                        case 6:
                            url = root_6;
                            break;
                        case 7:
                            url = root_7;
                            break;
                        case 8:
                            url = root_8;
                            break;
                        default:
                            url = root;
                    }
                    url += "smile/ajaxEditImageByWater";
                    $.ajax({
                        type:"GET",
                        dataType: "text",
                        url: url,
                        data: {frame: frame_sl, water: water_sl, idInsert: idInsert},
                        success:function(result){
                            processImage[fr] = parseInt(result);
                        }
                    });
                    flag = 1;
                    callBackW();
                    $('#processing-modal').modal({
                        show: 'false'
                    });
                    $('.link-next').html('<img src="{{asset("images/home/btn-hoantat.png")}}" alt="">');
                });

                $('.chose-again').click(function () {
                    $('#confirm-acceess').modal({
                        show: 'false'
                    });
                    return false;
                });
            }
            ga('send', 'event', 'Clip', 'TiepTucBuoc2', '');
        });
    });
    function callBackW() {
        autoWaiting = setInterval(function () {
            checkProcess();               
        },3000);
    }

    
    function checkProcess() {
        console.log(processImage);
        if(
            parseInt(processImage[1]) >= 1 
            && parseInt(processImage[2]) > 1 
            && parseInt(processImage[3]) > 1 
            && parseInt(processImage[4]) > 1 
            && parseInt(processImage[5]) > 1 
            && parseInt(processImage[6]) > 1 
            && parseInt(processImage[7]) > 1 
            && parseInt(processImage[8]) > 1 
            && parseInt(processImage[9]) > 1 
            && parseInt(processImage[10]) > 1 
            && parseInt(processImage[11]) > 1 
            && parseInt(processImage[12]) > 1 
            && parseInt(processImage[13]) > 1 
            && parseInt(processImage[14]) > 1 
            && parseInt(processImage[15]) > 1 
            && parseInt(processImage[16]) > 1
            && parseInt(processImage[17]) > 1
            && parseInt(processImage[18]) > 1
            && parseInt(processImage[19]) > 1
            )
        {
                clearInterval(autoWaiting);
                $("#processing-modal").modal("hide");
                if(flag == 1) {
                    var url = $('.link-next').attr('href');
                    window.location.replace(url);
                }else{
                    return true;
                }
        }else{
            return false;
        }
    }
    function createImageStep(sourcefile, frame, idInsert) {
        switch(frame) {
            case 1:
                url = root_1;
                break;
            case 2:
                url = root_2;
                break;
            case 3:
                url = root_3;
                break;
            case 4:
                url = root_4;
                break;
            case 5:
                url = root_5;
                break;
            case 6:
                url = root_6;
                break;
             case 7:
                url = root_7;
                break;
            case 8:
                url = root_8;
                break;
            default:
                var url = root;
        }
        url += "smile/ajaxCreateImageFrame";
        $.get(url,{sourcefile: sourcefile, frame: frame, idInsert: idInsert},
            function(res){
                processImage[frame] = parseInt(res);
            }
        );
    }
    var image_id = 1;
    function  choose_image(element) {
        var frame_sl = 0;
        frame_sl = $('.frame-select').val();
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
            $('#btn-dongy').on('click', function () {
                $(this).hide();
                mergeFrame();
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
    $('#frame1, #frame2, #frame3, #frame4, #frame5, #frame6, #frame7, #frame8').click(function() {
        var frame_sl  = $('.frame-select').val();
        var water_sl  = $('.water-select').val();
        var src       = "#"+$(this).data('name');
        var id_active = "#"+$(this).attr('id');
        image_id = $(this).data('id');
        if(frame_sl == 0 || water_sl == 0){
            $('.item').removeClass('active');
            $('.btn-access').show();
            $(this).addClass('active');           
            $('.img-replate').removeClass('an');
            $(src).addClass('an');        
            $('.frame-select').val(image_id);
            $('.image-append').html('');
        }else{
            $('#confirm-acceess').modal({
                show: 'false'
            });
            $('.chose-again').click(function() {
                $('#confirm-acceess').modal('hide');
            });
            $("#btn-dongy").unbind().click(function() {
                $(this).hide();
                mergeFrame();
                callBackW();
                setTimeout(function () {
                    $('.item').removeClass('active');
                    $('.btn-access').show();
                    $(id_active).addClass('active');           
                    $('.img-replate').removeClass('an');
                    $(src).addClass('an');        
                    $('.frame-select').val(image_id);
                    $('.water-select').val(0);
                    $('.image-append').html('');
                },800);
            });
        }
    });
    function mergeFrame() {
        $('#confirm-acceess').modal('hide');
        var frame = $(".frame-select").val();
        var water = $(".water-select").val();
        var fr = parseInt(frame) + 9;
        processImage[fr] = 0;
        $('#frame'+frame).removeAttr("onclick");
        $('#frame'+frame+ " .show-close").show();
        switch(parseInt(frame)) {
            case 1:
                url = root_1;
                break;
            case 2:
                url = root_2;
                break;
            case 3:
                url = root_3;
                break;
            case 4:
                url = root_4;
                break;
            case 5:
                url = root_5;
                break;
            case 6:
                url = root_6;
                break;
            case 7:
                url = root_7;
                break;
            case 8:
                url = root_8;
                break;
            default:
                url = root;
        }
        url += "smile/ajaxEditImageByWater";
        $.ajax({
            type:"GET",
            dataType: "text",
            url: url,
            data: {frame: frame, water: water, idInsert: idInsert},
            success:function(result){   
                processImage[fr] = parseInt(result);
            }
        });
    }
    function  image_append(element) {
        var src = $(element).data('name');
        var item = $(element).data('id');
        $('.water-select').val(item);
        if(image_id == 1) $('.image-append').css({top: "1%", left: "1%", width: "31%"});
        else if(image_id == 2) $('.image-append').css({top: "1%", left: "35%", width: "31%"});  
        else if(image_id == 3) $('.image-append').css({top: "1%", left: "69%", width: "31%"});  
        else if(image_id == 4) $('.image-append').css({top: "1%", left: "1%", width: "31%"});  
        else if(image_id == 5) $('.image-append').css({top: "1%", left: "35%", width: "31%"});  
        else if(image_id == 6) $('.image-append').css({top: "1%", left: "69%", width: "31%"});
        else if(image_id == 7) $('.image-append').css({top: "1%", left: "1%", width: "31%"});        
        else if(image_id == 8) $('.image-append').css({top: "1%", left: "35%", width: "31%"});        
        $('.image-append').html('<img src="'+src+'">');
    }
</script>

@endsection

