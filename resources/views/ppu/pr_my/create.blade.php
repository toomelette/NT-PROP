@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>Create Purchase Request</h1>
</section>
@endsection
@section('content2')

<section class="content">

    <div class="box box-solid">
        <form id="add_pr_form">
            <div class="box-header with-border">
                <h3 class="box-title">Create PR</h3>
                <button class="btn btn-primary btn-sm pull-right"  type="submit">
                    <i class="fa fa-check"></i> Save
                </button>
            </div>
            <div class="box-body">


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
        </form>
    </div>

</section>


@endsection


@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
    $(".select2_item").select2({
        ajax: {
            url: '{{route("dashboard.ajax.get","articles")}}',
            dataType: 'json',
            delay : 250,
        },
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
        placeholder: 'Type PAP Code/Title/Description',
    });

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
                $("#pr_items_table .zero").each(function () {
                    $(this).html('0.00');
                })
                $(".select2_papCode").select2("val", "");
                $(".select2_papCode").trigger('change');
                $(".remove_row_btn").each(function () {
                    $(this).click();
                })
                $(".add_button").click();
                toast('success','Purchase request succesfully created','Success');
            },
            error: function (res) {
                errored(form,res);
            }
        })
    })
</script>
@endsection