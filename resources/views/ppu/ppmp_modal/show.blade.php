@extends('layouts.modal-content')

@section('modal-header')
    {{$ppmp->ppmp_code}} - {{$ppmp->gen_desc}}
@endsection

@section('modal-body')

@endsection

@section('modal-footer')
    <div class="row">
        {!! \App\Swep\ViewHelpers\__html::timestamp($ppmp,'5') !!}
        <div class="col-md-2">
            <button class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
@endsection

@section('scripts')

@endsection

