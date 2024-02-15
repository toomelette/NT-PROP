<small>
    Driver: <span class="text-strong">{{$data->driverAssigned->employee->fullname ?? ''}}</span>
    <br>
    Destination: <span class="text-strong">{{$data->destination ?? ''}}</span>
    <br>
    Vehicle: <span class="text-strong">{{$data->vehicleAssigned->make ?? ''}} {{$data->vehicleAssigned->model1 ?? ''}} - {{$data->vehicleAssigned->plate_no ?? ''}}</span>
    <br>
    Authorized Passenger(s):
    @if(!empty($data->passengers))
        <ul style="padding-left: 13px">
            @foreach($data->passengers as $passenger)
                <li class="text-strong">{{$passenger->name}}</li>
            @endforeach
        </ul>
    @endif
</small>
