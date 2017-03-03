@if($friends)
	@foreach($friends as $item)
		<li class='list-group-item' >{{$item->name}} - {{$item->appear_count}}<a href="javascript:;" onclick='choose_friend(this);' data-id='{{$item->oauth_uid}}' data-name='{{$item->name}}'><span class="glyphicon glyphicon-plus" style='color: #8f96ba; float: right'></span></a></li>
	@endforeach
@endif