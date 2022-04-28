@extends('layouts.modal-content')

@section('modal-header')
    Activity Logs
@endsection

@section('modal-body')
    @php
        $ignore = [
            'updated_at','created_at',
        ];
        $rand = \Illuminate\Support\Str::random(16);
    @endphp
    <table class="table table-bordered table-condensed" id="view_history_{{$rand}}">
        <thead>
            <tr>
                <th>Action</th>
                <th>Caused by</th>
                <th>Old</th>
                <th>Changed</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @if($activities->count() > 0)
                @foreach($activities as $activity)
                    @php
                        $user = new $activity->causer_type;
                        $user = $user->with('employee')->where('id','=',$activity->causer_id)->first();
                    @endphp
                    <tr>
                        <td>{{strtoupper($activity->description)}}</td>
                        <td>{{$user->employee->lastname}}, {{$user->employee->firstname}}</td>
                        <td>
                            @if(isset($activity->properties['old']))
                                @if(count($activity->properties['old']) > 0)
                                    <ul>
                                        @foreach($activity->properties['old'] as$key=> $old)
                                            @if(!in_array($key,$ignore))
                                                <li> {{$old}} - <span class="text-muted small"><i>{{strtoupper($key)}}</i></span></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if(isset($activity->properties['attributes']))
                                @if(count($activity->properties['attributes']) > 0)
                                    <ul>
                                        @foreach($activity->properties['attributes'] as $key=> $new)
                                            @if(!in_array($key,$ignore))
                                                <li> {{$new}} - <span class="text-muted small"><i>{{strtoupper($key)}}</i></span></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </td>
                        <td>
                            {{\Illuminate\Support\Carbon::parse($activity->created_at)->format('Y-m-d | h:i A')}}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endsection

@section('modal-footer')

@endsection

@section('scripts')
    <script type="text/javascript">
        $("#view_history_{{$rand}}").DataTable({
            order : [[4,'desc']]
        });
    </script>
@endsection

