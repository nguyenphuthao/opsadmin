@extends('BACKEND.master')
@section('title')
{{$title}}
@endsection
@section('stylesheets')
<!-- Add fancyBox -->
<link rel="stylesheet" href="{{asset('admin/assets/js/fancybox/source/jquery.fancybox.css')}}" type="text/css" media="screen" />
<link rel="stylesheet" href="{{asset('admin/assets/js/fancybox/source/helpers/jquery.fancybox-thumbs.css')}}" type="text/css" media="screen" />
<link rel="stylesheet" href="{{asset('admin/assets/js/fancybox/source/helpers/jquery.fancybox-thumbs.css')}}" type="text/css" media="screen" />
<link rel="stylesheet" href="{{asset('admin/assets/js/fancybox/source/helpers/jquery.fancybox-buttons.css')}}" type="text/css" media="screen" />
<!-- End fancyBox -->
<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/css/theme-default/libs/DataTables/jquery.dataTables.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/css/theme-default/libs/DataTables/extensions/dataTables.colVis.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('admin/assets/css/theme-default/libs/DataTables/extensions/dataTables.tableTools.css')}}" />


@endsection
@section('content')
<!-- BEGIN CONTENT-->
<div id="content">
    <section>
        
        <div class="card card-bordered style-primary section-body">
            <input type="hidden" value="<?php ($this->session->userdata('start'))? print $this->session->userdata('start') : print 0 ?>" id="start" />
            <input type="hidden" value="<?=$default_func?>" id="func_sort" />
            <input type="hidden" value="<?=$default_sort?>" id="type_sort" />
            <div class="card-head">
                <div class="tools">
                    <div class="btn-group">

                        <!-- <a href=" {{ PATH_URL}}admincp/{{$module}}/update/" class='btn btn-icon-toggle'><i class='md md-add-circle'></i></a> -->
                        <a href="javascript:;" class='btn btn-icon-toggle' onclick="deleteAll()"><i class='md md-delete'></i></a>
                        <div class="btn-group">
                            <a href="#" class="btn btn-icon-toggle dropdown-toggle" data-toggle="dropdown"><i class="md md-colorize"></i></a>
                            <ul class="dropdown-menu animation-dock pull-right menu-card-styling" role="menu" style="text-align: left;">
                                <li><a href="javascript:void(0);" data-style="style-default-dark"><i class="fa fa-circle fa-fw text-default-dark"></i> Default dark</a></li>
                                <li><a href="javascript:void(0);" data-style="style-default"><i class="fa fa-circle fa-fw text-muted"></i> Default</a></li>
                                <li><a href="javascript:void(0);" data-style="style-default-light"><i class="fa fa-circle fa-fw text-default-light"></i> Default light</a></li>
                                <li><a href="javascript:void(0);" data-style="style-default-bright"><i class="fa fa-circle fa-fw text-default-bright"></i> Default bright</a></li>
                                <li><a href="javascript:void(0);" data-style="style-primary-dark"><i class="fa fa-circle fa-fw text-primary-dark"></i> Primary dark</a></li>
                                <li><a href="javascript:void(0);" data-style="style-primary"><i class="fa fa-circle fa-fw text-primary"></i> Primary</a></li>
                                <li><a href="javascript:void(0);" data-style="style-primary-light"><i class="fa fa-circle fa-fw text-primary-light"></i> Primary light</a></li>
                                <li><a href="javascript:void(0);" data-style="style-primary-bright"><i class="fa fa-circle fa-fw text-primary-bright"></i> Primary bright</a></li>
                                <li><a href="javascript:void(0);" data-style="style-accent-dark"><i class="fa fa-circle fa-fw text-accent-dark"></i> Accent dark</a></li>
                                <li><a href="javascript:void(0);" data-style="style-accent"><i class="fa fa-circle fa-fw text-accent"></i> Accent</a></li>
                                <li><a href="javascript:void(0);" data-style="style-accent-light"><i class="fa fa-circle fa-fw text-accent-light"></i> Accent light</a></li>
                                <li><a href="javascript:void(0);" data-style="style-accent-bright"><i class="fa fa-circle fa-fw text-accent-bright"></i> Accent bright</a></li>
                                <li><a href="javascript:void(0);" data-style="style-danger"><i class="fa fa-circle fa-fw text-danger"></i> Danger</a></li>
                                <li><a href="javascript:void(0);" data-style="style-warning"><i class="fa fa-circle fa-fw text-warning"></i> Warning</a></li>
                                <li><a href="javascript:void(0);" data-style="style-success"><i class="fa fa-circle fa-fw text-success"></i> Success</a></li>
                                <li><a href="javascript:void(0);" data-style="style-info"><i class="fa fa-circle fa-fw text-info"></i> Info</a></li>
                            </ul>
                        </div>
                        <a class="btn btn-icon-toggle btn-refresh"><i class="md md-refresh"></i></a>
                        <a class="btn btn-icon-toggle btn-collapse"><i class="fa fa-angle-down"></i></a>
                        <a class="btn btn-icon-toggle btn-close"><i class="md md-close"></i></a>
                    </div>
                </div>
                <header><i class="fa fa-fw fa-tag"></i> DANH SÁCH KẾT NỐI</header>
            </div><!--end .card-head -->
            <div class="card-body style-default-bright">
                <div class="table-responsive">
                    <div id="datatable1_wrapper" class="dataTables_wrapper no-footer">
                        <div class="dataTables_length" id="datatable1_length">
                            <label>
                                <select name="datatable1_length" id='per_page' onchange="searchContent(0,this.value)">
                                 <option value="10">10</option>
                                 <option value="25">25</option>
                                 <option value="50">50</option>
                                 <option value="100">100</option>
                             </select>
                             entries per page
                         </label>
                        </div>
                        <div class="ColVis"><a class="btn btn-primary btn-raised" href="{{PATH_URL.'admincp/'.$module}}/export">Xuất excel</a></div>
                        <div class="ColVis"><button class="ColVis_Button ColVis_MasterButton" onclick="searchContentType(0)"><span>Tìm</span></button></div>

                        <div class="dataTables_filter">
                            <label>
                                <i class="fa fa-search"></i>
                                <input type="search" id="search_content" class="" placeholder="" onkeypress="return enterSearch(event)">
                            </label>
                        </div>

                        <div id="loadContent"></div>
                    </div>
                </div>
            </div><!--end .card-body -->
        </div>
    </section>
