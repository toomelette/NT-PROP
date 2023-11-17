@forelse($data->GatePassDetails as $item)
    <p>{{$item->item}}</p>
    @empty
@endforelse

