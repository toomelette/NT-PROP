@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('printables.print_layouts.print_layout_main')

@section('wrapper')

    <table style="width: 100%; font-family: Cambria; font-size: 14px">
        <tr>
            <td style="width: 12%">
                <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
            </td>
            <td class="text-left">
                <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                <p class="no-margin">Administration and Finance Department</p>
                <p class="no-margin">Property/Procurement/Building and Transportation Maintenance Section</p>
            </td>
            <td style="width: 25%">

            </td>
        </tr>
    </table>
    <table style="width: 100%; font-family: Cambria; font-size: 14px">
        <tr>
            <td style="width: 50%">
                <p style="font-size: 22px" class="text-strong">REQUEST FOR SHUTTLE SERVICE</p>
            </td>
            <td style="width: 15%">

            </td>
            <td style="padding-right: 0px !important;">
                <table style="width: 100%; font-size: 14px" class="tbl-bordered">
                    <tr>
                        <td>Request No.</td>
                        <td class="b-bottom text-center text-strong">{{$request->request_no}}</td>
                    </tr>
                    <tr>
                        <td>Date & Time</td>
                        <td class="text-strong b-bottom text-center">{{\App\Swep\Helpers\Helper::dateFormat($request->created_at,'F d, Y')}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width: 100%; font-family: Cambria; font-size: 14px" class="tbl-bordered">
        <tr>
            <td style="width: 10%">Requisitioner</td>
            <td class="text-strong text-left">{{$request->name}}</td>
        </tr>
        <tr>
            <td>Dept/Div:</td>
            <td class="text-strong text-left">{{$request->responsibilityCenter->desc ?? ''}}</td>
        </tr>
        <tr>
            <td>Purpose:</td>
            <td class="text-strong text-left">{{$request->purpose}}</td>
        </tr>
        <tr>
            <td>Authorized Passenger(s):</td>
            <td class="text-strong text-left">{{$request->passengers->implode('name','; ')}}</td>
        </tr>
    </table>
    <br>

    <table style="width: 100%; font-family: Cambria" class="tbl-bordered">
        <thead>
        <tr>
            <th style="width: 25%; font-size: 11px !important;">Date and Time of Departure</th>
            <th class="text-center">Destination</th>
            <th class="text-center">Vehicle Assigned</th>
            <th class="text-center">Driver Assigned</th>
        </tr>
        </thead>
        <tbody>
            @if(!empty($request->details))
                @foreach($request->details as $detail)
                    <tr>
                        <td>{{\App\Swep\Helpers\Helper::dateFormat($detail->datetime,'M. d, Y | h:i A')}}</td>
                        <td>{{$detail->destination}}</td>
                        <td>{{$detail->vehicle->make ?? ''}} {{$detail->vehicle->model ?? ''}} - {{$detail->vehicle->plate_no ?? ''}}</td>
                        <td>{{$detail->driver->employee->fullname ?? '-'}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <br>
    <table style="width: 100%; font-family: Cambria;font-size: 14px">
        <tr>
            <td style="width: 50%">
                Signature of Requisitioner: <br><br><br>
                <b>{{$request->requested_by}}</b><br>
                <span style="font-size: 12px;font-style: italic">{{ucwords(strtolower($request->requested_by_position))}}</span>
            </td>
            <td style="width: 50%">
                Approved by: <br><br><br>
                <b>{{$request->approved_by}}</b><br>
                <span style="font-size: 12px;font-style: italic">{{ucwords(strtolower($request->approved_by_position))}}</span>
            </td>
        </tr>
    </table>
@endsection

@section('scripts')
    <script type="text/javascript">

        $(document).ready(function () {
            print();
        })

    </script>
@endsection