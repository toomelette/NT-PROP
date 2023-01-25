
<div class="btn-group">
    <button class="btn btn-default btn-sm show_rfq_btn" type="button" data-toggle="modal" data-target="#prepare_rfq_modal" data="{{$data->slug}}">
        <i class="fa fa-file-text"></i>
    </button>

    <a class="btn btn-default btn-sm" type="button" href="{{route('dashboard.rfq.print',$data->slug)}}" target="_blank" data="{{$data->slug}}">
        <i class="fa fa-print"></i> RFQ
    </a>
</div>

