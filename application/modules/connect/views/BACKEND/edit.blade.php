@extends('BACKEND.master')
@section('title')
{{$title}}
@endsection
@section('stylesheets')
<style type="text/css" media="screen">
     .section-body label{font-size: 14px; color: #000;}   
</style>
<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/css/theme-default/libs/summernote/summernote.css')}}" />
<!-- <link type="text/css" rel="stylesheet" href="{{asset('admin/assets/datetimepicker/css/bootstrap-datetimepicker.min.css')}}" /> -->
@endsection

@section('content')
<!-- BEGIN CONTENT-->
<div id="content">
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">SỬA KẾT NỐI</li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="container">
            <form id="frmManagement" action="<?=PATH_URL.'admincp/'.$module.'/save/'?>" method="post" enctype="multipart/form-data">
                 <input type="hidden" value="<?=$id?>" name="hiddenIdAdmincp" />
                <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Họ tên:</label>
                    <div class="col-md-3 col-lg-3">
                        <input type="text" name="fullname" class="form-control" value="{{$result->fullname}}" required="required" title="">
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Email:</label>
                    <div class="col-md-3 col-lg-3">
                        <input type="text" name="email" class="form-control" value="{{$result->email}}" required="required" title="">
                    </div>
                </div>
                 <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Số điện thoại:</label>
                    <div class="col-md-3 col-lg-3">
                        <input type="text" name="phone" class="form-control" value="{{$result->phone}}" required="required" title="">
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Địa điểm:</label>
                    <div class="col-md-3 col-lg-3">
                        <select name="conn_location" class="form-control" required="required">
                            <option value="1" {{$result->conn_location == 1 ? "selected" : ''}}>Hồ Chí Minh - Hà Nội</option>
                            <option value="2" {{$result->conn_location == 2 ? "selected" : ''}}>Hà Nội - Hồ Chí Minh</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Ngày kết nối:</label>
                    <div class="col-md-3 col-lg-3">
                        <select name="conn_date" class='form-control'>
                            <option value="2017-01-07" {{$result->conn_date == '2017-01-07' ? "selected" : ''}}>07-01-2017</option>
                            <option value="2017-01-08" {{$result->conn_date == '2017-01-08' ? "selected" : ''}}>08-01-2017</option>
                            <option value="2017-01-21" {{$result->conn_date == '2017-01-21' ? "selected" : ''}}>21-01-2017</option>
                            <option value="2017-01-22" {{$result->conn_date == '2017-01-22' ? "selected" : ''}}>22-01-2017</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Thời gian:</label>
                    <div class="col-md-3 col-lg-3">
                        <select name="conn_time" class='form-control'>
                            <option value="9" {{$result->conn_time == 9 ? "selected" : ''}}>9 AM - 10 AM</option>
                            <option value="10" {{$result->conn_time == 10 ? "selected" : ''}}>10 AM - 11 AM</option>
                            <option value="11" {{$result->conn_time == 11 ? "selected" : ''}}>11 AM - 12 AM</option>
                            <option value="12" {{$result->conn_time == 12 ? "selected" : ''}}>12 AM - 13 PM</option>
                            <option value="13" {{$result->conn_time == 13 ? "selected" : ''}}>13 PM - 14 PM</option>
                            <option value="14" {{$result->conn_time == 14 ? "selected" : ''}}>14 PM - 15 PM</option>
                            <option value="15" {{$result->conn_time == 15 ? "selected" : ''}}>15 PM - 16 PM</option>
                            <option value="16" {{$result->conn_time == 16 ? "selected" : ''}}>16 PM - 17 PM</option>
                            <option value="17" {{$result->conn_time == 17 ? "selected" : ''}}>17 PM - 18 PM</option>
                            <option value="18" {{$result->conn_time == 18 ? "selected" : ''}}>18 PM - 19 PM</option>
                            <option value="19" {{$result->conn_time == 19 ? "selected" : ''}}>19 PM - 20 PM</option>
                            <option value="20" {{$result->conn_time == 20 ? "selected" : ''}}>20 PM - 21 PM</option>
                            <option value="21" {{$result->conn_time == 21 ? "selected" : ''}}>21 PM - 22 PM</option>
                        </select>
                    </div>
                </div> 
                <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Họ và tên người thân :</label>
                    <div class="col-md-3 col-lg-3">
                        <input type="text" name="fr_fullname" class="form-control" value="{{$friend->fullname}}" required="required" title="">
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Số điện thoại người thân:</label>
                    <div class="col-md-3 col-lg-3">
                        <input type="text" name="fr_phone" class="form-control" value="{{$friend->phone}}" required="required" title="">
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Email người thân:</label>
                    <div class="col-md-3 col-lg-3">
                        <input type="text" name="fr_email" class="form-control" value="{{$friend->email}}" required="required" title="">
                    </div>
                </div>
                 <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Trạng thái danh sách:</label>
                    <div class="col-md-3 col-lg-3">
                        <select name="waiting" class='form-control'>
                            <option value="0" {{$result->waiting == 0 ? "selected" : ''}}>Thành công</option>
                            <option value="1" {{$result->waiting == 1 ? "selected" : ''}}>Chờ</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class='col-md-2 col-lg-2'>Trạng thái danh sách:</label>
                    <div class="col-md-3 col-lg-3">
                        <textarea name="note" class="form-control" rows="3" >{{$result->note}}</textarea>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="txt_error col-md-offset-2 col-md-10">                
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class="col-md-2 col-lg-2">&nbsp;</label>
                    <input type="submit" class='btn btn-primary btn-save' value='Lưu'>
                </div>
            </form>
            </div> 
        </div><!--end .section-body -->
    </section>
</div><!--end #content-->
<!-- END CONTENT -->
@endsection
@section('script')
<script src="{{asset('admin/assets/formvalidation/formvalidation.io.js')}}"></script>
<script src="{{asset('admin/assets/js/form/jquery.form.min.js')}}"></script>
<!-- <script src="{{asset('admin/assets/datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script> -->

<script type="text/javascript">
    var root = '<?php echo PATH_URL;?>';
    var module = '<?php echo $module;?>';
    $(document).ready(function() {
        
        $('#frmManagement').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                nameAdmincp: {
                    validators: {
                        notEmpty: {
                            message: 'Vui lòng nhập nội dung câu hỏi'
                        },
                    }
                }
            }            
        }).on('success.form.fv', function(e){
            e.preventDefault();
            var $form = $(e.target);
            $form.ajaxSubmit({
                url: $form.attr('action'),
                dataType: 'text',
                success: function(responseText, statusText, xhr, $form) {
                    if(responseText == 'success'){
                        location.href=root+"admincp/"+module;
                    }else{
                        $('.txt_error').addClass('alert-danger alert');
                        $('.txt_error').html(responseText);
                        $('.btn-save').removeClass('disabled').removeAttr("disabled");
                    }
                }
            });
        });
    });
</script>
@endsection