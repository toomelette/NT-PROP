@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Create Purchase Order</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <div class="box box-solid">
            <form id="po_form">
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
                    {!! \App\Swep\ViewHelpers\__form2::textbox('po_number',[
                                            'label' => 'PO Number:',
                                            'cols' => 3
                                        ],
                                        $po_number ?? null
                                        ) !!}
                    {!! \App\Swep\ViewHelpers\__form2::select('mode', [
                                            'label' => 'Mode of Procurement:',
                                            'cols' => 3,
                                            'options' => \App\Swep\Helpers\Arrays::ModeOfProcurement(),
                                        ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                                'label' => 'PO Date:',
                                'cols' => 3,
                                'type' => 'date',
                                'required' => 'required'
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

                    {{--{!! \App\Swep\ViewHelpers\__form2::textbox('authorized_official',[
                                         'label' => 'Authorized Official:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ],
                                    \App\Swep\Helpers\Helper::getSetting('po_authorized_official')->string_value ?? null
                                    ) !!}--}}
                    {!! \App\Swep\ViewHelpers\__form2::select('authorized_official', [
                                            'label' => 'Authorized Official:',
                                            'cols' => 3,
                                            'options' => \App\Swep\Helpers\Arrays::AuthorizedOfficial(),
                                        ]) !!}
                    {{--{!! \App\Swep\ViewHelpers\__form2::textbox('authorized_official_designation',[
                                         'label' => 'Designation:',
                                         'cols' => 3,
                                         'required' => 'required'
                                     ],
                                    \App\Swep\Helpers\Helper::getSetting('po_authorized_official_designation')->string_value ?? null
                                    ) !!}--}}
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

                    {{--{!! \App\Swep\ViewHelpers\__form2::select('ref_book', [
                                        'label' => 'Reference Type:',
                                        'cols' => 2,
                                        'options' => [
                                            'PR' => 'PR',
                                            'JR' => 'JR'
                                        ],
                                    ]) !!}--}}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                                            'label' => 'RFQ Reference Number:',
                                            'cols' => 3,
                                            'required' => 'required'
                                        ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
                                            'label' => 'Remarks:',
                                            'cols' => 6
                                        ]) !!}
                    <div class="clearfix"></div>
                    <div class="row hidden" id="divRows">
                        {{--<input class="hidden" type="text" id="vatValue" name="vatValue"/>
                        <input class="hidden" type="text" id="poValue" name="poValue"/>
                        <input class="hidden" type="text" id="tax_base_1" name="tax_base_1"/>
                        <input class="hidden" type="text" id="tax_base_2" name="tax_base_2"/>--}}
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
                                <input class="form-control" name="poValue" id="poValue" type="text" value="" placeholder="" autocomplete="" required="">
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
                                <table class="table table-bordered table-striped table-hover hidden" id="trans_table" style="width: 100% !important">
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
                                </table>
                            </div>
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary hidden" id="saveBtn">Save</button>
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
                words += ' AND ' + convertTwoDigitNumber(decimalPart) + ' CENTS';
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
                    let pOjOTax = ($('#poValue').val() / 100) * taxBase;
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
                let pOjOTax = ($('#poValue').val() / 100) * taxBase;
                $('#tax_base_1').val(tb1.toFixed(2));
                $('#tax_base_2').val(pOjOTax.toFixed(2));
                let totalAmt = totalGross - (tb1 + pOjOTax);
                //$('input[name="total_gross"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('input[name="total"]').val(totalAmt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('input[name="total_in_words"]').val(numberToWords(totalAmt));
            });

            $('input[name="poValue"]').on('keypress', function(event) {
                if (event.which === 13) { // Check if Enter key is pressed
                    var totalGrossRaw = $('input[name="total_gross"]').val();
                    var cleanedTotalGross = totalGrossRaw.replace(/,/g, '');
                    var totalGross = parseFloat(cleanedTotalGross);
                    let taxBase = totalGross;
                    if($('#isVat').val() === 'True'){
                        taxBase = totalGross/1.12;
                    }
                    let tb1 = ($('#vatValue').val()/ 100)*taxBase;
                    let pOjOTax = ($('#poValue').val() / 100) * taxBase;
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

            $('input[name="poValue"]').on('blur', function() {
                var totalGrossRaw = $('input[name="total_gross"]').val();
                var cleanedTotalGross = totalGrossRaw.replace(/,/g, '');
                var totalGross = parseFloat(cleanedTotalGross);
                let taxBase = totalGross;
                if($('#isVat').val() === 'True'){
                    taxBase = totalGross/1.12;
                }
                let tb1 = ($('#vatValue').val()/ 100)*taxBase;
                let pOjOTax = ($('#poValue').val() / 100) * taxBase;
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
                    let pOjOTax = ($('#poValue').val() / 100) * taxBase;
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
                let pOjOTax = ($('#poValue').val() / 100) * taxBase;
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
                let pOjOTax = ($('#poValue').val() / 100) * taxBase;
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
            let form = $('#po_form');
            let uri = '{{route("dashboard.po.store")}}';
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
                            let link = "{{route('dashboard.po.print1','slug')}}";
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
            let uri = '{{route("dashboard.po.findSupplier", ["slug"]) }}';
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
                    $('input[name="poValue"]').val(res.tcPO.percent);
                    console.log(res);
                },
                error: function (res) {
                    toast('error',res.responseJSON.message,'Error!');
                    console.log(res);
                }
            })
        });

        $('input[name="ref_number"]').unbind().bind('keyup', function(e) {
            if($('input[name="ref_number"]').val() === ''){
                toast('error','Reference Number cannot be empty','Invalid!');
            }
            else {
                //let refBook = $('select[name="ref_book"]').val();
                let supplier = $('select[name="supplier"]').val();
                if (e.keyCode === 13) {
                    let uri = '{{route("dashboard.po.findTransByRefNumber", ["refNumber", "refBook", "add", "id"]) }}';
                    uri = uri.replace('refNumber',$(this).val());
                    //uri = uri.replace('refBook',refBook);
                    uri = uri.replace('id',supplier);
                    $.ajax({
                        url : uri,
                        type: 'GET',
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success: function (res) {
                            $('#saveBtn').removeClass('hidden');
                            $('#divRows').removeClass('hidden');
                            $('#trans_table tbody').remove();
                            $('#slug').val(res.trans.slug);
                            let slugs = '';
                            let tableHtml = '<tbody>';
                            let overAllTotal = 0;
                            for(let i=0; i<res.transDetails.length; i++){
                                //let num1 = parseFloat(res.transDetails[i].unit_cost);
                                //let num2 = parseFloat(res.transDetails[i].total_cost);
                                //num1 = isNaN(num1) ? 0 : num1;
                                //num2 = isNaN(num2) ? 0 : num2;
                                let stock = res.transDetails[i].stock_no;
                                stock = stock === null ? '' : stock;
                                slugs += res.transDetails[i].slug + '~';
                                let aqTotalCost = 0;
                                let aqUnitCost = 0;
                                let offerDetails = "";
                                for (const aqd of res.aqOfferDetails) {
                                    if(aqd.item_slug === res.transDetails[i].slug){
                                        aqUnitCost = parseFloat(aqd.amount);
                                        offerDetails = aqd.description;
                                    }
                                }
                                //aqUnitCost = parseFloat(aqTotalCost * res.transDetails[i].qty);
                                aqTotalCost = isNaN(aqUnitCost) ? 0 : aqUnitCost * res.transDetails[i].qty;
                                aqUnitCost = isNaN(aqUnitCost) ? 0 : aqUnitCost;
                                overAllTotal += aqTotalCost;
                                let propNo = res.transDetails[i].property_no == null ? "" : res.transDetails[i].property_no;
                                let natureOfWork = res.transDetails[i].nature_of_work == null ? "" : res.transDetails[i].nature_of_work;
                                tableHtml += '<tr id='+res.transDetails[i].slug+'>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][stock_no]" name="items['+res.transDetails[i].slug+'][stock_no]" type="text" value="' + stock + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][unit]" name="items['+res.transDetails[i].slug+'][unit]" type="text" value="' + res.transDetails[i].unit + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][item]" name="items['+res.transDetails[i].slug+'][item]" type="text" value="' +  res.transDetails[i].item + '"></td>' +
                                    '<td><textarea class="input-sm" id="items['+res.transDetails[i].slug+'][description]" name="items['+res.transDetails[i].slug+'][description]" type="text">'+ offerDetails +'</textarea></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][qty]" name="items['+res.transDetails[i].slug+'][qty]" type="text" value="' + res.transDetails[i].qty + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][unit_cost]" name="items['+res.transDetails[i].slug+'][unit_cost]" type="text" value="' + aqUnitCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][total_cost]" name="items['+res.transDetails[i].slug+'][total_cost]" type="text" value="' + aqTotalCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][property_no]" name="items['+res.transDetails[i].slug+'][property_no]" type="text" value="' + propNo + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][nature_of_work]" name="items['+res.transDetails[i].slug+'][nature_of_work]" type="text" value="' + natureOfWork + '"></td>' +
                                    '<td><button type=\'button\' class=\'btn btn-danger btn-sm delete-btn\' data-slug='+res.transDetails[i].slug+' onclick="deleteRow(this)"><i class=\'fa fa-times\'></i></button></td>' +
                                    '</tr>';

                            }
                            $('#refBook').val(res.trans.ref_book);
                            slugs = slugs.slice(0, -1); // Remove the last '~' character
                            $('#itemSlugEdit').val(slugs);
                            tableHtml += '</tbody>';
                            if($('#isGovernment').val() === 'True'){
                                $('input[name="total_gross"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                                $('input[name="total"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                                $('input[name="total_in_words"]').val(numberToWords(totalAmt));
                            }
                            else {
                                let taxBase = overAllTotal;
                                if($('#isVat').val() === 'True'){
                                    taxBase = overAllTotal/1.12;
                                }
                                let tb1 = ($('#vatValue').val()/ 100)*taxBase;
                                let pOjOTax = ($('#poValue').val() / 100) * taxBase;
                                $('#tax_base_1').val(tb1.toFixed(2));
                                $('#tax_base_2').val(pOjOTax.toFixed(2));
                                let totalAmt = overAllTotal - (tb1 + pOjOTax);
                                $('input[name="total_gross"]').val(overAllTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                                $('input[name="total"]').val(totalAmt.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                                $('input[name="total_in_words"]').val(numberToWords(totalAmt));
                            }
                            $('#trans_table').append(tableHtml).removeClass('hidden');
                            console.log(res);
                        },
                        error: function (res) {
                            toast('error',res.responseJSON.message,'Error!');
                            $('#divRows').addClass('hidden');
                            $('#saveBtn').addClass('hidden');
                            $('#trans_table tbody').remove();
                            $('#trans_table').addClass('hidden');
                            console.log(res);
                        }
                    })
                }
            }
        });
    </script>
@endsection
