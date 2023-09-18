@forelse($data->transDetails as $qty)
    <p>{{$qty->qty}}</p>
    @empty
@endforelse

