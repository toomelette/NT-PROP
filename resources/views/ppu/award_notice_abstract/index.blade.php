@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Manage Award Notice Abstract</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Award Notice Abstract</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="ana_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="ana_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>Notice Number</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Ref Number</th>
                                    <th>Awardee</th>
                                    <th>Award Date</th>
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

            ana_tbl = $("#ana_table").DataTable({
                "ajax" : '{{route("dashboard.awardNoticeAbstract.index")}}',
                "columns": [
                    { "data": "award_notice_number" },
                    { "data": "title" },
                    { "data": "category" },
                    { "data": "ref_number" },
                    { "data": "awardee" },
                    { "data": "award_date" },
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
                        $("#ana_table_container").fadeIn();
                        if(find != ''){
                            ana_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            ana_tbl.search(this.value).draw();
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
                                $("#ana_table #"+item).addClass('success');
                            })
                        }
                        $("#ana_table #"+active).addClass('success');
                    }
                }
            });

            $("body").on("click",".edit_btn",function () {
                let btn = $(this);
                load_modal2(btn);
                let uri = '{{route("dashboard.awardNoticeAbstract.edit","slug")}}';
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
            });


        })
    </script>
@endsection