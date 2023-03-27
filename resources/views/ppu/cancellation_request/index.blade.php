@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Request</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Request</h3>
                <button class="btn btn-primary btn-sm pull-right" type="button" data-toggle="modal" data-target="#add_supplier_modal"><i class="fa fa-plus"></i> Add Supplier</button>
            </div>
            <div class="box-body">
                <div class="panel">
                    <div class="box box-sm box-default box-solid collapsed-box">
                        <div class="box-header with-border filter-box">
                            <p class="no-margin"><i class="fa fa-filter"></i> Advanced Filters <small id="filter-notifier" class="label bg-blue blink"></small></p>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool advanced_filters_toggler" data-widget="collapse"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="cr_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="cr_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>Type</th>
                                    <th>Ref No.</th>
                                    <th>Ref Date</th>
                                    <th>ABC</th>
                                    <th>Requested By</th>
                                    <th>Action</th>
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
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        var active;
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            cr_tbl = $("#cr_table").DataTable({
                "ajax" : '{{route("dashboard.cancellationRequest.index")}}',
                "columns": [
                    { "data": "ref_book" },
                    { "data": "ref_number" },
                    { "data": "ref_date" },
                    { "data": "total_amount" },
                    { "data": "requester" },
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
                        $("#cr_table_container").fadeIn();
                        if(find != ''){
                            cr_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            cr_tbl.search(this.value).draw();
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
                                $("#cr_table #"+item).addClass('success');
                            })
                        }
                        $("#cr_table #"+active).addClass('success');
                    }
                }
            });
        })
    </script>
@endsection