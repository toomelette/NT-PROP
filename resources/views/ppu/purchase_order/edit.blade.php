@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Edit Purchase Order</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <div class="box box-solid">
            <form id="po_form">
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                        <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                    </div>
                    <input class="" type="text" id="refBook" name="refBook"/>
                    <input class="" type="text" id="slug" name="slug"/>
                    <input class="" type="text" id="itemSlugEdit" name="itemSlugEdit"/>
                    <input class="" type="text" id="isVat" name="isVat"/>
                    <input class="" type="text" id="isGovernment" name="isGovernment"/>
                    <div class="clearfix"></div>
                    {!! \App\Swep\ViewHelpers\__form2::textbox('ref_no',[
                                            'label' => 'PO Number:',
                                            'cols' => 3,
                                            'readonly' => 'readonly'
                                        ],
                                        $order ?? null
                                        ) !!}
                    {!! \App\Swep\ViewHelpers\__form2::select('mode', [
                                            'label' => 'Mode of Procurement:',
                                            'cols' => 3,
                                            'options' => [
                                                'Shopping' => 'Shopping',
                                                'Small Value Procurement' => 'Small Value Procurement',
                                                'Direct Retail Purchase' => 'Direct Retail Purchase',
                                                'Direct Contracting' => 'Direct Contracting'
                                            ]
                                        ],
                                        $order ?? null
                                        ) !!}
                    <div class="clearfix"></div>
                    {!! \App\Swep\ViewHelpers\__form2::select('supplier',[
                        'cols' => 3,
                        'label' => 'Supplier:',
                        'readonly' => 'readonly',
                        'options' => \App\Swep\Helpers\Arrays::suppliers(),
                    ],
                    $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_address',[
                                         'label' => 'Supplier Address',
                                         'cols' => 3,
                                     ],
                    $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_tin',[
                                         'label' => 'TIN:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ],
                    $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_representative',[
                                            'label' => 'Contact Person:',
                                            'cols' => 3,
                                            'required' => 'required'
                                        ],
                    $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('place_of_delivery',[
                                         'label' => 'Place of Delivery:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ], $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('delivery_date',[
                                'label' => 'Date of Delivery:',
                                'cols' => 3,
                                'type' => 'date'
                             ], $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('delivery_term',[
                                         'label' => 'Delivery Term:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ], $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('payment_term',[
                                         'label' => 'Payment Term:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ], $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::select('authorized_official', [
                                            'label' => 'Authorized Official:',
                                            'cols' => 3,
                                            'options' => [
                                                'ATTY. JOHANA S. JADOC' => 'ATTY. JOHANA S. JADOC',
                                                'HELEN B. LOBATON' => 'HELEN B. LOBATON',
                                                'WILFREDO R. MONARES' => 'WILFREDO R. MONARES'
                                            ]
                                        ], $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::select('authorized_official_designation', [
                                            'label' => 'Designation:',
                                            'cols' => 3,
                                            'options' => [
                                                'MANAGER III, AFD-VISAYAS' => 'MANAGER III, AFD-VISAYAS',
                                                'MANAGER III, RDE-VISAYAS' => 'MANAGER III, RDE-VISAYAS',
                                                'MANAGER III, RD-VISAYAS' => 'MANAGER III, RD-VISAYAS'
                                            ]
                                        ], $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('funds_available',[
                                         'label' => 'Chief Accountant:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ], $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('funds_available_designation',[
                                         'label' => 'Designation:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ], $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
                                            'label' => 'Remarks:',
                                            'cols' => 6
                                        ], $order ?? null) !!}
                    <div class="clearfix"></div>
                    <div class="row" id="divRows">
                        {{--<input class="hidden" type="text" id="vatValue" name="vatValue"/>
                        <input class="hidden" type="text" id="poValue" name="poValue"/>
                        <input class="hidden" type="text" id="tax_base_1" name="tax_base_1"/>
                        <input class="hidden" type="text" id="tax_base_2" name="tax_base_2"/>--}}
                        <div class="col-md-12">
                            <div class="form-group col-md-2 vatValue">
                                <label for="vatValue">VAT Percent:</label>
                                <input class="form-control" name="vatValue" id="vatValue" type="text" value="{{$order->vat}}" placeholder="" autocomplete="" required="">
                            </div>
                            <div class="form-group col-md-2 tax_base_1">
                                <label for="tax_base_1">VAT Amount:</label>
                                <input class="form-control" name="tax_base_1" id="tax_base_1" type="text" value="{{$order->tax_base_1}}" placeholder="" autocomplete="" required="">
                            </div>
                            <div class="form-group col-md-2 vatValue">
                                <label for="vatValue">W/TAX Percent:</label>
                                <input class="form-control" name="poValue" id="poValue" type="text" value="{{$order->withholding_tax}}" placeholder="" autocomplete="" required="">
                            </div>
                            <div class="form-group col-md-2 tax_base_2">
                                <label for="tax_base_2">W/TAX Amount:</label>
                                <input class="form-control" name="tax_base_2" id="tax_base_2" type="text" value="{{$order->tax_base_2}}" placeholder="" autocomplete="" required="">
                            </div>
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('total_gross',[
                                            'label' => 'Total Gross:',
                                            'cols' => 2,
                                            'required' => 'required'
                                        ],
                                        $order ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('total',[
                                            'label' => 'Total:',
                                            'cols' => 2,
                                            'required' => 'required'
                                        ],
                                        $order ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('total_in_words',[
                                            'label' => 'Total in words:',
                                            'cols' => 8,
                                            'required' => 'required'
                                        ],
                                        $order ?? null) !!}
                            <div class="" id="tableContainer" style="margin-top: 50px">
                                <table class="table table-bordered table-striped table-hover" id="trans_table" style="width: 100% !important">
                                    <thead>
                                        <tr class="">
                                            <th>Stock No.</th>
                                            <th>Unit</th>
                                            <th>Item</th>
                                            <th>Description</th>
                                            <th>Qty</th>
                                            <th>Unit Cost</th>
                                            <th>Total Cost</th>
                                            <th>Prop. No.</th>
                                            <th>Nature of Work</th>
                                            <th style="width: 3%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($trans->transDetails as $transDetail)
                                            <tr id="{{$transDetail->slug}}">
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][stock_no]" name="items['{{$transDetail->slug}}'][stock_no]" type="text" value="{{$transDetail->stock_no}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][unit]" name="items['{{$transDetail->slug}}'][unit]" type="text" value="{{$transDetail->unit}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][item]" name="items['{{$transDetail->slug}}'][item]" type="text" value="{{$transDetail->item}}"></td>
                                                <td><textarea class="input-sm" id="items['{{$transDetail->slug}}'][description]" name="items['{{$transDetail->slug}}'][description]" type="text">{{$transDetail->description}}</textarea></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][qty]" name="items['{{$transDetail->slug}}'][qty]" type="text" value="{{$transDetail->qty}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][unit_cost]" name="items['{{$transDetail->slug}}'][unit_cost]" type="text" value="{{$transDetail->unit_cost}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][total_cost]" name="items['{{$transDetail->slug}}'][total_cost]" type="text" value="{{$transDetail->total_cost}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][property_no]" name="items['{{$transDetail->slug}}'][property_no]" type="text" value="{{$transDetail->property_no}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][nature_of_work]" name="items['{{$transDetail->slug}}'][nature_of_work]" type="text" value="{{$transDetail->nature_of_work}}"></td>
                                                <td><button type="button" class="btn btn-danger btn-sm delete-btn" data-slug="{{$transDetail->slug}}" onclick="deleteRow(this)"><i class="fa fa-times"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </section>
@endsection


@section('modals')

@endsection

@section('scripts')

@endsection
