@if($friends)
@foreach($friends as $value)
	<div class="col-md-4 col-lg-4 col-ms-12 items" onclick='choose_friend(this);'>
		<input type="checkbox" class='friend-check' name='friends[]' value="{{$value->oauth_uid}}" />
		<div class="panel row list">
	      	<div class="panel-body">
	      		<div class="col-md-4 col-ms-4 col-xs-4"><img src="{{$value->picture_url ? $value->picture_url : asset('images/user.jpg')}}" alt="{{$value->name}}"></div>
	      		<div class="col-md-8 col-ms-8 col-xs-8 fullname">{{$value->name}} ({{$value->appear_count}} lượt)</div>
	      	</div>			      	
	    </div>
	    <div class="check"><img src="{{asset('images/check.png')}}" alt="checkbox"></div>
	</div>
@endforeach
@endif