@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Requisition and Issue Slip</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Manage Requisition and Issue Slip</h3>
                <div class="btn-group pull-right">
                    {{--                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#property-tag-by-location"><i class="fa fa-print"></i> Property Tag by Location</button>--}}
                    <a class="btn btn-primary btn-sm" href="{{route('dashboard.ris.create')}}" > <i class="fa fa-plus"></i> Create</a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="ris_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="ris_table" style="width: 100% !important">
                                <thead>
                                <tr style="width: 100%">
                                    <th style="width: 8%">RIS Number</th>
                                    <th style="width: 8%">Department</th>
                                    <th style="width: 30%">Item</th>
                                    <th style="width: 8%">Quantity</th>
                                    <th style="width: 8%">Actual Quantity</th>
                                    <th style="width: 15%">Requested By:</th>
                                    <th style="width: 5%">Action</th>
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
@endsection

@section('scripts')
    <script type="text/javascript">
        let active;
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            iar_tbl = $("#ris_table").DataTable({
                "ajax" : '{{route("dashboard.ris.index")}}',
                "columns": [
                    { "data": "ref_no" },
                    { "data": "resp_center" },
                    { "data": "item" },
                    { "data": "qty" },
                    { "data": "actual_qty" },
                    { "data": "requested_by" },
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
                        $("#ris_table_container").fadeIn();
                        if(find != ''){
                            ris_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            ris_tbl.search(this.value).draw();
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
                                $("#ris_tbl #"+item).addClass('success');
                            })
                        }
                        $("#ris_tbl #"+active).addClass('success');
                    }
                }
            });
        })
    </script>
@endsection