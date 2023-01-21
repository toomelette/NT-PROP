<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SRA Web Portal - AFD</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.css-plugins')
    <style>
        @charset "utf-8";

        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box
        }

        .limiter {
            width: 100%;
            margin: 0 auto
        }

        .container-login100 {
            width: 100%;
            min-height: 100vh;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 15px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            position: relative;
            z-index: 1
        }

        .container-login100::before {
            content: "";
            display: block;
            position: absolute;
            z-index: -1;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.80)
        }

        .login_topimg {
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            width: 100%;
            min-height: 185px;
            position: relative;
            background: #91B3D1 url({{asset('swep/login/top.jpg')}}) no-repeat;
            background-size: auto;
            background-position: center
        }

        .login_topimg img {
            width: 100%;
            height: auto
        }

        .login_topimg .logo_wrap {
            border-radius: 5px;
            background: #fff;
            padding: 13px 55px;
            position: relative;
            top: -21px;
            margin: 10px auto;
            max-width: 255px
        }

        #login .wrap-login100 {
            background-color: #fff;
            padding: 20px 45px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            width: 100%
        }

        .login100-form {
            width: 100%;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap
        }

        .login100-form-title {
            font-size: 25px;
            color: #243762;
            line-height: 1.2;
            text-transform: uppercase;
            text-align: center;
            width: 100%;
            display: block
        }

        .login100-form-subtitle {
            font-size: 16px;
            color: #243762;
            line-height: 1.2;
            text-align: center;
            width: 100%;
            display: block
        }

        .wrap-input100 {
            position: relative;
            width: 100%;
            z-index: 1
        }

        #login input {
            outline: none;
            border: none
        }

        #login label {
            display: inline-block;
            margin-bottom: .5rem
        }

        .input-checkbox100 {
            display: none
        }

        input {
            outline: none;
            border: none
        }

        .wrap-input100 {
            position: relative;
            width: 100%;
            z-index: 1
        }

        .input100 {
            font-size: 15px;
            line-height: 1.2;
            color: #686868;
            display: block;
            width: 100%;
            background: #e6e6e6;
            height: 45px;
            border-radius: 3px;
            padding: 0 30px 0 55px
        }

        .focus-input100 {
            display: block;
            position: absolute;
            border-radius: 3px;
            bottom: 0;
            left: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            box-shadow: 0px 0px 0px 0px;
            color: rgba(211, 63, 141, 0.6)
        }

        .symbol-input100 {
            font-size: 15px;
            color: #999999;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            align-items: center;
            position: absolute;
            border-radius: 25px;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding-left: 23px;
            padding-bottom: 5px;
            pointer-events: none;
            -webkit-transition: all 0.4s;
            -o-transition: all 0.4s;
            -moz-transition: all 0.4s;
            transition: all 0.4s
        }

        ::-webkit-input-placeholder {
            opacity: 1;
            -webkit-transition: opacity .5s;
            transition: opacity .5s
        }

        :-moz-placeholder {
            opacity: 1;
            -moz-transition: opacity .5s;
            transition: opacity .5s
        }

        ::-moz-placeholder {
            opacity: 1;
            -moz-transition: opacity .5s;
            transition: opacity .5s
        }

        :-ms-input-placeholder {
            opacity: 1;
            -ms-transition: opacity .5s;
            transition: opacity .5s
        }

        ::placeholder {
            opacity: 1;
            transition: opacity .5s
        }

        *:focus::-webkit-input-placeholder {
            opacity: 0
        }

        *:focus:-moz-placeholder {
            opacity: 0
        }

        *:focus::-moz-placeholder {
            opacity: 0
        }

        *:focus:-ms-input-placeholder {
            opacity: 0
        }

        *:focus::placeholder {
            opacity: 0
        }

        .lnr {
            speak: none;
            font-style: normal;
            font-weight: 400;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        .flex-sb-m {
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: space-between;
            -ms-align-items: center;
            align-items: center
        }

        .w-full {
            width: 100%
        }

        .p-b-30 {
            padding-bottom: 30px
        }

        .input-checkbox100:checked+.label-checkbox100::before {
            color: #09569B
        }

        .label-checkbox100::before {
            content: "\f00c";
            font-family: FontAwesome;
            font-size: 13px;
            color: transparent;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 2px;
            background: #fff;
            border: 1px solid #e6e6e6;
            left: 0;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            transform: translateY(-50%)
        }

        .label-checkbox100 {
            font-size: 14px;
            font-weight: normal;
            color: #999999;
            line-height: 1.2;
            display: block;
            position: relative;
            padding-left: 26px;
            cursor: pointer
        }

        .m-b-16 {
            margin-bottom: 16px
        }

        .p-b-55 {
            padding-bottom: 55px
        }

        .container-login100-form-btn {
            width: 100%;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            flex-wrap: wrap;
            justify-content: center
        }

        .login100-form-btn:hover {
            background: #333333
        }

        .label-checkbox100::before {
            content: "\f00c";
            font-family: FontAwesome;
            font-size: 13px;
            color: transparent;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 3px;
            background: #fff;
            border: 2px solid #09569B;
            left: 0;
            top: 48%;
            -webkit-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            transform: translateY(-50%)
        }

        #login button:hover {
            cursor: pointer
        }

        .login100-form-btn {
            font-size: 16px;
            line-height: 1.5;
            color: #fff;
            text-transform: uppercase;
            width: 100%;
            height: 45px;
            border-radius: 3px;
            background: #09569B;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 25px;
            -webkit-transition: all 0.4s;
            -o-transition: all 0.4s;
            -moz-transition: all 0.4s;
            transition: all 0.4s
        }

        #login button {
            outline: none !important;
            border: none
        }

        @media (max-width: 768px) {
            .container {
                width: 750px
            }

            #login .wrap-login100 {
                padding: 27px
            }

            .login_topimg .logo_wrap {
                padding: 5px 55px
            }
        }
    </style>
