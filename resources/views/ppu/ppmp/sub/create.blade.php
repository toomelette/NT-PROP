@php($rand = \Illuminate\Support\Str::random())
@extends('layouts.modal-content',['form_id'=>'add_ppmp_subaccount_form_'.$rand, 'slug' => $parentPpmp->slug])

@section('modal-header')
    {{\Illuminate\Support\Str::limit($parentPpmp->article->article ?? 'N/A',50,'...')}}
@endsection

@section('modal-body')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-2">
                <p class="no-margin">Year:</p>
                <p class="text-strong" >{{$parentPpmp->pap->year ?? 'N/A'}}</p>
            </div>
            <div class="col-md-3">
                <p class="no-margin">PAP Code:</p>
                <p class="text-strong" >{{$parentPpmp->pap->pap_code ?? 'N/A'}}</p>
            </div>
            <div class="col-md-7">
                <p class="no-margin">PAP Title:</p>
                <p class="text-strong" >{{$parentPpmp->pap->pap_title ?? 'N/A'}}</p>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <p class="no-margin">PPMP:</p>
                <p class="text-strong">{{$parentPpmp->article->article}}</p>
            </div>
        </div>
    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::select('stockNo',[
            'cols' => 12,
            'label' => 'General Description',
            'class' => 'select2_article_'.$rand,
            'autocomplete' => 'off',
            'options' => [],
        ]
        ) !!}
    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('unitCost',[
            'cols' => 4,
            'label' => 'Unit Cost:',
            'class' => 'text-right autonum_'.$rand.' unit_cost unit_costXqty',
            'autocomplete' => 'off',
        ]
        ) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('qty',[
            'cols' => 4,
            'label' => 'Quantity:',
            'type' => 'number',
            'class' => 'text-right qty unit_costXqty',
        ]) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('uom',[
            'cols' => 4,
            'label' => 'Unit:',
            'options' => \App\Swep\Helpers\PPUHelpers::ppmpSizes(),
            'readonly' => 'readonly',
        ]) !!}
    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('estTotalCost',[
            'id' => 'total_est_budget',
            'cols' => 4,
            'label' => 'Total estimated budget:',
            'class' => 'total_est_budget',
            'readonly' => 'readonly',
        ]) !!}


        {!! \App\Swep\ViewHelpers\__form2::select('modeOfProc',[
            'cols' => 4,
            'label' => 'Mode of Procurement',
            'options' => \App\Swep\Helpers\Helper::modesOfProcurement(),
            'readonly' => 'readonly',
        ]) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('budgetType',[
            'label' => 'Budget type:*',
            'cols' => 4,
            'options' => \App\Swep\Helpers\Helper::budgetTypes(),
            'readonly' => 'readonly',
        ]) !!}
    </div>

    <div class="row">
        <div class="col-md-12">
            <label>Schedule/Milestone of Activities: (Must be a number)</label>
            <table class="milestone" style="width: 100%;">
                <tr class="text-center">
                    @foreach(\App\Swep\Helpers\Helper::milestones() as $month)
                        <td>{{$month}}</td>
                    @endforeach
                </tr>
                <tr>
                    @foreach(\App\Swep\Helpers\Helper::milestones() as $month)
                        <td>
                            @php($column = 'qty_'.strtolower($month))
                            <input type="text" class="no-style-input qty_{{strtolower($month)}}"  value="" name="qty_{{strtolower($month)}}" autocomplete="off">
                        </td>
                    @endforeach
                </tr>
            </table>
            <br>
        </div>

    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
            'cols' => 12,
            'label' => 'Remark (brief description of the Program/Project):',
        ]) !!}
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
            dropdownParent: $('#add_ppmp_subaccount_modal'),
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
                $("#add_ppmp_subaccount_modal select[name='"+i+"']").val(item).trigger('change');
                $("#add_ppmp_subaccount_modal input[name='"+i+"']").val(item).trigger('change');
            })
        });
        
        $("#add_ppmp_subaccount_form_{{$rand}}").submit(function (e) {
            e.preventDefault()
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.ppmp_subaccounts.store")}}',
                data : form.serialize()+"&parentPpmp={{$parentPpmp->slug}}",
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    active_{{$passed_rand}} = res.slug;
                    ppmp_subaccount_tbl_{{$passed_rand}}.draw(false);
                    succeed(form,true,true);
                    toast('success','PPMP sub account successfully created.','Success!');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })
    </script>
@endsection

