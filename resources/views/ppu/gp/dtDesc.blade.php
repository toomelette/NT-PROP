@forelse($data->GatePassDetails as $description)
    <p>{{$description->description}}</p>
    @empty
@endforelse

