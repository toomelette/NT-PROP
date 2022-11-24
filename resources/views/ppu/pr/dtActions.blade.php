<div class="btn-group">
    <button type="button" class="btn btn-default btn-sm show_pr_btn" data="{{$pr->slug}}" data-toggle="modal" data-target="#show_pr_modal" title="" data-placement="left" data-original-title="View more">
        <i class="fa fa-file-text"></i>
    </button>
    <a class="btn btn-default btn-sm print_pr_btn" data="{{$pr->slug}}" target="popup" href="{{route('dashboard.pr.print',$pr->slug)}}"title="" data-placement="left" data-original-title="Print">
        <i class="fa fa-print"></i>
    </a>
    <button type="button" class="btn btn-default btn-sm edit_pr_btn" data="{{$pr->slug}}" data-toggle="modal" data-target="#edit_pr_modal" title="" data-placement="left" data-original-title="Edit">
        <i class="fa fa-edit"></i>
    </button>
    <button type="button" onclick="delete_data('{{$pr->slug}}','{{route('dashboard.pr.destroy',$pr->slug)}}')" data="{{$pr->slug}}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
        <i class="fa fa-trash"></i>
    </button>
</div>