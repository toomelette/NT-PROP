@forelse($data->transDetails as $item)
    <p>{{$item->item}}</p>
    @empty
@endforelse

