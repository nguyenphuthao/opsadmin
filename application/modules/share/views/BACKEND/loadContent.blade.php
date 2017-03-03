<table class="table table-striped table-hover dataTable no-footer" role="grid" aria-describedby="datatable1_info">
   <thead>
      <tr role="row">
         <th>#</th>
            <th class="sorting" onclick="sort('name')">Facebook ID</th>
            <th>Tên tài khoản</th>
            <th class="sorting" onclick="sort('id')">Video Id</th>
            <th>Link</th>
            <th class="sorting" onclick="sort('created_at')">Thời gian</th>
      </tr>
   </thead>
   <tbody>
      @if($result)
      @foreach ($result as $key => $value)
         <tr class="gradeX odd" role="row">
            <td>{{($key+1)*($start?($start-1)+$record:1)}}</td>
            <td>{{$value->oauth_uid}}</td>
            <td>{{$value->name}}</td>
            <td>{{$value->video_id}}</td>
            <td><a target="_blank" href="{{$value->link}}">{{$value->link}}</a></td>
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
        // $('.custom_chk:checkbox').not(this).prop('checked', this.checked);
   });
</script>


