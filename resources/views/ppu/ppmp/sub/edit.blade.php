@php
    $rand = Str::random();
@endphp
@extends('layouts.modal-content',['form_id' => 'edit_ppmp_subaccount_form_'.$rand ,'slug' => $ppmp->slug])

@section('modal-header')
    {{$ppmp->article->article ?? '-'}}
@endsection

@section('modal-body')
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::select('stockNo',[
            'cols' => 12,
            'label' => 'General Description',
            'class' => 'select2_article_'.$rand,
            'autocomplete' => 'off',
            'options' => [],
            'select2_preSelected' =>  $ppmp->article->article ?? null,
        ],
        $ppmp->stockNo
        ) !!}
    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('unitCost',[
            'cols' => 4,
            'label' => 'Unit Cost:',
            'class' => 'text-right autonum_'.$rand.' unit_cost autonum_'.$rand.' unit_costXqty',
            'autocomplete' => 'off',
        ],$ppmp
        ) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('qty',[
            'cols' => 4,
            'label' => 'Quantity:',
            'type' => 'number',
            'class' => 'text-right qty unit_costXqty',
        ],$ppmp
        ) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('uom',[
            'cols' => 4,
            'label' => 'Unit:',
            'options' => \App\Swep\Helpers\PPUHelpers::ppmpSizes(),
            'readonly' => 'readonly',
        ],$ppmp
        ) !!}
    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('estTotalCost',[
            'id' => 'total_est_budget',
            'cols' => 4,
            'label' => 'Total estimated budget:',
            'class' => 'total_est_budget text-right',
            'readonly' => 'readonly',
        ],number_format($ppmp->estTotalCost,2)
        ) !!}


        {!! \App\Swep\ViewHelpers\__form2::select('modeOfProc',[
            'cols' => 4,
            'label' => 'Mode of Procurement',
            'options' => \App\Swep\Helpers\Helper::modesOfProcurement(),
            'readonly' => 'readonly',
        ],$ppmp
        ) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('budgetType',[
            'label' => 'Budget type:*',
            'cols' => 4,
            'options' => \App\Swep\Helpers\Helper::budgetTypes(),
            'readonly' => 'readonly',
        ],$ppmp
        ) !!}
    </div>

@endsection

@section('modal-footer')
    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Save</button>
@endsection

@section('scripts')
<script type="text/javascript">
    $(".autonum_{{$rand}}").each(function(){
        new AutoNumeric(this, autonum_settings);
    });

    $(".select2_article_{{$rand}}").select2({
        ajax: {
            url: '{{route("dashboard.ajax.get","articles")}}',
            dataType: 'json',
            delay : 250,
        },
        dropdownParent: $('#edit_ppmp_subaccount_modal'),
        placeholder: 'Select item',
        language : {
            "noResults": function(){

                return "No item found. Click <button type='button' data-target='#add_article_modal' data-toggle='modal' class='btn btn-success btn-xs add'>Add item</button> to add your desired item to the database.";
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });


    $('.select2_article_{{$rand}}').on('select2:select', function (e) {
        let data = e.params.data;
        $.each(data.populate,function (i, item) {
            $("#edit_ppmp_subaccount_modal select[name='"+i+"']").val(item).trigger('change');
            $("#edit_ppmp_subaccount_modal input[name='"+i+"']").val(item).trigger('change');
        })
    });
    $("#edit_ppmp_subaccount_form_{{$rand}}").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let uri = '{{route("dashboard.ppmp_subaccounts.update","slug")}}';
        uri = uri.replace('slug',form.attr('data'));
        loading_btn(form);
        $.ajax({
            url : uri,
            data : form.serialize(),
            type: 'PATCH',
            headers: {
                {!! __html::token_header() !!}
            },
            success: function (res) {
                succeed(form,true,true);
                active_{{$passed_rand}} = res.slug;
                ppmp_subaccount_tbl_{{$passed_rand}}.draw(false);
                toast('info','Item successfully saved.','Updated');
            },
            error: function (res) {
                errored(form,res);
            }
        })
    })
</script>
@endsection

