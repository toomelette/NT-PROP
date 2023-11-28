@php
    $rand = \Illuminate\Support\Str::random(10);
@endphp
<tr id="item_{{$rand}}">
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][stock_no]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'stockNo',
        ],$item->stock_no ?? null) !!}

    </td>
    <td>
{{--        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][unit]',[--}}
{{--            'class' => 'input-sm',--}}
{{--            'readonly'=>'readonly',--}}
{{--            'for' => 'uom',--}}
{{--        ],$item->unit ?? null) !!}--}}
        {!! \App\Swep\ViewHelpers\__form2::selectOnly('items['.$rand.'][unit]',[
            'class' => 'input-sm',
            'for' => 'uom',
            'options' => \App\Swep\Helpers\Arrays::unitsOfMeasurement(),
            'container_class' => 'items_'.$rand.'_unit',
        ],$item->unit ?? null) !!}

    </td>
    @if(request()->ajax())
        <td>
            {!! \App\Swep\ViewHelpers\__form2::selectOnly('items['.$rand.'][item]',[
                'class' => 'input-sm select2_item_'.$rand.' items_'.$rand.'_item',
                'options' => [],
                'select2_preSelected' => $item->article->article ?? null,
            ],$item->item ?? null) !!}
            <input name="items[{{$rand}}][itemName]" value="{{$item->item ?? null}}" for="itemName" hidden>
        </td>
    @else
        <td>
            {!! \App\Swep\ViewHelpers\__form2::selectOnly('items['.$rand.'][item]',[
                'class' => 'input-sm select2_item items_'.$rand.'_item',
                'options' => [],
                'select2_preSelected' => $item->article->article ?? null,
            ],$item->item ?? null) !!}
            <input name="items[{{$rand}}][itemName]" value="" for="itemName" hidden>
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
            'type' => 'number',
            'step' => 'any',
        ],$item->qty ?? null) !!}
    </td>

    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][actual_qty]',[
        'type' => 'number',
        'step' => 'any',
    ],$item->actual_qty ?? null) !!}
    </td>

        <td>
            {!! \App\Swep\ViewHelpers\__form2::textareaOnly('items['.$rand.'][remarks]',[
             'label' => 'Remarks:',
         ],$item->remarks ?? null) !!}
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

            {{--dropdownParent: $('#'+DDParent_{{$rand}}.attr('id')),--}}
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

