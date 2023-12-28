@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('layouts.modal-content',['form_id' => 'edit_pr_'.$rand,'slug'=> $pr->slug])

@section('modal-header')
   {{$pr->ref_no}} - Edit Purchase Request
@endsection

@section('modal-body')
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
              'cols' => 5,
              'label' => 'Department/Division/Section:',
              'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
          ],$pr ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('pap_code',[
              'cols' => 7,
              'label' => 'PAP Code:',
              'options' => [],
              'class' => 'select2_pap_code_'.$rand,
              'select2_preSelected' => ($pr->pap->pap_code ?? null).' | '.($pr->pap->pap_title ?? null)
          ]
          ,$pr ?? null) !!}

    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::select('document_type',[
                                    'label' => 'Document Type:',
                                    'cols' => 3,
                                    'options' => \App\Swep\Helpers\Arrays::documentType(),
                                    'id' => 'inventory-account-code',
                                ],$pr ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('account_code',[
                    'label' => 'Account Code:',
                    'cols' => 3,
                    'options' => \App\Swep\Helpers\Arrays::inventoryAccountCode(),
                    'id' => 'inventory-account-code',
                ],$pr ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
              'cols' => 2,
              'label' => 'PR Date:',
              'type' => 'date',
          ], $pr ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('sai',[
            'cols' => 2,
            'label' => 'SAI No.:',
        ],$pr ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('sai_date',[
          'cols' => 2,
          'label' => 'SAI Date.:',
          'type' => 'date',
        ],$pr ?? null) !!}
    </div>
    <div class="row">
        <div class="col-md-12">
            <button data-target="#pr_items_table_{{$rand}}" uri="{{route('dashboard.ajax.get','add_row')}}?view=pr_items" style="margin-bottom: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
            <table id="pr_items_table_{{$rand}}" class="table-bordered table table-condensed table-striped">
                <thead>
                <tr>
                    <th style="width: 8%">Stock No.</th>
                    <th style="width: 8%">Unit</th>
                    <th style="width: 25%">Item</th>
                    <th>Description</th>
                    <th style="width: 8%">Qty</th>
                    <th style="width: 8%">Unit Cost</th>
                    <th style="width: 8%">Total Cost</th>
                    <th style="width: 50px"></th>
                </tr>
                </thead>
                <tbody>
                    @if(!empty($pr->transDetails))
                        @php
                            $grandTotal = 0;
                        @endphp
                        @foreach($pr->transDetails as $item)
                            @php
                                $grandTotal = $grandTotal + $item->total_cost;
                            @endphp
                            @include('dynamic_rows.pr_items',[
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
                <tfoot>
                <tr>
                    <th colspan="6">
                    </th>
                    <th class="grandTotal text-right">{{number_format($grandTotal,2)}}</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textarea('purpose',[
                  'cols' => 12,
                  'label' => 'Purpose: ',
                  'rows' => 4
                ],$pr ?? null) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                  'cols' => 12,
                  'label' => 'Requested by: ',
                  'rows' => 4
                ],$pr ?? null) !!}
            </div>
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                  'cols' => 12,
                  'label' => 'Requested by (Designation): ',
                  'rows' => 4
                ],$pr ?? null) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                  'cols' => 12,
                  'label' => 'Approved by: ',
                  'rows' => 4
                ],$pr ?? null) !!}
            </div>
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                  'cols' => 12,
                  'label' => 'Approved by (Designation): ',
                  'rows' => 4
                ],$pr ?? null) !!}
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
        $("#edit_pr_{{$rand}}").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            let uri = '{{route("dashboard.my_pr.update","slug")}}';
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
                    activePr = res.slug;
                    pr_tbl.draw(false);
                    toast('info','Purchase request successfully updated.','Updated');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })

        $(".select2_pap_code_{{$rand}}").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","pap_codes")}}',
                dataType: 'json',
                delay : 250,
            },
            dropdownParent: $('#edit_pr_modal'),
            placeholder: 'Type PAP Code/Title/Description',
        });
    </script>
@endsection

