<small>
    Ticket No.: <span class="text-strong">{{$data->ticket_no ?? ''}}</span>
    <br>
    Purpose: <span class="text-strong">{{$data->purpose ?? ''}}</span>
    <br>
    Destination: <span class="text-strong">{{$data->destination ?? ''}}</span>
    <br>
    Vehicle: <span class="text-strong">{{$data->vehicles->make ?? ''}} {{$data->vehicles->model1 ?? ''}} - {{$data->vehicles->plate_no ?? ''}}</span>
    <br>
    From: <span class="text-strong">{{$data->departure ?? ''}}</span>
    <br>
    To:<span class="text-strong">{{$data->return ?? ''}}</span>
    <br>
    Authorized Passenger(s): {{$data->passengers}}
    @php
        $passengers = explode(',',$data->passengers);
    @endphp
    @if(!empty($data->passengers))
        <ul style="padding-left: 12px">
            @foreach($passengers as $passenger)
                <li class="text-strong">{{$passenger}}</li>
            @endforeach
        </ul>
    @endif
</small>
