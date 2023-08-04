@extends('layouts.modal-content')

@section('modal-header')
    {{$request->request_no}} | {{$request->requested_by}}
@endsection

@section('modal-body')

    <div class="well well-sm">
        <div class="row">
            <div class="col-md-2">
                <dl style="margin-bottom: 5px">
                    <dt>Request No.:</dt>
                    <dd>{{$request->request_no}}</dd>
                </dl>
            </div>
            <div class="col-md-3">
                <dl style="margin-bottom: 5px">
                    <dt>Date & Time of Request:</dt>
                    <dd>{{\App\Swep\Helpers\Helper::dateFormat($request->created_at,'Y-m-d \a\t h:i A')}}</dd>
                </dl>
            </div>
            <div class="col-md-7">
                <dl style="margin-bottom: 5px">
                    <dt>Authorized Passengers:</dt>
                    <dd>
                        @if(!empty($request->passengers))
                            {{$request->passengers->implode('name','; ')}}
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <dl style="margin-bottom: 5px">
                    <dt>Action:</dt>
                    <dd>{{$request->action}}</dd>
                </dl>
            </div>
            <div class="col-md-4">
                <dl style="margin-bottom: 5px">
                    <dt>Action made by:</dt>
                    <dd>{{$request->action_by}} at {{$request->action_at}}</dd>
                </dl>
            </div>
            <div class="col-md-6">
                @if($request->action == 'DISAPPROVED')
                <dl style="margin-bottom: 5px">
                    <dt>Reason for disapproval:</dt>
                    <dd>
                        {{$request->remarks}}
                    </dd>
                </dl>
                @endif
            </div>
        </div>
    </div>
    @if($request->action == 'APPROVED')
        <table class="table table-condensed table-bordered table-striped">
            <thead>
            <tr>
                <th>Date & time of Departure</th>
                <th>Destination</th>
                <th>Vehicle Assigned</th>
                <th>Driver Assigned</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($request->details))
                @foreach($request->details as $detail)
                    <tr>
                        <td>{{\App\Swep\Helpers\Helper::dateFormat($detail->datetime,'M. d, Y | h:i A')}}</td>
                        <td>{{$detail->destination}}</td>
                        <td>{{$detail->vehicle->make ?? ''}} {{$detail->vehicle->model ?? ''}} - {{$detail->vehicle->plate_no ?? ''}}</td>
                        <td>{{$detail->driver->employee->fullname ?? ''}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    @endif
@endsection

@section('modal-footer')
    <button class="btn btn-default pull-right" data-dismiss="modal">Close</button>
@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection

