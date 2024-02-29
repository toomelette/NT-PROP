@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Vehicles</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <section class="content">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Manage Vehicle</h3>
                    <div class="btn-group pull-right">
                        {{--                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#property-tag-by-location"><i class="fa fa-print"></i> Property Tag by Location</button>--}}
                        <a class="btn btn-primary btn-sm" href="{{route('dashboard.vehicles.create')}}" > <i class="fa fa-plus"></i> Add</a>
                    </div>

{{--                    <div class="box box-sm box-default box-solid collapsed-box"  style=" margin-top: 5px;">--}}
{{--                        <div class="box-header with-border">--}}
{{--                            <p class="no-margin"><i class="fa fa-filter"></i> Advanced Filters <small id="filter-notifier" class="label bg-blue blink"></small></p>--}}
{{--                            <div class="box-tools pull-right">--}}
{{--                                <button type="button" class="btn btn-box-tool advanced_filters_toggler" data-widget="collapse"><i class="fa fa-plus"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="box-body" style="display: none">--}}
{{--                            <form id="filter_form">--}}
{{--                                <div class="row">--}}
{{--                                    {!! \App\Swep\ViewHelpers\__form2::select('year',[--}}
{{--                                        'cols' => '2 dt_filter-parent-div',--}}
{{--                                        'label' => 'Year:',--}}
{{--                                        'class' => 'dt_filter filters',--}}
{{--                                        'options' => \App\Swep\Helpers\Arrays::years(),--}}
{{--                                        'for' => 'select2_papCode',--}}
{{--                                    ],\Illuminate\Support\Carbon::now()->format('Y')) !!}--}}

{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="vehicle_table_container" style="display: none">
                                <table class="table table-bordered table-striped table-hover" id="vehicle_table" style="width: 100% !important">
                                    <thead>
                                    <tr style="width: 100%">
                                        <th style="width: 15%">Year</th>
                                        <th style="width: 20%">Brand</th>
                                        <th style="width: 20%">Model</th>
                                        <th style="width: 12%">Plate Number</th>
                                        <th style="width: 13%">KM per Liter</th>
                                        <th style="width: 13%">Normal KM per Liter</th>
                                        <th style="width: 20%">Status</th>
                                        <th style="width: 20px">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div id="tbl_loader">
                                <center>
                                    <img style="width: 100px" src="{{asset('images/loader.gif')}}">
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_modal','lg') !!}
        </section>

    </section>

@endsection

@section('scripts')
    <script type="text/javascript">
        let active;
        $(document).ready(function () {

            modal_loader = $("#modal_loader").parent('div').html();

            vehicle_tbl = $("#vehicle_table").DataTable({
                "ajax" : '{{route("dashboard.vehicles.index")}}',
                {{--"ajax" : '{{\Illuminate\Support\Facades\Request::url()}}?year='+$("#filter_form select[name='year']").val(),--}}
                "columns": [
                    { "data": "year" },
                    { "data": "make" },
                    { "data": "model1" },
                    { "data": "plate_no" },
                    { "data": "usage" },
                    { "data": "normal_usage" },
                    { "data": "status" },
                    { "data": "action" }
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],
                "responsive": false,
                'dom' : 'lBfrtip',
                "processing": true,
                "serverSide": true,
                "initComplete": function( settings, json ) {
                    style_datatable("#"+settings.sTableId);
                    $('#tbl_loader').fadeOut(function(){
                        $("#vehicle_table_container").fadeIn();
                        if(find != ''){
                            vehicle_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            vehicle_tbl.search(this.value).draw();
                        }
                    });
                },

                "language":
                    {
                        "processing": "<center><img style='width: 70px' src='{{asset("images/loader.gif")}}'></center>",
                    },
                "drawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();
                    $('[data-toggle="modal"]').tooltip();
                    if(active != ''){
                        if(Array.isArray(active) == true){
                            $.each(active,function (i,item) {
                                $("#vehicle_tbl #"+item).addClass('success');
                            })
                        }
                        $("#vehicle_tbl #"+active).addClass('success');
                    }
                }
            });
        })
    </script>
@endsection