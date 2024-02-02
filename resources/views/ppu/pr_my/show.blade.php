@extends('layouts.modal-content')

@section('modal-header')
    Timeline
@endsection

@section('modal-body')

    <div class="tab-pane active" id="timeline">

        <ul class="timeline timeline-inverse">

            @forelse($timeline as $dateGroup => $actions)
                <li class="time-label">
                <span class="bg-blue">
                    {{Carbon::parse($dateGroup)->format('M. d, Y')}}
                </span>
                </li>
                @forelse($actions as $title => $action)
                    @switch($title)
                        @case('PR received by PPBTMS.')
                            <li>
                                <i class="fa fa-check bg-green"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {{Carbon::parse($action->received_at)->format('h:i A')}}</span>
                                    <h3 class="timeline-header no-border"><a href="#">{{$title}} {{$action->ref_no}}</a>
                                    </h3>
                                </div>
                            </li>
                        @break
                        @case('AQ Finalized.')
                            <li>
                                <i class="fa fa-check bg-green"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {{Carbon::parse($action->updated_at)->format('h:i A')}}</span>
                                    <h3 class="timeline-header no-border"><a href="#">{{$title}} {{$action->ref_no}}</a>
                                    </h3>
                                </div>
                            </li>
                        @break
                        @default
                            <li>
                                <i class="fa fa-check bg-green"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {{Carbon::parse($action->created_at ?? null)->format('h:i A')}}</span>
                                    <h3 class="timeline-header no-border"><a href="#">{{$title}} {{$action->ref_no ?? 'N/A'}}</a>
                                    </h3>
                                </div>
                            </li>
                        @break
                    @endswitch

                @empty
                @endforelse
            @empty
            @endforelse
        </ul>
    </div>
@endsection

@section('modal-footer')

@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection

