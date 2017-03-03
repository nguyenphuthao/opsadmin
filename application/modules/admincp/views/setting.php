<script src="<?php echo PATH_URL?>static/js/jquery.datetimepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo PATH_URL?>static/css/jquery.datetimepicker.css"/>
<script type="text/javascript">
var root = '<?=PATH_URL?>';
function save(){
	var options = {
		success:       showResponse  // post-submit callback 
    };
	$('#frmManagement').ajaxSubmit(options);
}
function showResponse(responseText, statusText, xhr, $form) {
	if(responseText=='success-setting'){
		var url = $.url(document.location.href);
		if(url.fsegment(1)=='save'){
			location.reload();
		}else{
			location.href=root+"admincp/setting/#/save";
		}
	}
}
function chooseServer(){
	var id = $('#select_sv').val();
	$('.tab-setting').hide('fade');
	if(id > 0){
		$('.tab-setting#setting'+id).show('fade');
	}	
}
$(document).ready(function(){
	$('.tab-setting').hide();
	$('#date_server').datetimepicker({
		format:'Y-m-d H:i:00',
	    allowTimes:[
	    	'00:00', '00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30',
	    	'04:00', '04:30', '05:00', '05:30', '06:00', '06:30', '07:00', '07:30',
	    	'08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
	    	'12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
	    	'16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30',
	    	'20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:59'
	    ]
	});

});

</script>

<div class="gr_perm_error" style="display:none;">

	<p><strong>FAILURE: </strong><span id="txt_error">Permission Denied.</span></p>

</div>

<div class="gr_perm_success" style="display:none;">

	<p><strong>SAVE SUCCESS.</strong></p>

</div>

