<div class="btn-group">
    <button type="button" class="btn btn-default btn-sm show_pr_btn" data="{{$jr->slug}}" data-toggle="modal" data-target="#show_pr_modal" title="" data-placement="left" data-original-title="View more">
        <i class="fa fa-file-text"></i>
    </button>
    <a class="btn btn-default btn-sm print_pr_btn" data="{{$jr->slug}}" target="popup" href="{{route('dashboard.jr.print',$jr->slug)}}"title="" data-placement="left" data-original-title="Print">
        <i class="fa fa-print"></i>
    </a>
    <button type="button" class="btn btn-default btn-sm edit_jr_btn" data="{{$jr->slug}}" data-toggle="modal" data-target="#edit_jr_modal" title="" data-placement="left" data-original-title="Edit">
        <i class="fa fa-edit"></i>
    </button>
    <button type="button" onclick="delete_data('{{$jr->slug}}','{{route('dashboard.my_jr.destroy',$jr->slug)}}')" data="{{$jr->slug}}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
        <i class="fa fa-trash"></i>
    </button>
</div>