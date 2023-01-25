@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>My Purchase Requests</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">My Purchase Requests</h3>
                <button class="btn btn-primary btn-sm pull-right" data-target="#add_pr_modal" data-toggle="modal"> <i class="fa fa-plus"></i> Create</button>
            </div>

            <div class="box-body">
                <div id="pr_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="pr_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th >Dept/Division</th>
                            <th >Div/Sec</th>
                            <th>PAP Code</th>
                            <th>PR No.</th>
                            <th>PR Date</th>
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
    <div class="modal fade" id="add_pr_modal" role="dialog" aria-labelledby="add_pr_modal_label">
        <div class="modal-dialog" style="width: 80%" role="document">
            <div class="modal-content">
                <form id="add_pr_form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Create Purchase Request</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                                'cols' => 5,
                                'label' => 'Department/Division/Section:',
                                'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
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
                                  'label' => 'PR Date:',
                                  'type' => 'date',
                              ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('sai',[
                                'cols' => 2,
                                'label' => 'SAI No.:',
                            ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('sai_date',[
                              'cols' => 2,
                              'label' => 'SAI Date.:',
                              'type' => 'date',
                            ]) !!}
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="min-height: 500px">
                                <button data-target="#pr_items_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=pr_items" style="margin-bottom: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                                <table id="pr_items_table" class="table-bordered table table-condensed table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 8%">Stock No.</th>
                                        <th style="width: 8%">Unit</th>
                                        <th style="width: 25%">Item</th>
                                        <th>Description</th>
                                        <th style="width: 8%">Qty</th>
                                        <th style="width: 8%">Unit Cost</th>
                                        <th style="width: 8%">Total Cost</th>
                                        <th style="width: 50px"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @include('dynamic_rows.pr_items')
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="6">
                                        </th>
                                        <th class="grandTotal text-right zero">0.00</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    {!! \App\Swep\ViewHelpers\__form2::textarea('purpose',[
                                      'cols' => 12,
                                      'label' => 'Purpose: ',
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_pr_modal','80',null,true) !!}
@endsection

@section('scripts')
    <script type="text/javascript">
        var activePr = '';
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            pr_tbl = $("#pr_table").DataTable({
                "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}',
                "columns": [
                    { "data": "dept" },
                    { "data": "div_sec" },
                    { "data": "pap_code" },
                    { "data": "ref_no" },
                    { "data": "date" },
                    { "data": "transDetails" },
                    { "data": "total" },
                    { "data": "action" }
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],
                "columnDefs":[
                    {
                        "targets" : 0,
                        "class" : 'w-10p'
                    },
                    {
                        "targets" : 1,
                        "class" : 'w-12p'
                    },
                    {
                        "targets" : [2,3,4],
                        "class" : 'w-8p'
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
                            pr_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            pr_tbl.search(this.value).draw();
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
                    if(activePr != ''){
                        $("#"+settings.sTableId+" #"+activePr).addClass('success');
                    }
                }
            });
        })






        $("#add_pr_form").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.my_pr.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,false);
                    activePr = res.slug;
                    pr_tbl.draw(false);
                    $("#pr_items_table .zero").each(function () {
                        $(this).html('0.00');
                    })
                    $(".select2_papCode").select2("val", "");
                    toast('success','Purchase request succesfully created','Success');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })

        $("body").on("click",".edit_pr_btn",function () {
            let btn = $(this);
            let uri = '{{route("dashboard.my_pr.edit","slug")}}';
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
                    populate_modal2_error(res);
                }
            })
        })

        $("body").on("change",".unitXcost",function () {
            let parentTableId = $(this).parents('table').attr('id');
            let trId = $(this).parents('tr').attr('id');
            let qty = parseFloat($("#"+trId+" .qty").val());
            let unit_cost = parseFloat($("#"+trId+" .unit_cost").val().replaceAll(',',''));
            let totalCost = unit_cost*qty;
            let grandTotal = 0;
            $("#"+trId+" .totalCost").html($.number(totalCost,2));

            $("#"+parentTableId+" .totalCost").each(function () {
                grandTotal = grandTotal + parseFloat($(this).html().replaceAll(',',''));
            })
            $("#"+parentTableId+" .grandTotal").html($.number(grandTotal,2))
        })

        $(".select2_item").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","articles")}}',
                dataType: 'json',
                delay : 250,
            },
            dropdownParent: $("#add_pr_modal"),
            placeholder: 'Select item',
        });

        $('.select2_item').on('select2:select', function (e) {
            let t = $(this);
            let parentTrId = t.parents('tr').attr('id');
            let data = e.params.data;

            $("#"+parentTrId+" [for='stockNo']").val(data.id);
            $("#"+parentTrId+" [for='itemName']").val(data.text);
            $("#"+parentTrId+" [for='uom']").val(data.populate.uom);
            $("#"+parentTrId+" [for='unit_cost']").html('Est: '+$.number(data.populate.unit_cost,2));
        });
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
            dropdownParent: $('#add_pr_modal'),
            placeholder: 'Type PAP Code/Title/Description',
        });

    </script>
@endsection