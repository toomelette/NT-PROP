@if(count($items) > 0)
    <table class="milestone jr_items" style="width: 100%; font-size: 12px">
        <thead>
        <tr>
            <th class="text-center" >Qty, Unit</th>
            <th class="text-center">Item, Desc</th>
{{--            <th class="text-center">Unit Cost</th>--}}
{{--            <th class="text-center">Total Cost</th>--}}
        </tr>
        </thead>
        <tbody>

        @foreach($items as $item)
            @php
                $max = 3;
            @endphp
            @if($loop->iteration < $max)
                <tr>
                    <td class="text-center">{{ $item->qty  }} {{strtoupper($item->unit)}}</td>
                    <td class="text-left">
                        {{$item->item }}
                        @if($item->description != '')
                            <br>
                            <span style="white-space: pre-wrap"> - {!! \Illuminate\Support\Str::limit($item->description,150,' ... ') !!}</span>
                        @endif
                </tr>
            @else
                @php
                    $remaining = $loop->count - $max + 1;
                @endphp
                <tr>
                    <td colspan="4" class="text-center">{{$remaining}} more item{{$remaining > 1 ? 's': ''}}</td>
                </tr>
                @php
                    break;
                @endphp
            @endif

        @endforeach
        </tbody>
    </table>
@else
    <small class="text-center text-muted">No items.</small>
@endif