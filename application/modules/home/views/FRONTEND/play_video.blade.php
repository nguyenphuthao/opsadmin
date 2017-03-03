@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('stylesheet')

@endsection
@section('content')
	<div class="wrapper">
		<div class="container">
        	<video id="player" width="960" controls poster="{{asset('images/cover.jpg')}}">
			    <source src="{{$urlvideo}}" type="video/mp4" class='aaa'>
			    Your browser does not support HTML5 video.
		  	</video>
		</div>
	</div>
@endsection
@section('script')
<script>




var myVideo = document.getElementById("player");
var windowWidth = $(window).width();
$(window).resize(function(){
    windowWidth = $(window).width();
    scale(windowWidth);
});
function scale (windowWidth) {
	if(windowWidth >= 1024) myVideo.width = 960;
    if(windowWidth < 1024 && windowWidth >= 980) myVideo.width = 960;
    if(windowWidth < 980 && windowWidth >= 800) myVideo.width = 800;
    if(windowWidth < 800 && windowWidth >= 780) myVideo.width = 800;
    if(windowWidth < 780 && windowWidth >= 768) myVideo.width = 768;
    if(windowWidth < 768 && windowWidth >= 720) myVideo.width = 720;
    if(windowWidth < 720 && windowWidth >= 640) myVideo.width = 640;
    if(windowWidth < 640 && windowWidth >= 600) myVideo.width = 600;
    if(windowWidth < 600 && windowWidth >= 568) myVideo.width = 568;
    if(windowWidth < 568 && windowWidth >= 563) myVideo.width = 563;
    if(windowWidth < 563 && windowWidth >= 549) myVideo.width = 540;
    if(windowWidth < 549 && windowWidth >= 534) myVideo.width = 520;
    if(windowWidth < 534 && windowWidth >= 480) myVideo.width = 460;
    if(windowWidth < 480 && windowWidth >= 360) myVideo.width = 360;
    if(windowWidth < 360 && windowWidth >= 338) myVideo.width = 320;
    if(windowWidth < 338 && windowWidth >= 330) myVideo.width = 320;
    if(windowWidth <= 320) myVideo.width = 320;
}
$(document).ready(function(){
	scale(windowWidth);
})
</script> 
@endsection