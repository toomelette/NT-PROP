@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('layouts.modal-content',['form_id'=>'edit_jr_form_'.$rand, 'slug' => $jr->slug])

@section('modal-header')
    {{$jr->jrNo}}
@endsection

@section('modal-body')
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::select('respCenter',[
            'cols' => 5,
            'label' => 'Department/Division/Section:',
            'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
        ],
        $jr ?? null) !!}

        {!! \App\Swep\ViewHelpers\__form2::select('papCode',[
            'cols' => 5,
            'label' => 'PAP Code:',
            'options' => [],
            'class' => 'select2_papCode_'.$rand,
            'select2_preSelected' => ($jr->pap->pap_code ?? null).' | '.($jr->pap->pap_title ?? null)
        ],
        $jr ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('jrDate',[
            'cols' => 2,
            'label' => 'JR Date.:',
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
                    <th style="width: 18%">Nature of Work</th>
                    <th style="width: 50px"></th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($jr->items))
                    @php
                        $grandTotal = 0;
                    @endphp
                    @foreach($jr->items as $item)
                        @php
                            $grandTotal = $grandTotal + $item->totalCost;
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
                ],
                $jr ?? null) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('certifiedBy',[
                  'cols' => 12,
                  'label' => 'Certified by: ',
                  'rows' => 4
                ],
                $jr ?? null) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('requestedBy',[
                  'cols' => 12,
                  'label' => 'Requested by: ',
                  'rows' => 4
                ],
                $jr ?? null) !!}
            </div>
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('requestedByDesignation',[
                  'cols' => 12,
                  'label' => 'Requested by (Designation): ',
                  'rows' => 4
                ],
                $jr ?? null) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('approvedBy',[
                  'cols' => 12,
                  'label' => 'Approved by: ',
                  'rows' => 4
                ],
                $jr ?? null) !!}
            </div>
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('approvedByDesignation',[
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
        $(".select2_papCode_{{$rand}}").select2({
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
            let uri = '{{route("dashboard.jr.update","slug")}}';
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
    </script>
@endsection

