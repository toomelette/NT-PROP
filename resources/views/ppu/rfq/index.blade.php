@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>Requests for Quotation</h1>
</section>
@endsection
@section('content2')

<section class="content">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">PRs & JRs pending of RFQ</a></li>
            <li><a href="#tab_2" data-toggle="tab">All RFQs</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div id="rfq_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="rfq_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th >Ref Book</th>
                            <th >Ref No.</th>
                            <th>Date</th>
                            <th>PAP</th>
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
                <div id="all_rfq_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="all_rfq_table" style="width: 100% !important">
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
    <div class="modal fade" id="add_rfq_modal" tabindex="-1" role="dialog" aria-labelledby="add_rfq_modal_label">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="add_rfq_form">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Prepare QRF</h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="radio">
                                      <label>
                                          <input type="radio" name="prJr" class="radioPrJr" target="prEntry" value="pr">
                                          Purchase Request (PR)
                                      </label>
                                  </div>
                              </div>
                              {!! \App\Swep\ViewHelpers\__form2::textbox('prNo',[
                                  'label' => 'Enter PR No.:',
                                  'cols' => 12,
                                  'id' => 'pr_entry',
                                  'for' => 'prEntry',
                                  'class' => 'numberEntry',
                              ]) !!}
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="radio">
                                      <label>
                                          <input type="radio" name="prJr" class="radioPrJr" target="jrEntry" value="jr">
                                          Job Request (JR)
                                      </label>
                                  </div>
                              </div>
                              {!! \App\Swep\ViewHelpers\__form2::textbox('jrNo',[
                                  'label' => 'Enter JR No.:',
                                  'cols' => 12,
                                  'id' => 'pr_entry',
                                  'for' => 'jrEntry',
                                  'class' => 'numberEntry',
                              ]) !!}
                          </div>
                      </div>
                  </div>
                  <hr>
                  <div class="row">
                      <input name="transaction_slug" value="">
                      {!! \App\Swep\ViewHelpers\__form2::textbox('deadline',[
                          'type' => 'date',
                          'label' => 'Set deadline: ',
                          'cols' => 6,
                      ]) !!}
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    {!! \App\Swep\ViewHelpers\__html::blank_modal('prepare_rfq_modal','70') !!}
    {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_rfq_modal','70') !!}
@endsection

@section('scripts')
<script type="text/javascript">
    var active = '';
    var all_rqf_tbl_active = '';
    $(document).ready(function () {
        //-----DATATABLES-----//
        modal_loader = $("#modal_loader").parent('div').html();
        //Initialize DataTable

        rqf_tbl = $("#rfq_table").DataTable({
            "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}',
            "columns": [
                { "data": "ref_book" },
                { "data": "ref_no" },
                { "data": "date" },
                { "data": "pap_code" },
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
                        rqf_tbl.search(find).draw();
                    }
                });
                //Need to press enter to search
                $('#'+settings.sTableId+'_filter input').unbind();
                $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                    if (e.keyCode == 13) {
                        rqf_tbl.search(this.value).draw();
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

        all_rqf_tbl = $("#all_rfq_table").DataTable({
            "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}?all_rqf=true',
            "columns": [
                { "data": "ref_no" },
                { "data": "transRefBook" },
                { "data": "transRefNo" },
                { "data": "dates" },
                { "data": "transDetails" },
                { "data": "rfq_deadline" },
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
                        rqf_tbl.search(find).draw();
                    }
                });
                //Need to press enter to search
                $('#'+settings.sTableId+'_filter input').unbind();
                $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                    if (e.keyCode == 13) {
                        all_rqf_tbl.search(this.value).draw();
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
                if(all_rqf_tbl_active != ''){
                    $("#"+settings.sTableId+" #"+all_rqf_tbl_active).addClass('success');
                }
            }
        });
    })

    $(".numberEntry").change(function () {
        let input = $(this);
        let form = $("#add_rfq_form");
        $.ajax({
            url : '{{route("dashboard.ajax.post","rfq_prNo")}}',
            data : form.serialize(),
            type: 'POST',
            headers: {
                {!! __html::token_header() !!}
            },
            success: function (res) {
                succeed(form,false,false);
                console.log(res);
            },
            error: function (res) {
                console.log(res);
                errored(form,res);
            }
        })
    });

    $("body").on("click",'.prepare_rfq_btn',function () {
        let btn = $(this);
        load_modal2(btn);
        $.ajax({
            url : '{{route("dashboard.rfq.create")}}?trans='+btn.attr('data'),
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
    })

    $("#add_rfq_form").submit(function (e) {
        e.preventDefault()
        let form = $(this);
        loading_btn(form);
        $.ajax({
            url : '{{route("dashboard.rfq.store")}}',
            data : form.serialize(),
            type: 'POST',
            headers: {
                {!! __html::token_header() !!}
            },
            success: function (res) {
                all_rqf_tbl_active = res.slug;
                all_rqf_tbl.draw(false);
                rfq_tbl.draw(false);
                succeed(form,true,true);
            },
            error: function (res) {
                errored(form,res);
            }
        })
    })

    $("document").ready(function () {
        $(".numberEntry").each(function () {
            $(this).attr('disabled','disabled');
        })
    })

    $("body").on("change",".radioPrJr",function () {
        let t = $(this);
        let target = t.attr('target');
        $(".numberEntry").each(function () {
            $(this).attr('disabled','disabled');
        })
        $("input[for='"+target+"']").removeAttr('disabled');
    })
    $("body").on("click",".edit_rfq_btn",function () {
        let btn = $(this);
        load_modal2(btn);
        let uri = '{{route("dashboard.rfq.edit","slug")}}';
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
    })
</script>
@endsection