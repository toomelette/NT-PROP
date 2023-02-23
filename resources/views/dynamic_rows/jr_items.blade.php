@php
    $rand = \Illuminate\Support\Str::random(10);
@endphp
<tr id="item_{{$rand}}">
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][property_no]',[
            'class' => 'input-sm',
            'for' => 'property_no',
            'container_class' => 'items_'.$rand.'_property_no',
        ],$item->stockNo ?? null) !!}
    </td>
    <td>
        {!! \App\Swep\ViewHelpers\__form2::selectOnly('items['.$rand.'][unit]',[
            'class' => 'input-sm',
            'for' => 'unit',
            'options' => \App\Swep\Helpers\Arrays::unitsOfMeasurement(),
            'container_class' => 'items_'.$rand.'_unit',
        ],$item->unit ?? null) !!}
    </td>

    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][item]',[
            'class' => 'input-sm',
            'container_class' => 'items_'.$rand.'_item',
        ],$item->item ?? null) !!}
    </td>

    <td>
        {!! \App\Swep\ViewHelpers\__form2::textareaOnly('items['.$rand.'][description]',[
            'class' => 'input-sm',
            'label' => 'Description:',
            'container_class' => 'items_'.$rand.'_description',
        ],$item->description ?? null) !!}
    </td>
    {{--<td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][unit_cost]',[
            'class' => 'input-sm qty autonum',
            'container_class' => 'items_'.$rand.'_unit_cost',
        ],$item->unit_cost ?? null) !!}
    </td>--}}
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][qty]',[
            'class' => 'input-sm qty unitXcost autonum',
            'container_class' => 'items_'.$rand.'_qty',
        ],$item->qty ?? null) !!}
    </td>
    {{--<td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][total_cost]',[
            'class' => 'input-sm qty autonum',
            'container_class' => 'items_'.$rand.'_total_cost',
        ],$item->total_cost ?? null) !!}
    </td>--}}
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textareaOnly('items['.$rand.'][nature_of_work]',[
            'class' => 'input-sm',
            'label' => 'Description:',
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

            dropdownParent: $('#'+DDParent_{{$rand}}.attr('id')),


            placeholder: 'Select item',
        });
        $('.select2_item_{{$rand}}').on('select2:select', function (e) {
            let t = $(this);
            let parentTrId = t.parents('tr').attr('id');
            let data = e.params.data;

            $("#"+parentTrId+" [for='stockNo']").val(data.id);
            $("#"+parentTrId+" [for='unit']").val(data.populate.unit);
            $("#"+parentTrId+" [for='unitCost']").html('Est: '+$.number(data.populate.unitCost,2));
        });

    </script>
@endif

