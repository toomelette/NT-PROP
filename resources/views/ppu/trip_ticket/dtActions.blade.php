<div class="btn-group">

    <a class="btn btn-default btn-sm" href="{{route('dashboard.trip_ticket.print',$data->slug)}}" target="_blank">
        <i class="fa fa-print"></i>
    </a>
    <a type="button" class="btn btn-default btn-sm edit_btn" href="{{route('dashboard.trip_ticket.edit',$data->slug)}}">
        <i class="fa fa-edit"></i>
    </a>

</div>