
<div class="btn-group">
    <button type="button" class="btn btn-success btn-sm edit_btn" data="{{$data->slug}}" data-toggle="modal" data-target="#edit_modal" title="" data-placement="left" data-original-title="Edit">
        <i class="fa fa-edit"></i>
    </button>
    <a class="btn btn-default btn-sm" href="{{route('dashboard.par.print',$data->slug)}}" target="_blank">
        <i class="fa fa-print"></i>
    </a>
</div>

