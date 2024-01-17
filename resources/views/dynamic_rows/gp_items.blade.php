@php
    $rand = \Illuminate\Support\Str::random(10);
@endphp
<tr id="item_{{$rand}}" style="width: 100%">


    <td style="width: 10%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][qty]',[
            'type' => 'number',
            'step' => 'any',
        ],$item->qty ?? null) !!}
    </td>

    <td style="width: 10%">
        {!! \App\Swep\ViewHelpers\__form2::selectOnly('items['.$rand.'][unit]',[
            'class' => 'input-sm',
            'for' => 'uom',
            'options' => \App\Swep\Helpers\Arrays::unitsOfMeasurement(),
            'container_class' => 'items_'.$rand.'_unit',
        ],$item->unit ?? null) !!}

    </td>

    <td style="width: 35%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][item]',[
            'class' => 'input-sm select2_item_'.$rand.' items_'.$rand.'_item',
            'options' => [],
        ],$item->item ?? null) !!}
    </td>

    <td style="width: 35%">
        {!! \App\Swep\ViewHelpers\__form2::textareaOnly('items['.$rand.'][description]',[
            'class' => 'input-sm items_'.$rand.'_description',
            'label' => 'Description:',
        ],$item->description ?? null) !!}
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


        {{--$(".select2_item_{{$rand}}").select2({--}}
        {{--    ajax: {--}}
        {{--        url: '{{route("dashboard.ajax.get","articles")}}',--}}
        {{--        dataType: 'json',--}}
        {{--        delay : 250,--}}
        {{--    },--}}

        {{--    --}}{{--dropdownParent: $('#'+DDParent_{{$rand}}.attr('id')),--}}
        {{--    placeholder: 'Select item',--}}
        {{--});--}}
        {{--$('.select2_item_{{$rand}}').on('select2:select', function (e) {--}}
        {{--    let t = $(this);--}}
        {{--    let parentTrId = t.parents('tr').attr('id');--}}
        {{--    let data = e.params.data;--}}

        {{--    $("#"+parentTrId+" [for='stockNo']").val(data.id);--}}
        {{--    $("#"+parentTrId+" [for='uom']").val(data.populate.uom);--}}
        {{--    $("#"+parentTrId+" [for='itemName']").val(data.text);--}}
        {{--});--}}

    </script>
@endif

