@php($rand = \Illuminate\Support\Str::random())
@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>PAP: {{$pap->pap_code}} <i class="fa fa-chevron-right"></i> {{$pap->pap_title}}</h1>
    </section>

    <section class="content">
    </section>


@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
    </script>


@endsection