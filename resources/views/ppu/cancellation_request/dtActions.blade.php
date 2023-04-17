<div class="btn-group">
    <a class="btn btn-default btn-sm" href="{{route('dashboard.cancellationRequest.print',$data->slug)}}" target="_blank">
        <i class="fa fa-print"></i>
    </a>
   @if($myIndex == false)
        @if($data->is_cancelled == false)
            <a class="cancel_btn btn btn-danger btn-sm" href="#" data="{{$data->slug}}">
                <i class="fa fa-times"></i>
            </a>
        @endif
    @endif
</div>

