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
                    {!! \App\Swep\ViewHelpers\__form2::select('ref_book', [
                                        'label' => 'Reference Type:',
                                        'cols' => 2,
                                        'options' => [
                                            'PR' => 'PR',
                                            'JR' => 'JR'
                                        ]
                                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                                            'label' => 'Reference Number:',
                                            'cols' => 3,
                                        ]) !!}

                    <div class="clearfix"></div>

                    <div id="content" class="hidden">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('po_no',[
                                            'label' => 'P.O. No.:',
                                            'cols' => 4,
                                            'readonly' => 'readonly',
                                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('po_date',[
                                'label' => 'Date:',
                                'cols' => 4,
                                'type' => 'date',
                                'required' => 'required'
                             ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('mode',[
                                            'label' => 'Mode of Procurement:',
                                            'cols' => 4,
                                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('supplier',[
                                            'label' => 'Supplier:',
                                            'cols' => 4,
                                        ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_address',[
                                                                    'label' => 'Address:',
                                                                    'cols' => 4,
                                                                ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_tin',[
                                                                     'label' => 'TIN:',
                                                                     'cols' => 4,
                                                                 ]) !!}

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="" id="tableContainer" style="margin-top: 50px">
                                        <table class="table table-bordered table-striped table-hover hidden" id="trans_table" style="width: 100% !important">
                                            <thead>
                                            <tr class="">
                                                <th>Reference Type</th>
                                                <th>Reference No.</th>
                                                <th>Reference Date (dd/mm/yyyy)</th>
                                                <th>ABC</th>
                                                <th>Requested By</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer pull-right">
                        <button type="button" class="btn btn-primary hidden" id="saveBtn">Save</button>
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

        /*$('#saveBtn').click(function(e) {
            //let refBook = $('select[name="ref_book"]').val();
            if($('input[name="ref_number"]').val() === ''){
                toast('error','Reference Number cannot be empty','Invalid!');
            }
            else if ($('input[name="reason"]').val() === ''){
                toast('error','Reason cannot be empty','Invalid!');
            }
            else {
                e.preventDefault();
                let form = $('#rc_form');
                loading_btn(form);
                $.ajax({
                    type: 'POST',
                    url: '{{route("dashboard.purchaseOrder.store")}}',
                    data: form.serialize(),
                    headers: {
                        {!! __html::token_header() !!}
                    },
                    success: function(res) {
                        console.log(res);
                        $('#printIframe').attr('src',res.route);
                        $('#saveBtn').addClass('hidden');
                        $('#trans_table tbody').remove();
                        $('#trans_table').addClass('hidden');
                        form.find('input, select, textarea').val('');
                        toast('success','Request successful.','Success!');
                    },
                    error: function(res) {
                        // Display an alert with the error message
                        toast('error',res.responseJSON.message,'Error!');
                    }
                });
            }
        });*/

        $('input[name="ref_number"]').unbind().bind('keyup', function(e) {
            if($('input[name="ref_number"]').val() === ''){
                toast('error','Reference Number cannot be empty','Invalid!');
            }
            else {
                let refBook = $('select[name="ref_book"]').val();
                if (e.keyCode === 13) {
                    let uri = '{{route("dashboard.purchaseOrder.findRefNumber", ["refNumber", "refBook"]) }}';
                    uri = uri.replace('refNumber',$(this).val());
                    uri = uri.replace('refBook',refBook);
                    $.ajax({
                        url : uri,
                        type: 'GET',
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success: function (res) {
                            $('#content').removeClass('hidden');
                            //$('#saveBtn').removeClass('hidden');
                            $('#trans_table tbody').remove();
                            $('#slug').val(res[0].slug);

                            $('input[name="supplier"]').val(res[1].awardee);
                            $('input[name="supplier_address"]').val(res[1].awardee_address);
                            $('input[name="supplier_tin"]').val(res[1].awardee_tin);

                            $('input[name="po_no"]').val(res[2]);

                            let num = parseFloat(res[0].abc);
                            let tableHtml = '<tbody>';
                            tableHtml += '<tr><td>' + res[0].ref_book + '</td><td>' + res[0].ref_no + '</td><td>' + $.datepicker.formatDate('dd/mm/yy', new Date(res[0].date)) + '</td><td>' + num.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td>' + res[0].requested_by + '</td></tr>';
                            tableHtml += '</tbody></table>';

                            $('#trans_table').append(tableHtml).removeClass('hidden');
                            console.log(res);
                        },
                        error: function (res) {
                            toast('error',res.responseJSON.message,'Error!');
                            /*$('#content').addClass('hidden');
                            $('#saveBtn').addClass('hidden');
                            $('#trans_table tbody').remove();
                            $('#trans_table').addClass('hidden');*/
                            console.log(res);
                        }
                    })
                }
            }
        });
    </script>
@endsection
