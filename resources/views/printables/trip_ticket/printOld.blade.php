{{--@php--}}
{{--    use SimpleSoftwareIO\QrCode\Facades\QrCode;--}}
{{--    $rand = \Illuminate\Support\Str::random();--}}

{{--    $passengers = collect(explode(",",$tt->passengers))->chunk(3);--}}
{{--@endphp--}}

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


                <div style="margin-top: 100px; margin-bottom: -100px; text-align: right"><strong><u> Date: {{$tt->date}}</u></strong></div>

                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin-top: 105px">
                    <tbody>

                    <tr style="border-top: 1px solid black; border-left: 1px solid black; text-align: center;  border-right: 1px solid black;">
                        <td style="width: 100%; border-right: 1px solid black; font-size: 30px">
                            <strong>DRIVER'S TRIPS TICKET</strong>
                        </td>
                    </tr>

                    <tr style=" border-left: 1px solid black; text-align: left;  border-right: 1px solid black;">
                        <td style="width: 100%; border-right: 1px solid black; font-size: 17px">
                            <strong>A. &nbsp; &nbsp; To be filled by the Administrative Official Authorizing Official Travel:</strong>
                        </td>
                    </tr>

                    <tr style=" border-left: 1px solid black; text-align: left;  border-right: 1px solid black;">
                        <td style="width: 100%; border-right: 1px solid black; font-size: 15px">
                            <ol>
                               <li style="margin-bottom: 5px">&nbsp; &nbsp; Name of driver of the Vehicle:  &nbsp;  &nbsp;  <strong> {{$tt->drivers->employee->fullname}}</li></strong>
                               <li style="margin-bottom: 5px">&nbsp; &nbsp; Government car to be used, Plate No.:  &nbsp;  &nbsp;  <strong>  {{$tt->vehicles->make . ' ' .$tt->vehicles->model . ' - ' . $tt->vehicles->plate_no}} </li></strong>
                               <li style="margin-bottom: 5px">&nbsp; &nbsp; Name of Authorized passenger:  &nbsp;  &nbsp;  <strong> {{$tt->passengers}}</li></strong>
                               <li style="margin-bottom: 5px">&nbsp; &nbsp; Place or places to be visited/inspected:  &nbsp;  &nbsp;  <strong> {{$tt->destination}} </li></strong>
                               <li style="margin-bottom: 5px">&nbsp; &nbsp; Purpose:  &nbsp;  &nbsp;  <strong> {{$tt->purpose}} </li></strong>
                            </ol>
                        </td>
                    </tr>

                    <tr style=" border-left: 1px solid black; text-align: left;  border-right: 1px solid black;">
                        <td style="width: 100%; border-right: 1px solid black; font-size: 17px">
                            <strong>B. &nbsp; &nbsp; To be filled by the Driver:</strong>
                        </td>
                    </tr>


                    <tr style=" border-left: 1px solid black; text-align: left;  border-right: 1px solid black;">
                        <td style="width: 100%; border-right: 1px solid black; font-size: 15px">
                            <ol>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Time of departure from office/garage:  &nbsp;  &nbsp;  <strong> {{$tt->departure ?? null}}</li></strong>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Time of arrival at (per No.4):  &nbsp;  &nbsp;  <strong>  {{$tt->arrival ?? null}} </li></strong>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Time of departure from (per No.4):  &nbsp;  &nbsp;  <strong> {{$tt->return_departure ?? null}}</li></strong>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Time of arrival back to office/garage:  &nbsp;  &nbsp;  <strong> {{$tt->return ?? null}} </li></strong>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Approximate distance travelled (to and from):  &nbsp;  &nbsp;  <strong> {{$tt->distance_traveled ?? null}} </li></strong>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Gasoline issued, Purchased and consumed:
                                    <ul>
                                        <li style="margin-bottom: 5px"> &nbsp; &nbsp; Balance in tank:  &nbsp;  &nbsp;  <strong>{{$tt->gas_balance}}</strong> </li>
                                        <li style="margin-bottom: 5px"> &nbsp; &nbsp; Issued by the office from stock:  &nbsp;  &nbsp;  <strong>{{$tt->gas_issued}}</strong> </li>
                                        <li style="margin-bottom: 5px"> &nbsp; &nbsp; Add-Purchased during trip:  &nbsp;  &nbsp;  <strong>{{$tt->purchased}}</strong> </li>
                                        <li style="margin-bottom: 5px"> &nbsp; &nbsp; TOTAL:  &nbsp;  &nbsp;  <strong>{{$tt->total}}</strong> </li>
                                    </ul>
                                </li>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Gear oil issued:  &nbsp;  &nbsp;  <strong> {{$tt->gear_oil ?? null}} </li></strong>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Lub. oil issued:  &nbsp;  &nbsp;  <strong> {{$tt->lubricant_oil ?? null}} </li></strong>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Grease issued:  &nbsp;  &nbsp;  <strong> {{$tt->grease ?? null}} </li></strong>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Odometer readings, if any:
                                    <ul>
                                        <li style="margin-bottom: 5px"> &nbsp; &nbsp; At beginning of trip:  &nbsp;  &nbsp;  <strong>{{$tt->odometer_from}}</strong> </li>
                                        <li style="margin-bottom: 5px"> &nbsp; &nbsp; At end of trip:  &nbsp;  &nbsp;  <strong>{{$tt->odometer_to}}</strong> </li>
                                        <li style="margin-bottom: 5px"> &nbsp; &nbsp; Distance Travelled (per No. 5):  &nbsp;  &nbsp;  <strong>{{$tt->distance_traveled}}</strong> </li>
                                    </ul>
                                </li>
                                <li style="margin-bottom: 5px">&nbsp; &nbsp; Remarks:  &nbsp;  &nbsp;  <strong> {{$tt->remarks ?? null}} </li></strong>
                            </ol>
                        </td>
                    </tr>

                    <tr style=" border-left: 1px solid black; text-align: left;  border-right: 1px solid black;">
                        <td style="width: 100%; border-right: 1px solid black; font-size: 17px">
                            <strong>&nbsp; &nbsp; I hereby certify to the correctness of the above statement of record of travel.</strong>
                        </td>
                    </tr>

                    <tr style="width: 70%; text-align: center; border-left: 1px solid black; border-right: 1px solid black;">
                        <td style="font-size: 17px; ">
                            _____________________________________
                        </td>
                    </tr>
                    <tr style="width: 70%; text-align: center; border-left: 1px solid black; border-right: 1px solid black;">
                        <td style="font-size: 17px;">
                            Driver
                        </td>
                    </tr>

                    <tr style=" border-left: 1px solid black; text-align: left;  border-right: 1px solid black;">
                        <td style="width: 100%; border-right: 1px solid black; font-size: 17px">
                            <strong>&nbsp; &nbsp; I hereby certify that I used this car on official business as stated above.</strong>
                        </td>
                    </tr>

                    <tr style="width: 70%; text-align: center; border-left: 1px solid black; border-right: 1px solid black;">
                        <td style="font-size: 17px;">
                            _____________________________________
                        </td>
                    </tr>
                    <tr style="width: 70%; text-align: center; border-left: 1px solid black; border-right: 1px solid black;">
                        <td style="font-size: 17px;">
                            Signature of Passenger
                        </td>
                    </tr>





                    </tbody>
                </table>

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