</head>
<body class="hold-transition skin-green layout-top-nav">
<div class="wrapper">
    <header class="main-header">

    </header>
    <div class="content-wrapper">
        <div class="limiter" id="login">
            <div class="container-login100" style="background-image:url(https://i.ytimg.com/vi/fNzq5YasHX8/maxresdefault.jpg)">
                <div class="container">
                    <div class="row">

                        <div class="col-md-6"></div>
                        <div class="col-md-5 col-md-offset-3">
                            <div class="login_topimg"> </div>
                            <div class="wrap-login100">
                                @if(Session::has('AUTH_AUTHENTICATED'))
                                    {!! __html::alert('danger', '<i class="icon fa fa-ban"></i> Oops!', Session::get('AUTH_AUTHENTICATED')) !!}
                                @endif
                                    @if(Session::has('FOR_PERMANENT'))
                                        {!! __html::alert('danger', '<i class="icon fa fa-ban"></i> Oops!', Session::get('FOR_PERMANENT')) !!}
                                    @endif

                                @if(Session::has('AUTH_UNACTIVATED'))
                                    {!! __html::alert('danger', '<i class="icon fa fa-ban"></i> Oops!', Session::get('AUTH_UNACTIVATED')) !!}
                                @endif

                                @if(Session::has('CHECK_UNAUTHENTICATED'))
                                    {!! __html::alert('danger', '<i class="icon fa fa-ban"></i> Oops!', Session::get('CHECK_UNAUTHENTICATED')) !!}
                                @endif

                                @if(Session::has('CHECK_NOT_LOGGED_IN'))
                                    {!! __html::alert('danger', '<i class="icon fa fa-ban"></i> Oops!', Session::get('CHECK_NOT_LOGGED_IN')) !!}
                                @endif

                                @if(Session::has('CHECK_NOT_ACTIVE'))
                                    {!! __html::alert('danger', '<i class="icon fa fa-ban"></i> Oops!', Session::get('CHECK_NOT_ACTIVE')) !!}
                                @endif

                                @if(Session::has('PROFILE_UPDATE_USERNAME_SUCCESS'))
                                    {!! __html::alert('success', '<i class="icon fa fa-check"></i> Success!', Session::get('PROFILE_UPDATE_USERNAME_SUCCESS')) !!}
                                @endif

                                @if(Session::has('PROFILE_UPDATE_PASSWORD_SUCCESS'))
                                    {!! __html::alert('success', '<i class="icon fa fa-check"></i> Success!', Session::get('PROFILE_UPDATE_PASSWORD_SUCCESS')) !!}
                                @endif

                                @if(Session::has('PASSWORD_RESET_SUCCESS'))
                                    {!! __html::alert('success', '<i class="icon fa fa-check"></i> Success!', Session::get('PASSWORD_RESET_SUCCESS')) !!}
                                @endif

                                @if(Session::has('PASSWORD_RESET_FAILED'))
                                    {!! __html::alert('danger', '<i class="icon fa fa-times"></i> Success!', Session::get('PASSWORD_RESET_FAILED')) !!}
                                @endif
                                <form class="login100-form validate-form" action="{{ route('auth.login') }}" method="POST">
                                    @csrf
                                    <span class="login100-form-title "> SWEP </span>
                                    <span class="login100-form-subtitle m-b-16"> SRA - Web Protal | Login </span>
                                    @if ($errors->has('username'))
                                        <span class="help-block" style="color: darkred"> {{ $errors->first('username') }}</span>
                                    @endif
                                    <div class="wrap-input100 validate-input m-b-16" data-validate="Valid email is required: ex@abc.xyz">
                                        <input class="input100" type="text" name="username" id="username" placeholder="Username" type="text" value="{{ __sanitize::html_attribute_encode(old('username')) }}">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <span class="glyphicon glyphicon-user"></span>
                                        </span>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="help-block" style="color: darkred">{{ $errors->first('password') }}</span>
                                    @endif
                                    <div class="wrap-input100 validate-input m-b-16" data-validate="Password is required">
                                        <input class="input100" type="password" name="password" id="password" placeholder="Password">
                                        <span class="focus-input100"></span>
                                            <span class="symbol-input100">
                                                <span class="glyphicon glyphicon-lock"></span>
                                            </span>
                                    </div>




                                    <div class="flex-sb-m w-full p-b-30">

                                        <div><a href="#" class="txt1" data-toggle="modal" data-target="#reset_modal">Forgot username/password? Click here</a> </div>
                                    </div>
                                    <div class="container-login100-form-btn p-t-25">
                                        <button class="login100-form-btn" type="submit"> Login </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>
