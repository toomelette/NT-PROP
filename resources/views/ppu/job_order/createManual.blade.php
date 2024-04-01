@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Create Job Order - Manual</h1>
    </section>
@endsection
@section('content2')
    @php
        $employees = \App\Models\Employee::query()
        ->where(function ($query) {
            $query->where('locations', '=', 'VISAYAS')
                ->orWhere('locations', '=', 'LUZON/MINDANAO')
                ->orWhere('as_signatory','=',1);
        })
        ->where('is_active', '=', 'ACTIVE')
        ->orderBy('fullname', 'asc')
        ->get();

       $employeesCollection = $employees->map(function ($data){
            return [
                'id' => $data->employee_no,
                'text' => $data->firstname.' '.$data->lastname.' '.($data->name_ext != '' ? $data->name_ext.'.':'').' - '.$data->employee_no,
                'employee_no' => $data->employee_no,
                'fullname' => $data->firstname.' '.$data->lastname.' '.$data->name_ext,
                'position' => $data->position,
            ];
        })->toJson();
    @endphp

    <section class="content col-md-12">
        <div class="box box-solid">
            <form id="jo_form">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                        <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                    </div>
                    <input class="hidden" type="text" id="refBook" name="refBook"/>
                    <input class="hidden" type="text" id="slug" name="slug"/>
                    <input class="hidden" type="text" id="itemSlugEdit" name="itemSlugEdit"/>
                    <input class="hidden" type="text" id="isVat" name="isVat"/>
                    <input class="hidden" type="text" id="isGovernment" name="isGovernment"/>
                    {!! \App\Swep\ViewHelpers\__form2::textbox('jo_number',[
                                            'label' => 'JO Number:',
                                            'cols' => 3
                                        ],
                                        $jo_number ?? null
                                        ) !!}

                    {!! \App\Swep\ViewHelpers\__form2::select('mode', [
                                            'label' => 'Mode of Procurement:',
                                            'cols' => 3,
                                            'options' => \App\Swep\Helpers\Arrays::ModeOfProcurement(),
                                        ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                                'label' => 'JO Date:',
                                'cols' => 3,
                                'type' => 'date',
                                'required' => 'required'
                             ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::select('jr_type',[
                            'cols' => 3,
                            'label' => 'JR Type:',
                            'class' => 'jr_type_selector',
                            'options' => \App\Swep\Helpers\Arrays::jrType(),
                        ]) !!}
                    <div class="clearfix"></div>
                    <div class="form-group col-md-3 supplier">
                        <label for="awardee">Supplier: </label>
                        {!! Form::select('supplier', $suppliers, null, ['class' => 'form-control']) !!}
                    </div>
                    {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_address',[
                                         'label' => 'Supplier Address',
                                         'cols' => 3,
                                     ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_tin',[
                                         'label' => 'TIN:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_representative',[
                                            'label' => 'Contact Person:',
                                            'cols' => 3,
                                            'required' => 'required'
                                        ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('place_of_delivery',[
                                         'label' => 'Place of Delivery:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ], 'SRA BACOLOD') !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('delivery_date',[
                                'label' => 'Date of Delivery:',
                                'cols' => 3,
                                'type' => 'date'
                             ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('delivery_term',[
                                         'label' => 'Delivery Term:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('payment_term',[
                                         'label' => 'Payment Term:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::select('authorized_official', [
                                            'label' => 'Authorized Official:',
                                            'cols' => 3,
                                            'options' => \App\Swep\Helpers\Arrays::AuthorizedOfficial(),
                                        ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::select('authorized_official_designation', [
                                            'label' => 'Designation:',
                                            'cols' => 3,
                                            'options' => \App\Swep\Helpers\Arrays::AuthorizedOfficialDesignation(),
                                        ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('funds_available',[
                                         'label' => 'Chief Accountant:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ],
                                    \App\Swep\Helpers\Arrays::ChiefAccountant()
                                    ) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('funds_available_designation',[
                                         'label' => 'Designation:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ],
                                    \App\Swep\Helpers\Arrays::ChiefAccountantDesignation()
                                    ) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                                            'label' => 'JR Reference Number:',
                                            'cols' => 3,
                                            'required' => 'required'
                                        ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                            'cols' => 3,
                            'label' => 'Department/Division/Section:',
                            'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
                        ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::select('pap_code',[
                        'cols' => 3,
                        'label' => 'PAP Code:',
                        'options' => [],
                        'class' => 'select2_papCode',
                    ]) !!}
                    <div class="clearfix"></div>
                    {!! \App\Swep\ViewHelpers\__form2::select('requested_by',[
                                    'label' => 'Requested By:',
                                    'cols' => 3,
                                    'options' => [],
                                    'id' => 'requested_by',
                                ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                                  'cols' => 3,
                                  'label' => 'Requested by (Designation): ',
                                ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                                  'cols' => 3,
                                  'label' => 'Approved by: ',
                                ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                                  'cols' => 3,
                                  'label' => 'Approved by (Designation): ',
                                ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
                                            'label' => 'Remarks:',
                                            'cols' => 6
                                        ]) !!}
                    <div class="clearfix"></div>
                    <div class="row" id="divRows">
                        <div class="col-md-12">
                            <div class="form-group col-md-2 vatValue">
                                <label for="vatValue">VAT Percent:</label>
                                <input class="form-control" name="vatValue" id="vatValue" type="text" value="" placeholder="" autocomplete="" required="">
                            </div>
                            <div class="form-group col-md-2 tax_base_1">
                                <label for="tax_base_1">VAT Amount:</label>
                                <input class="form-control" name="tax_base_1" id="tax_base_1" type="text" value="" placeholder="" autocomplete="" required="">
                            </div>
                            <div class="form-group col-md-2 vatValue">
                                <label for="vatValue">W/TAX Percent:</label>
                                <input class="form-control" name="joValue" id="joValue" type="text" value="" placeholder="" autocomplete="" required="">
                            </div>
                            <div class="form-group col-md-2 tax_base_2">
                                <label for="tax_base_2">W/TAX Amount:</label>
                                <input class="form-control" name="tax_base_2" id="tax_base_2" type="text" value="" placeholder="" autocomplete="" required="">
                            </div>
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('total_gross',[
                                            'label' => 'Total Gross:',
                                            'cols' => 2,
                                            'required' => 'required'
                                        ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('total',[
                                            'label' => 'Total:',
                                            'cols' => 2,
                                            'required' => 'required'
                                        ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('total_in_words',[
                                            'label' => 'Total in words:',
                                            'cols' => 8,
                                            'required' => 'required'
                                        ]) !!}
                            <div class="" id="tableContainer" style="margin-top: 50px">
                                <button data-target="#trans_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=jo_items" style="margin-bottom: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                                <table class="table table-bordered table-striped table-hover" id="trans_table" style="width: 100% !important">
                                    <thead>
                                    <tr class="">
                                        <th style="width: 5%">Stock No.</th>
                                        <th>Unit</th>
                                        <th style="width: 15%">Item</th>
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
                                    @include('dynamic_rows.jo_items')
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="6">
                                        </th>
                                        <th class="grandTotal text-right zero">0.00</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
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
    <script type="text/javascript">
        var data = {!!$employeesCollection!!};

        $(".select2_item").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","articles")}}',
                dataType: 'json',
                delay : 250,
            },
            placeholder: 'Select item',
        });

        $('.select2_item').on('select2:select', function (e) {
            let t = $(this);
            let parentTrId = t.parents('tr').attr('id');
            let data = e.params.data;

            $("#"+parentTrId+" [for='stockNo']").val(data.id);
            $("#"+parentTrId+" [for='itemName']").val(data.text);
            $("#"+parentTrId+" [for='unit']").val(data.populate.uom);
            $("#"+parentTrId+" [for='unit_cost']").html('Est: '+$.number(data.populate.unit_cost,2));
        });

        $(".select2_papCode").select2({
            ajax: {
                url: function () {
                    let baseUrl = "{{route('dashboard.ajax.get','pap_codes')}}";
                    let respCode = $(this).parents('form').find('select[name="resp_center"]').val();
                    return baseUrl+'?respCode='+respCode;
                },
                dataType: 'json',
                delay : 250,

            },
            placeholder: 'Type PAP Code/Title/Description',
        });

        $("#requested_by").select2({
            data : data,
        });

        $("#requested_by").change(function (){
            let value = $(this).val();
            if(value != ''){
                let index = data.findIndex( object => {
                    return object.id == value;
                });
                $("input[name='requested_by_designation']").val(data[index].position);
            }else{
                $("input[name='requested_by_designation']").val('');
            }
        });

        function numberToWords(number) {
            const units = ['', 'ONE', 'TWO', 'THREE', 'FOUR', 'FIVE', 'SIX', 'SEVEN', 'EIGHT', 'NINE', 'TEN', 'ELEVEN', 'TWELVE', 'THIRTEEN', 'FOURTEEN', 'FIFTEEN', 'SIXTEEN', 'SEVENTEEN', 'EIGHTEEN', 'NINETEEN'];
            const tens = ['', '', 'TWENTY', 'THIRTY', 'FORTY', 'FIFTY', 'SIXTY', 'SEVENTY', 'EIGHTY', 'NINETY'];

            if (number === 0) {
                return 'ZERO';
            }

            // Function to convert a two-digit number
            function convertTwoDigitNumber(number) {
                if (number < 20) {
                    return units[number];
                } else {
                    const digitOne = Math.floor(number / 10);
                    const digitTwo = number % 10;
                    return tens[digitOne] + ' ' + units[digitTwo];
                }
            }

            // Function to convert a whole number
            function convertWholeNumber(number) {
                if (number < 100) {
                    return convertTwoDigitNumber(number);
                } else if (number < 1000) {
                    const digitHundreds = Math.floor(number / 100);
                    const remainingDigits = number % 100;
                    return units[digitHundreds] + ' HUNDRED ' + convertTwoDigitNumber(remainingDigits);
                } else if (number < 1000000) {
                    const digitThousands = Math.floor(number / 1000);
                    const remainingDigits = number % 1000;
                    return convertWholeNumber(digitThousands) + ' THOUSAND ' + convertWholeNumber(remainingDigits);
                } else {
                    return 'Sorry, the number is too large to convert.';
                }
            }

            let words = '';

            if (number < 0) {
                words += 'minus ';
                number = Math.abs(number);
            }

            const wholePart = Math.floor(number);
            const decimalPart = Math.round((number - wholePart) * 100);

            words += convertWholeNumber(wholePart);

            if (decimalPart > 0) {
                words += ' AND ' + convertTwoDigitNumber(decimalPart) + 'CENTS';
            }

            return words.trim();
        }

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
            })
            $("#"+parentTableId+" .grandTotal").html($.number(grandTotal,2))
        })

        $(document).ready(function() {
            $('input[name="vatValue"]').on('keypress', function(event) {
                if (event.which === 13) { // Check if Enter key is pressed
                    var totalGrossRaw = $('input[name="total_gross"]').val();
                    var cleanedTotalGross = totalGrossRaw.replace(/,/g, '');
                    var totalGross = parseFloat(cleanedTotalGross);
                    let taxBase = totalGross;
                    if($('#isVat').val() === 'True'){
                        taxBase = totalGross/1.12;
                    }
                    let tb1 = ($('#vatValue').val()/ 100)*taxBase;
                    let pOjOTax = ($('#joValue').val() / 100) * taxBase;
                    $('#tax_base_1').val(tb1.toFixed(2));
                    $('#tax_base_2').val(pOjOTax.toFixed(2));
                    let totalAmt = totalGross - (tb1 + pOjOTax);
                    //$('input[name="total_gross"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('input[name="total"]').val(totalAmt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('input[name="total_in_words"]').val(numberToWords(totalAmt));
                    // Prevent the default form submission behavior
                    event.preventDefault();
                }
            });

            $('input[name="vatValue"]').on('blur', function() {
                var totalGrossRaw = $('input[name="total_gross"]').val();
                var cleanedTotalGross = totalGrossRaw.replace(/,/g, '');
                var totalGross = parseFloat(cleanedTotalGross);
                let taxBase = totalGross;
                if($('#isVat').val() === 'True'){
                    taxBase = totalGross/1.12;
                }
                let tb1 = ($('#vatValue').val()/ 100)*taxBase;
                let pOjOTax = ($('#joValue').val() / 100) * taxBase;
                $('#tax_base_1').val(tb1.toFixed(2));
                $('#tax_base_2').val(pOjOTax.toFixed(2));
                let totalAmt = totalGross - (tb1 + pOjOTax);
                //$('input[name="total_gross"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('input[name="total"]').val(totalAmt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('input[name="total_in_words"]').val(numberToWords(totalAmt));
            });

            $('input[name="joValue"]').on('keypress', function(event) {
                if (event.which === 13) { // Check if Enter key is pressed
                    var totalGrossRaw = $('input[name="total_gross"]').val();
                    var cleanedTotalGross = totalGrossRaw.replace(/,/g, '');
                    var totalGross = parseFloat(cleanedTotalGross);
                    let taxBase = totalGross;
                    if($('#isVat').val() === 'True'){
                        taxBase = totalGross/1.12;
                    }
                    let tb1 = ($('#vatValue').val()/ 100)*taxBase;
                    let pOjOTax = ($('#joValue').val() / 100) * taxBase;
                    $('#tax_base_1').val(tb1.toFixed(2));
                    $('#tax_base_2').val(pOjOTax.toFixed(2));
                    let totalAmt = totalGross - (tb1 + pOjOTax);
                    //$('input[name="total_gross"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('input[name="total"]').val(totalAmt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('input[name="total_in_words"]').val(numberToWords(totalAmt));
                    // Prevent the default form submission behavior
                    event.preventDefault();
                }
            });

            $('input[name="joValue"]').on('blur', function() {
                var totalGrossRaw = $('input[name="total_gross"]').val();
                var cleanedTotalGross = totalGrossRaw.replace(/,/g, '');
                var totalGross = parseFloat(cleanedTotalGross);
                let taxBase = totalGross;
                if($('#isVat').val() === 'True'){
                    taxBase = totalGross/1.12;
                }
                let tb1 = ($('#vatValue').val()/ 100)*taxBase;
                let pOjOTax = ($('#joValue').val() / 100) * taxBase;
                $('#tax_base_1').val(tb1.toFixed(2));
                $('#tax_base_2').val(pOjOTax.toFixed(2));
                let totalAmt = totalGross - (tb1 + pOjOTax);
                //$('input[name="total_gross"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('input[name="total"]').val(totalAmt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('input[name="total_in_words"]').val(numberToWords(totalAmt));
            });

            $('input[name="total_gross"]').on('keypress', function(event) {
                if (event.which === 13) { // Check if Enter key is pressed
                    var totalGrossRaw = $(this).val();
                    var cleanedTotalGross = totalGrossRaw.replace(/,/g, '');
                    var totalGross = parseFloat(cleanedTotalGross);
                    let taxBase = totalGross;
                    if($('#isVat').val() === 'True'){
                        taxBase = totalGross/1.12;
                    }
                    let tb1 = ($('#vatValue').val()/ 100)*taxBase;
                    let pOjOTax = ($('#joValue').val() / 100) * taxBase;
                    $('#tax_base_1').val(tb1.toFixed(2));
                    $('#tax_base_2').val(pOjOTax.toFixed(2));
                    let totalAmt = totalGross - (tb1 + pOjOTax);
                    //$('input[name="total_gross"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('input[name="total"]').val(totalAmt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('input[name="total_in_words"]').val(numberToWords(totalAmt));
                    // Prevent the default form submission behavior
                    event.preventDefault();
                }
            });

            $('input[name="total_gross"]').on('blur', function() {
                var totalGrossRaw = $(this).val();
                var cleanedTotalGross = totalGrossRaw.replace(/,/g, '');
                var totalGross = parseFloat(cleanedTotalGross);
                let taxBase = totalGross;
                if($('#isVat').val() === 'True'){
                    taxBase = totalGross/1.12;
                }
                let tb1 = ($('#vatValue').val()/ 100)*taxBase;
                let pOjOTax = ($('#joValue').val() / 100) * taxBase;
                $('#tax_base_1').val(tb1.toFixed(2));
                $('#tax_base_2').val(pOjOTax.toFixed(2));
                let totalAmt = totalGross - (tb1 + pOjOTax);
                //$('input[name="total_gross"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('input[name="total"]').val(totalAmt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('input[name="total_in_words"]').val(numberToWords(totalAmt));
            });
        });

        function deleteRow(button) {
            const row = button.closest('tr');
            if (row) {
                const sixthTd = row.getElementsByTagName('td')[6]; // Get the 7th td element (index 5)
                const inputElement = sixthTd.querySelector('input'); // Find the input element within the td
                const value = inputElement.value;
                const sanitizedValue = value.replace(/,/g, '');

                let overAllTotal1 = $('input[name="total_gross"]').val();
                const overAllTotal1sanitizedValue = overAllTotal1.replace(/,/g, '');
                let overAllTotal = overAllTotal1sanitizedValue - sanitizedValue;
                let taxBase = overAllTotal;
                if($('#isVat').val() === 'True'){
                    taxBase = overAllTotal/1.12;
                }
                let tb1 = ($('#vatValue').val()/ 100)*taxBase;
                let pOjOTax = ($('#joValue').val() / 100) * taxBase;
                $('#tax_base_1').val(tb1.toFixed(2));
                $('#tax_base_2').val(pOjOTax.toFixed(2));
                let totalAmt = overAllTotal - (tb1 + pOjOTax);
                $('input[name="total_gross"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('input[name="total"]').val(totalAmt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('input[name="total_in_words"]').val(numberToWords(totalAmt));
                row.remove();
                updateSlugs(row.id);
            }
        }

        function updateSlugs(slug) {
            const slugsInput = document.getElementById('itemSlugEdit');
            let slugs = slugsInput.value.split('~');
            const index = slugs.indexOf(slug);

            if (index !== -1) {
                slugs.splice(index, 1);
                slugsInput.value = slugs.join('~');
            }
        }

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#jo_form');
            let uri = '{{route("dashboard.jo.storeManual")}}';
            loading_btn(form);
            $.ajax({
                type: 'POST',
                url: uri,
                data: form.serialize(),
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function(res) {
                    console.log(res);
                    toast('success','Successfully created.','Success!');
                    $('#printIframe').attr('src',res.route);
                    $('#trans_table tbody').remove();
                    $('#slug').val('');
                    $('#divRows').addClass('hidden');
                    $('#saveBtn').addClass('hidden');
                    $('#trans_table').addClass('hidden');
                    succeed(form,true,true);
                    Swal.fire({
                        title: 'Successfully created',
                        icon: 'success',
                        html:
                            'Click the print button below to print.',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText:
                            '<i class="fa fa-print"></i> Print',
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                        cancelButtonText:
                            'Dismiss',
                        cancelButtonAriaLabel: 'Thumbs down'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let link = "{{route('dashboard.jo.printManual','slug')}}";
                            link = link.replace('slug',res.slug);
                            window.open(link, '_blank');
                        }
                    })
                },
                error: function(res) {
                    // Display an alert with the error message
                    toast('error',res.responseJSON.message,'Error!');
                }
            });
        });

        $('select[name="supplier"]').change(function() {
            let uri = '{{route("dashboard.jo.findSupplier", ["slug"]) }}';
            uri = uri.replace('slug',$(this).val());
            $.ajax({
                url : uri,
                type: 'GET',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    $('input[name="supplier_address"]').val(res.supplier.address);
                    $('input[name="supplier_tin"]').val(res.supplier.tin);
                    $('input[name="supplier_representative"]').val(res.supplier.contact_person);
                    $('input[name="isVat"]').val(res.supplier.is_vat == 1?"True":"False");
                    $('input[name="isGovernment"]').val(res.supplier.is_government == 1?"True":"False");
                    $('input[name="vatValue"]').val(res.tax_computation.percent);
                    $('input[name="joValue"]').val(res.tcJO.percent);
                    console.log(res);
                },
                error: function (res) {
                    toast('error',res.responseJSON.message,'Error!');
                    console.log(res);
                }
            })
        });


    </script>
@endsection
