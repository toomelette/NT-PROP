<small>
    Driver: <span class="text-strong">{{$data->driver->employee->fullname ?? ''}}</span>
    <br>
    Destination: <span class="text-strong">{{$data->destination ?? ''}}</span>
    <br>
    Vehicle: <span class="text-strong">{{$data->vehicle->make ?? ''}} {{$data->vehicle->model ?? ''}} - {{$data->vehicle->plate_no ?? ''}}</span>
    <br>
    Authorized Passenger(s):
    @if(!empty($data->requestForVehicle->passengers))
        <ul style="padding-left: 13px">
            @foreach($data->requestForVehicle->passengers as $passenger)
                <li>{{$passenger->name}}</li>
            @endforeach
        </ul>
    @endif
</small>
