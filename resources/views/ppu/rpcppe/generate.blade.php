@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Generate RPCPPE</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <div class="box box-solid">

            <form id="rpci_form" action="{{route('dashboard.rpcppe.printRpcppe')}}">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                        <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                    </div>
                    {!! \App\Swep\ViewHelpers\__form2::select('fund_cluster',[
                    'id' => 'fund_cluster',
                    'label' => 'Fund Cluster:',
                    'cols' => 3,
                    'options' => \App\Swep\Helpers\Arrays::fundSources(),
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('as_of',[
                            'id' => 'as_of',
                           'label' => 'As of:',
                           'cols' => 3,
                           'type' => 'date',
                        ]) !!}

                    <div id="dateRangeDiv" class="hidden">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('date_start',[
                            'id' => 'date_start',
                           'label' => 'Date Start:',
                           'cols' => 3,
                           'type' => 'date',
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('date_end',[
                            'id' => 'date_end',
                           'label' => 'Date End:',
                           'cols' => 3,
                           'type' => 'date',
                        ]) !!}
                    </div>

                    <div class="clearfix"></div>

                    <div class="form-group col-md-4 period_covered">
                        <input id="period_covered" name="period_covered" type="checkbox" autocomplete="">
                        <label for="period_covered">Generate for a specific period</label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="box-footer pull-left">
                        <button class="btn btn-primary btn-md print" type="submit">
                            <i class="fa fa-print"></i> Print
                        </button>
                        <button class="btn btn-success btn-md downloadCsv" type="button">
                            <i class="fa fa-download"></i> Download Excel
                        </button>
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
            $('.downloadCsv').click(function (){
                var formData = $('#rpci_form').serialize();
                $.ajax({
                    url: "{{route('dashboard.rpcppe.printRpcppeExcel')}}",
                    type: 'GET',
                    data: formData,
                    success: function(data) {
                        console.log('Success:', data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });

            // Add change event listener to the checkbox
            $('#period_covered').change(function() {
                // Check if checkbox is checked
                if ($(this).is(':checked')) {
                    // Remove the 'hidden' class to show the dateRangeDiv
                    $('#dateRangeDiv').removeClass('hidden');
                    $('.as_of').addClass('hidden');
                } else {
                    // Add the 'hidden' class to hide the dateRangeDiv
                    $('#dateRangeDiv').addClass('hidden');
                    $('.as_of').removeClass('hidden');
                }
            });

            $('#as_of').val(new Date().toISOString().slice(0, 10));
            let href = "{{ route('dashboard.rpcppe.printRpcppe', ['fund_cluster', 'as_of']) }}";
            href = href.replace('fund_cluster', 'all');
            href = href.replace('as_of', $('#as_of').val());
            $('a.btn').attr('href', href);

            $('#fund_cluster, #as_of').change(function() {
                let href = "{{ route('dashboard.rpcppe.printRpcppe', ['fund_cluster', 'as_of']) }}";
                href = href.replace('fund_cluster', $('#fund_cluster').val() || "all");
                href = href.replace('as_of', $('#as_of').val());
                $('a.btn').attr('href', href);
            });
        });

    </script>
@endsection
