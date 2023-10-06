
<div class="btn-group btn-block">
    <a class="btn btn-sm btn-success" href="{{route('dashboard.par.edit',$data->slug)}}">
        <i class="fa fa-edit"></i>
    </a>
    <a class="btn btn-default btn-sm" href="{{route('dashboard.par.print',$data->slug)}}" target="_blank">
        <i class="fa fa-print"></i>
    </a>
    <button type="button" onclick="delete_data('{{$data->slug}}','{{route('dashboard.par.destroy',$data->slug)}}')" data="{{$data->slug}}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
        <i class="fa fa-trash"></i>
    </button>
</div>

<a class="btn-block btn btn-sm btn-default" href="{{route('dashboard.par.print_property_tag',$data->slug)}}" target="_blank">
    <i class="fa fa-print"></i> Property Tag
</a>

