<script type="text/javascript">
function save(){
	var options = {
		beforeSubmit:  showRequest,  // pre-submit callback 
		success:       showResponse  // post-submit callback 
    };
	$('#frmManagement').ajaxSubmit(options);
}

function showRequest(formData, jqForm, options) {
	var form = jqForm[0];
	if(form.oldpassAdmincp.value == '' || form.newpassAdmincp.value == '' || form.renewpassAdmincp.value == ''){
		$('#txt_error').html('Please enter information!!!');
		$('#loader').fadeOut(300);
		show_perm_denied();
		return false;
	}
	
	if(form.newpassAdmincp.value != form.renewpassAdmincp.value){
		$('#txt_error').html('New password & Re-new password incorrect!!!');
		$('#loader').fadeOut(300);
		show_perm_denied();
		return false;
	}
}

function showResponse(responseText, statusText, xhr, $form) {
	if(responseText=='success_update_profile'){
		location.href=root+"admincp/logout/";
	}else if(responseText=='error_update_profile'){
		$('#txt_error').html('Old password incorrect!!!');
		$('#loader').fadeOut(300);
		show_perm_denied();
		return false;
	}
}
</script>
<div class="gr_perm_error" style="display:none;">
	<p><strong>FAILURE: </strong><span id="txt_error">Permission Denied.</span></p>
</div>
<div class="table">
	<div class="head_table"><div class="head_title_edit">Update profile</div></div>
	<div class="clearAll"></div>

	<form id="frmManagement" action="<?=PATH_URL.'admincp/update_profile/'?>" method="post" enctype="multipart/form-data">
	<div class="row_text_field_first">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Old password:</td>
				<td class="right_text_field"><input value="" type="password" name="oldpassAdmincp" /></td>
			</tr>
		</table>
	</div>

	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">New password:</td>
				<td class="right_text_field"><input value="" type="password" name="newpassAdmincp" /></td>
			</tr>
		</table>
	</div>
	
	<div class="row_text_field">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="left_text_field">Re-new password:</td>
				<td class="right_text_field"><input value="" type="password" name="renewpassAdmincp" /></td>
			</tr>
		</table>
	</div>
	</form>
</div>