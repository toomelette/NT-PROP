@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Cancellation Request</h1>
    </section>
@endsection
@section('content2')


    <section class="content col-md-12">
        <div class="box box-solid">

            <form id="rc_form">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                        <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                    </div>
                    <input class="hidden" type="text" id="slug" name="slug"/>
                    {!! \App\Swep\ViewHelpers\__form2::select('ref_book', [
                                        'label' => 'Reference Type:',
                                        'cols' => 3,
                                        'options' => [
                                            'PR' => 'PR',
                                            'JR' => 'JR'
                                        ]
                                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                                            'label' => 'Reference Number:',
                                            'cols' => 3,
                                        ]) !!}

                    <div class="hidden" id="divReason">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('reason',[
                                             'label' => 'Reason:',
                                             'cols' => 6,
                                         ]) !!}
                    </div>

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

        $('#saveBtn').click(function(e) {
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
                    url: '{{route("dashboard.cancellationRequest.store")}}',
                    data: form.serialize(),
                    headers: {
                        {!! __html::token_header() !!}
                    },
                    success: function(res) {
                        console.log(res);
                        $('#printIframe').attr('src',res.route);
                        $('#divReason').addClass('hidden');
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
        });

        $('input[name="ref_number"]').unbind().bind('keyup', function(e) {
            if($('input[name="ref_number"]').val() === ''){
                toast('error','Reference Number cannot be empty','Invalid!');
            }
            else {
                let refBook = $('select[name="ref_book"]').val();
                if (e.keyCode === 13) {
                    let uri = '{{route("dashboard.cancellationRequest.ByRefNumber", ["refNumber", "refBook"]) }}';
                    uri = uri.replace('refNumber',$(this).val());
                    uri = uri.replace('refBook',refBook);
                    $.ajax({
                        url : uri,
                        type: 'GET',
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success: function (res) {
                            $('#divReason').removeClass('hidden');
                            $('#saveBtn').removeClass('hidden');
                            $('#trans_table tbody').remove();
                            $('#slug').val(res.slug);

                            let num = parseFloat(res.abc);
                            let tableHtml = '<tbody>';
                            tableHtml += '<tr><td>' + res.ref_book + '</td><td>' + res.ref_no + '</td><td>' + $.datepicker.formatDate('dd/mm/yy', new Date(res.date)) + '</td><td>' + num.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td>' + res.requested_by + '</td></tr>';
                            tableHtml += '</tbody></table>';

                            $('#trans_table').append(tableHtml).removeClass('hidden');
                            console.log(res);
                        },
                        error: function (res) {
                            toast('error',res.responseJSON.message,'Error!');
                            $('#divReason').addClass('hidden');
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
