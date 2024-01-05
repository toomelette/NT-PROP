@forelse($data->wasteDetails as $item)
    <p>{{$item->item}}</p>
    @empty
@endforelse

