@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Manage Job Requests</h1>
    </section>
@endsection
@section('content2')
    <section class="content">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Job Requests</h3>
            </div>

            <div class="box-body">
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
                                        'cols' => '2 dt_filter-parent-div',
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
                                    <div class="col-md-2 ">
                                        <label>Requisitioner:</label>
                                        <select name="requested_by"  class="form-control dt_filter select2_requested_by">
                                            <option value="" selected>Don't filter</option>
                                            @php
                                                $requisitioners = \App\Models\Transactions::query()->select('requested_by')->where('ref_book','=','JR')->groupBy('requested_by')->orderBy('requested_by')->pluck('requested_by');
                                            @endphp
                                            {!! \App\Swep\Helpers\Helper::populateOptionsFromArray($requisitioners,null,true) !!}
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <hr style="margin: 3px">
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

                <div class="table-responsive" id="jr_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="jr_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th >Dept/Div/RC</th>
                            <th >Div/Sec</th>
                            <th>PAP Code</th>
                            <th>JR No.</th>
                            <th>Date</th>
                            <th >Items</th>
                            <th >Total</th>
                            <th >Requested By</th>
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


    </section>


@endsection


@section('modals')
<div class="modal fade" id="add_jr_modal" tabindex="-1" role="dialog" aria-labelledby="add_jr_modal_label">
  <div class="modal-dialog" style="width: 80%" role="document">
    <div class="modal-content">
      <form id="add_jr_form">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Create Job Request</h4>
          </div>
          <div class="modal-body">
              <div class="row">
                  {!! \App\Swep\ViewHelpers\__form2::select('respCenter',[
                      'cols' => 5,
                      'label' => 'Department/Division/Section:',
                      'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
                  ]) !!}

                  {!! \App\Swep\ViewHelpers\__form2::select('papCode',[
                      'cols' => 5,
                      'label' => 'PAP Code:',
                      'options' => [],
                      'class' => 'select2_papCode',
                  ]) !!}
                  {!! \App\Swep\ViewHelpers\__form2::textbox('jrDate',[
                      'cols' => 2,
                      'label' => 'JR Date.:',
                      'type' => 'date',
                  ]) !!}
              </div>
              <div class="row">
                  <div class="col-md-12">
                      <button data-target="#pr_items_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=jr_items" style="margin-bottom: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                      <table id="pr_items_table" class="table-bordered table table-condensed table-striped">
                          <thead>
                          <tr>
                              <th style="width: 8%">Property No.</th>
                              <th style="width: 8%">Unit</th>
                              <th style="width: 25%">Item</th>
                              <th>Description</th>
                              <th style="width: 8%">Qty</th>
                              <th style="width: 18%">Nature of Work</th>
                              <th style="width: 50px"></th>
                          </tr>
                          </thead>
                          <tbody>
                          @include('dynamic_rows.jr_items')
                          </tbody>

                      </table>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-4">
                      <div class="row">
                          {!! \App\Swep\ViewHelpers\__form2::textarea('purpose',[
                            'cols' => 12,
                            'label' => 'Purpose: ',
                            'rows' => 4
                          ]) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="row">
                          {!! \App\Swep\ViewHelpers\__form2::textbox('abc',[
                            'cols' => 12,
                            'label' => 'ABC: ',
                            'class' => 'text-right autonum',
                          ]) !!}

                          {!! \App\Swep\ViewHelpers\__form2::textbox('certifiedBy',[
                            'cols' => 12,
                            'label' => 'Certified by: ',
                            'rows' => 4
                          ]) !!}
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="row">
                          {!! \App\Swep\ViewHelpers\__form2::textbox('requestedBy',[
                            'cols' => 12,
                            'label' => 'Requested by: ',
                            'rows' => 4
                          ]) !!}
                      </div>
                      <div class="row">
                          {!! \App\Swep\ViewHelpers\__form2::textbox('requestedByDesignation',[
                            'cols' => 12,
                            'label' => 'Requested by (Designation): ',
                            'rows' => 4
                          ]) !!}
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="row">
                          {!! \App\Swep\ViewHelpers\__form2::textbox('approvedBy',[
                            'cols' => 12,
                            'label' => 'Approved by: ',
                            'rows' => 4
                          ]) !!}
                      </div>
                      <div class="row">
                          {!! \App\Swep\ViewHelpers\__form2::textbox('approvedByDesignation',[
                            'cols' => 12,
                            'label' => 'Approved by (Designation): ',
                            'rows' => 4
                          ]) !!}
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
          </div>
      </form>
    </div>
  </div>
</div>

{!! \App\Swep\ViewHelpers\__html::blank_modal('edit_jr_modal',80) !!}
@endsection

@section('scripts')
    <script type="text/javascript">
        modal_loader = $("#modal_loader").parent('div').html();
        //Initialize DataTable
        var active = '';
        jr_tbl = $("#jr_table").DataTable({
            "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}?year='+$("#filter_form select[name='year']").val(),
            "columns": [
                { "data": "dept" },
                { "data": "divSec" },
                { "data": "pap_code" },
                { "data": "ref_no" },
                { "data": "date" },
                { "data": "items" },
                { "data": "abc" },
                { "data": "requested_by" },
                { "data": "action" }
            ],
            "buttons": [
                {!! __js::dt_buttons() !!}
            ],
            "columnDefs":[
                {
                    "targets" : 0,
                    "class" : 'w-12p'
                },
                {
                    "targets" : 1,
                    "class" : 'w-12p',
                    'visible' : false,
                },
                {
                    "targets" : [2,3,4],
                    "class" : 'w-10p'
                },
                {
                    "targets" : 6,
                    "class" : 'w-10p text-right'
                },
                {
                    "targets": 7,
                    "class": 'w-10p',
                },
                {
                    "targets" : 8,
                    "orderable" : false,
                    "class" : 'action4'
                },
            ],
            "order": [[4,'desc']],
            "responsive": false,
            'dom' : 'lBfrtip',
            "processing": true,
            "serverSide": true,
            "initComplete": function( settings, json ) {
                style_datatable("#"+settings.sTableId);
                $('#tbl_loader').fadeOut(function(){
                    $("#"+settings.sTableId+"_container").fadeIn();
                    if (find != '') {
                        jr_tbl.search(find).draw();
                        window.history.pushState({}, document.title, "/dashboard/jr");
                    }
                });
                //Need to press enter to search
                $('#'+settings.sTableId+'_filter input').unbind();
                $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                    if (e.keyCode == 13) {
                        jr_tbl.search(this.value).draw();
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
        })


        $(".select2_papCode").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","pap_codes")}}',
                dataType: 'json',
                delay : 250,
            },
            dropdownParent: $('#add_jr_modal'),
            placeholder: 'Type PAP Code/Title/Description',
        });
        
        $("#add_jr_form").submit(function (e) {
            e.preventDefault()
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.jr.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    remove_loading_btn(form);
                    active = res.slug;
                    jr_tbl.draw(false);
                    succeed(form,true,true);
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })

        /*$("body").on("click",".edit_jr_btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.jr.edit","slug")}}';
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
        })*/

        $("body").on('click','.receive_btn',function () {
            let btn = $(this);

            Swal.fire({
                title: 'Receive JR?',
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
                        url : '{{route('dashboard.jr.index')}}?receive_pr=1',
                        type: 'GET',
                        data: {'trans':btn.attr('data')},
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success : function (res) {
                            active = res.slug;
                            jr_tbl.draw();
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
                    toast('success','PR was successfully marked as received.','Success!');

                }
            })
        })

        $("body").on("change",".dt_filter",function () {
            let form = $(this).parents('form');
            $("#search_by_item_form").get(0).reset();
            filterDT(jr_tbl);
        })


        $("#search_by_item_form").submit(function (e) {
            e.preventDefault();
            let data = $("#search_by_item_form").serialize();
            jr_tbl.ajax.url("{{Request::url()}}?"+$("#filter_form").serialize()+"&"+data).load();
        })

        $("body").on('click','.cancel_transaction_btn',function () {
            let btn = $(this);
            let uri  = '{{route('dashboard.jr.cancel','slug')}}';
            uri = uri.replace('slug',btn.attr('data'));
            Swal.fire({
                title: 'Cancel Transation?',
                input: 'text',
                html: 'Please enter a cancellation reason:',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                confirmButtonColor: '#dd4b39',
                showCancelButton: true,
                cancelButtonText : 'Do not cancel',
                confirmButtonText: '<i class="fa fa-check"></i> Cancel',
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
                            active = res.slug;
                            jr_tbl.draw();
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
                    toast('success','JR was successfully marked as cancelled.','Success!');
                }
            })
        })

        $(".select2_requested_by").select2();
        $("#resp_center_select2").select2();

        $("body").on('click','.unlock_transaction_btn',function () {
            let btn = $(this);
            let uri  = '{{route('dashboard.jr.unlock','slug')}}';
            uri = uri.replace('slug',btn.attr('data'));
            Swal.fire({
                title: 'Unlock Transaction?',
                confirmButtonColor: '#0eb6ed',
                showCancelButton: true,
                cancelButtonText : 'No',
                confirmButtonText: '<i class="fa fa-check"></i> Yes, Submit',
                showLoaderOnConfirm: true,
                preConfirm: (text) => {
                    return $.ajax({
                        url : uri,
                        type: 'POST',
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success : function (res) {
                            active = res.slug;
                            jr_tbl.draw();
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
                    toast('success','JR was successfully unlocked.','Success!');

                }
            })
        });

        $("body").on("click",".edit_jr_btn",function () {
            let btn = $(this);
            let uri = '{{route("dashboard.jr.edit_thru_admin","slug")}}';
            load_modal2(btn);
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
                    alert('No Access');
                    $('#edit_jr_modal').modal('hide');
                    populate_modal2_error(res);
                }
            })
        })
    </script>
@endsection