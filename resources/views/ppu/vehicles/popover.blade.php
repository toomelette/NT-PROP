<small>
    Purpose: <span class="text-strong">{{$data->purpose ?? ''}}</span>
    <br>
    Destination: <span class="text-strong">{{$data->destination ?? ''}}</span>
    <br>
    Vehicle: <span class="text-strong">{{$data->vehicleAssigned->make ?? ''}} {{$data->vehicleAssigned->model1 ?? ''}} - {{$data->vehicleAssigned->plate_no ?? ''}}</span>
    <br>
    From: <span class="text-strong">{{$data->from ?? ''}}</span>
    <br>
    To:<span class="text-strong">{{$data->to ?? ''}}</span>
    <br>
    Authorized Passenger(s):
    @if(!empty($data->passengers))
        <ul style="padding-left: 12px">
            @foreach($data->passengers as $passenger)
                <li class="text-strong">{{$passenger->name}}</li>
            @endforeach
        </ul>
    @endif
</small>
