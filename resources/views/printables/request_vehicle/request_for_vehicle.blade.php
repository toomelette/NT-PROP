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
    <table style="width: 100%; font-family: Cambria; font-size: 14px" class="tbl-bordered">
        <tr>
            <td style="width: 50%; margin: 0px !important;">
                <p style="font-size: 22px; margin-top: 10px" class="text-strong">REQUEST FOR SHUTTLE SERVICE</p>
            </td>

            <td style="padding-right: 0px !important; padding-left: 0px !important; padding-top: 0px !important; padding-bottom: 0px !important;">
                <table style="width: 100%; font-size: 14px" >
                    <tr style="border-bottom: 1px solid black;">
                        <td style="text-align: center;">Request No.</td>
                        <td class="text-center text-strong">{{$request->request_no}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Date & Time</td>
                        <td class="text-strong text-center">{{\App\Swep\Helpers\Helper::dateFormat($request->created_at,'F d, Y')}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width: 100%; font-family: Cambria; font-size: 14px; border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;">
        <tr style="border-bottom: 1px solid black;">
            <td style="width: 10%; border-right: 1px solid black;">Requisitioner</td>
            <td colspan="4" class="text-strong text-left">{{$request->name}}</td>
        </tr>
        <tr style="border-bottom: 1px solid black;">
            <td style="border-right: 1px solid black;">Dept/Div:</td>
            <td colspan="4" class="text-strong text-left">{{$request->responsibilityCenter->desc ?? ''}}</td>
        </tr>
        <tr style="border-bottom: 1px solid black;">
            <td style="border-right: 1px solid black;">Purpose:</td>
            <td colspan="4" class="text-strong text-left">{{$request->purpose}}</td>
        </tr>
        <tr style="border-bottom: 1px solid black;">
            <td style="border-right: 1px solid black;">Authorized Passenger(s):</td>
            <td colspan="4" class="text-strong text-left">{{$request->passengers->implode('name','; ')}}</td>
        </tr>


        <tr style="border-bottom: 1px solid black;">
            <td style="border-right: 1px solid black;">Destination:</td>
            <td colspan="3" class="text-strong">{{$request->destination}}</td>
        </tr>
        <tr style="border-bottom: 1px solid black;">
            <td style="width: 20%; border-right: 1px solid black;"><small>Date and Time of Departure</small></td>
            <td style="width: 30%; border-right: 1px solid black;" class="text-strong">{{\App\Swep\Helpers\Helper::dateFormat($request->from,'F d, Y | h:i A')}}</td>
            <td style="width: 20%; border-right: 1px solid black;"><small> Date and Time of Return (If applicable)</small></td>
            <td class="text-strong">{{\App\Swep\Helpers\Helper::dateFormat($request->to,'F d, Y | h:i A')}}</td>
        </tr>
        <tr style="border-bottom: 1px solid black;">
            <td style="border-right: 1px solid black;">Vehicle Assigned</td>
            <td style="border-right: 1px solid black;" class="text-strong">{{$request->vehicleAssigned->make ?? ''}} {{$request->vehicleAssigned->model ?? ''}} - {{$request->vehicleAssigned->plate_no ?? ''}}</td>
            <td style="border-right: 1px solid black;">Driver Assigned</td>
            <td class="text-strong">{{$request->driverAssigned->employee->fullname ?? ' - '}}</td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; font-family: Cambria">
        <tr>
            <td style="width: 50%">
                Signature of Requisitioner: <br><br><br><br><br>
                <b style="font-size: 14px;">{{$request->requested_by}}</b><br>
                <span style="font-size: 12px; font-style: italic">{{ ucwords(strtoupper($request->requested_by_position)) }}</span>

            </td>
            <td style="width: 50%">
                Approved by: <br><br><br><br><br>
                <b style="font-size: 14px;">{{$request->approved_by}}</b><br>
                <span style="font-size: 12px; font-style: italic">{{ ucwords(strtoupper($request->approved_by_position))}}</span>
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