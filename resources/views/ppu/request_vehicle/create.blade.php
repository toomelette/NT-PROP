@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Create new Request for Vehicle</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <form id="create_request_form">
            <div class="box box-solid">
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
                            'cols' => 12,
                            'label' => 'Authorized Passengers: (Type and press enter to add more passengers)',
                            'id' => 'passengers_tags',
                            'placeholder' => 'Press enter after typing',
                        ]) !!}
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <p class="page-header-sm text-info" style="border-bottom: 1px solid #cedbe1">
                                Request Details
                            </p>
                            <table class="table table-condensed table-bordered table-striped" id="details_table">
                                <thead>
                                <tr>
                                    <th style="width: 300px">Date and Time of Departure</th>
                                    <th>Destination</th>
                                    <th style="width: 70px">
                                        <button type="button" class="btn btn-success btn-xs" id="add_row_btn"><i class="fa fa-plus"></i> Add row</button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                                    'cols' => 12,
                                    'label' => 'Requested by:'
                                ],Auth::user()->employee->firstname.' '.Auth::user()->employee->lastname) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_position',[
                                    'cols' => 12,
                                    'label' => 'Position:'
                                ],Auth::user()->employee->position) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer with-border">
                    <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="fa fa-check"></i> Save and submit</button>
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
                    succeed(form,true,false);
                    $("#details_table .remove_row_btn").each(function () {
                        $(this).trigger('click');

                    });
                    $("#add_row_btn").trigger('click');
                },
                error: function (res) {
                    errored(form,res);
                    toast('error','Please fill out the required fields properly.','Error');
                }
            })
        })

        $('.bootstrap-tagsinput input').on('keypress', function(e){
            if (e.keyCode == 13){
                e.keyCode = 188;
                e.preventDefault();
            };
        });
    </script>
@endsection