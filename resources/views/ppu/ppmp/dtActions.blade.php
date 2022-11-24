<div class="btn-group">
    <button type="button" class="btn btn-default btn-sm show_ppmp_btn" data="{{$data->slug}}" data-toggle="modal" data-target="#show_ppmp_modal" title="" data-placement="left" data-original-title="View more">
        <i class="fa fa-file-text"></i>
    </button>
    <button type="button" class="btn btn-default btn-sm edit_ppmp_btn" data="{{$data->slug}}" data-toggle="modal" data-target="#edit_ppmp_modal" title="" data-placement="left" data-original-title="Edit">
        <i class="fa fa-edit"></i>
    </button>
    <button type="button" onclick="delete_data('{{$data->slug}}','{{route('dashboard.ppmp.destroy',$data->slug)}}')" data="{{$data->slug}}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
        <i class="fa fa-trash"></i>
    </button>
</div>