@forelse($data->Vehicle as $vehicle)
    <p>{{$vehicle->vehicle}}</p>
@empty
@endforelse

