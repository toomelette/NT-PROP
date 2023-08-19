
<div class="btn-group btn-block btn-group-justified">
    <a class="btn btn-sm btn-success" href="{{route('dashboard.par.edit',$data->slug)}}">
        <i class="fa fa-edit"></i>
    </a>
    <a class="btn btn-default btn-sm" href="{{route('dashboard.par.print',$data->slug)}}" target="_blank">
        <i class="fa fa-print"></i>
    </a>
</div>

<a class="btn-block btn btn-sm btn-default" href="{{route('dashboard.par.print_property_tag',$data->slug)}}" target="_blank">
    <i class="fa fa-print"></i> Property Tag
</a>

