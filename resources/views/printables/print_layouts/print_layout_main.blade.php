<html>
<head>
    <link rel="stylesheet"  href="{{asset('template/plugins/print/bootstrap.print.css')}}">

    <link rel="stylesheet" href="{{asset('template/bower_components/font-awesome/css/font-awesome.min.css')}}">

    <script type="text/javascript" src="{{asset('template/bower_components/jquery/dist/jquery.min.js')}}"></script>

    <link type="text/css" rel="stylesheet" href="{{asset('css/print.css')}}?s={{\Illuminate\Support\Str::random()}}">
    <link type="text/css" rel="stylesheet" href="{{asset('template/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arial">
    <style type="text/css">
        .no-margin{
            margin: 0 0 0 0;
        }

        table {
            font-size: 12px
        }
        td, th {
            padding: 5px !important
        }

        .text-stong{

        }

        @media print{
            .noPrint{
                display: none;
            }
        }
    </style>
    <title>Print | SRA Web Portal</title>

</head>
<body onload="" onafterprint="">
<div class="wrapper" style="overflow:hidden !important; text-align: center">
    @yield('wrapper')
</div>



<script type="text/javascript">
    $(document).ready(function(){
        $("#loader").fadeOut(function(){
            $("#content").fadeIn(1000);
        })
    })
</script>

@yield('scripts')
</body></html>