@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Drivers</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <section class="content">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Manage Drivers</h3>
                    <div class="btn-group pull-right">
                        {{--                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#property-tag-by-location"><i class="fa fa-print"></i> Property Tag by Location</button>--}}
                        <a class="btn btn-primary btn-sm" href="{{route('dashboard.drivers.create')}}" > <i class="fa fa-plus"></i> Add</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="driver_table_container" style="display: none">
                                <table class="table table-bordered table-striped table-hover" id="driver_table" style="width: 100% !important">
                                    <thead>
                                    <tr style="width: 100%">
                                        <th style="width: 15%">Employee No.</th>
                                        <th style="width: 20%">Name</th>
                                        <th style="width: 20%">Contact No.</th>
                                        <th style="width: 12%">Email Address</th>
                                        <th style="width: 12%">Action</th>
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

            driver_tbl = $("#driver_table").DataTable({
                "ajax" : '{{route("dashboard.drivers.index")}}',
                {{--"ajax" : '{{\Illuminate\Support\Facades\Request::url()}}?year='+$("#filter_form select[name='year']").val(),--}}
                "columns": [
                    { "data": "employee_no" },
                    { "data": "employee_slug" },
                    { "data": "contact_no" },
                    { "data": "color" },
                    { "data": "action" },
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],

                // "order" : [[4,'asc']],
                "responsive": false,
                'dom' : 'lBfrtip',
                "processing": true,
                "serverSide": true,
                "initComplete": function( settings, json ) {
                    style_datatable("#"+settings.sTableId);
                    $('#tbl_loader').fadeOut(function(){
                        $("#driver_table_container").fadeIn();
                        if(find != ''){
                            driver_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            driver_tbl.search(this.value).draw();
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
                                $("#driver_tbl #"+item).addClass('success');
                            })
                        }
                        $("#driver_tbl #"+active).addClass('success');
                    }
                }
            });
        })
    </script>
@endsection