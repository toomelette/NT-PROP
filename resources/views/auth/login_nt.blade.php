<html>
<head>
    <link type="text/css" rel="stylesheet" href="{{asset('template/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">

    <link type="text/css" rel="stylesheet" href="{{asset('template/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <script type="text/javascript" src="{{ asset('template/bower_components/jquery/dist/jquery.min.js') }}"></script>

    <style>
        .form-signin
        {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }
        .form-signin .form-signin-heading, .form-signin .checkbox
        {
            margin-bottom: 10px;
        }
        .form-signin .checkbox
        {
            font-weight: normal;
        }
        .form-signin .form-control
        {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .form-signin .form-control:focus
        {
            z-index: 2;
        }
        .form-signin input[type="text"]
        {
            margin-bottom: -1px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }
        .form-signin input[type="password"]
        {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        .account-wall
        {
            margin-top: 20px;
            padding: 40px 0px 20px 0px;
            background-color: #f7f7f7;
            -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        }
        .login-title
        {
            color: #555;
            font-size: 18px;
            font-weight: 400;
            display: block;
        }
        .profile-img
        {
            width: 96px;
            height: 96px;
            margin: 0 auto 10px;
            display: block;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }
        .need-help
        {
            margin-top: 10px;
        }
        .new-account
        {
            display: block;
            margin-top: 10px;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4" style="margin-top: 15%">
            <h1 class="text-center login-title">Sign in to continue to ePMS Portal</h1>

            @if(Session::has('AUTH_AUTHENTICATED'))
                {!! __html::alert('danger', '<i class="icon fa fa-ban"></i> Oops!', Session::get('AUTH_AUTHENTICATED')) !!}
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

            <div class="account-wall">

                <form class="form-signin"  action="{{ route('auth.login') }}" method="POST">
                    @csrf
                    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                    @if ($errors->has('username'))
                        <span class="help-block" style="color: darkred"> {{ $errors->first('username') }}</span>
                    @endif
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    @if ($errors->has('password'))
                        <span class="help-block" style="color: darkred">{{ $errors->first('password') }}</span>
                    @endif
                    <button class="btn btn-lg btn-primary btn-block" type="submit">
                        Sign in</button>

                </form>

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
                                    </div>p
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
</body>
<script>

</script>
</html>