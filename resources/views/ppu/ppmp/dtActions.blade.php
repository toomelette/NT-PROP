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
    @if(Route::getCurrentRoute()->getName() == 'dashboard.ppmp.index')
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a href="#" data-toggle="modal" data-target="#subaccount_modal" class="subaccount_btn" data="{{$data->slug}}" data-original-title="" title=""><i class="fa icon-service-record"></i> Sub accounts</a></li>
        </ul>
    </div>
    @endif
</div>