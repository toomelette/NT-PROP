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
                <br><br>

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
                                    'cols' => '2 dt_filter-parent-div',
                                    'label' => 'Year:',
                                    'class' => 'dt_filter filters',
                                    'options' => \App\Swep\Helpers\Arrays::years(),
                                    'for' => 'select2_papCode',
                                ],\Illuminate\Support\Carbon::now()->format('Y')) !!}

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id="ris_table_container" style="display: none">
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

            ris_tbl = $("#ris_table").DataTable({
                {{--"ajax" : '{{route("dashboard.ris.index")}}',--}}
                "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}?year='+$("#filter_form select[name='year']").val(),
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

        $("body").on('click','.receive_btn',function () {
            let btn = $(this);
            let url = '{{route('dashboard.ris.receiveRIS','slug')}}';
            url = url.replace('slug',btn.attr('data'));

            Swal.fire({
                title: 'Receive RIS?',
                // input: 'text',
                html: btn.attr('text'),
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check"></i> Receive',
                showLoaderOnConfirm: true,
                preConfirm: (email) => {
                    return $.ajax({
                        url : url,
                        type: 'PATCH',
                        data: {'trans':btn.attr('data')},
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success : function (res) {
                            activePr = res.slug;
                            ris_tbl.draw(false);
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
                    toast('success','RIS was successfully marked as received.','Success!');

                }
            })
        })
        $("body").on("change",".dt_filter",function () {
            filterDT(ris_tbl);
        });
        $("#resp_center_select2").select2();
    </script>
@endsection