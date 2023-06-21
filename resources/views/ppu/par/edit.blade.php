@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('layouts.modal-content',['form_id' => 'edit_form' , 'slug' => $par->slug])

@section('modal-header')
    <strong>Edit Property Acknowledgement Receipt</strong>
@endsection

@section('modal-body')
    <div class="row">
        <input class="hidden" type="text" id="slug" name="slug" value=""/>
        {!! \App\Swep\ViewHelpers\__form2::textbox('par_code',[
                    'label' => 'PAR No.:',
                    'cols' => 3,
                    'readonly' => 'readonly',
                    ],
                $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('updated_at',[
                            'label' => 'PAR Date:',
                            'cols' => 3,
                            'type' => 'date',
                            'readonly' => 'readonly',
                         ],
                        $par ?? null) !!}
        <div class="clearfix"></div>
        {!! \App\Swep\ViewHelpers\__form2::select('respcenter',[
                                'label' => 'Resp. Center:',
                                'cols' => 6,
                                'options' => \App\Swep\Helpers\PPUHelpers::respCentersArray(),
                            ],
                            $par ?? null) !!}
        <div class="form-group col-md-6 employee_name ">
            <label for="employee_name">Search Employee:*</label>
            <input autocomplete="off" class="form-control " id="employee_name" name="employee_name" type="text" value="" placeholder="Name of employee"><ul class="typeahead dropdown-menu"></ul>
        </div>
        {!! \App\Swep\ViewHelpers\__form2::textbox('acctemployee_fname',[
            'label' => 'Acct. Officer:',
            'cols' => 4,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('acctemployee_post',[
            'label' => 'Position:',
            'cols' => 4,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('acctemployee_no',[
            'label' => 'Emp. No.:',
            'cols' => 4,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('article',[
            'label' => 'Article:',
            'cols' => 6,
            ],
        $par ?? null) !!}
        {{--{!! \App\Swep\ViewHelpers\__form2::select('article',[
              'cols' => 6,
              'label' => 'Article:',
              'class' => 'select2_article',
              'autocomplete' => 'off',
              'options' => [],
          ],
        $par ?? null) !!}--}}
        {!! \App\Swep\ViewHelpers\__form2::textarea('description',[
              'cols' => 6,
              'label' => 'Description: ',
              'rows' => 2
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('propertyno',[
            'label' => 'Property No.:',
            'cols' => 4,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('uom',[
            'label' => 'Unit:',
            'cols' => 4,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('acquiredcost',[
            'label' => 'Acquired Cost:',
            'cols' => 4,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('qtypercard',[
            'label' => 'Qty Per Card:',
            'cols' => 3,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('onhandqty',[
            'label' => 'Qty Onhand:',
            'cols' => 3,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('shortqty',[
            'label' => 'Short Qty:',
            'cols' => 3,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('shortvalue',[
            'label' => 'Short Value:',
            'cols' => 3,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('dateacquired',[
                    'label' => 'Date Acquired:',
                    'cols' => 4,
                    'type' => 'date'
                 ],
                $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
            'label' => 'Remarks:',
            'cols' => 8,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('supplier',[
            'label' => 'Supplier:',
            'cols' => 4,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('invoiceno',[
            'label' => 'Invoice No.:',
            'cols' => 4,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('invoicedate',[
                    'label' => 'Invoice Date:',
                    'cols' => 4,
                    'type' => 'date'
                 ],
                $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('pono',[
            'label' => 'P.O. No.:',
            'cols' => 4,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('podate',[
                    'label' => 'P.O. Date:',
                    'cols' => 4,
                    'type' => 'date'
                 ],
                $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('invtacctcode',[
            'label' => 'Inv. Account Code:',
            'cols' => 4,
            ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('location',[
            'label' => 'Location:',
            'cols' => 4,
            'options' => \App\Swep\Helpers\Arrays::location(),
        ],
        $par ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('acquiredmode',[
            'label' => 'Acquisition Mode:',
            'cols' => 4,
            'options' => \App\Swep\Helpers\Arrays::acquisitionMode(),
        ],
        $par ?? null) !!}
    </div>

@endsection
@section('modal-footer')
    <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        let active;

        $("#saveBtn").click(function(e) {
            e.preventDefault();
            let form = $('#edit_form');
            let uri = '{{route("dashboard.par.update","slug")}}';
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
                    active = res.id;
                    ana_tbl.draw(false);
                    toast('info','PAR successfully updated.','Updated');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        });
    });
</script>
@endsection

