@extends('layouts.admin-master')

@section('content')

    <section class="content-header">

    </section>
@endsection
@section('content2')

    <section class="content">
        <form id="verify_email_form">
            @csrf
            <div class="login-box">
                <div class="text-center" style="margin-top:10%;">
                    <h1><span style="font-size: 70px; color: grey"><i class="fa fa-info-circle"></i></span></h1>
                    <h3>Kindly update your <b>OFFICIAL</b> email address below.</h3>
                    <hr>
                    <h4>Your Name:
                        <span class="text-strong">
                    {{\Illuminate\Support\Facades\Auth::user()->employee->lastname}}, {{\Illuminate\Support\Facades\Auth::user()->employee->firstname}}
                </span>
                    </h4>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address:</label>
                        <input  name="email" class="form-control input-lg" placeholder="Enter email">
                        <span class="warning-message small text-green text-strong"> You will receive email updates regarding the status of your PRs and JRs. </span>
                    </div>

                    <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-check"></i> Save</button>

                </div>
            </div>
        </form>

    </section>


@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        $("#verify_email_form").submit(function (e) {

            e.preventDefault();
            let form = $(this);
            let uri = '/verifyEmail';
            loading_btn(form);
            $.ajax({
                url : uri,
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    form.get(0).reset();
                    toast('success','Email address successfully verified. Please wait.')
                    setTimeout(function () {
                        window.open('/{{\Illuminate\Support\Facades\Request::get('next') ?? '/dashboard/home'}}','_self');
                    },2000);
                },
                error: function (res) {
                    remove_loading_btn(form);
                    toast('error',res.responseJSON.message,'Error');
                }
            })

        })
    </script>
@endsection