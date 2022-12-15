<div class="btn-group">
    <a href="{{route('dashboard.pap.show',$data->slug)}}"  type="button" class="btn btn-default btn-sm list_submenus_btn"  title="" data-placement="left" data-original-title="Submenus">
        <i class="fa fa-list"></i>
    </a>
    <button type="button" data="{{$data->slug}}" class="btn btn-default btn-sm edit_pap_btn" data-toggle="modal" data-target="#edit_pap_modal" title="" data-placement="top" data-original-title="Edit">
        <i class="fa fa-edit"></i>
    </button>
    <button type="button" onclick="delete_data('{{$data->slug}}','{{route("dashboard.pap.destroy",$data->slug)}}')" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
        <i class="fa fa-trash"></i>
    </button>
</div>
<a href="{{route('dashboard.ppmp_modal.index')}}?pap={{$data->slug}}" ><button class="btn btn-default btn-sm" data="{{$data->slug}}" style="margin-top: 5px; width: 97px"> <i class="fa icon-procurement"></i> PPMP</button></a>
