@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Edit Job Order</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <div class="box box-solid">
            <form id="jo_form">
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                        <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                    </div>
                    <input class="hidden" type="text" id="refBook" name="refBook"/>
                    <input class="hidden" type="text" id="slug" name="slug" value="{{$slug}}"/>
                    <input class="hidden" type="text" id="itemSlugEdit" name="itemSlugEdit"/>
                    <input class="hidden" type="text" id="isVat" name="isVat"/>
                    <input class="hidden" type="text" id="isGovernment" name="isGovernment"/>
                    <div class="clearfix"></div>
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
                    {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                                'label' => 'JO Date:',
                                'cols' => 3,
                                'type' => 'date',
                                'required' => 'required'
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
                                    ],
                    $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('delivery_date',[
                                'label' => 'Date of Delivery:',
                                'cols' => 3,
                                'type' => 'date'
                             ],
                    $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('delivery_term',[
                                         'label' => 'Delivery Term:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ],
                    $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('payment_term',[
                                         'label' => 'Payment Term:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ],
                    $order ?? null) !!}
                    {!! \App\Swep\ViewHelpers\__form2::select('authorized_official', [
                                            'label' => 'Authorized Official:',
                                            'cols' => 3,
                                            'options' => [
                                                'ATTY. JOHANA S. JADOC' => 'ATTY. JOHANA S. JADOC',
                                                'HELEN B. LOBATON' => 'HELEN B. LOBATON',
                                                'WILFREDO R. MONARES' => 'WILFREDO R. MONARES'
                                            ]
                                        ],
                    $order ?? null) !!}
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
                                <input class="form-control" name="joValue" id="joValue" type="text" value="{{$order->withholding_tax}}" placeholder="" autocomplete="" required="">
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
                                        ], $order ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('total',[
                                            'label' => 'Total:',
                                            'cols' => 2,
                                            'required' => 'required'
                                        ], $order ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('total_in_words',[
                                            'label' => 'Total in words:',
                                            'cols' => 8,
                                            'required' => 'required'
                                        ], $order ?? null) !!}
                            <div class="row" id="tableContainer" style="margin-top: 50px">
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
                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary" id="saveBtn">Update</button>
                                </div>
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
            let uri = '{{route("dashboard.jo.update","slug")}}';
            uri = uri.replace('slug',$('#slug').val());
            loading_btn(form);
            $.ajax({
                type: 'PATCH',
                url: uri,
                data: form.serialize(),
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function(res) {
                    console.log(res);
                    toast('success','Successfully Updated.','Success!');
                    $('#printIframe').attr('src',res.route);
                    succeed(form,true,true);
                    Swal.fire({
                        title: 'Successfully Updated',
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
                            let link = "{{route('dashboard.jo.print','slug')}}";
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
    </script>
@endsection
