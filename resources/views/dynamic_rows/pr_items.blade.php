@php
    $rand = \Illuminate\Support\Str::random(10);
@endphp
<tr id="item_{{$rand}}">
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][stockNo]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'stockNo',
        ],$item->stockNo ?? null) !!}
    </td>
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][unit]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'uom',
        ],$item->unit ?? null) !!}
    </td>
    @if(request()->ajax())
        <td>
            {!! \App\Swep\ViewHelpers\__form2::selectOnly('items['.$rand.'][item]',[
                'class' => 'input-sm select2_item_'.$rand.' items_'.$rand.'_item',
                'options' => [],
                'select2_preSelected' => $item->article->article ?? null,
            ],$item->item ?? null) !!}
        </td>
    @else
        <td>
            {!! \App\Swep\ViewHelpers\__form2::selectOnly('items['.$rand.'][item]',[
                'class' => 'input-sm select2_item items_'.$rand.'_item',
                'options' => [],
            ],$item->item ?? null) !!}
        </td>
    @endif
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textareaOnly('items['.$rand.'][description]',[
            'class' => 'input-sm items_'.$rand.'_description',
            'label' => 'Description:',
        ],$item->description ?? null) !!}
    </td>
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][qty]',[
            'class' => 'input-sm qty unitXcost items_'.$rand.'_qty',
            'type' => 'number',
        ],$item->qty ?? null) !!}
    </td>
    @if(request()->ajax())
        <td>
            {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][unitCost]',[
                'class' => 'input-sm unitCost text-right unitXcost autonum_'.$rand.' items_'.$rand.'_unitCost',
            ],$item->unitCost ?? null) !!}
            <small for="unitCost" class="pull-right text-muted">{{$item->article->unitPrice ?? null}}</small>
        </td>
    @else
        <td>
            {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][unitCost]',[
                'class' => 'input-sm unitCost unitXcost autonum items_'.$rand.'_unitCost',
            ],$item->unitCost ?? null) !!}
            <small for="unitCost" class="pull-right text-muted">{{$item->article->unitPrice ?? null}}</small>
        </td>
    @endif
    <td class="text-right">
        @if(!empty($item))
            <span class="totalCost zero">{{number_format($item->totalCost,2)}}</span>
        @else
            <span class="totalCost zero" >0.00</span>
        @endif

    </td>
    <td>
        <button data="S01QH" type="button" class="btn btn-danger btn-sm remove_row_btn"><i class="fa fa-times"></i></button>
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

            dropdownParent: $('#'+DDParent_{{$rand}}.attr('id')),


            placeholder: 'Select item',
        });
        $('.select2_item_{{$rand}}').on('select2:select', function (e) {
            let t = $(this);
            let parentTrId = t.parents('tr').attr('id');
            let data = e.params.data;

            $("#"+parentTrId+" [for='stockNo']").val(data.id);
            $("#"+parentTrId+" [for='uom']").val(data.populate.uom);
            $("#"+parentTrId+" [for='unitCost']").html('Est: '+$.number(data.populate.unitCost,2));
        });

    </script>
@endif

