<div class="btn-group btn-block">
    <a class="btn btn-sm btn-success" href="{{route('dashboard.par.edit',$data->slug)}}">
        <i class="fa fa-edit"></i>
    </a>
    <a class="btn btn-default btn-sm" href="{{route('dashboard.par.print',$data->slug)}}" target="_blank">
        <i class="fa fa-print"></i>
    </a>
    <a class="btn btn-sm btn-success" href="{{route('dashboard.par.uploadPic',$data->slug)}}">
        <i class="fa fa-upload"></i>
    </a>
    <button style="margin-left: 1px" type="button" onclick="delete_data('{{$data->slug}}','{{route('dashboard.par.destroy',$data->slug)}}')" data="{{$data->slug}}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
        <i class="fa fa-trash"></i>
    </button>

    <a class="btn btn-sm btn-success" href="{{route('dashboard.par.propCard',$data->slug)}}" data="{{$data->slug}}">
        <i class="fa fa-file-text"></i>
    </a>

    <a class="btn btn-sm btn-primary" href="{{route('dashboard.par.print_property_tag',$data->slug)}}" target="_blank">
        <i class="fa fa-print"></i>
    </a>
</div>



