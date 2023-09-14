@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Requisition and Issue Slip</h1>
    </section>
@endsection
@section('content2')

    <section class="content col-md-12">

        <div role="document">
            <form id="add_form">

                <div class="box box-success">
                    <div class="box-body">

                        {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                           'label' => 'IAR No.:',
                           'cols' => 3,

                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('resp_center',[
                           'label' => 'Department/Division:',
                           'cols' => 3,
                          'id' => 'resp_center',
                        ]) !!}

{{--                        {!! \App\Swep\ViewHelpers\__form2::textbox('ris_no',[--}}
{{--                           'label' => 'RIS No.:',--}}
{{--                           'cols' => 3,--}}
{{--                          'id' => 'ris_no',--}}
{{--                        ]) !!}--}}

{{--                        {!! \App\Swep\ViewHelpers\__form2::textbox('ris_date',[--}}
{{--                            'label' => 'Date:',--}}
{{--                            'cols' => 3,--}}
{{--                            'type' => 'date',--}}
{{--                            'id' => 'ris_date',--}}
{{--                         ]) !!}--}}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('supplier',[
                               'label' => 'Supplier:',
                               'cols' => 3,
                              'id' => 'supplier',
                            ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::select('account_code',[
                                   'label' => 'Account Code:',
                                   'cols' => 3,
                                   'options' => \App\Swep\Helpers\Arrays::inventoryAccountCode(),
                                   'id' => 'inventory-account-code',
                               ]) !!}


{{--                        {!! \App\Swep\ViewHelpers\__form2::textbox('sai_no',[--}}
{{--                           'label' => 'SAI No.:',--}}
{{--                           'cols' => 3,--}}
{{--                          'id' => 'sai_no',--}}
{{--                        ]) !!}--}}

{{--                        {!! \App\Swep\ViewHelpers\__form2::textbox('sai_date',[--}}
{{--                          'label' => 'Date:',--}}
{{--                          'cols' => 3,--}}
{{--                          'type' => 'date',--}}
{{--                          'id' => 'sai_date',--}}
{{--                        ]) !!}--}}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('invoice_number',[
                            'label' => 'Invoice Number:',
                            'cols' => 3,
                            'id' => 'invoice_number',
                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('invoice_date',[
                           'label' => 'Invoice Date:',
                           'cols' => 3,
                           'type' => 'date',
                           'id' => 'invoice_date',
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('po_number',[
                            'label' => 'PO Number:',
                            'cols' => 3,
                            'id' => 'po_number',
                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('po_date',[
                           'label' => 'PO Date:',
                           'cols' => 3,
                           'type' => 'date',
                           'id' => 'po_date',
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textarea('purpose',[
                         'label' => 'Purpose:',
                         'cols' => 3,
                         'id' => 'purpose',
                       ]) !!}

                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">

                        <div class="" id="tableContainer" style="margin-top: 50px">
                            <table class="table table-bordered table-striped table-hover hidden" id="trans_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>Stock No.</th>
                                    <th>Unit</th>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Actual Qty</th>
                                    <th>Remarks</th>
                                    <th style="width: 3%"></th>
                                </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="pull-right">
                    <button type="button" class="btn btn-primary" id="saveBtn">Save</button>

                </div>

            </form>
        </div>

    </section>
@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">


        $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#add_form');
            let uri = '{{route("dashboard.ris.store")}}';
            loading_btn(form);

            $.ajax({
                url : uri,
                data: form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    console.log(res);
                    toast('success','RIS Successfully created.','Success!');
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
                            let link = "{{route('dashboard.ris.print','slug')}}";
                            link = link.replace('slug',res.slug);
                            window.open(link, '_blank');
                        }
                    })
                },
                error: function (res) {
                    toast('error',res.responseJSON.message,'Error!');
                }
            });


        });

        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove();
        }


        $('input[name="ref_number"]').unbind().bind('keyup', function(e) {
            if($('input[name="ref_number"]').val() === ''){
                toast('error','Reference Number cannot be empty','Invalid!');
            }
            else {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    let uri = '{{route("dashboard.ris.findTransByRefNumber", "refNumber") }}';
                    uri = uri.replace('refNumber',$(this).val());
                    $.ajax({
                        url : uri,
                        type: 'GET',
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success: function (res) {
                            console.log(res);

                            $("#resp_center").val(res.rc.department);
                            $("#supplier").val(res.trans.supplier);
                            $("#invoice_number").val(res.trans.invoice_number);
                            $("#invoice_date").val(res.trans.invoice_date);
                            $("#po_number").val(res.trans.po_number);
                            $("#po_date").val(res.trans.po_date);
                            $("#purpose").val(res.trans.purpose);


                            $('#trans_table tbody').remove();
                            let tableHtml = '<tbody>';
                            for(let i=0; i<res.transDetails.length; i++){
                                let stock = res.transDetails[i].stock_no;
                                stock = stock === null ? '' : stock;
                                let actual_qty = res.transDetails[i].actual_qty == null ? "" : res.transDetails[i].actual_qty;
                                let remarks = res.transDetails[i].remarks == null ? "" : res.transDetails[i].remarks;
                                tableHtml += '<tr id='+res.transDetails[i].slug+'>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][stock_no]" name="items['+res.transDetails[i].slug+'][stock_no]" type="text" value="' + stock + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][unit]" name="items['+res.transDetails[i].slug+'][unit]" type="text" value="' + res.transDetails[i].unit + '"></td>' +
                                    '<td><textarea class="input-sm" id="items['+res.transDetails[i].slug+'][description]" name="items['+res.transDetails[i].slug+'][description]" type="text">'+ res.transDetails[i].description +'</textarea></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][qty]" name="items['+res.transDetails[i].slug+'][qty]" type="text" value="' + res.transDetails[i].qty + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][actual_qty]" name="items['+res.transDetails[i].slug+'][actual_qty]" type="text" value="' + actual_qty + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][remarks]" name="items['+res.transDetails[i].slug+'][remarks]" type="text" value="' + remarks +'"></td>' +
                                    '<td><button type=\'button\' class=\'btn btn-danger btn-sm delete-btn\' data-slug='+res.transDetails[i].slug+' onclick="deleteRow(this)"><i class=\'fa fa-times\'></i></button></td>' +
                                    '</tr>';

                            }
                            tableHtml += '</tbody>';
                            $('#trans_table').append(tableHtml).removeClass('hidden');



                        },
                        error: function (res) {
                            toast('error',res.responseJSON.message,'Error!');
                        }
                    })
                }
            }
        });


    </script>
@endsection

