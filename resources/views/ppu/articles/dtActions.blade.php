<div class="btn-group">
    <button type="button" class="btn btn-default btn-sm show_article_btn" data="{{$data->id}}" data-toggle="modal" data-target="#show_article_modal" title="" data-placement="left" data-original-title="View more">
        <i class="fa fa-file-text"></i>
    </button>
    <button type="button" class="btn btn-default btn-sm edit_article_btn" data="{{$data->id}}" data-toggle="modal" data-target="#edit_article_modal" title="" data-placement="left" data-original-title="Edit">
        <i class="fa fa-edit"></i>
    </button>
    <button type="button" onclick="delete_data('{{$data->id}}','{{route('dashboard.articles.destroy',$data->id)}}')" data="{{$data->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
        <i class="fa fa-trash"></i>
    </button>
</div>