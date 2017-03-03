<p>Có tổng số: <strong>{{$totalVideo}}</strong> video được tạo</p>
<p>Có <strong>{{$total}}</strong> video tạo thành công</p>
<table class="table table-striped table-hover dataTable no-footer" role="grid" aria-describedby="datatable1_info">
   <thead>
      <tr role="row">
      	<th>#</th>
         <th>Tài khoản</th>
         <th>Id Video</th>
      	<th>Ngày tạo</th>
      </tr>
   </thead>
   <tbody>
      @if($result)
      @foreach ($result as $key => $value)
         <?php //pr($value);?>
      	<tr class="gradeX odd" role="row">
      		<td>{{($key+1)*($start?($start-1)+$record:1)}}</td>
            <td><a href="{{PATH_URL.'admincp/users/update/'.$value->user_id}}">{{$value->name}}</a></td>	
            <td><a target="_blank" href="{{PATH_URL.'chinh-sua-clip/'.$value->oauth_uid.'/'.$value->id}}">{{$value->id}}</a></td>
            <td>{{date("H:i d-m-Y", strtotime($value->created_at))}}</td>
         </tr>
      @endforeach
	@endif
   </tbody>
</table>
<div class="dataTables_info" id="datatable1_info" role="status" aria-live="polite">Showing {{$start?($start-1)*$record:1}} to {{$total<$record?$total:$record}} of <?php echo $total;?> entries</div>
{{$pageLink}}

<script type="text/javascript">
   $(".select-all-items").click(function () {
      $('input.custom_chk:checkbox').not(this).prop('checked', this.checked);
   });
</script>


