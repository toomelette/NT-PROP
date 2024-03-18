@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Trip Ticket</h1>
    </section>
@endsection
@section('content2')

<section class="content col-md-12">
    <div role="document">
        <form id="edit_form">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Trip Ticket</h3>
                    <button class="btn btn-primary btn-sm pull-right" id="saveBtn" type="button">
                        <i class="fa fa-check"></i> Save
                    </button>
                    <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.trip_ticket.index')}}">Back</a>
                </div>
                    <div class="box-body">

                        <input type="hidden" name="slug" id="slug" value="{{$tt->slug}}">

                        {!! \App\Swep\ViewHelpers\__form2::textbox('request_no',[
                         'label' => 'Request Number',
                         'cols' => 3,
                         ],
                         $tt ?? null
                             ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::select('driver',[
                            'label' => 'Driver:',
                            'cols' => 3,
                            'options' => \App\Swep\Helpers\Arrays::drivers(),
                         ],
                         $tt ?? null
                             ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::select('vehicle',[
                           'label' => 'Vehicle:',
                           'cols' => 3,
                           'options' => \App\Swep\Helpers\Arrays::vehicles(),
                        ],
                         $tt ?? null
                             ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('passengers',[
                       'label' => 'Passengers:',
                       'cols' => 3,
                        ],
                         $tt ?? null
                             ) !!}


                        {!! \App\Swep\ViewHelpers\__form2::textarea('purpose',[
                        'label' => 'Purpose:',
                        'cols' => 3,
                         ],
                         $tt ?? null
                             ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('destination',[
                          'label' => 'Destination:',
                          'cols' => 3,
                         ],
                         $tt ?? null
                             ) !!}


                        {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                          'label' => 'Date:',
                          'cols' => 3,
                          'type' => 'date',
                         ],
                         $tt ?? null
                             ) !!}

                    </div>
                </div>

            <div class="box box-success">
                <div class="box-body">

                    {!! \App\Swep\ViewHelpers\__form2::textbox('departure',[
                      'label' => 'Departure',
                      'cols' => 3,
                      'type' => 'datetime-local',
                    ],
                         $tt ?? null
                             ) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('return',[
                      'label' => 'Return',
                      'cols' => 3,
                      'type' => 'datetime-local',
                    ],
                         $tt ?? null
                             ) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                     'label' => 'Approved By:',
                     'cols' => 3,
                    ],
                         $tt ?? null
                             ) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                      'label' => 'Designation:',
                      'cols' => 3,
                      ],
                         $tt ?? null
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
                    ],
                         $tt ?? null
                             ) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('gas_issued',[
                      'label' => 'Gas Issued (L)',
                      'cols' => 3,
                      'type' => 'number',
                      'class' => 'gas_liter'
                    ],
                         $tt ?? null
                             ) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('purchased',[
                      'label' => 'Purchased during trip (L)',
                      'cols' => 3,
                      'type' => 'number',
                      'class' => 'gas_liter'
                    ],
                         $tt ?? null
                             ) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('total',[
                     'label' => 'TOTAL (L)',
                     'cols' => 3,
                     'type' => 'number',
                   ],
                         $tt ?? null
                             ) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('consumed',[
                     'label' => 'Consumed (L)',
                     'cols' => 3,
                     'type' => 'number',
                     'class' => 'consumedd',
                   ],
                         $tt ?? null
                             ) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('gas_remaining_balance',[
                     'label' => 'Remaining Balance (L)',
                     'cols' => 3,
                     'type' => 'number',
                   ],
                         $tt ?? null
                             ) !!}
                    <div class="box-body">

                    {!! \App\Swep\ViewHelpers\__form2::textbox('odometer_from',[
                      'label' => 'Odometer from:',
                      'cols' => 2,
                      'type' => 'number',

                    ],
                         $tt ?? null
                             ) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('odometer_to',[
                      'label' => 'Odometer to:',
                      'cols' => 2,
                      'type' => 'number',
                      'class' => 'odometer',
                    ],
                         $tt ?? null
                             ) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('distance_traveled',[
                      'label' => 'Distance Travelled (km)',
                      'cols' => 2,
                      'type' => 'number',
                      'class' => 'distanceTravelled',
                    ],
                         $tt ?? null
                             ) !!}
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
        let vehicleUsage = {{$tt->vehicles->usage ?? 0}};
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
            $('input[name="gas_remaining_balance"]').val(dif.toFixed(3));

        });

        $('.odometer').on("input", function () {
            let odoFrom = $('input[name="odometer_from"]').val();
            let odoTo = $('input[name="odometer_to"]').val();
            let tot = odoTo - odoFrom;
            $('input[name="distance_traveled"]').val(tot);
            $('input[name="distance_traveled"]').change();
        });


        $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#edit_form');
            let uri = '{{route("dashboard.trip_ticket.update","slug")}}';
            uri = uri.replace('slug',$('#slug').val());
            loading_btn(form);
            $.ajax({
                type: 'PATCH',
                url: uri,
                data: form.serialize(),
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function(res) {
                    console.log(res);
                    toast('success','Successfully Updated.','Success!');
                    $('#printIframe').attr('src',res.route);
                    succeed(form,true,true);
                    Swal.fire({
                        title: 'Successfully Updated',
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
                error: function(res) {
                    toast('error',res.responseJSON.message,'Error!');
                }
            });
        });


        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        $(".distanceTravelled").change(function (){
            let km = $(this).val();
            console.log(vehicleUsage);
            let consumption = km / vehicleUsage;
            $(".consumedd").val(consumption.toFixed(3));
            $('.consumedd').change();
        });


    </script>
@endsection

