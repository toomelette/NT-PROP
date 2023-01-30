
<div class="btn-group">
    <a class="btn btn-default btn-sm " type="button" href="{{route('dashboard.aq.create',$data->transaction->slug ?? '')}}" data="{{$data->slug}}">
        <i class="fa icon-procurement"></i> Prepare AQ
    </a>
    <button class="btn btn-default">
        <i class="fa fa-print"></i>
    </button>
</div>

