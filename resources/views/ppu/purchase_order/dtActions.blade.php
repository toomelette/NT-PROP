
<div class="btn-group">
    @if($data->mode != "Public Bidding")
        <a class="btn btn-default btn-sm edit_btn" href="{{route('dashboard.po.edit',$data->slug)}}">
            <i class="fa fa-edit"></i>
        </a>
    @endif
    <a class="btn btn-default btn-sm" href="{{route('dashboard.po.print1',$data->slug)}}" target="_blank">
        <i class="fa fa-print"></i>
    </a>
</div>

