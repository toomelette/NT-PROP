@php
    $rand = \Illuminate\Support\Str::random(10);
@endphp
<tr id="item_{{$rand}}" style="width: 100%">
    <td style="width: 5%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][date]',[
            'class' => 'input-sm',
            'type' => 'date',
            'readonly'=>'readonly',
            'for' => 'date',
        ],$item->date ?? null) !!}
    </td>
    <td style="width: 10%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][ref_no]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'ref_no',
        ],$item->ref_no ?? null) !!}
    </td>
    <td style="width: 10%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][receipt_qty]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'receipt_qty',
        ],$item->receipt_qty ?? null) !!}
    </td>
    <td style="width: 10%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][qty]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'qty',
        ],$item->qty ?? null) !!}
    </td>
    <td style="width: 10%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][purpose]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'purpose',
        ],$item->purpose ?? null) !!}
    </td>
    <td style="width: 10%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][bal_qty]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'bal_qty',
        ],$item->bal_qty ?? null) !!}
    </td>
    <td style="width: 10%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][amount]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'amount',
        ],$item->amount ?? null) !!}
    </td>
    <td style="width: 10%">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items['.$rand.'][remarks]',[
            'class' => 'input-sm',
            'readonly'=>'readonly',
            'for' => 'remarks',
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

    </script>
@endif