<div class="modal fade" id="reset_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 20%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Account Recovery</h4>
            </div>
            <div class="modal-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Password Reset</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Username Lookup</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane active" id="tab_1">
                            <form id="reset_password_form">
                                <div class="row">
                                    {!! __form::textbox(
                                        '12 username', 'username', 'text', 'Username:', 'Username','', '', '', ''
                                      ) !!}
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-refresh"></i> Reset</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <form id="search_username_form">
                                <div class="row">
                                    {!! __form::textbox(
                                        '12 firstname', 'firstname', 'text', 'Firstname:', 'Firstname','', '', '', ''
                                      ) !!}
                                    {!! __form::textbox(
                                        '12 lastname', 'lastname', 'text', 'Lastname:', 'Lastname','', '', '', ''
                                      ) !!}
                                    {!! __form::textbox(
                                        '12 birthday', 'birthday', 'date', 'Birthday:', 'birthday','', '', '', ''
                                      ) !!}
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-search"></i> Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.js-plugins')



<script type="text/javascript">
    $("#search_username_form").submit(function (e) {
        e.preventDefault();
        form = $(this);
        loading_btn(form);
        $.ajax({
            url : '{{route("auth.username_lookup")}}',
            data : form.serialize(),
            type: 'POST',
            headers: {
                {!! __html::token_header() !!}
            },
            success: function (res) {
                Swal.fire({
                    title: 'User found!',
                    icon: 'success',
                    html:
                        'Name: <b>'+res.fullname+'</b><br>' +
                        'Username: <b>'+res.username+'</b>',
                    showCloseButton: true,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText:
                        '<i class="fa fa-check"></i> Done',
                    confirmButtonAriaLabel: 'Thumbs up, great!',
                    cancelButtonText:
                        '<i class="fa fa-thumbs-down"></i>',
                    cancelButtonAriaLabel: 'Thumbs down'
                });
                form.get(0).reset();
                remove_loading_btn(form);
            },
            error: function (res) {
                errored(form,res);
            }
        })
    })

    $('#reset_modal').on('shown.bs.modal', function() {
        $(document).off('focusin.modal');
    });


    $("#reset_password_form").submit(function (e) {
        e.preventDefault();
        form = $(this);
        loading_btn(form);
        $.ajax({
            url : '{{route("auth.reset_password")}}',
            data : form.serialize(),
            type: 'POST',
            headers: {
                {!! __html::token_header() !!}
            },
            success: function (res) {
                remove_loading_btn(form);
                Swal.fire({
                    title: 'Verify your email address',
                    input: 'text',
                    html: 'Please enter your email address below: <br> <b>'+res.email+'</b>',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Verify',
                    showLoaderOnConfirm: true,
                    preConfirm: (email) => {
                        return $.ajax({
                            url : '{{route('auth.verify_email')}}',
                            type: 'POST',
                            data: {'email':email,'slug':res.slug},
                            headers: {
                                {!! __html::token_header() !!}
                            },
                        })
                            .then(response => {
                                return  response;
                            })
                            .catch(error => {
                                console.log(error);
                                Swal.showValidationMessage(
                                    'Error : '+ error.responseJSON.message,
                                )
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'A link was sent to your email. Please check your spam messages also.',
                            icon : 'success',
                        })
                    }
                })
            },
            error: function (res) {
                console.log(res);
                if(res.status == 503){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.responseJSON.message,
                    })
                }
                errored(form,res);
            }
        })
    })
</script>



</body>
</html>