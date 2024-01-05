
<div class="btn-group">
    @if($data->is_locked!=1)
    <a type="button" class="btn btn-default btn-sm edit_btn" href="{{route('dashboard.wmr.edit',$data->slug)}}"><i class="fa fa-edit"></i>
    </a>
    @endif
    <a class="btn btn-default btn-sm" href="{{route('dashboard.wmr.print',$data->slug)}}" target="_blank">
        <i class="fa fa-print"></i>
    </a>
    @if($data->is_locked!=1)
    <button type="button" class="btn btn-default btn-sm receive_btn" data="{{$data->slug}}" title="" data-placement="left" data-original-title="Receive">
        <i class="fa  fa-download"></i>
    </button>
    @endif

</div>

