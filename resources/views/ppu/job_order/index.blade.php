@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Manage Job Order</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Job Order</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="box box-sm box-default box-solid collapsed-box">
                                <div class="box-header with-border">
                                    <p class="no-margin"><i class="fa fa-filter"></i> Advanced Filters <small id="filter-notifier" class="label bg-blue blink"></small></p>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool advanced_filters_toggler" data-widget="collapse"><i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body" style="display: none">
                                    <form id="filter_form">
                                        <div class="row">
                                            {!! \App\Swep\ViewHelpers\__form2::select('year',[
                                                'cols' => '1 dt_filter-parent-div',
                                                'label' => 'Year:',
                                                'class' => 'dt_filter filters',
                                                'options' => \App\Swep\Helpers\Arrays::years(),
                                                'for' => 'select2_papCode',
                                            ],\Illuminate\Support\Carbon::now()->format('Y')) !!}

                                            {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                                                'cols' => '3 dt_filter-parent-div',
                                                'label' => 'Department/Division/Section:',
                                                'class' => 'dt_filter filters',
                                                'options' => \App\Swep\Helpers\Arrays::groupedRespCodes('all'),
                                                'for' => 'select2_papCode',
                                                'id' => 'resp_center_select2',
                                            ]) !!}
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="table-responsive" id="jo_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="jo_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th style="width: 5px;">Dept/Div/RC</th>
                                    <th>JO Number</th>
                                    <th>Mode</th>
                                    <th>Supplier</th>
                                    <th>Contact Person</th>
                                    <th>Total</th>
                                    <th>Date</th>
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

            jo_tbl = $("#jo_table").DataTable({
                //"ajax" : '{{route("dashboard.jo.index")}}',
                "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}?year='+$("#filter_form select[name='year']").val(),
                "columns": [
                    { "data": "dept" },
                    { "data": "ref_no" },
                    { "data": "mode" },
                    { "data": "supplier_name" },
                    { "data": "supplier_representative" },
                    { "data": "total" },
                    { "data": "date" },
                    { "data": "action" }
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],
                "order" : [[1,'desc']],
                "responsive": false,
                'dom' : 'lBfrtip',
                "processing": true,
                "serverSide": true,
                "initComplete": function( settings, json ) {
                    style_datatable("#"+settings.sTableId);
                    $('#tbl_loader').fadeOut(function(){
                        $("#jo_table_container").fadeIn();
                        if(find != ''){
                            jo_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            jo_tbl.search(this.value).draw();
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
                                $("#jo_table #"+item).addClass('success');
                            })
                        }
                        $("#jo_table #"+active).addClass('success');
                    }
                }
            });

            $("body").on("click",".edit_btn",function () {
                let btn = $(this);
                load_modal2(btn);
                let uri = '{{route("dashboard.jo.edit","slug")}}';
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

            $("body").on('click','.cancel_transaction_btn',function () {
                let btn = $(this);
                let uri  = '{{route('dashboard.jo.cancel','slug')}}';
                uri = uri.replace('slug',btn.attr('data'));
                Swal.fire({
                    title: 'Cancel Transaction?',
                    input: 'text',
                    html: 'Please enter a cancellation reason:',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    confirmButtonColor: '#dd4b39',
                    showCancelButton: true,
                    cancelButtonText : 'No',
                    confirmButtonText: '<i class="fa fa-check"></i> Yes, Submit',
                    showLoaderOnConfirm: true,
                    preConfirm: (text) => {
                        return $.ajax({
                            url : uri,
                            type: 'POST',
                            data: {'cancellation_reason':text},
                            headers: {
                                {!! __html::token_header() !!}
                            },
                            success : function (res) {
                                activePr = res.slug;
                                pr_tbl.draw();
                            }
                        })
                            .then(response => {
                                return  response;

                            })
                            .catch(error => {
                                console.log(error);
                                Swal.showValidationMessage(
                                    'Error : '+ error.responseJSON.message,
                                )
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        toast('success','PO was successfully marked as cancelled.','Success!');

                    }
                })
            })

            $("body").on("change",".dt_filter",function () {
                filterDT(jo_tbl);
            });
            $("#resp_center_select2").select2();

        })
    </script>
@endsection