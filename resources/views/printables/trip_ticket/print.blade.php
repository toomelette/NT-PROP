@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    $rand = \Illuminate\Support\Str::random();

    $passengers = collect(explode(",",$tt->passengers))->chunk(3);
@endphp

@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')

    <div class="printable" style="break-after: page">
        <div style="width: 100%;">
            <div class="" style="margin-bottom: 100px; padding-top: 20px;">
                <div>
                    <img src="{{ asset('images/sra.png') }}" style="width:100px; float: left">
                </div>
                <div style="float: left; font-family: cambria; text-align: left; margin-left: 15px; margin-top: 10px">
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Republic of the Philippines</p>
                    <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">{{\App\Swep\Helpers\Values::headerAddress()}}, {{\App\Swep\Helpers\Values::headerTelephone()}}</p>
                    <p class="no-margin text-strong" style="font-size: 14px;">
                        PROPERTY/PROCUREMENT/BUILDING & TRANSPORT MAINTENANCE SECTION
                    </p>
                </div>

                <span class="" style="float: right">
                    {{ QrCode::size(50)->generate(route("dashboard.trip_ticket.index",$tt->slug)) }}
                </span>

                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin-top: 105px">
                    <tbody>

                    <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                        <td rowspan="2" style="width: 49%; border-right: 1px solid black; font-size: 30px">
                            <strong>DRIVER'S TRIPS TICKET</strong>
                        </td>
                        <td style="margin-top: 5px; width: 14%; border-right: 1px solid black; border-top: 1px solid black; position: relative;">
                            Trip Ticket No.:
                        </td>
                        <td style="border-top: 1px solid black;" class="text-strong ">
                            {{$tt->ticket_no}}
                        </td>


                    </tr>
                        <td style="margin-top: 5px; width: 14%; border-right: 1px solid black; border-top: 1px solid black; position: relative;">
                            Date:
                        </td>
                        <td style="border-top: 1px solid black; " class="text-strong ">
                            {{$tt->date}}
                        </td>
                    </tbody>
                </table>

                <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >


                    <tr style="border: 1px solid black; width: 100%;">

                        <td rowspan="2" style="border-right: 1px solid black; width: 13.4%; vertical-align: center;">
                            Vehicle:
                        </td>
                        <td rowspan="2" class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 34.7%">
                            {{$tt->vehicles->make . ' ' .$tt->vehicles->model . ' - ' . $tt->vehicles->plate_no}}
                        </td>

                        <td style="border-right: 1px solid black; width: 13.75%; vertical-align: center;">
                            Driver:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 36.3%">
                            {{$tt->drivers->employee->fullname}}
                        </td>
                    </tr>

                </table>

                <table style="font-family: Cambria,Arial; width: 100%; border: #0a0a0a;" >


                    <tr style=" width: 100%; border-bottom: 1px solid black; ">

                        <td  rowspan="2" style="border-right: 1px solid black; border-left: 1px solid black; width: 13.4%; vertical-align: top;">
                            Purpose:
                        </td>
                        <td rowspan="2" class="text-strong" style="border-right: 1px solid black; vertical-align: top;  width: 34.7%">
                            {{$tt->purpose}}
                        </td>

                        <td  style="border-right: 1px solid black; border-left: 1px solid black; width: 13.4%; vertical-align: center;">
                            Departure:
                        </td>
                        <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 34.7%">
                            {{$tt->departure}}
                        </td>

                    </tr>

                    <tr style=" width: 100%; border-bottom: 1px solid black; ">

                        <td style="border-right: 1px solid black; width: 13.75%; vertical-align: center;">
                            Return:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 36.3%">
                            {{$tt->return}}
                        </td>

                    </tr>

                </table>

                <table style="font-family: Cambria,Arial; width: 100%; border: #0a0a0a;" >


                    <tr style=" width: 100%; border-left: 1px solid black; border-bottom: 1px solid black; ">

                        <td style="border-right: 1px solid black; width: 13.62%; vertical-align: center;">
                            Destination:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 87.25%">
                            {{$tt->destination}}
                        </td>


                    </tr>

                </table>

                <table style="font-family: Cambria,Arial; width: 100%; border: #0a0a0a;  text-align: center" >
                    <tr style=" width: 100%; border-bottom: 1px solid black; ">
                        <td rowspan="2" style="border-right: 1px solid black; border-left: 1px solid black; font-size: 15px; width: 62.5%; vertical-align: center;">
                            <b>Fuel Issued, Purchased, Used</b>
                        </td>
                        <td rowspan="2" style="border-right: 1px solid black; border-left: 1px solid black; font-size: 15px; width: 37.5%; vertical-align: center;">
                            <b>Odometer</b>
                        </td>
                    </tr>
                </table>

                <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >

                    <div style="font-family: Cambria, Arial; font-size: 13px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; display: flex;">

                        <!-- Left column -->
                        <div style="width: 29%; padding: 10px; ">
                            <h5 style="margin: 0 0 10px 0; text-align: left; width: 200px;">Balance in tank (L):</h5>
                            <h5 style="margin: 0 0 10px 0; text-align: left; width: 200px;">Issued from Office/Supplier (L): </h5>
                            <h5 style="margin: 0 0 10px 0; text-align: left; width: 200px;">Purchase/s during trip (L): </h5>
                            <h5 style="margin: 0; text-align: left; width: 200px;">TOTAL (L): </h5>
                        </div>

                        <div style="width: 10%; padding-right: 10px; border-right: 1px solid black; text-align: right;">
                            <h5><u><b style="margin-left: 2px">{{ $tt->gas_balance ?? "________" }}</b></u></h5>
                            <h5><u><b style="margin-left: 2px">{{ $tt->gas_issued ?? "________" }}</b></u></h5>
                            <h5><u><b style="margin-left: 2px">{{ $tt->purchased ?? "________" }}</b></u></h5>
                            <h5><u><b style="margin-left: 2px">{{ $tt->total ?? "________" }}</b></u></h5>
                        </div>

                        <!-- Middle column -->
                        <div style="width: 30%; padding: 10px; border-right: 1px solid black;">
                            <h5 style="margin: 0; text-align: left;">Fuel consumed (L): </h5>
                            <h5><u><b style="margin-left: 5px; text-align: left;">{{$tt->consumed ?? "________" }}</b></u></h5>
                            <h5 style="margin: 0; text-align: left;">Balance at end of Trip (L): </h5>
                            <h5><u><b style="margin-left: 2px; text-align: left;">{{$tt->gas_remaining_balance ?? "________" }}</b></u></h5>
                        </div>

                        <!-- Right column -->
                        <div style="width: 29%; padding: 10px;">
                            <h5 style="margin: 0 0 10px 0; text-align: left; width: 200px;">Odometer: Start of trip: </h5>
                            <h5 style="margin: 0 0 10px 0; text-align: left; width: 200px;">Odometer: End of trip: </h5>
                            <h5 style="margin: 0; text-align: left; width: 200px;">Distance Travelled (KM): </h5>
                        </div>

                        <div style="width: 10%; text-align: right; padding-right: 10px; ">
                            <h5><u><b style="margin-left: 2px">{{$prevOdo = $tt->vehicles->odometer + $tt->vehicles->tripTickets->sum('distance_traveled') ?? "________" }}</b></u></h5>
                            <h5>
                                <u>
                                <b style="margin-left: 2px">
                                    @if($tt->distance_traveled != null)
                                    {{$prevOdo + $tt->distance_traveled ?? "________" }}
                                        @else
                                        ________
                                    @endif
                                </b>
                                </u>
                            </h5>
                            <h5><u><b style="margin-left: 5px">{{$tt->distance_traveled ?? "________" }}</b></u></h5>
                        </div>
                    </div>

                </table>

                <div style="font-family: Cambria,Arial; display: flex; border-right: 1px solid black; border-bottom: 1px solid black">

                    <div style="flex: 1; text-align: center; border-left: 1px solid black">

                        <h5 class="" style="margin-left: 10px; margin-right: 10px; margin-bottom: 10px; text-align: justify; float: left">
                            I hereby certify to the correctness of the above statement / travel:
                        </h5><br><br><br>

                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            <b><u>{{$tt->drivers->employee->fullname}}</u></b>
                        </td><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            DRIVER
                        </td><br>


                    </div>

                    <div style="font-family: Cambria,Arial; flex: 1; text-align: center; border-left: 1px solid black">

                        <h5 class="" style="margin-left: 10px; margin-bottom: 10px; text-align: justify; float: left">Approved by:</h5><br><br><br>

                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            <b><u>{{$tt->approved_by}}</u></b>
                        </td><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            {{$tt->approved_by_designation}}
                        </td><br>

                    </div>

                </div>

                <div style="font-family: Cambria,Arial; border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black">
                    <br><h5 class="" style=" margin: 0px; padding-left: 10px; float: left">
                        I hereby certify that this vehicle is used on official business / travel:
                    </h5><br>
                    <h5 class="" style="margin: 0px; padding-left: 10px; float: left">
                        <i>(Signature of Authorized Passengers)</i>
                    </h5><br><br><br>

                    @foreach($passengers as $group)
                        <div>
                            @foreach($group as $passenger)
                                <div style="float: left; width: 33.33%; ">
                                    <h5 class="" style="margin: 0;">
                                        <u>{{$passenger}}</u>
                                    </h5>
                                </div>
                            @endforeach
                            <br><br><br>
                        </div>
                    @endforeach
                    <br><br><br>
                </div>
            </div>
        </div>
    </div>




@endsection

@section('scripts')


 <script type="text/javascript">
     $(document).ready(function () {
             print();
     })
 </script>
@endsection