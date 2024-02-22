@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Prepare AQ Manual</h1>
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
            <form id="aq_form">
                <div class="box-header with-border">
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-primary btn-sm" id="submitForm_btn"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>

                <div class="box-body">
                    {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                               'label' => 'Requisitioning Office/Department',
                               'cols' => 4,
                               'id' => 'resp_center',
                               'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
                        ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::select('requested_by',[
                            'label' => 'Requested By:',
                            'cols' => 4,
                            'options' => [],
                            'id' => 'requested_by'
                        ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                          'cols' => 4,
                          'label' => 'Requested by (Designation): '
                        ]) !!}

                    <button data-target="#aq_items_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=aq_items" style="margin-bottom: 5px; margin-top: 5px; margin-right: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                    <table class="table-bordered table-striped table-condensed" style="width: 100%; overflow-y: auto" id="aq_items_table">
                        <thead id="items_head">
                            <tr>
                                <th style="width: 5%">Stock #</th>
                                <th style="width: 10%">Unit</th>
                                <th style="width: 18%">Item</th>
                                <th style="width: 18%">Description</th>
                                <th style="width: 8%">Qty</th>
                                <th style="width: 8%">Unit Cost</th>
                                <th style="width: 8%">Total Cost</th>
                                <th style="width: 14%">Scope of work</th>
                                <th style="width: 3%"></th>
                            </tr>
                        </thead>
                        <tbody id="items_body">
                            @include('dynamic_rows.aq_items')
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6">
                                </th>
                                <th class="grandTotal text-right zero">0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
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

        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

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
            $("#"+parentTrId+" [for='uom']").val(data.populate.uom);
            $("#"+parentTrId+" [for='itemName']").val(data.text);
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
    </script>
@endsection