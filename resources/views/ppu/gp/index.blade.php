@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Gate Pass</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Gate Pass</h3>
                <div class="btn-group pull-right">
                    {{--                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#property-tag-by-location"><i class="fa fa-print"></i> Property Tag by Location</button>--}}
                    <a class="btn btn-primary btn-sm" href="{{route('dashboard.gp.create')}}" > <i class="fa fa-plus"></i> Create</a>
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
                        <div class="row">
                            <form id="search_by_item_form">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Search by Item: <small class="text-danger">(This may take some time.)</small></label>
                                        <div class="input-group">
                                            <input name="item" type="text" class="form-control">
                                            <span class="input-group-btn">
                                            <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id="gp_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="gp_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>Gate Pass No.</th>
                                    <th>Bearer</th>
                                    <th>Originated From</th>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Qty</th>
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

            gp_tbl = $("#gp_table").DataTable({
                {{--"ajax" : '{{route("dashboard.gp.index")}}',--}}
                "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}?year='+$("#filter_form select[name='year']").val(),
                "columns": [
                    { "data": "gp_number" },
                    { "data": "bearer" },
                    { "data": "originated_from" },
                    { "data": "item" },
                    { "data": "description" },
                    { "data": "qty" },
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
                        $("#gp_table_container").fadeIn();
                        if(find != ''){
                            gp_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            gp_tbl.search(this.value).draw();
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
                                $("#gp_tbl #"+item).addClass('success');
                            })
                        }
                        $("#gp_tbl #"+active).addClass('success');
                    }
                }
            });
        })

        $("body").on('click','.receive_btn',function () {
            let btn = $(this);
            let url = '{{route('dashboard.gp.receiveGp','slug')}}';
            url = url.replace('slug',btn.attr('data'));

            Swal.fire({
                title: 'Receive Gate Pass?',
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
                            gp_tbl.draw(false);
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
                    toast('success','Gate Pass was successfully marked as received.','Success!');

                }
            })
        })
        $("body").on("change",".dt_filter",function () {
            filterDT(gp_tbl);
        });
        $("#resp_center_select2").select2();

        $("body").on("change",".dt_filter",function () {
            let form = $(this).parents('form');
            $("#search_by_item_form").get(0).reset();
            filterDT(gp_tbl);
        })

        $("#search_by_item_form").submit(function (e) {
            e.preventDefault();
            let data = $("#search_by_item_form").serialize();
            gp_tbl.ajax.url("{{Request::url()}}?"+$("#filter_form").serialize()+"&"+data).load();
        })

    </script>
@endsection