@php
    $rand = \Illuminate\Support\Str::random(10);
@endphp
<tr id="item_{{$rand}}">
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('ref_number',[
            'label' => 'Reference Number',
        ]) !!}

    </td>
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('ref_number',[
            'label' => 'Stock No.',
        ]) !!}
    
    </td>
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('ref_number',[
            'label' => 'Unit',
        ]) !!}

    </td>

    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('ref_number',[
            'label' => 'Item',
        ]) !!}
    </td>
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('ref_number',[
            'label' => 'Qty',
        ]) !!}
    </td>
    <td>
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('ref_number',[
            'label' => 'Unit Cost',
        ]) !!}
    </td>
    <td class="text-right">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('ref_number',[
            'label' => 'Total Cost',
        ]) !!}
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
            $("#"+parentTrId+" [for='unit_cost']").html('Est: '+$.number(data.populate.unit_cost,2));
            $("#"+parentTrId+" [for='itemName']").val(data.text);
        });

    </script>
@endif

