<div class="btn-group">
    <a href="{{route('dashboard.request_vehicle.print',$data->slug)}}" target="_blank" data="{{$data->slug}}" type="button" class="btn btn-default btn-sm"><i class="fa fa-print"></i></a>
    @if(empty($data->action))
        <button data="{{$data->slug}}" data-toggle="modal" data-target="#actions_modal" type="button" class="btn btn-primary btn-sm actions_btn">Actions</button>
    @else
        <button data="{{$data->slug}}" data-toggle="modal" data-target="#actions_modal" type="button" class="btn btn-default btn-sm actions_btn">View</button>
        <a class="btn btn-sm btn-success" href="{{route('dashboard.request_vehicle.tripTicket',$data->slug)}}" data="{{$data->slug}}">
            <i class="fa fa-file-text"></i>
        </a>
    @endif

</div>