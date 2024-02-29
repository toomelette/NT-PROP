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

                        {!! \App\Swep\ViewHelpers\__form2::select('driver',[
                            'label' => 'Driver:',
                            'cols' => 3,
                            'options' => \App\Swep\Helpers\Arrays::drivers(),
                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::select('vehicle',[
                           'label' => 'Vehicle:',
                           'cols' => 3,
                           'options' => \App\Swep\Helpers\Arrays::vehicles(),
                           'class' => 'vehicle1'
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
                         'label' => "<span style='color: red;'>Date: (REQUIRED)</span>",
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
                      'type' => 'datetime-local',
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('return',[
                      'label' => 'Return',
                      'cols' => 3,
                      'type' => 'datetime-local',
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                     'label' => 'Approved By:',
                     'cols' => 3,
                    ],
                    \App\Swep\Helpers\Helper::getSetting('tt_approved_by')->string_value ?? null
                     ) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                      'label' => 'Designation:',
                      'cols' => 3,
                      ],
                    \App\Swep\Helpers\Helper::getSetting('tt_approved_by_designation')->string_value ?? null
                     ) !!}


                </div>
            </div>

            <div class="box box-success">
                <div class="box-body">

                    {!! \App\Swep\ViewHelpers\__form2::textbox('gas_balance',[
                      'label' => 'Balance in Tank (L)',
                      'cols' => 3,
                      'type' => 'number',
                      'class' => 'gas_liter'
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('gas_issued',[
                      'label' => 'Gas Issued (L)',
                      'cols' => 3,
                      'type' => 'number',
                      'class' => 'gas_liter'
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('purchased',[
                      'label' => 'Purchased during trip (L)',
                      'cols' => 3,
                      'type' => 'number',
                      'class' => 'gas_liter'
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('total',[
                     'label' => 'TOTAL (L)',
                     'cols' => 3,
                     'type' => 'number',
                   ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('consumed',[
                     'label' => 'Consumed (L)',
                     'cols' => 3,
                     'type' => 'number',
                     'class' => 'consumedd',
                   ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('gas_remaining_balance',[
                     'label' => 'Remaining Balance (L)',
                     'cols' => 3,
                     'type' => 'number',
                   ]) !!}

                    <div class="box-body">

                    {!! \App\Swep\ViewHelpers\__form2::textbox('odometer_from',[
                      'label' => 'Odometer from:',
                      'cols' => 2,
                      'type' => 'number',

                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('odometer_to',[
                      'label' => 'Odometer to:',
                      'cols' => 2,
                      'type' => 'number',
                      'class' => 'odometer',
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('distance_traveled',[
                      'label' => 'Distance Travelled (km)',
                      'cols' => 2,
                      'type' => 'number',
                      'class' => 'distanceTravelled',
                    ]) !!}

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
        let vehicleUsage = 0;
        $('.vehicle1').on("change", function () {
            let uri = '{{route("dashboard.trip_ticket.findOdo", "vehicle") }}';
            uri = uri.replace('vehicle',$(this).val());
            $.ajax({
                url : uri,
                type: 'GET',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    console.log(res);
                    $('input[name="odometer_from"]').val(res.currentOdo);
                    $('input[name="gas_balance"]').val(res.ticket.gas_remaining_balance);
                    vehicleUsage = res.usage;
                },
                error: function (res) {
                    toast('error',res.responseJSON.message,'Error!');
                }
            })

        });

        $('.gas_liter').on("input", function () {
            let total = 0;

            $('.gas_liter').each(function () {
                let value = parseFloat($(this).val()) || 0;
                total += value;
            });

            $('input[name="total"]').val(total);
        });

        $('.consumedd').on("change input", function () {
            let t = $(this).val();
            let total1 = $('input[name="total"]').val();
            let dif = total1 - t;
            $('input[name="gas_remaining_balance"]').val(dif);
        });

        $('.odometer').on("input", function () {
            let odoFrom = $('input[name="odometer_from"]').val();
            let odoTo = $('input[name="odometer_to"]').val();
            let tot = odoTo - odoFrom;
            $('input[name="distance_traveled"]').val(tot);
            $('input[name="distance_traveled"]').change();
            $('.consumedd').change();
        });



        $('input[name="request_no"]').unbind().bind('keyup', function(e) {
            if($('input[name="request_no"]').val() === ''){
                toast('error','Reference Number cannot be empty','Invalid!');
            }
            else {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    let uri = '{{route("dashboard.trip_ticket.findTransByRefNumber", "requestNo") }}';
                    uri = uri.replace('requestNo',$(this).val());
                    $.ajax({
                        url : uri,
                        type: 'GET',
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success: function (res) {
                            console.log(res);


                            setTimeout(function (){
                                $("select[name='vehicle']").change();
                            },1000)
                            $('select[name="driver"]').val(res.dl.slug);
                            $('select[name="vehicle"]').val(res.va.slug);

                            let uri = '{{route("dashboard.trip_ticket.findOdo", "vehicle") }}';
                            uri = uri.replace('vehicle',res.va.slug);
                            $.ajax({
                                url : uri,
                                type: 'GET',
                                headers: {
                                    {!! __html::token_header() !!}
                                },
                                success: function (res) {
                                    console.log(res);
                                    // $('input[name="odometer_from"]').val(res.ticket.odometer_to);
                                    $('input[name="odometer_from"]').val(res.currentOdo);
                                    $('input[name="gas_balance"]').val(res.ticket.gas_remaining_balance);
                                },
                                error: function (res) {
                                    toast('error',res.responseJSON.message,'Error!');
                                }
                            })

                            let pass = "";
                            for (let i = 0; i < res.ps.length; i++) {
                                let passname = res.ps[i].name;
                                pass += passname;
                                if (i < res.ps.length - 1) {
                                    pass += ", ";
                                }
                            }

                            $('input[name="passengers"]').val(pass);
                            $('input[name="purpose"]').val(res.rv.purpose);
                            $('input[name="destination"]').val(res.rv.destination);
                            $('input[name="departure"]').val(res.rv.from);
                            $('input[name="return"]').val(res.rv.to);

                        },
                        error: function (res) {
                            toast('error',res.responseJSON.message,'Error!');
                        }
                    })
                }
            }
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#add_form');
            let uri = '{{route("dashboard.trip_ticket.store")}}';
            loading_btn(form);

            $.ajax({
                url : uri,
                data: form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,false);
                    toast('success','Trip Ticket successfully added.','Success!');
                    Swal.fire({
                        title: 'Trip Ticket Successfully created',
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
                            let link = "{{route('dashboard.trip_ticket.print','slug')}}";
                            link = link.replace('slug',res.slug);
                            window.open(link, '_blank');
                        }
                    })
                },
                error: function (res) {
                    errored(form,res);
                    //toast('error',res.responseJSON.message,'Error!');
                }
            })
        });

        $(".distanceTravelled").change(function (){
           let km = $(this).val();
           let consumption = km / vehicleUsage;
           $(".consumedd").val(consumption.toFixed(2));
        });
    </script>
@endsection

