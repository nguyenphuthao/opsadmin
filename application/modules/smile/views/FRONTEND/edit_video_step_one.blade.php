@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')
   @include('FRONTEND\stylesheet_smile')
@endsection
@section('content')
    
    @include('FRONTEND\header_smile')
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
                        <div class="form-edit">
                            <form action="{{PATH_URL.'chinh-sua-clip-buoc-2/'.$id}}" method="post" accept-charset="utf-8" onsubmit="return validateForm();">
                                <input type="hidden" name='friends_list' value={{$friends_list}}>
                                <input type="hidden" name='total_like' value="{{$total_like}}">
                                <div class="content-clip">
    								<div style="position: absolute;top:32px;left:0;right:0;text-align:center; z-index: 99999; color: red; font-size: 16px;">
    									Bạn hãy chọn đủ 8 hình ảnh
    								</div>
                                    <div class="scrol_clip_1">
                                        <div class="gallery-img clearfix">
                                            @if(count($result) < 6) <p style='color: red'> Số lượng hình ảnh của bạn không đủ để thực hiện clip </p>;
                                            @else
                                            @foreach($result as $key => $item)
                                            <div class="item" onclick="choose_images(this)">
                                                <input type="checkbox" class='images-check' name='images[]' value="{{$item->images_id}}" />
                                                <a href="javascript:;" >
                                                    <div class="image">
                                                        <img src="{{$item->images_thumb}}" alt="" width="114" height='90' />
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
                                    <button type="submit" onclick="ga('send', 'event', 'Clip', 'TiepTuc', '');" value="Submit" class="next"><img src="{{asset('images/home/btn-tiep-tuc.png')}}" alt="" /></button>
                                </div>
                            </form>
                        </div>
                        <div class="participation-content" id='loading' style='display: none;'>  
                            <div id="fountainG" style='width: 64%;'><div class="text" style='text-align: center'>Hệ thống đang tải dữ liệu. Vui lòng đợi trong giây lát ...</div><img src="{{asset("images/home/load.gif")}}" alt="Loading" width="32" style="margin-top: 11%;"></div>
                        </div>
                    </div>
                    
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
            if(num_image <= 8){
                $(element).addClass('highlight');
                $(element).find('.numbers').html(num_image);
                $(element).addClass('active');
                $(element).children(':checkbox').prop('checked', true);
                num_image++;
            }
        }
    }
    function validateForm() {
        if(num_image != 9){
            alert("Số lượng hình ảnh bạn cần chọn là 8 hình"); 
            return false;
        }else{
            $('.form-edit').hide();
            $('#loading').show();
        }
    }
</script>
@endsection

