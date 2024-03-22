
<div class="btn-group">
    {{--@if($data->mode != "Public Bidding")
        <a class="btn btn-default btn-sm edit_btn" href="{{route('dashboard.po.edit',$data->slug)}}">
            <i class="fa fa-edit"></i>
        </a>
    @endif--}}
    @if($data->transaction->document_type == "MANUAL PO")
        <a class="btn btn-default btn-sm" href="{{route('dashboard.po.printManual',$data->slug)}}" target="_blank">
            <i class="fa fa-print"></i>
        </a>
    @else
        <a class="btn btn-default btn-sm edit_btn" href="{{route('dashboard.po.edit',$data->slug)}}">
            <i class="fa fa-edit"></i>
        </a>
        <a class="btn btn-default btn-sm" href="{{route('dashboard.po.print1',$data->slug)}}" target="_blank">
            <i class="fa fa-print"></i>
        </a>
    @endif
        @if($data->transaction != null)
            @if($data->transaction->cancelled_at == null)
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a style="color: #dd4b39" href="#" class="cancel_transaction_btn text-danger" data="{{$data->slug}}" data-original-title="" title=""><i class="fa fa-times"></i> Cancel Transaction</a>
                        </li>
                    </ul>
                </div>
            @endif
        @endif
</div>

