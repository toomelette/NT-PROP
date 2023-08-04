@if(!empty($data->details))
    <ul style="padding-left: 15px; font-size: 12px; font-family: Consolas">
        @foreach($data->details as $detail)
            <li>{{\App\Swep\Helpers\Helper::dateFormat($detail->datetime,'M. d, Y | h:i A')}} - {{$detail->destination}}</li>
        @endforeach
    </ul>
@endif