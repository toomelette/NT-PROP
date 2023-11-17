@forelse($data->GatePassDetails as $qty)
    <p>{{$qty->qty}}</p>
    @empty
@endforelse