</div><!--end #content-->
<!-- END CONTENT -->
@endsection
@section('script')

<!-- Add fancyBox -->
<script type="text/javascript" src="{{asset('admin/assets/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/assets/js/fancybox/source/jquery.fancybox.pack.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/assets/js/fancybox/source/helpers/jquery.fancybox-buttons.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/assets/js/fancybox/source/helpers/jquery.fancybox-media.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/assets/js/fancybox/source/helpers/jquery.fancybox-thumbs.js')}}"></script>
<!-- End fancyBox -->
<script src="{{asset('admin/assets/js/libs/jquery-ui/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
    var root = '<?php echo PATH_URL;?>';
    var module = '<?php echo $module;?>';    
</script>
<script src="{{asset('admin/assets/js/jquery.url.js')}}"></script>
<script src="{{asset('admin/assets/js/admin.js')}}"></script>
<script type="text/javascript" charset="utf-8">
    function enterSearch(a) {
        if (a.keyCode == 13) {
         searchContentType(0);
        }
    }
    function searchContentType(d, b) {
        if (b == undefined) {
            if ($("#per_page").val()) {
                b = $("#per_page").val()
            } else {
                b = 10
            }
        }
        var a = $("#func_sort").val();
        var c = $("#type_sort").val();
        var fa = $("#first_access").val();
        $("#start").val(d);
        $.post(root + "admincp/" + module + "/ajaxLoadContent", {
            func_order_by : a,
            order_by : c,
            start : d,
            first_access : fa,
            per_page : b,
            dateFrom : $("#caledar_from").val(),
            dateTo : $("#caledar_to").val(),
            name : $("#search_content").val()
        }, function (e) {
            $("#loadContent").html(e);
            $(".sort").removeClass("icon_sort_desc");
            $(".sort").removeClass("icon_sort_asc");
            $(".sort").addClass("icon_no_sort");
            if (c == "DESC") {
                $("#" + a).addClass("icon_sort_desc")
            } else {
                $("#" + a).addClass("icon_sort_asc")
            }
        })
    }

</script>
</script>
@endsection