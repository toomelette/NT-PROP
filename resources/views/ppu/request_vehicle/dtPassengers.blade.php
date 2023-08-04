@if(!empty($data->passengers))
    <ul style="padding-left: 15px; font-size: 12px;">
        @foreach($data->passengers as $passenger)
            <li>{{$passenger->name}}</li>
        @endforeach
    </ul>
@endif