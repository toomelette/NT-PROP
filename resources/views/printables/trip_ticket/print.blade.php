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
            </div>

            <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black;  border-left: 1px solid black; border-right: 1px solid black; margin-top: 105px">
                <tbody>

                    <tr style="border-top: 1px solid black; border-left: 1px solid black;  text-align: center;  border-right: 1px solid black;">
                        <td rowspan="2" style="width: 65%; border-right: 1px solid black; font-size: 30px">
                            <strong>DRIVER'S TRIP TICKET</strong>
                        </td>
                        <td style="width: 35%;  font-size: 15px; text-align: left;">
                           Ticket No.:  &nbsp;  &nbsp;<strong>{{$tt->ticket_no}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 35%;  font-size: 15px; text-align: left; border-top: 1px solid black;" >
                            Date:  &nbsp;  &nbsp; <strong>{{$tt->date}}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; ">

                    <tr style=" border-left: 1px solid black; border-top: 1px solid black; text-align: left;  border-right: 1px solid black;">
                        <td style="width: 50%;  font-size: 17px">
                            <strong>A. &nbsp; &nbsp; To be filled by the Administrative Official Authorizing Official Travel</strong>
                        </td>
                    </tr>

            </table>

            <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; ">

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        1. Name of driver of the Vehicle
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->drivers->employee->fullname}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        2. Government car to be used, Plate No.
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->vehicles->make . ' ' .$tt->vehicles->model1 . ' - ' . $tt->vehicles->plate_no}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        3. Name of Authorized passenger(s)
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        * SEE BELOW *
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        4. Place or places to be visited/inspected
                    </td>
                    <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->destination}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        5. Purpose
                    </td>
                    <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->purpose}}
                    </td>
                </tr>

            </table>

            <table style="font-family: Cambria,Arial; width: 100%; border-left: 1px solid black; border-right: 1px solid black; ">

                <tr style=" border-left: 1px solid black; text-align: left;  border-right: 1px solid black;">
                    <td style="width: 50%;  font-size: 17px">
                        <strong>B. &nbsp; &nbsp; To be filled by the Driver</strong>
                    </td>
                </tr>

            </table>

            <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; ">

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        1. Time of departure from office/garage
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->departure ?? null}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        2. Time of arrival at (per No.4)
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->arrival ?? null}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        3. Time of departure from (per No.4)
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->return_departure ?? null}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        4. Time of arrival back to office/garage
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->return ?? null}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        5. Approximate distance travelled (to and from) (KM)
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->distance_traveled ?? null}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style=" width: 50%; vertical-align: center;">
                        <b>6. Gasoline issued, Purchased and Consumed (L)</b>
                    </td>
                </tr>


                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        &nbsp; 6.1. Balance in tank (L)
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->gas_balance}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        &nbsp; 6.2. Issued by the office from stock (L)
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->gas_issued}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        &nbsp; 6.3. Add-Purchased during trip (L)
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->purchased}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        &nbsp; 6.4. Deduct use in trip (L)
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->consumed}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        &nbsp; 6.5. TOTAL (L)
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->total}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        7. Gear oil issued
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->gear_oil ?? null}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        8. Lub. oil issued
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->lubricant_oil ?? null}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        9. Grease issued
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->grease ?? null}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style=" width: 50%; vertical-align: center;">
                        <b>10. Odometer Readings, if any</b>
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        &nbsp; 10.1. At beginning of trip
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->odometer_from}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        &nbsp; 10.2. At end of trip
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->odometer_to}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        &nbsp; 10.3. Distance Travelled (per No. 5) (KM)
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->distance_traveled}}
                    </td>
                </tr>

                <tr style="border: 1px solid black; width: 100%;">
                    <td  style="border-right: 1px solid black; width: 50%; vertical-align: center;">
                        11. Remarks
                    </td>
                    <td  class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 50%">
                        {{$tt->remarks ?? null}}
                    </td>
                </tr>

            </table>

            <div style="font-family: Cambria,Arial; display: flex; border-right: 1px solid black; border-bottom: 1px solid black">

                <div style="flex: 1; text-align: center; border-left: 1px solid black">

                    <h5 class="" style="margin-left: 10px; margin-right: 10px; margin-bottom: 10px; text-align: justify; float: left">
                        I hereby certify to the correctness of the above statement of record of travel.
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
                <br><h5 class="" style=" margin: 0px; padding-left: 10px; font-size: 17px; float: left">
                   I hereby certify that I/We used this motor vehicle on official business as stated above:
                </h5><br>
                <h5 class="" style="margin: 0px; padding-left: 10px; float: left">
                    <i>(Signature of Authorized Passengers)</i>
                </h5><br><br><br>

                @foreach($passengers as $group)
                    <div>
                        @foreach($group as $passenger)
                            <div style="float: left; width: 33.33%; ">
                                <h5 class="" style="margin: 0; font-size: 15px">
                                    <b><u>{{$passenger}}</u></b>
                                </h5>
                            </div>
                        @endforeach
                        <br><br><br>
                    </div>
                @endforeach
                <br><br>
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