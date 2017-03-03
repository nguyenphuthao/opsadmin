<p>Có <strong>{{$totalU}}</strong> người tham gia tạo clip</p>
<p>Có <strong>{{$totalS}}</strong> người đã tạo clip thành công</p>
<table class="table table-striped table-hover dataTable no-footer" role="grid" aria-describedby="datatable1_info">
   <thead>
      <tr role="row">
      	<th>#</th>
         	<th class="sorting" onclick="sort('fullname')">Họ tên</th>
            <th class="sorting" onclick="sort('email')">Email</th>
            <th>First Name</th>
            <th>Last Name</th>
         	          
            <th>Profile Facebook</th>
            <th>Đăng nhập lần cuối</th>
      </tr>
   </thead>
   <tbody>
      @if($result)
      @foreach ($result as $key => $value)
      	<tr class="gradeX odd" role="row">
      		<td>{{($key+1)*($start?($start-1)+$record:1)}}</td>
            	<td><a href="<?= PATH_URL . 'admincp/' . $module . '/update/' . $value->id ?>"> {{$value->name}} </a></td>
               <th>{{$value->email}}</th>
            	<td>{{$value->first_name}}</td>
               <td>{{$value->last_name}}</td>
               <td><a href="{{$value->profile_url}}">{{$value->profile_url}}</a></td>
               <td>{{date("H:i d-m-Y", strtotime($value->update_at))}}</td>
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


