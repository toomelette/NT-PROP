@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Driver</h1>
    </section>
@endsection
@section('content2')

    @php
        $employees = \App\Models\Employee::query()
        ->where(function ($query) {
            $query->where('locations', '=', 'VISAYAS')
                ->orWhere('locations', '=', 'LUZON/MINDANAO');
        })
        ->where('is_active', '=', 'ACTIVE')
        ->orderBy('fullname', 'asc')
        ->get();

       $employeesCollection = $employees->map(function ($data){
            return [
                'id' => $data->slug,
                'text' => $data->firstname.' '.$data->lastname.' - '.$data->employee_no,
                'employee_no' => $data->employee_no,
                'fullname' => $data->firstname.' '.$data->lastname,
                'position' => $data->position,
            ];
        })->toJson();
    @endphp

<section class="content col-md-12">
        <div role="document">
            <form id="add_form">

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Driver</h3>
{{--                        <button class="btn btn-primary btn-sm pull-right"  id="saveBtn" type="submit">--}}
{{--                            <i class="fa fa-check"></i> Save--}}
{{--                        </button>--}}
                        <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.drivers.index')}}">Back</a>
                    </div>

                    <div class="box-body">

                        {!! \App\Swep\ViewHelpers\__form2::select('employee_no',[
                                       'label' => 'Employee Number',
                                       'cols' => 4,
                                       'options' => [],
                                       'id' => 'employee_no',
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('contact_no',[
                                      'label' => 'Contact Number',
                                      'cols' => 4,
                                      'id' => 'contact_no',
                       ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('color',[
                                     'label' => 'Email Address',
                                     'cols' => 4,
                                     'id' => 'color',
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

        var data = {!!$employeesCollection!!};
        $("#employee_no").select2({
            data : data,
        });

        {{--$('#saveBtn').click(function(e) {--}}
        {{--    e.preventDefault();--}}
        {{--    let form = $('#add_form');--}}
        {{--    let uri = '{{route("dashboard.drivers.store")}}';--}}
        {{--    loading_btn(form);--}}

        {{--    $.ajax({--}}
        {{--        url : uri,--}}
        {{--        data: form.serialize(),--}}
        {{--        type: 'POST',--}}
        {{--        headers: {--}}
        {{--         {!! __html::token_header() !!}--}}
        {{--        },--}}
        {{--        success: function (res) {--}}
        {{--            succeed(form,true,false);--}}
        {{--            $(".remove_row_btn").each(function () {--}}
        {{--                $(this).click();--}}
        {{--            })--}}
        {{--            $(".add_button").click();--}}
        {{--            toast('success','Vehicle Successfully created.','Success!');--}}
        {{--            Swal.fire({--}}
        {{--                title: 'Vehicle Successfully created',--}}
        {{--                icon: 'success',--}}
        {{--                showCloseButton: true,--}}
        {{--                showCancelButton: false, // Removed the showCancelButton property--}}
        {{--                focusConfirm: false,--}}
        {{--                confirmButtonAriaLabel: 'Thumbs up, great!',--}}
        {{--                cancelButtonText: 'Dismiss',--}}
        {{--                cancelButtonAriaLabel: 'Thumbs down'--}}
        {{--            });--}}
        {{--        },--}}
        {{--        error: function (res) {--}}
        {{--            toast('error',res.responseJSON.message,'Error!');--}}
        {{--        }--}}
        {{--    });--}}


        {{--});--}}

    </script>
@endsection

