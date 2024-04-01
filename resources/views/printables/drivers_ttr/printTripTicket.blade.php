@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    $rand = \Illuminate\Support\Str::random();
    $pages = [];
@endphp

@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')
    {!! $html !!}

@endsection

@section('scripts')


 <script type="text/javascript">
     $(document).ready(function () {
             print();
     })
 </script>
@endsection