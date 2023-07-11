@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Email Notification Recipients</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Suppliers</h3>
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
                        <div id="email_recipients_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="email_recipients_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>RC Code</th>
                                    <th>Department</th>
                                    <th>Division</th>
                                    <th>Section</th>
                                    <th>Email Addresses</th>
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
        {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_modal','20') !!}
    </section>


@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        let active;
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            email_recipients_tbl = $("#email_recipients_table").DataTable({
                "ajax" : '{{route("dashboard.email_recipients.index")}}',
                "columns": [
                    { "data": "rc_code" },
                    { "data": "department" },
                    { "data": "division" },
                    { "data": "section" },
                    { "data": "email_addresses" },
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
                        $("#email_recipients_table_container").fadeIn();
                        if(find != ''){
                            email_recipients_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            email_recipients_tbl.search(this.value).draw();
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
                                $("#email_recipients_table #"+item).addClass('success');
                            })
                        }
                        $("#email_recipients_table #"+active).addClass('success');
                    }
                }
            });
        })


        $("#add_supplier_form").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.supplier.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    active = res.id;
                    email_recipients_tbl.draw(false);
                    succeed(form,true,false);
                    toast('success','Supplier successfully added.','Success!');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })

        $("body").on("click",".edit_btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.email_recipients.edit","slug")}}';
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
        })
    </script>
@endsection