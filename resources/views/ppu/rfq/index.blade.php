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
            <li class="active"><a href="#tab_1" data-toggle="tab">Prepare RFQ</a></li>
            {{--<li class="active"><a href="#tab_1" data-toggle="tab">PRs & JRs pending of RFQ</a></li>--}}
            <li><a href="#tab_2" data-toggle="tab">All RFQs</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <form id="add_rfq_form">
                    <div class="row">
                        <input class="hidden" type="text" id="slug" name="slug"/>
                        <input class="hidden" type="text" id="itemSlug" name="itemSlug"/>
                        {!! \App\Swep\ViewHelpers\__form2::select('ref_book', [
                                            'label' => 'Reference Type:',
                                            'cols' => 2,
                                            'options' => [
                                                'PR' => 'PR',
                                                'JR' => 'JR'
                                            ]
                                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                                                'label' => 'Reference Number:',
                                                'cols' => 3,
                                            ]) !!}

                        <div class="row hidden" id="divRows">
                            <div class="col-md-12">
                                {!! \App\Swep\ViewHelpers\__form2::select('mode', [
                                            'label' => 'Mode of Procurement:',
                                            'cols' => 3,
                                            'options' => \App\Swep\Helpers\Arrays::ModeOfProcurement(),
                                        ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_deadline',[
                                    'label' => 'Deadline:',
                                    'cols' => 3,
                                    'type' => 'date',
                                ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_s_name',[
                                    'label' => 'Signatory Name:',
                                    'cols' => 3,
                                ],
                                \App\Swep\Helpers\Helper::getSetting('rfq_name')->string_value ?? null
                                ) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_s_position',[
                                    'label' => 'Signatory Position:',
                                    'cols' => 3,
                                ],
                                \App\Swep\Helpers\Helper::getSetting('rfq_position')->string_value ?? null
                                ) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by',[
                                                'label' => 'Canvass By:',
                                                'cols' => 3,
                                            ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by_designation',[
                                                    'label' => 'Canvass By Designation:',
                                                    'cols' => 3,
                                                ]) !!}

                                <div class="col-md-12">
                                    <div class="table-responsive" id="tableContainer" style="margin-top: 50px">
                                        <table class="table table-bordered table-striped table-hover hidden" id="trans_table" style="width: 100% !important">
                                            <thead>
                                            <tr class="">
                                                <th>Stock No.</th>
                                                <th>Unit</th>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th>Unit Cost</th>
                                                <th>Total Cost</th>
                                                <th width="3%"></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-primary hidden" id="saveBtn">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {{--<div id="rfq_table_container" style="display: none">
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
                </div>--}}
            </div>

            <div class="tab-pane" id="tab_2">
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
                                        'for' => 'select2_year',
                                    ],\Illuminate\Support\Carbon::now()->format('Y')) !!}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="all_rfq_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="all_rfq_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th >RFQ No.</th>
                            <th >Ref Book.</th>
                            <th>PR/JR #</th>
                            <th>PR/JR Date <i class="fa fa-arrow-right"></i> RFQ Date</th>
                            <th >Items</th>
                            <th >RFQ Deadline</th>
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
    function deleteRow(button) {
        const row = button.closest('tr');
        if (row) {
            row.remove();
            updateSlugs(row.id);
        }
    }

    function updateSlugs(slug) {
        const slugsInput = document.getElementById('itemSlug');
        let slugs = slugsInput.value.split('~');
        const index = slugs.indexOf(slug);

        if (index !== -1) {
            slugs.splice(index, 1);
            slugsInput.value = slugs.join('~');
        }
    }

    var active = '';
    var all_rqf_tbl_active = '';
    $('input[name="ref_number"]').unbind().bind('keyup', function(e) {
        if($('input[name="ref_number"]').val() === ''){
            toast('error','Reference Number cannot be empty','Invalid!');
        }
        else {
            let refBook = $('select[name="ref_book"]').val();
            if (e.keyCode === 13) {
                let uri = '{{route("dashboard.rfq.findTransByRefNumber", ["refNumber", "refBook", "add", "id"]) }}';
                uri = uri.replace('refNumber',$(this).val());
                uri = uri.replace('refBook',refBook);
                $.ajax({
                    url : uri,
                    type: 'GET',
                    headers: {
                        {!! __html::token_header() !!}
                    },
                    success: function (res) {
                        $('#saveBtn').removeClass('hidden');
                        $('#divRows').removeClass('hidden');
                        $('#trans_table tbody').remove();
                        $('#slug').val(res.trans.slug);
                        let slugs = '';
                        let tableHtml = '<tbody>';
                        for(let i=0; i<res.transDetails.length; i++){
                            let num1 = parseFloat(res.transDetails[i].unit_cost);
                            let num2 = parseFloat(res.transDetails[i].total_cost);
                            num1 = isNaN(num1) ? 0 : num1;
                            num2 = isNaN(num2) ? 0 : num2;
                            let stock = res.transDetails[i].stock_no;
                            stock = stock === null ? '' : stock;
                            slugs += res.transDetails[i].slug + '~';
                            tableHtml += '<tr id='+res.transDetails[i].slug+'><td>' + stock + '</td><td>' + res.transDetails[i].unit + '</td><td>' + res.transDetails[i].item + '</td><td>' + res.transDetails[i].qty + '</td><td>' + num1.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td>' + num2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td><button type=\'button\' class=\'btn btn-danger btn-sm delete-btn\' data-slug='+res.transDetails[i].slug+' onclick="deleteRow(this)"><i class=\'fa fa-times\'></i></button></td></tr>';
                        }
                        tableHtml += '</tbody></table>';
                        slugs = slugs.slice(0, -1); // Remove the last '~' character
                        $('#itemSlug').val(slugs);

                        $('#trans_table').append(tableHtml).removeClass('hidden');
                        console.log(res);
                    },
                    error: function (res) {
                        toast('error',res.responseJSON.message,'Error!');
                        $('#divRows').addClass('hidden');
                        $('#saveBtn').addClass('hidden');
                        $('#trans_table tbody').remove();
                        $('#trans_table').addClass('hidden');
                        console.log(res);
                    }
                })
            }
        }
    });

    $('#saveBtn').click(function(e) {
        //let refBook = $('select[name="ref_book"]').val();
        if($('input[name="ref_number"]').val() === ''){
            toast('error','Reference Number cannot be empty','Invalid!');
        }
        else {
            e.preventDefault();
            let form = $('#add_rfq_form');
            loading_btn(form);
            $.ajax({
                type: 'POST',
                url: '{{route("dashboard.rfq.store")}}',
                data: form.serialize(),
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function(res) {
                    console.log(res);
                    $('#printIframe').attr('src',res.route);
                    $('#saveBtn').addClass('hidden');
                    $('#trans_table tbody').remove();
                    $('#trans_table').addClass('hidden');
                    $('#divRows').addClass('hidden');
                    $('#ref_book').val('');
                    $('#ref_number').val('');
                    $('#slug').val('');
                    $('#itemSlug').val('');
                    toast('success','RFQ successfully created.','Success!');
                    Swal.fire({
                        title: 'RFQ Successfully Created',
                        icon: 'success',
                        html:
                            'Click the print button below to print RFQ.<br>You may also view RFQs on RFQ Tab of this page or by navigating to PRs and JRs.',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText:
                            '<i class="fa fa-print"></i> Print',
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                        cancelButtonText:
                            'Dismiss',
                        cancelButtonAriaLabel: 'Thumbs down'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let link = "{{route('dashboard.rfq.print','slug')}}";
                            link = link.replace('slug',res.slug);
                            window.open(link, '_blank');
                        }
                    })
                },
                error: function(res) {
                    // Display an alert with the error message
                    toast('error',res.responseJSON.message,'Error!');
                }
            });
        }
    });

    $(document).ready(function () {
        /*$("#add_rfq_form").submit(function (e) {
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
        })*/

        //-----DATATABLES-----//
        modal_loader = $("#modal_loader").parent('div').html();
        //Initialize DataTable

        /*rqf_tbl = $("#rfq_table").DataTable({
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
        });*/

        all_rqf_tbl = $("#all_rfq_table").DataTable({
            "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}?all_rqf=true',
            //"ajax": '{{ \Illuminate\Support\Facades\Request::url() }}?all_rqf=true&year=' + $("#filter_form select[name='year']").val(),
            "columns": [
                { "data": "ref_no" },
                { "data": "transRefBook" },
                { "data": "cross_ref_no" },
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
                        all_rqf_tbl.search(find).draw();
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

        /*$("body").on("change",".dt_filter",function () {
            filterDT(all_rqf_tbl);
        })*/
    });



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