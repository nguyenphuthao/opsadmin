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
                <li class="active">CHI TIẾT TÀI KHOẢN KẾT NỐI</li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="container">
                <table class="table table-striped table-hover dataTable no-footer" role="grid" aria-describedby="datatable1_info">
                    <thead>
                      <tr role="row">
                            <th class="sorting" onclick="sort('fullname')">Họ tên</th>
                            <th class="sorting" onclick="sort('email')">Số lần tạo video</th>
                            <th>Số lần tạo thành công</th>
                            <th>Số lần share</th>
                            <th>Video</th>
                      </tr>
                   </thead>
                   <tbody>
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$totalVideo}}</td>
                            <td>{{$totalVideoSuccess}}</td>
                            <td>{{$totalShare}}</td>
                            <td>    
                                @if($listVideo)
                                    <ul>                                    
                                    @foreach($listVideo as $item)
                                        <li><a target="_blank" href="{{PATH_URL.'chinh-sua-clip/'.$user->oauth_uid.'/'.$item->id}}">{{$item->id}}</a></li>
                                    @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr> 
                   </tbody>
                </table>
            </div> 
        </div><!--end .section-body -->
    </section>
</div><!--end #content-->
<!-- END CONTENT -->
@endsection
@section('script')
<script src="{{asset('admin/assets/formvalidation/formvalidation.io.js')}}"></script>
<script src="{{asset('admin/assets/js/form/jquery.form.min.js')}}"></script>
@endsection