@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>Abstract of Quotations</h1>
</section>
@endsection
@section('content2')

<section class="content">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">PRs & JRs pending of AQ</a></li>
            <li><a href="#tab_2" data-toggle="tab">All AQs</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div id="aq_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="aq_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th >RFQ No.</th>
                            <th >Ref Book.</th>
                            <th>PR/JR #</th>
                            <th>PR/JR Date <i class="fa fa-arrow-right"></i> RFQ Date</th>
                            <th >Items</th>

                            <th >Total</th>
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

            <div class="tab-pane" id="tab_2">
                <div id="all_aq_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="all_aq_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th >AQ No.</th>
                            <th >Ref Book.</th>
                            <th>PR/JR #</th>
                            <th>PR/JR Date <i class="fa fa-arrow-right"></i> RFQ Date</th>
                            <th >Items</th>

                            <th >Total</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="all_rfq_tbl_loader">
                    <center>
                        <img style="width: 100px" src="{{asset('images/loader.gif')}}">
                    </center>
                </div>
            </div>

        </div>
    </div>
</section>


@endsection


@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
    var active = '';
    var all_aq_tbl_active = '';
    $(document).ready(function () {
        //-----DATATABLES-----//
        modal_loader = $("#modal_loader").parent('div').html();
        //Initialize DataTable

        aq_tbl = $("#aq_table").DataTable({
            "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}',
            "columns": [
                { "data": "ref_no" },
                { "data": "transRefBook" },
                { "data": "cross_ref_no" },
                { "data": "dates" },
                { "data": "transDetails" },
                { "data": "abc" },
                { "data": "action" }
            ],
            "buttons": [
                {!! __js::dt_buttons() !!}
            ],
            "columnDefs":[
                {
                    "targets" : 0,
                    "class" : 'w-8p'
                },
                {
                    "targets" : 1,
                    "class" : 'w-12p'
                },
                {
                    "targets" : 2,
                    "class" : 'w-10p'
                },
                {
                    "targets" : 3,
                    "class" : 'w-12p'
                },

                {
                    "targets" : 5,
                    "class" : 'w-8p text-right'
                },
                {
                    "targets" : 6,
                    "orderable" : false,
                    "class" : 'action4'
                },
            ],
            'order' : [[2,'desc']],
            "responsive": false,
            'dom' : 'lBfrtip',
            "processing": true,
            "serverSide": true,
            "initComplete": function( settings, json ) {
                style_datatable("#"+settings.sTableId);
                $('#tbl_loader').fadeOut(function(){
                    $("#"+settings.sTableId+"_container").fadeIn();
                    if(find != ''){
                        aq_tbl.search(find).draw();
                    }
                });
                //Need to press enter to search
                $('#'+settings.sTableId+'_filter input').unbind();
                $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                    if (e.keyCode == 13) {
                        aq_tbl.search(this.value).draw();
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
                    $("#"+settings.sTableId+" #"+active).addClass('success');
                }
            }
        });

        all_aq_tbl = $("#all_aq_table").DataTable({
            "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}?all_aq=true',
            "columns": [
                { "data": "ref_no" },
                { "data": "transRefBook" },
                { "data": "cross_ref_no" },
                { "data": "dates" },
                { "data": "transDetails" },
                { "data": "abc" },
                { "data": "action" }
            ],
            "buttons": [
                {!! __js::dt_buttons() !!}
            ],
            "columnDefs":[
                {
                    "targets" : 0,
                    "class" : 'w-8p'
                },
                {
                    "targets" : 1,
                    "class" : 'w-8p'
                },
                {
                    "targets" : 2,
                    "class" : 'w-10p'
                },
                {
                    "targets" : 3,
                    "class" : 'w-14p'
                },

                {
                    "targets" : 5,
                    "class" : 'w-8p'
                },
                {
                    "targets" : 6,
                    "orderable" : false,
                    "class" : 'action4'
                },
            ],
            "responsive": false,
            'dom' : 'lBfrtip',
            "processing": true,
            "serverSide": true,
            "initComplete": function( settings, json ) {
                style_datatable("#"+settings.sTableId);
                $('#all_rfq_tbl_loader').fadeOut(function(){
                    $("#"+settings.sTableId+"_container").fadeIn();
                    if(find != ''){
                        all_aq_tbl.search(find).draw();
                    }
                });
                //Need to press enter to search
                $('#'+settings.sTableId+'_filter input').unbind();
                $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                    if (e.keyCode == 13) {
                        all_aq_tbl.search(this.value).draw();
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
                if(all_aq_tbl_active != ''){
                    $("#"+settings.sTableId+" #"+all_aq_tbl_active).addClass('success');
                }
            }
        });
    })

</script>
@endsection