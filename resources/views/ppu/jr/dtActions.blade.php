<div class="btn-group">
    <button type="button" class="btn btn-default btn-sm show_pr_btn" data="{{$jr->slug}}" data-toggle="modal" data-target="#show_pr_modal" title="" data-placement="left" data-original-title="View more">
        <i class="fa fa-file-text"></i>
    </button>
    <a class="btn btn-default btn-sm print_pr_btn" data="{{$jr->slug}}" target="popup" href="{{route('dashboard.jr.print',$jr->slug)}}"title="" data-placement="left" data-original-title="Print">
        <i class="fa fa-print"></i>
    </a>
    @if($jr->user_created == \Illuminate\Support\Facades\Auth::user()->user_id && 1==2)
        <button type="button" class="btn btn-default btn-sm edit_jr_btn" data="{{$jr->slug}}" data-toggle="modal" data-target="#edit_jr_modal" title="" data-placement="left" data-original-title="Edit">
            <i class="fa fa-edit"></i>
        </button>
        <button type="button" onclick="delete_data('{{$jr->slug}}','{{route('dashboard.jr.destroy',$jr->slug)}}')" data="{{$jr->slug}}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
            <i class="fa fa-trash"></i>
        </button>
    @endif

    @if($jr->received_at == null)
        <button type="button" class="btn btn-default btn-sm receive_btn" data="{{$jr->slug}}" title="" data-placement="left" data-original-title="Receive">
            <i class="fa  fa-download"></i>
        </button>
    @endif

    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            @if($jr->cancelled_at == null)
                <li>
                    <a style="color: #dd4b39" href="#" class="cancel_transaction_btn text-danger" data="{{$jr->slug}}" data-original-title="" title=""><i class="fa fa-times"></i> Cancel Transaction</a>
                </li>
                <li>
                    <a class="btn btn-default btn-sm text-black" data="{{$jr->slug}}" target="popup" href="{{route('dashboard.jr.monitoringIndex', $jr->ref_no)}}"title="" data-placement="left" data-original-title="View">
                        View Monitoring
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>