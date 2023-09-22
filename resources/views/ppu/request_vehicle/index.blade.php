@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Requests for Shuttle Service</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id="articles_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="articles_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>Requisitioner</th>
                                    <th>Date</th>
                                    <th>Request No.</th>
                                    <th>Passengers</th>
                                    <th>Destination</th>
                                    <th>Date of Departure</th>
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


@section('modals')
    {!! \App\Swep\ViewHelpers\__html::blank_modal('actions_modal','lg') !!}
@endsection

@section('scripts')
    <script type="text/javascript">
        //-----DATATABLES-----//
        let active = '';
        modal_loader = $("#modal_loader").parent('div').html();
        //Initialize DataTable

        request_tbl = $("#articles_table").DataTable({
            "ajax" : '{{route("dashboard.request_vehicle.index")}}',
            "columns": [
                { "data": "requested_by" },
                { "data": "created_at" },
                { "data": "request_no" },
                { "data": "passengers" },
                { "data": "destination" },
                { "data": "from" },
                { "data": "action" }
            ],
            "buttons": [
                {!! __js::dt_buttons() !!}
            ],
            "columnDefs":[
                {
                    "targets" : [1,2],
                    "class" : 'w-8p'
                },
                {
                    "targets" : 6,
                    "orderable" : false,
                    "class" : 'action4'
                },
            ],
            "order" : [[1,'desc'],[2,'desc']],
            "responsive": false,
            'dom' : 'lBfrtip',
            "processing": true,
            "serverSide": true,
            "initComplete": function( settings, json ) {
                style_datatable("#"+settings.sTableId);
                $('#tbl_loader').fadeOut(function(){
                    $("#articles_table_container").fadeIn();
                    if(find != ''){
                        request_tbl.search(find).draw();
                    }
                });
                //Need to press enter to search
                $('#'+settings.sTableId+'_filter input').unbind();
                $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                    if (e.keyCode == 13) {
                        request_tbl.search(this.value).draw();
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
                            $("#articles_table #"+item).addClass('success');
                        })
                    }
                    $("#articles_table #"+active).addClass('success');
                }
            }
        });

        $("body").on("click",".actions_btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.request_vehicle.actions","slug")}}';
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
                    populate_modal2_error(res);
                }
            })
        })
    </script>
@endsection