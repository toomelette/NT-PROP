@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Waste Materials Report</h1>
    </section>
@endsection
@section('content2')

<section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Manage Waste Materials Report</h3>
                <div class="btn-group pull-right">
                    <a class="btn btn-primary btn-sm" href="{{route('dashboard.wmr.create')}}" > <i class="fa fa-plus"></i> Create</a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="wmr_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="wmr_table" style="width: 100% !important">
                                <thead>
                                <tr style="width: 100%">
                                    <th style="width: 15%">Place of Storage</th>
                                    <th style="width: 12%">Unit</th>
                                    <th style="width: 30%">Item</th>
                                    <th style="width: 12%">Qty</th>
                                    <th style="width: 15%">O.R. No.</th>
                                    <th style="width: 12%">Amount</th>
                                    <th style="width: 50px"></th>
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

            wmr_tbl = $("#wmr_table").DataTable({
                "ajax" : '{{route("dashboard.wmr.index")}}',
                "columns": [
                    { "data": "storage" },
                    { "data": "unit" },
                    { "data": "item" },
                    { "data": "qty" },
                    { "data": "or_no" },
                    { "data": "amount" },
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
                        $("#wmr_table_container").fadeIn();
                        if(find != ''){
                            wmr_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            wmr_tbl.search(this.value).draw();
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
                                $("#wmr_tbl #"+item).addClass('success');
                            })
                        }
                        $("#wmr_tbl #"+active).addClass('success');
                    }
                }
            });
        })
    </script>
@endsection