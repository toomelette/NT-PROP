@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Generate Fuel Consumption Report</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <div class="box box-solid">
            <form id="ttr_form" action="{{route('dashboard.ttr.printReport')}}">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                        <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                    </div>
{{--                    {!! \App\Swep\ViewHelpers\__form2::select('driver',[--}}
{{--                    'id' => 'drivers',--}}
{{--                    'label' => 'Driver',--}}
{{--                    'cols' => 3,--}}
{{--                    'options' => \App\Swep\Helpers\Arrays::drivers(),--}}
{{--                    ]) !!}--}}

                    {!! \App\Swep\ViewHelpers\__form2::select('vehicle',[
                    'id' => 'vehicles',
                    'label' => 'Vehicle',
                    'cols' => 3,
                    'options' => \App\Swep\Helpers\Arrays::vehicles(),
                    ]) !!}


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
                <div class="box-footer pull-left">
                    <button class="btn btn-primary btn-md" type="submit">
                        <i class="fa fa-print; "></i> Print
                    </button>
                </div>

                    <div class="clearfix"></div>


            </form>


        </div>
    </section>

@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            // Add change event listener to the checkbox
            // $('#period_covered').change(function() {
            //     // Check if checkbox is checked
            //     if ($(this).is(':checked')) {
            //         // Remove the 'hidden' class to show the dateRangeDiv
            //         $('#dateRangeDiv').removeClass('hidden');
            //         $('.as_of').addClass('hidden');
            //     } else {
            //         // Add the 'hidden' class to hide the dateRangeDiv
            //         $('#dateRangeDiv').addClass('hidden');
            //         $('.as_of').removeClass('hidden');
            //     }
            // });
        });
    </script>
@endsection
