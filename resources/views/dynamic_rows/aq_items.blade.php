@php
    $rand = \Illuminate\Support\Str::random(10);
@endphp
<tr id="item_{{$rand}}" style="width: 100%">
    <td style="width: 5%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][stock_no]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'stockNo',
        ],$item->stock_no ?? null) !!}

    </td>
    <td style="width: 10%">
        {!! \App\Swep\ViewHelpers\__form2::selectOnly('items['.$rand.'][unit]',[
            'class' => 'input-sm',
            'for' => 'uom',
            'options' => \App\Swep\Helpers\Arrays::unitsOfMeasurement(),
            'container_class' => 'items_'.$rand.'_unit',
        ],$item->unit ?? null) !!}

    </td>
    @if(request()->ajax())
        <td style="width: 25%">
            {!! \App\Swep\ViewHelpers\__form2::selectOnly('items['.$rand.'][item]',[
                'class' => 'input-sm select2_item_'.$rand.' items_'.$rand.'_item',
                'options' => [],
                'select2_preSelected' => $item->article->article ?? null,
            ],$item->item ?? null) !!}
            <input name="items[{{$rand}}][itemName]" value="{{$item->item ?? null}}" for="itemName" hidden>
        </td>
    @else
        <td style="width: 25%">
            {!! \App\Swep\ViewHelpers\__form2::selectOnly('items['.$rand.'][item]',[
                'class' => 'input-sm select2_item items_'.$rand.'_item',
                'options' => [],
                'select2_preSelected' => $item->article->article ?? null,
            ],$item->item ?? null) !!}
            <input name="items[{{$rand}}][itemName]" value="" for="itemName" hidden>
        </td>
    @endif
    <td style="width: 25%">
        {!! \App\Swep\ViewHelpers\__form2::textareaOnly('items['.$rand.'][description]',[
            'class' => 'input-sm items_'.$rand.'_description',
            'label' => 'Description:',
        ],$item->description ?? null) !!}
    </td>
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][qty]',[
            'class' => 'input-sm qty unitXcost items_'.$rand.'_qty',
            'type' => 'number',
            'step' => 'any',
        ],$item->qty ?? null) !!}
    </td>

    @if(request()->ajax())
        <td style="width: 8%">
            {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][unit_cost]',[
                'class' => 'input-sm unit_cost text-right unitXcost autonum_'.$rand.' items_'.$rand.'_unit_cost',
            ],$item->unit_cost ?? null) !!}
            <small for="unit_cost" class="pull-right text-muted">{{$item->article->unitPrice ?? null}}</small>
        </td>
    @else
        <td style="width: 8%">
            {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][unit_cost]',[
                'class' => 'input-sm unit_cost unitXcost autonum items_'.$rand.'_unit_cost',
            ],$item->unit_cost ?? null) !!}
            <small for="unit_cost" class="pull-right text-muted">{{$item->article->unitPrice ?? null}}</small>
        </td>
    @endif

    <td class="text-right">
        @if(!empty($item))
            <span class="totalCost zero">{{number_format($item->total_cost,2)}}</span>
        @else
            <span class="totalCost zero" >0.00</span>
        @endif

    </td>
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textareaOnly('items['.$rand.'][nature_of_work]',[
            'class' => 'input-sm',
            'label' => 'Scope:',
        ],$item->nature_of_work ?? null) !!}
    </td>
    <td>
        <button tabindex="-1" data="S01QH" type="button" class="btn btn-danger btn-sm remove_row_btn"><i class="fa fa-times"></i></button>
    </td>
</tr>

<script>
    let DDParent_{{$rand}} = $("#item_{{$rand}}").parents('.modal');
</script>

@if(request()->ajax())
    <script type="text/javascript">
        $(".autonum_{{$rand}}").each(function(){
            new AutoNumeric(this, autonum_settings);
        });


        $(".select2_item_{{$rand}}").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","articles")}}',
                dataType: 'json',
                delay : 250,
            },
            placeholder: 'Select item',
        });
        $('.select2_item_{{$rand}}').on('select2:select', function (e) {
            let t = $(this);
            let parentTrId = t.parents('tr').attr('id');
            let data = e.params.data;

            $("#"+parentTrId+" [for='stockNo']").val(data.id);
            $("#"+parentTrId+" [for='uom']").val(data.populate.uom);
            $("#"+parentTrId+" [for='itemName']").val(data.text);
        });

    </script>
@endif

