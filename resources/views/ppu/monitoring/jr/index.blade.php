@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Job Request Monitoring</h1>
    </section>
@endsection

@section('content2')
    <section class="content">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Monitoring</h3>
            </div>

            <div class="box-body">
                <div class="panel">
                    <div class="box box-sm box-default box-solid collapsed-box">
                        <div class="box-header with-border">
                            <p class="no-margin"><i class="fa fa-filter"></i> Advanced Filters <small id="filter-notifier" class="label bg-blue blink"></small></p>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool advanced_filters_toggler" data-widget="collapse"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body" style="display: none">
                            <form id="filter_form">
                                <div class="row">

                                    {!! \App\Swep\ViewHelpers\__form2::select('year',[
                                        'cols' => '1 dt_filter-parent-div',
                                        'label' => 'Year:',
                                        'class' => 'dt_filter filters',
                                        'options' => \App\Swep\Helpers\Arrays::years(),
                                        'for' => 'select2_papCode',
                                    ],\Illuminate\Support\Carbon::now()->format('Y')) !!}

                                    {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                                        'cols' => '3 dt_filter-parent-div',
                                        'label' => 'Department/Division/Section:',
                                        'class' => 'dt_filter filters',
                                        'options' => \App\Swep\Helpers\Arrays::groupedRespCodes('all'),
                                        'for' => 'select2_papCode',
                                    ]) !!}

                                </div>
                            </form>

                        </div>

                    </div>
                </div>
                <div id="jr_monitoring_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="jr_monitoring_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th style="width: 10%">JR No.</th>
                            <th style="width: 10%">Date Created</th>
                            <th style="width: 10%">Date Received</th>
                            <th style="width: 10%">RFQ Date</th>
                            <th style="width: 10%">AQ Date</th>
                            <th style="width: 15%">RBAC Reso Date</th>
                            <th style="width: 10%">NOA Date</th>
                            <th style="width: 10%">PO/JO Date</th>
                            <th style="width: 10%">Action</th>
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
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        var activePr = '';
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            jr_monitoring_tbl = $("#jr_monitoring_table").DataTable({
                "ajax": '{{\Illuminate\Support\Facades\Request::url()}}',
                "columns": [
                    {"data": "jr_no"},
                    {"data": "date_created"},
                    {"data": "date_received"},
                    {"data": "rfq_date"},
                    {"data": "aq_date"},
                    {"data": "rbac_reso_date"},
                    {"data": "noa_date"},
                    {"data": "po_jo_date"},
                    {"data": "action"}
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],
                "order": [[1, 'desc']],
                "responsive": true,
                'dom': 'lBfrtip',
                "processing": true,
                "serverSide": true,
                "initComplete": function (settings, json) {
                    style_datatable("#" + settings.sTableId);
                    $('#tbl_loader').fadeOut(function () {
                        $("#" + settings.sTableId + "_container").fadeIn();
                        if (find != '') {
                            jr_monitoring_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#' + settings.sTableId + '_filter input').unbind();
                    $('#' + settings.sTableId + '_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            jr_monitoring_tbl.search(this.value).draw();
                        }
                    });
                },

                "language":
                    {
                        "processing": "<center><img style='width: 70px' src='{{asset("images/loader.gif")}}'></center>",
                    },
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                    $('[data-toggle="modal"]').tooltip();
                    if (activePr != '') {
                        $("#" + settings.sTableId + " #" + activePr).addClass('success');
                    }
                }
            });
        })

        $("body").on("change",".dt_filter",function () {
            let form = $(this).parents('form');
            filterDT(jr_monitoring_tbl);
        })
    </script>
@endsection