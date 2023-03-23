@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Request Cancellation</h1>
    </section>
@endsection
@section('content2')

    <section class="content col-md-12">
        <div class="box box-solid">
            <form id="rc_form">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    <input class="hidden" type="text" id="slug" name="slug"/>
                    {!! \App\Swep\ViewHelpers\__form2::textbox('ref_book',[
                                            'label' => 'Reference Book:',
                                            'cols' => 3,
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
                                <div class="" id="tableContainer">
                                    <table class="table table-bordered table-striped table-hover hidden" id="trans_table" style="width: 100% !important">
                                        <thead>
                                        <tr class="">
                                            <th>Reference Type</th>
                                            <th>Reference No.</th>
                                            <th>Reference Date (dd/mm/yyyy)</th>
                                            <th>ABC</th>
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

        $('input[name="ref_number"]').unbind().bind('keyup', function(e) {
            let refBook = $('input[name="ref_book"]').val();
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

                        let tableHtml = '<tbody>';
                            tableHtml += '<tr><td>' + res.ref_book + '</td><td>' + res.ref_no + '</td><td>' + $.datepicker.formatDate('dd/mm/yy', new Date(res.date)) + '</td><td>' + res.abc + '</td></tr>';
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
        });
    </script>
@endsection
