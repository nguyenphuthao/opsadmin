@foreach ($friendsList as $friend)
	<?php $oauth_uid[$friend->oauth_uid] = $friend->name;?>
@endforeach
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