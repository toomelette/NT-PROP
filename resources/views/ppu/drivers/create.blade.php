@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Driver</h1>
    </section>
@endsection
@section('content2')

<section class="content col-md-12">
        <div role="document">
            <form id="add_form">

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Driver</h3>
                        <button class="btn btn-primary btn-sm pull-right"  id="saveBtn" type="submit">
                            <i class="fa fa-check"></i> Save
                        </button>
                        <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.drivers.index')}}">Back</a>
                    </div>

                    <div class="box-body">




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

