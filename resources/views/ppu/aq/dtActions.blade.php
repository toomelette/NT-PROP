<div class="btn-group">
    <a class="btn btn-default btn-sm " type="button" href="{{route('dashboard.aq.create',$data->slug ?? '')}}" data="{{$data->slug}}">
        <i class="fa icon-procurement"></i>
        @if($all != "pending")
            Edit
        @else
            Prepare
        @endif
    </a>
    @if($all != "pending")
        @if(!empty($data->transaction->aq))
            <a class="btn btn-default btn-sm" href="{{route('dashboard.aq.print',$data->transaction->aq->slug)}}" target="_blank">
                <i class="fa fa-print"></i>
            </a>
            @if($data->transaction->aq->is_locked)
                <a class="btn btn-default btn-sm" id="aqUnlock" onclick="unlock();" data="{{$data->slug}}">
                    <i class="fa fa-unlock"></i>
                </a>
            @endif
        @endif
    @endif
    @if($data->document_type == "MANUAL AQ")
        <a class="btn btn-default btn-sm" href="{{route('dashboard.aq.print',$data->slug)}}" target="_blank">
            <i class="fa fa-print"></i>
        </a>
    @endif
</div>


