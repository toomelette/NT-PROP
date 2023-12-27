@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('layouts.modal-content',['form_id'=>'edit_jr_form_'.$rand, 'slug' => $jr->slug])

@section('modal-header')
    {{$jr->ref_no}}
@endsection

@section('modal-body')
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
            'cols' => 5,
            'label' => 'Department/Division/Section:',
            'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
        ],
        $jr ?? null) !!}

        {!! \App\Swep\ViewHelpers\__form2::select('pap_code',[
            'cols' => 7,
            'label' => 'PAP Code:',
            'options' => [],
            'class' => 'select2_pap_code_'.$rand,
            'select2_preSelected' => ($jr->pap->pap_code ?? null).' | '.($jr->pap->pap_title ?? null)
        ],
        $jr ?? null) !!}

    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::select('document_type',[
                                    'label' => 'Document Type:',
                                    'cols' => 3,
                                    'options' => \App\Swep\Helpers\Arrays::documentType(),
                                    'id' => 'inventory-account-code',
                                ]) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('account_code',[
                    'label' => 'Account Code:',
                    'cols' => 3,
                    'options' => \App\Swep\Helpers\Arrays::inventoryAccountCode(),
                    'id' => 'inventory-account-code',
                ]) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('jr_type',[
            'cols' => 5,
            'label' => 'JR Type:',
            'class' => 'jr_type_selector',
            'options' => \App\Swep\Helpers\Arrays::jrType(),
            'select2_preSelected' => $jr->jr_type,
        ]) !!}

        {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
            'cols' => 2,
            'label' => 'Date:',
            'type' => 'date',
        ],
       $jr ?? null) !!}
    </div>
    <div class="row">
        <div class="col-md-12">
            <button data-target="#pr_items_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=jr_items" style="margin-bottom: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
            <table id="pr_items_table" class="table-bordered table table-condensed table-striped">
                <thead>
                <tr>
                    <th style="width: 8%">Property No.</th>
                    <th style="width: 8%">Unit</th>
                    <th style="width: 25%">Item</th>
                    <th>Description</th>
                    <th style="width: 8%">Qty</th>
                    <th style="width: 8%">Unit Cost</th>
                    <th style="width: 8%">Total Cost</th>
                    <th style="width: 18%">Nature of Work</th>
                    <th style="width: 50px"></th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($jr->transDetails))
                    @php
                        $grandTotal = 0;
                    @endphp
                    @foreach($jr->transDetails as $item)
                        @php
                            $grandTotal = $grandTotal + $item->total_cost;
                        @endphp
                        @include('dynamic_rows.jr_items',[
                            'item' => $item,
                        ])
                    @endforeach
                @else
                    @php
                        $grandTotal = 0;
                    @endphp
                    @include('dynamic_rows.pr_items')
                @endif
                </tbody>

            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textarea('purpose',[
                  'cols' => 12,
                  'label' => 'Purpose: ',
                  'rows' => 4
                ],
                $jr ?? null) !!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('abc',[
                  'cols' => 12,
                  'label' => 'ABC: ',
                  'class' => 'text-right autonum_'.$rand,
                  'readonly' => 'readonly',
                ],
                $jr ?? null) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by',[
                  'cols' => 12,
                  'label' => 'Certified by: ',
                  'rows' => 4
                ],
                $jr ?? null) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                  'cols' => 12,
                  'label' => 'Requested by: ',
                  'rows' => 4
                ],
                $jr ?? null) !!}
            </div>
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                  'cols' => 12,
                  'label' => 'Requested by (Designation): ',
                  'rows' => 4
                ],
                $jr ?? null) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                  'cols' => 12,
                  'label' => 'Approved by: ',
                  'rows' => 4
                ],
                $jr ?? null) !!}
            </div>
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                  'cols' => 12,
                  'label' => 'Approved by (Designation): ',
                  'rows' => 4
                ],
                $jr ?? null) !!}
            </div>
        </div>
    </div>
    </div>

@endsection

@section('modal-footer')
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(".select2_pap_code_{{$rand}}").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","pap_codes")}}',
                dataType: 'json',
                delay : 250,
            },
            dropdownParent: $('#edit_jr_modal'),
            placeholder: 'Type PAP Code/Title/Description',
        });

        $(".autonum_{{$rand}}").each(function(){
            new AutoNumeric(this, autonum_settings);
        });
        
        $("#edit_jr_form_{{$rand}}").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            let uri = '{{route("dashboard.my_jr.update","slug")}}';
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
                    active = res.slug;
                    jr_tbl.draw(false);
                    toast('info','','Updated');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })
        $("body").on("change",".unitXcost",function () {
            let parentTableId = $(this).parents('table').attr('id');
            let trId = $(this).parents('tr').attr('id');
            let qty = parseFloat($("#"+trId+" .qty").val());
            let unit_cost = parseFloat($("#"+trId+" .unit_cost").val().replaceAll(',',''));
            let totalCost = unit_cost*qty;
            let grandTotal = 0;
            $("#"+trId+" .totalCost").html($.number(totalCost,2));

            $("#"+parentTableId+" .totalCost").each(function () {
                grandTotal = grandTotal + parseFloat($(this).html().replaceAll(',',''));
            });
            $('input[name="abc"]').val($.number(grandTotal,2));
        })
    </script>
@endsection

