@forelse($data->transDetails as $actual_qty)
    <p>{{$actual_qty->actual_qty}}</p>
    @empty
@endforelse

