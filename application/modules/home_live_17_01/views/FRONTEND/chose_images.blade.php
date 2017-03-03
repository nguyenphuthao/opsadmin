@extends('FRONTEND.main')
@section('title')
{{$title}}
@endsection
@section('content')
	<div class="wrapper">
		<div class="container">
			<div class="form-group">
				<?php $oauth_uid = array();?>
				<label for="sel1">Select list:</label>
				<select class="form-control friend">
					<option value="0">Chọn bạn</option>		
					@foreach ($friendsList as $friend)
		    			<option value='{{$friend->oauth_uid}}'>{{$friend->name}}</option>
		    			<?php $oauth_uid[$friend->oauth_uid] = $friend->name;?>
					@endforeach
				</select>
			</div>
			<div class="row show-list-images">		
				@foreach ($imagesTags as $image)
			    <div class="col-md-3">
			      	<div class="thumbnail">
			      		<img src="{{$image->images_large}}" alt="" class="img-responsive">
			          	<div class="caption">
			            	<?php
							    $imgFr = explode(",",$image->friends_list);
							?>
							Bạn và 
							@foreach ($imgFr as $key => $value)
								@if (array_key_exists($value, $oauth_uid))
									{{$oauth_uid[$value]}}
									@if(count($imgFr) - 1 > $key),@endif
								@endif
							@endforeach
			          	</div>
			      	</div>
			    </div>
			    @endforeach
		    </div>
	    </div>
	</div>
@endsection

@section('script')
<script type="text/javascript">
	$('.friend').change(function () {
		var friend_id = $(this).val();
		$.ajax({
			type:"POST",
			dataType: "text",
			url: root+"home/getImagesWithFriend",
			data: { friend_id: friend_id },
			success:function(result){
				$('.show-list-images').html(result);
			}
		});
	});
</script>
@endsection

