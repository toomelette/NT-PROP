@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Manage Notice to Proceed</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Notice to Proceed</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id="ntp_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="ntp_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>PR/JR No.</th>
                                    <th>Notice Number</th>
                                    <th>Document No.</th>
                                    <th>Supplier</th>
                                    <th>Representative</th>
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
        let active;
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            ntp_tbl = $("#ntp_table").DataTable({
                "ajax" : '{{route("dashboard.ntp.index")}}',
                "columns": [
                    { "data": "ref_no" },
                    { "data": "notice_number" },
                    { "data": "document_no" },
                    { "data": "supplier" },
                    { "data": "supplier_representative" },
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
                        $("#ntp_table_container").fadeIn();
                        if(find != ''){
                            ntp_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            ntp_tbl.search(this.value).draw();
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
                                $("#ntp_table #"+item).addClass('success');
                            })
                        }
                        $("#ntp_table #"+active).addClass('success');
                    }
                }
            });

            /*$("body").on("click",".edit_btn",function () {
                let btn = $(this);
                load_modal2(btn);
                let uri = '{{route("dashboard.noa.edit","slug")}}';
                uri = uri.replace('slug',btn.attr('data'));
                $.ajax({
                    url : uri,
                    type: 'GET',
                    headers: {
                        {!! __html::token_header() !!}
                    },
                    success: function (res) {
                        populate_modal2(btn,res);
                    },
                    error: function (res) {
                        console.log(res);
                        populate_modal2_error(res);
                    }
                })
            });*/
        })
    </script>
@endsection