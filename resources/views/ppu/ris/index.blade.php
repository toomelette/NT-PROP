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
                <h3 class="box-title">Requisition and Issue Slip</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="ris_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="ris_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>RIS Number</th>
                                    <th>Department/Division</th>
                                    <th>Supplier</th>
                                    <th>Account Code</th>
                                    <th>Invoice Number</th>
                                    <th>PO Number</th>
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

            ris_tbl = $("#ris_table").DataTable({
                "ajax" : '{{route("dashboard.ris.index")}}',
                "columns": [
                    { "data": "ref_no" },
                    { "data": "resp_center" },
                    { "data": "supplier" },
                    { "data": "account_code" },
                    { "data": "invoice_number" },
                    { "data": "po_number" },
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
                                $("#ris_table #"+item).addClass('success');
                            })
                        }
                        $("#ris_table #"+active).addClass('success');
                    }
                }
            });
        })
    </script>
@endsection