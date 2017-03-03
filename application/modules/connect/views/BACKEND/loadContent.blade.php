<table class="table table-striped table-hover dataTable no-footer" role="grid" aria-describedby="datatable1_info">
   <thead>
      <tr role="row">
         <th>
            <div class="checkbox checkbox-styled tile-text">
               <label>
                  <input type="checkbox" class='select-all-items'><span></span>
               </label>
            </div>
         </th>
      	<th>#</th>
         	<th class="sorting" onclick="sort('fullname')">Họ tên</th>
         	<th class="sorting" onclick="sort('email')">Email</th>
         	<th class="sorting" onclick="sort('phone')">Số điện thoại</th>
            <th >Họ tên người thân</th>
            <th >Email người thân</th>
            <th >SĐT người thân</th>
         	<th class="sorting" onclick="sort('conn_location')">Địa điểm</th>
            <th class="sorting" onclick="sort('conn_date')">Ngày</th>
            <th class="sorting" onclick="sort('conn_time')">Thời gian</th>
      </tr>
   </thead>
   <tbody>
      @if($result)
      @foreach ($result as $key => $value)
      	<tr class="gradeX odd" role="row">
            <td>
               <div class="checkbox checkbox-styled tile-text">
                  <label>
                     <input class='custom_chk' id="item{{$key}}" type="checkbox" value="{{$value->id}}"><span></span>
                  </label>
               </div>
            </td>
      		<td>{{($key+1)*($start?($start-1)+$record:1)}}</td>
            	<td><a href="<?= PATH_URL . 'admincp/' . $module . '/update/' . $value->id ?>"> {{$value->fullname}} </a></td>
            	<td>{{$value->email}}</td>
            	<td>{{$value->phone}}</td>
               <td>{{$value->fr_name}}</td>
               <td>{{$value->fr_email}}</td>
               <td>{{$value->fr_phone}}</td>
            	<td>{{$value->conn_location == 1 ? 'Hồ Chí Minh - Hà Nội' : 'Hà Nội - Hồ Chí Minh'}}</td>
               <td>{{date('d-m-Y', strtotime($value->conn_date))}}</td>
               <td>
                  <?php 
                     switch ($value->conn_time) {
                        case 9:
                          echo '9H - 10H';
                          break;
                        case 10:
                           echo '10H - 11H';
                           break;
                        case 11:
                           echo '11H - 12H';
                           break;
                        case 12:
                           echo '12h - 13H';
                           break;
                        case 13:
                           echo '13h - 14H';
                           break;
                        case 14:
                           echo '14H - 15H';
                           break;
                        case 15:
                           echo '15H - 16H';
                           break;
                        case 16:
                           echo '16H - 17H';
                           break;
                        case 17:
                           echo '17H - 18H';
                           break;
                        case 18:
                           echo '18H - 19H';
                           break;
                        case 19:
                           echo '19H - 20H';
                           break;
                        case 20:
                           echo '20H - 21H';
                           break;
                        case 21:
                           echo '21H - 22H';
                           break;    
                        default:
                           echo '9H -10H';                  
                  }?>
               </td>
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

