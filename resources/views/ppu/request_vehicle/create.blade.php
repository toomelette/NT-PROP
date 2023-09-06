@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Create new Request for Vehicle</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <button class="hidden" id="go-btn">GO</button>
        <form id="create_request_form">
            <div class="box box-solid">
                <div id="form-container">
                    <div class="box-body">
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('name',[
                                'cols' => 4,
                                'label' => 'Requisitioner:',
                            ],Auth::user()->employee->firstname.' '.Auth::user()->employee->lastname) !!}

                            {!! \App\Swep\ViewHelpers\__form2::select('rc',[
                                'cols' => 3,
                                'label' => 'Dept/Div:',
                                'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('purpose',[
                                'cols' => 5,
                                'label' => 'Purpose:',
                            ]) !!}
                        </div>
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('passengers',[
                                'cols' => 6,
                                'label' => 'Authorized Passengers: (Type and press enter to add more passengers)',
                                'id' => 'passengers_tags',
                                'placeholder' => 'Press enter after typing',
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('from',[
                                'cols' => 3,
                                'label' => 'Date & Time of Departure:',
                                'type' => 'datetime-local',
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('to',[
                                'cols' => 3,
                                'label' => 'Date and Time of Return: (If applicable)',
                                'type' => 'datetime-local',
                            ]) !!}
                        </div>
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('destination',[
                               'cols' => 6,
                               'label' => 'Destination:',
                           ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                                'cols' => 3,
                                'label' => 'Requested by:'
                            ],Auth::user()->employee->firstname.' '.Auth::user()->employee->lastname) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_position',[
                                'cols' => 3,
                                'label' => 'Position:'
                            ],Auth::user()->employee->position) !!}
                        </div>

                    </div>
                    <div class="box-footer with-border">
                        <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="fa fa-check"></i> Save and submit</button>
                    </div>
                </div>
                <div class="text-center" style="display: none" id="check-container">
                    <img style="width: 300px" class="" src="{{asset('images/check.gif')}}"/>

                    <h3>Request successfully created.</h3>
                    <a class="btn btn-sm btn-success" id="print-btn" type="button" target="_blank"><i class="fa fa-print"></i> Print Request</a>
                    <br><br>
                </div>
            </div>
        </form>
    </section>


    <table class="table table-condensed table-bordered table-striped" style="display: none">
        <tr id="row_template">
            <td>
                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details[slug][datetime_departure]',[
                    'type' => 'datetime-local',
                    'class' => 'details_slug_datetime_departure',
                ]) !!}
            </td>
            <td>
                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details[slug][destination]',[
                    'type' => 'text',
                    'class' => 'details_slug_destination',
                ]) !!}
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove_row_btn"><i class="fa fa-times"></i></button>
            </td>
        </tr>
    </table>

@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        $("#passengers_tags").tagsinput();

        $("#add_row_btn").click(function () {
            let rand = makeid(10);
            $("#details_table tbody").append('<tr>'+$("#row_template").html().replaceAll('slug',rand)+'</tr>');
        })

        $(document).ready(function () {
            $("#add_row_btn").trigger('click');
        })

        $("#create_request_form").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.request_vehicle.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    $("#form-container").slideUp();
                    $("#check-container").slideDown();
                    let slug = res.slug;
                    let printTarget = '{{route('dashboard.request_vehicle.print_own','slug')}}';
                    printTarget = printTarget.replaceAll('slug',slug);
                    $("#print-btn").attr('href',printTarget);
                    succeed(form,true,false);

                },
                error: function (res) {
                    errored(form,res);
                    toast('error','Please fill out the required fields.','Error');
                }
            })
        })

        $("#go-btn").click(function (){

        });

        $('.bootstrap-tagsinput input').on('keypress', function(e){
            if (e.keyCode == 13){
                e.keyCode = 188;
                e.preventDefault();
            };
        });
    </script>
@endsection