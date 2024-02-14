@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>Create Purchase Request</h1>
</section>
@endsection
@section('content2')
    @php
        $employees = \App\Models\Employee::query()
        ->where(function ($query) {
            $query->where('locations', '=', 'VISAYAS')
                ->orWhere('locations', '=', 'LUZON/MINDANAO')
                ->orWhere('as_signatory','=',1);
        })
        ->where('is_active', '=', 'ACTIVE')
        ->orderBy('fullname', 'asc')
        ->get();

       $employeesCollection = $employees->map(function ($data){
            return [
                'id' => $data->employee_no,
                'text' => $data->firstname.' '.$data->lastname.' '.($data->name_ext != '' ? $data->name_ext.'.':'').' - '.$data->employee_no,
                'employee_no' => $data->employee_no,
                'fullname' => $data->firstname.' '.$data->lastname.' '.$data->name_ext,
                'position' => $data->position,
            ];
        })->toJson();
    @endphp
<section class="content">

    <div class="box box-solid">
        <form id="add_pr_form">
            <div class="box-header with-border">
                <h3 class="box-title">Create PR</h3>
                <button class="btn btn-primary btn-sm pull-right" type="button" id="btnSave">
                    <i class="fa fa-check"></i> Save
                </button>
            </div>
            <div class="box-body">
                    <div class="row">
                        {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                            'cols' => 6,
                            'label' => 'Department/Division/Section:',
                            'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::select('pap_code',[
                            'cols' => 6,
                            'label' => 'PAP Code:',
                            'options' => [],
                            'class' => 'select2_papCode',
                        ]) !!}
                    </div>
                    <div class="row">
                        {!! \App\Swep\ViewHelpers\__form2::select('document_type',[
                                    'label' => 'Document Type:',
                                    'cols' => 3,
                                    'options' => \App\Swep\Helpers\Arrays::documentType(),
                                    'id' => 'document-type',
                                ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::select('account_code',[
                                    'label' => 'Account Code:',
                                    'cols' => 3,
                                    'options' => \App\Swep\Helpers\Arrays::inventoryAccountCode(),
                                    'id' => 'inventory-account-code',
                                ]) !!}
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
                                {{--{!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                                  'cols' => 12,
                                  'label' => 'Requested by: ',
                                  'rows' => 4
                                ]) !!}--}}
                                {!! \App\Swep\ViewHelpers\__form2::select('requested_by',[
                                    'label' => 'Requested By:',
                                    'cols' => 12,
                                    'rows' => 4,
                                    'options' => [],
                                    'id' => 'requested_by',
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
    var data = {!!$employeesCollection!!};

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

    $("#btnSave").click(function (e) {
        e.preventDefault();
        let form = $("#add_pr_form");
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
                toast('success','Purchase request successfully created','Success');
            },
            error: function (res) {
                errored(form,res);
            }
        })
    });

    $("#requested_by").select2({
        data : data,
    });

    $("#requested_by").change(function (){
        let value = $(this).val();
        if(value != ''){
            let index = data.findIndex( object => {
                return object.id == value;
            });
            $("input[name='requested_by_designation']").val(data[index].position);
        }else{
            $("input[name='requested_by_designation']").val('');
        }
    });
</script>
@endsection