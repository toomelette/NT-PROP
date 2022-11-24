@php
$rand = Str::random();
@endphp
@extends('layouts.modal-content',['form_id' => 'edit_ppmp_form_'.$rand ,'slug' => $ppmp->slug])

@section('modal-header')
    {{$ppmp->article->article ?? '-'}}
@endsection

@section('modal-body')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-2">
                <p class="no-margin">Year:</p>
                <p class="text-strong" for="year">
                    {{$ppmp->pap->year ?? 'N/A'}}
                </p>
            </div>
            <div class="col-md-3">
                <p class="no-margin">PAP Code:</p>
                <p class="text-strong" for="pap_code">
                    {{$ppmp->pap->pap_code ?? 'N/A'}}
                </p>
            </div>
            <div class="col-md-7">
                <p class="no-margin">PAP Title:</p>
                <p class="text-strong" for="pap_title">
                    {{$ppmp->pap->pap_title ?? 'N/A'}}
                </p>
            </div>

        </div>
    </div>
    <hr style="border: 1px dashed #1b7e5a; margin-top: 3px;margin-bottom: 10px">

    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::select('papCode',[
              'cols' => 7,
              'label' => 'PAP Code:',
              'options' => [],
              'class' => 'select2_papCode_'.$rand,
              'select2_preSelected' =>  (!empty($ppmp->pap)) ? $ppmp->papCode .' | '. $ppmp->pap->pap_title : null,
          ],$ppmp->papCode
          ) !!}

        {!! \App\Swep\ViewHelpers\__form2::select('sourceOfFund',[
            'cols' => 5,
            'label' => 'Source of Fund',
            'options' => \App\Swep\Helpers\Helper::fundSources(),
        ],$ppmp
        ) !!}
    </div>
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
            'class' => 'text-right autonum unit_cost autonum_'.$rand.' unit_costXqty',
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
                            <input type="text" class="no-style-input qty_{{strtolower($month)}}"  value="{{$ppmp->$column }}" name="qty_{{strtolower($month)}}" autocomplete="off">
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
    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(".autonum_{{$rand}}").each(function(){
            new AutoNumeric(this, autonum_settings);
        });

        $(".select2_papCode_{{$rand}}").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","pap_codes")}}',
                dataType: 'json',
                delay : 250,

            },
            dropdownParent: $('#edit_ppmp_modal'),
            placeholder: 'Type PAP Code/Title/Description',
        });

        $(".select2_article_{{$rand}}").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","articles")}}',
                dataType: 'json',
                delay : 250,
            },
            dropdownParent: $('#edit_ppmp_modal'),
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
                $("#edit_ppmp_modal select[name='"+i+"']").val(item).trigger('change');
                $("#edit_ppmp_modal input[name='"+i+"']").val(item).trigger('change');
            })
        });
        $('.select2_papCode_{{$rand}}').on('select2:select', function (e) {
            let data = e.params.data;
            $("#edit_ppmp_modal p[for='year']").html(data.year);
            $("#edit_ppmp_modal p[for='pap_code']").html(data.pap_code);
            $("#edit_ppmp_modal p[for='pap_title']").html(data.pap_title);
        });

        $("#edit_ppmp_form_{{$rand}}").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            let uri = '{{route("dashboard.ppmp.update","slug")}}';
            uri = uri.replace('slug',form.attr('data'));
            $.ajax({
                url : uri,
                data : form.serialize(),
                type: 'PATCH',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,true);
                    active = res.slug;
                    ppmp_tbl.draw(false);
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })


    </script>
@endsection

