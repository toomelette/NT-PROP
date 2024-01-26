@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Trip Ticket</h1>
    </section>
@endsection
@section('content2')

<section class="content col-md-12">
    <div role="document">
        <form id="add_form">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Trip Ticket</h3>
                    <button class="btn btn-primary btn-sm pull-right" id="saveBtn" type="button">
                        <i class="fa fa-check"></i> Save
                    </button>
                    <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.trip_ticket.index')}}">Back</a>
                </div>
                    <div class="box-body">

                        {!! \App\Swep\ViewHelpers\__form2::textbox('request_no',[
                         'label' => 'Request Number',
                         'cols' => 3,
                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('driver',[
                            'label' => 'Driver:',
                            'cols' => 3,
                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('vehicle',[
                           'label' => 'Vehicle:',
                           'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('passengers',[
                       'label' => 'Passengers:',
                       'cols' => 3,
                        ]) !!}


                        {!! \App\Swep\ViewHelpers\__form2::textbox('purpose',[
                        'label' => 'Purpose:',
                        'cols' => 3,
                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('destination',[
                          'label' => 'Destination:',
                          'cols' => 3,
                         ]) !!}


                        {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                          'label' => 'Date:',
                          'cols' => 3,
                          'type' => 'date',
                         ]) !!}

                    </div>
                </div>

            <div class="box box-success">
                <div class="box-body">

                    {!! \App\Swep\ViewHelpers\__form2::textbox('departure',[
                      'label' => 'Departure',
                      'cols' => 3,
                      'type' => 'date',
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('return',[
                      'label' => 'Return',
                      'cols' => 3,
                      'type' => 'date',
                    ]) !!}


                </div>
            </div>

            <div class="box box-success">
                <div class="box-body">

                    {!! \App\Swep\ViewHelpers\__form2::textbox('gas_balance',[
                      'label' => 'Gas Balance (L)',
                      'cols' => 2,
                      'type' => 'number',

                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('gas_issued',[
                      'label' => 'Gas Issued (L)',
                      'cols' => 2,
                      'type' => 'number',
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('purchased',[
                      'label' => 'Purchased during trip (L)',
                      'cols' => 2,
                      'type' => 'number',
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('total',[
                     'label' => 'TOTAL (L)',
                     'cols' => 2,
                     'type' => 'number',
                   ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('consumed',[
                     'label' => 'Consumed (L)',
                     'cols' => 2,
                     'type' => 'number',
                   ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('gas_remaining_balance',[
                     'label' => 'Remaining Balance (L)',
                     'cols' => 2,
                     'type' => 'number',
                   ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('odometer_from',[
                      'label' => 'Gas Balance (L)',
                      'cols' => 2,
                      'type' => 'number',

                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('gas_issued',[
                      'label' => 'Gas Issued (L)',
                      'cols' => 2,
                      'type' => 'number',
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('purchased',[
                      'label' => 'Purchased during trip (L)',
                      'cols' => 2,
                      'type' => 'number',
                    ]) !!}


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



    </script>
@endsection

