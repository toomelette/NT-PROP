<div class="btn-group">
    @if($data->action=="APPROVED")
        <a href="{{route('dashboard.request_vehicle.print_own',$data->slug)}}" target="_blank" data="{{$data->slug}}" type="button" class="btn btn-default btn-sm"><i class="fa fa-print"></i></a>
    @endif
</div>