<div class="table">

	<div class="head_table"><div class="head_title_edit">Setting</div></div>

	<div class="clearAll"></div>



	<form id="frmManagement" action="<?=PATH_URL.'admincp/setting/'?>" method="post" enctype="multipart/form-data">

	<div class="row_text_field_first">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Website Name:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['title-admincp'])){ print $setting['title-admincp']; }else{ print'Name of website'; } ?>" type="text" name="contentAdmincp[title-admincp]" /></td>

			</tr>

		</table>

	</div>
	<!-- Phan doi text ten server, ngay gio ra server by Tan -->
	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Tên Server:</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['name_server'])){ print $setting['name_server']; }else{ print''; } ?>" type="text" name="contentAdmincp[name_server]" /></td>
			</tr>
		</table>
	</div>
	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Ngày giờ ra server:</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['date_server'])){ print $setting['date_server']; }else{ print''; } ?>" type="text" id='date_server' name="contentAdmincp[date_server]" /></td>
			</tr>
		</table>
	</div>
	<!-- Phan doi text ten server, ngay gio ra server by Tan -->
	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Yahoo Support:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['yahoo-support'])){ print $setting['yahoo-support']; }else{ print''; } ?>" type="text" name="contentAdmincp[yahoo-support]" /></td>

			</tr>

		</table>

	</div>

	

	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Skype Support:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['skype-support'])){ print $setting['skype-support']; }else{ print''; } ?>" type="text" name="contentAdmincp[skype-support]" /></td>

			</tr>

		</table>

	</div>

	

	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Facebook fanpage:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['fb-page'])){ print $setting['fb-page']; }else{ print''; } ?>" type="text" name="contentAdmincp[fb-page]" /></td>

			</tr>

		</table>

	</div>

	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Default Server Chơi ngay:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['defaultserver'])){ print $setting['defaultserver']; }else{ print''; } ?>" type="text" name="contentAdmincp[defaultserver]" /></td>

			</tr>

		</table>

	</div>

	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Đăng nhập nhận quà (0 Tắt / 1 Mở):</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['dangnhapnhanqua'])){ print $setting['dangnhapnhanqua']; }else{ print''; } ?>" type="text" name="contentAdmincp[dangnhapnhanqua]" /></td>

			</tr>

		</table>

	</div>

	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Nạp thẻ tích điểm (0 Tắt / 1 Mở)</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['napthetichdiem'])){ print $setting['napthetichdiem']; }else{ print''; } ?>" type="text" name="contentAdmincp[napthetichdiem]" /></td>

			</tr>

		</table>

	</div>

	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Site Redirect Landing page:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['site_redirect'])){ print $setting['site_redirect']; }else{ print''; } ?>" type="text" name="contentAdmincp[site_redirect]" /><br/>

					<i>VD : http://tamquoccola.com<b>/</b></i>

				</td>

			</tr>

		</table>

	</div>





	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Các Thẻ Link/ Liên Kết Footer (Các thẻ cách nhau bằng dấu phẩy):</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['lienket'])){ print $setting['lienket']; }else{ print''; } ?>" type="text" name="contentAdmincp[lienket]" /></td>

			</tr>

		</table>

	</div>

	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Tên Công Ty:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['tencongty'])){ print $setting['tencongty']; }else{ print''; } ?>" type="text" name="contentAdmincp[tencongty]" /></td>

			</tr>

		</table>

	</div>



	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Địa Chỉ Công Ty:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['diachicongty'])){ print $setting['diachicongty']; }else{ print''; } ?>" type="text" name="contentAdmincp[diachicongty]" /></td>

			</tr>

		</table>

	</div>

	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Điện Thoại Công Ty:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['dienthoaicongty'])){ print $setting['dienthoaicongty']; }else{ print''; } ?>" type="text" name="contentAdmincp[dienthoaicongty]" /></td>

			</tr>

		</table>

	</div>


    <div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Email Công Ty:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['emailcongty'])){ print $setting['emailcongty']; }else{ print''; } ?>" type="text" name="contentAdmincp[emailcongty]" /></td>

			</tr>

		</table>

	</div>



	<div class="row_text_field">

		<table cellspacing="0" cellpadding="0" border="0" width="100%">

			<tr>

				<td class="left_text_field">Text Arial:</td>

				<td class="right_text_field"><input value="<?php if(isset($setting['textArial'])){ print $setting['textArial']; }else{ print''; } ?>" type="text" name="contentAdmincp[textArial]" /></td>

			</tr>

		</table>

	</div>

	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Bật/ Tắt Event Nạp Thẻ Lần Đầu (Bật: 1, Tắt: 0):</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['onofflandaunap'])){ print $setting['onofflandaunap']; }else{ print''; } ?>" type="text" name="contentAdmincp[onofflandaunap]" /></td>
			</tr>
		</table>
	</div>

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Bật/ Tắt Event May Mắn (Bật: 1, Tắt: 0):</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['onoffrandom'])){ print $setting['onoffrandom']; }else{ print''; } ?>" type="text" name="contentAdmincp[onoffrandom]" /></td>
			</tr>
		</table>
	</div>	

	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Item Event Random:</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['itemrandom'])){ print $setting['itemrandom']; }else{ print''; } ?>" type="text" name="contentAdmincp[itemrandom]" /></td>
			</tr>
		</table>
	</div>
	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Item Event Random 2:</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['itemrandom2'])){ print $setting['itemrandom2']; }else{ print''; } ?>" type="text" name="contentAdmincp[itemrandom2]" /></td>
			</tr>
		</table>
	</div>
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Bật/ Tắt Event Mỗi Ngày (Bật: 1, Tắt: 0):</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['onoffeventeveryday'])){ print $setting['onoffeventeveryday']; }else{ print''; } ?>" type="text" name="contentAdmincp[onoffeventeveryday]" /></td>
			</tr>
		</table>
	</div>	
	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Item trao thưởng Mỗi Ngày:</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['itemeveryday'])){ print $setting['itemeveryday']; }else{ print''; } ?>" type="text" name="contentAdmincp[itemeveryday]" /></td>
			</tr>
		</table>
	</div>
	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Số lượng Item trao thưởng Mỗi Ngày:</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['sumitemeveryday'])){ print $setting['sumitemeveryday']; }else{ print''; } ?>" type="text" name="contentAdmincp[sumitemeveryday]" /></td>
			</tr>
		</table>
	</div>

	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Bật/ Tắt Event khởi động server 7 ngày (Bật: 1, Tắt: 0):</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['onoffevent_startServer7days'])){ print $setting['onoffevent_startServer7days']; }else{ print''; } ?>" type="text" name="contentAdmincp[onoffevent_startServer7days]" /></td>
			</tr>
		</table>
	</div>

	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Bật/ Tắt Event khởi động server 7 ngày (Bật: 1, Tắt: 0):</td>
				<td class="right_text_field"><input value="<?php if(isset($setting['onoffevent_incrementalmoney'])){ print $setting['onoffevent_incrementalmoney']; }else{ print''; } ?>" type="text" name="contentAdmincp[onoffevent_incrementalmoney]" /></td>
			</tr>
		</table>
	</div>
	

	</form>

</div>