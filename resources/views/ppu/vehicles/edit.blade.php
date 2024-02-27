@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Vehicle</h1>
    </section>
@endsection
@section('content2')

<section class="content col-md-12">
    <div role="document">
        <form id="edit_form">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Vehicle Details</h3>
                    <button class="btn btn-primary btn-sm pull-right" id="saveBtn" type="button">
                        <i class="fa fa-check"></i> Save
                    </button>
                    <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.vehicles.index')}}">Back</a>
                </div>
                    <div class="box-body">

                        <input type="hidden" name="slug" id="slug" value="{{$vhcl->slug}}">

                        {!! \App\Swep\ViewHelpers\__form2::textbox('year',[
                           'label' => 'Year:',
                           'cols' => 3,
                        ],
                             $vhcl ?? null
                                 ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('make',[
                            'label' => 'Brand:',
                            'cols' => 3,
                         ],
                             $vhcl ?? null
                                 ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('model1',[
                           'label' => 'Model:',
                           'cols' => 3,
                        ],
                             $vhcl ?? null
                                 ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('plate_no',[
                          'label' => 'Plate Number:',
                          'cols' => 3,
                       ],
                             $vhcl ?? null
                                 ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('odometer',[
                        'label' => 'Current Odometer:',
                        'cols' => 3,
                        ],
                             $vhcl ?? null
                                 ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('usage',[
                      'label' => 'KM per Liter:',
                      'cols' => 3,
                      'type' => 'number',
                      ],
                             $vhcl ?? null
                                 ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('normal_usage',[
                      'label' => 'Normal KM per Liter:',
                      'cols' => 3,
                      'type' => 'number',
                      ],
                             $vhcl ?? null
                                 ) !!}


                        {!! \App\Swep\ViewHelpers\__form2::select('status',[
                               'label' => 'Condition:',
                               'cols' => 3,
                               'options' => \App\Swep\Helpers\Arrays::condition(),
                       ],
                             $vhcl ?? null
                                 ) !!}


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


        $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#edit_form');
            let uri = '{{route("dashboard.vehicles.update","slug")}}';
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
                        title: 'Vehicle Successfully updated',
                        icon: 'success',
                        showCloseButton: true,
                        showCancelButton: false, // Removed the showCancelButton property
                        focusConfirm: false,
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                        cancelButtonText: 'Dismiss',
                        cancelButtonAriaLabel: 'Thumbs down'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to dashboard.vehicles.index
                            window.location.href = '{{ route("dashboard.vehicles.index") }}';
                        }
                    });
                },
                error: function(res) {
                    toast('error',res.responseJSON.message,'Error!');
                }
            });
        });

    </script>
@endsection

