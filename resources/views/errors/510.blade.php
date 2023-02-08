@extends('layouts.admin-master')

@section('content')

@endsection
@section('content2')

    <section class="content">
        <div class="text-center" style="margin-top:10%;">
            <h1><span style="font-size: 120px; color: grey"><i class="fa fa-exclamation-triangle"></i></span></h1>
            <h1>{{$exception->getMessage()}}</h1>

{{--            <p>Contact MIS Personnel for assistance.</p>--}}
        </div>

    </section>


@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection