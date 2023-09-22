@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>My Job Requests</h1>
    </section>
@endsection
@section('content2')
    <section class="content">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"> My Job Requests</h3>
                <a class="btn btn-primary btn-sm pull-right" href="{{route('dashboard.my_jr.create')}}" > <i class="fa fa-plus"></i> Create</a>

                <h5 class="pull-right text-strong" style="margin-top: 40px; margin-bottom: -5px">Search by Date Format (yyyy-MM-dd). Ex. 2023-12-31</h5>
            </div>

            <div class="box-body">
                <div class="table-responsive" id="jr_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="jr_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th >Dept/Div/RC</th>
                            <th >Div/Sec</th>
                            <th>PAP Code</th>
                            <th>JR No.</th>
                            <th>JR Date</th>
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

        </div>


    </section>


@endsection


@section('modals')
<div class="modal fade" id="add_jr_modal" role="dialog" aria-labelledby="add_jr_modal_label">
  <div class="modal-dialog" style="width: 80%" role="document">
    <div class="modal-content">
      <form id="add_jr_form">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Create Job Request</h4>
          </div>
          <div class="modal-body">
              <div class="row">
                  {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                      'cols' => 5,
                      'label' => 'Department/Division/Section:',
                      'class' => 'resp_center_selector',
                      'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
                      'for' => 'select2_papCode',
                  ]) !!}

                  {!! \App\Swep\ViewHelpers\__form2::select('pap_code',[
                      'cols' => 7,
                      'label' => 'PAP Code:',
                      'options' => [],
                      'class' => 'select2_papCode',
                  ]) !!}

              </div>
              <div class="row">
                  {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                      'cols' => 2,
                      'label' => 'JR Date:',
                      'type' => 'date',
                  ]) !!}
              </div>
              <div class="row">
                  <div class="col-md-12">
                      <button data-target="#pr_items_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=jr_items" style="margin-bottom: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                      <table id="jr_items_table" class="table-bordered table table-condensed table-striped">
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

                          {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by',[
                            'cols' => 12,
                            'label' => 'Certified by: ',
                            'rows' => 4
                          ]) !!}
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="row">
                          {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                            'cols' => 12,
                            'label' => 'Requested by: ',
                            'rows' => 4
                          ]) !!}
                      </div>
                      <div class="row">
                          {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                            'cols' => 12,
                            'label' => 'Requested by (Designation): ',
                            'rows' => 4
                          ]) !!}
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="row">
                          {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                            'cols' => 12,
                            'label' => 'Approved by: ',
                            'rows' => 4
                          ]) !!}
                      </div>
                      <div class="row">
                          {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
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
            "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}',
            "columns": [
                { "data": "dept" },
                { "data": "div_sec" },
                { "data": "pap_code" },
                { "data": "ref_no" },
                { "data": "date" },
                { "data": "items" },
                { "data": "abc" },
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
                    "visible" : false,
                },
                {
                    "targets" : 2,
                    "class" : 'w-12p'
                },
                {
                    "targets" : [3,4],
                    "class" : 'w-12p'
                },
                {
                    "targets" : 6,
                    "class" : 'w-8p text-right'
                },
                {
                    "targets" : 7,
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
                    if(find != ''){
                        jr_tbl.search(find).draw();
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

        $("body").on("change",".dt_filter",function () {
            let form = $(this).parents('form');
            filterDT(jr_tbl);
        })

        $("input[name='date_after']").change(function () {
            let t = $(this);
            let before =  $("input[name='date_before']");
            if(t.val() != ''){
                before.attr('min',t.val());
            }else{
                before.removeAttr('min');
            }
        })


        $(".select2_papCode").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","pap_codes")}}',
                dataType: 'json',
                delay : 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            },
            dropdownParent: $('#add_jr_modal'),
            placeholder: 'Type PAP Code/Title/Description',
        });

        setTimeout(function () {
            // $(".select2_papCode").select2('enable',false);
        },500);

        $(".resp_center_selector").change(function () {
            // alert();
        })

        $("#add_jr_form").submit(function (e) {
            e.preventDefault()
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.my_jr.store")}}',
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

        $("body").on("click",".edit_jr_btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.my_jr.edit","slug")}}';
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
        });
    </script>
@endsection