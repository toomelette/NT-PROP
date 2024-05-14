@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Generate RPCPPE</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <div class="box box-solid">

            <form id="rsepi_form" action="{{route('dashboard.rsepi.printRsepi')}}">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                        <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                    </div>
                    {!! \App\Swep\ViewHelpers\__form2::select('view',[
                        'id' => 'view',
                        'label' => 'Layout:',
                        'cols' => 3,
                        'options' => [
                            'per_employee' => 'Per Employee',
                            'per_account_code' => 'Per Account Code',
                        ],
                        'required' => 'required',
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::select('fund_cluster',[
                    'id' => 'fund_cluster',
                    'label' => 'Fund Cluster:',
                    'cols' => 3,
                    'options' => \App\Swep\Helpers\Arrays::fundSources(),
                    ]) !!}

{{--                    {!! \App\Swep\ViewHelpers\__form2::select('condition',[--}}
{{--                    'id' => 'condition',--}}
{{--                    'label' => 'Condition:',--}}
{{--                    'cols' => 3,--}}
{{--                    'options' => \App\Swep\Helpers\Arrays::condition(),--}}
{{--                    ]) !!}--}}

                    {!! \App\Swep\ViewHelpers\__form2::select('employee_no',[
                    'id' => 'employee_no',
                    'label' => 'Employee Number',
                    'cols' => 3,
                    'options' => \App\Swep\Helpers\Arrays::employee(),
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
{{--                        <button class="btn btn-success btn-md downloadCsv" type="button">--}}
{{--                            <i class="fa fa-download"></i> Download Excel--}}
{{--                        </button>--}}
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

        {{--$('#as_of').val(new Date().toISOString().slice(0, 10));--}}
        {{--let href = "{{ route('dashboard.rpcppe.printRpcppe', ['fund_cluster', 'as_of']) }}";--}}
        {{--href = href.replace('fund_cluster', 'all');--}}
        {{--href = href.replace('as_of', $('#as_of').val());--}}
        {{--$('a.btn').attr('href', href);--}}

        {{--$('#fund_cluster, #as_of').change(function() {--}}
        {{--    let href = "{{ route('dashboard.rpcppe.printRpcppe', ['fund_cluster', 'as_of']) }}";--}}
        {{--    href = href.replace('fund_cluster', $('#fund_cluster').val() || "all");--}}
        {{--    href = href.replace('as_of', $('#as_of').val());--}}
        {{--    $('a.btn').attr('href', href);--}}
        {{--});--}}

            $('#as_of').val(new Date().toISOString().slice(0, 10));
            updateHref();

            $('#fund_cluster, #as_of, #condition').change(function() {
                updateHref();
            });

            function updateHref() {
                let href = "{{ route('dashboard.rpcppe.printRpcppe', ['fund_cluster', 'as_of', 'condition']) }}";
                href = href.replace('fund_cluster', $('#fund_cluster').val() || "all");
                href = href.replace('as_of', $('#as_of').val());
                // href = href.replace('condition', $('#condition').val() || "all");
                $('a.btn').attr('href', href);
            }

        });

            $("#employee_no").select2();

    </script>
@endsection
