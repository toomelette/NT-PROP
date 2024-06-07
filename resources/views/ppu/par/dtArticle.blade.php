{{$data->article}}
<div class="table-subdetail" style="margin-top: 3px">
    {{$data->acctemployee_fname}}
    {!! $data->inv_date ? '<div class="table-subdetail" style="margin-top: 3px">Inventory Taken: ' . $data->inv_date . '</div>' : '' !!}
</div>
