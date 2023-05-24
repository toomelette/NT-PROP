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
                    <input class="hidden" type="text" id="slug" name="slug"/>
                    {!! \App\Swep\ViewHelpers\__form2::textbox('mode',[
                                            'label' => 'Mode of Procurement:',
                                            'cols' => 3,
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
                                     ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('delivery_date',[
                                'label' => 'Date of Delivery:',
                                'cols' => 3,
                                'type' => 'date',
                                'required' => 'required'
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



                    {!! \App\Swep\ViewHelpers\__form2::select('ref_book', [
                                        'label' => 'Reference Type:',
                                        'cols' => 2,
                                        'options' => [
                                            'PR' => 'PR',
                                            'JR' => 'JR'
                                        ],
                                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                                            'label' => 'Reference Number:',
                                            'cols' => 3,
                                            'required' => 'required'
                                        ]) !!}
                    <div class="row hidden" id="divRows">
                        <div class="col-md-12">
                            <div class="" id="tableContainer" style="margin-top: 50px">
                                <table class="table table-bordered table-striped table-hover hidden" id="trans_table" style="width: 100% !important">
                                    <thead>
                                    <tr class="">
                                        <th>Stock No.</th>
                                        <th>Unit</th>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Unit Cost</th>
                                        <th>Total Cost</th>
                                        <th width="3%"></th>
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
        $(document).ready(function() {

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
                    $('input[name="supplier_address"]').val(res.address);
                    $('input[name="supplier_tin"]').val(res.tin);
                    $('input[name="supplier_representative"]').val(res.contact_person);
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
                let refBook = $('select[name="ref_book"]').val();
                if (e.keyCode === 13) {
                    let uri = '{{route("dashboard.po.findTransByRefNumber", ["refNumber", "refBook", "add", "id"]) }}';
                    uri = uri.replace('refNumber',$(this).val());
                    uri = uri.replace('refBook',refBook);
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
                            for(let i=0; i<res.transDetails.length; i++){
                                let num1 = parseFloat(res.transDetails[i].unit_cost);
                                let num2 = parseFloat(res.transDetails[i].total_cost);
                                num1 = isNaN(num1) ? 0 : num1;
                                num2 = isNaN(num2) ? 0 : num2;
                                let stock = res.transDetails[i].stock_no;
                                stock = stock === null ? '' : stock;
                                slugs += res.transDetails[i].slug + '~';
                                tableHtml += '<tr id='+res.transDetails[i].slug+'><td>' + stock + '</td><td>' + res.transDetails[i].unit + '</td><td>' + res.transDetails[i].item + '</td><td>' + res.transDetails[i].qty + '</td><td>' + num1.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td>' + num2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td><button type=\'button\' class=\'btn btn-danger btn-sm delete-btn\' data-slug='+res.transDetails[i].slug+' onclick="deleteRow(this)"><i class=\'fa fa-times\'></i></button></td></tr>';
                            }
                            tableHtml += '</tbody></table>';
                            slugs = slugs.slice(0, -1); // Remove the last '~' character
                            $('#itemSlug').val(slugs);

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
