@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Inspection and Acceptance Report</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Inspection and Acceptance Report</h3>
                <div class="btn-group pull-right">
                    {{--                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#property-tag-by-location"><i class="fa fa-print"></i> Property Tag by Location</button>--}}
                    <a class="btn btn-primary btn-sm" href="{{route('dashboard.iar.create')}}" > <i class="fa fa-plus"></i> Create</a>
                </div>
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
                        <div class="table-responsive" id="iar_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="iar_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>IAR Number</th>
                                    <th>Supplier</th>
                                    <th>Invoice Number</th>
                                    <th>Requisitioning Office/Department</th>
                                    <th>Requested By</th>
                                    <th>PR/JR Number</th>
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

            iar_tbl = $("#iar_table").DataTable({
                {{--"ajax" : '{{route("dashboard.iar.index")}}',--}}
                "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}?year='+$("#filter_form select[name='year']").val(),
                "columns": [
                    { "data": "ref_no" },
                    { "data": "supplier" },
                    { "data": "invoice_number" },
                    { "data": "resp_center" },
                    { "data": "requested_by" },
                    { "data": "cross_ref_no" },
                    { "data": "action" }
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],
                "order" : [[0,'desc']],
                "responsive": false,
                'dom' : 'lBfrtip',
                "processing": true,
                "serverSide": true,
                "initComplete": function( settings, json ) {
                    style_datatable("#"+settings.sTableId);
                    $('#tbl_loader').fadeOut(function(){
                        $("#iar_table_container").fadeIn();
                        if(find != ''){
                            iar_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            iar_tbl.search(this.value).draw();
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
                                $("#iar_tbl #"+item).addClass('success');
                            })
                        }
                        $("#iar_tbl #"+active).addClass('success');
                    }
                }
            });
        })

        $("body").on('click','.receive_btn',function () {
            let btn = $(this);
            let url = '{{route('dashboard.iar.receiveIar','slug')}}';
            url = url.replace('slug',btn.attr('data'));

            Swal.fire({
                title: 'Receive IAR?',
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
                            iar_tbl.draw(false);
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
                    toast('success','IAR was successfully marked as received.','Success!');

                }
            })
        })

        $("body").on("change",".dt_filter",function () {
            filterDT(iar_tbl);
        });
        $("#resp_center_select2").select2();

    </script>
@endsection