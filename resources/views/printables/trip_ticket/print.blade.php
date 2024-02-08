@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    $rand = \Illuminate\Support\Str::random();

@endphp

@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')

    <div class="printable">
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
                        <td rowspan="2" style="width: 49%; border-right: 1px solid black; font-size: 35px">
                            <strong>Trip Ticket</strong>
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
                    </tr>
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

                <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >


                    <tr style=" width: 100%;">

                        <td rowspan="2" style="border-right: 1px solid black; border-left: 1px solid black; width: 13.4%; vertical-align: center;">
                            Passengers:
                        </td>
                        <td rowspan="2" class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 34.7%">
                            {{$tt->passengers}}
                        </td>

                        <td style="border-right: 1px solid black; width: 13.75%; vertical-align: center;">
                            Destination:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 36.3%">
                            {{$tt->destination}}
                        </td>
                    </tr>

                </table>

                <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >


                    <tr style=" border: 1px solid black; width: 100%;">

                        <td rowspan="2" style="border-right: 1px solid black; width: 13.6%; vertical-align: center;">
                            Purpose:
                        </td>
                        <td rowspan="2" class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 86.1%">
                            {{$tt->purpose}}
                        </td>
                    </tr>

                </table>

                <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >

                <div style="font-family: Cambria,Arial; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">

                    <h5 class="" style="margin-left: 5px; text-align: left; float: left">
                            Balance in tank:  {{$tt->gas_balance}}
                    </h5><br><br>
                    <h5 class="" style="margin-left: 5px; text-align: left; float: left">
                        Issued from Office/Supplier:  {{$tt->gas_issued}}
                    </h5><br><br>
                    <h5 class="" style="margin-left: 5px; text-align: left; float: left">
                        Add: purchase/s during trip:  {{$tt->purchased}}
                    </h5><br><br>
                    <h5 class="" style="margin-left: 5px; text-align: left; float: left">
                        TOTAL:  {{$tt->total}}
                    </h5>
                </div>
                </table>

                <table style="font-family: Cambria,Arial;   width: 100%; border: #0a0a0a;" >


                    <tr style=" width: 100%; border: 1px solid black;">

                <td style="border-right: 1px solid black; width: 25%; vertical-align: center;">
                    deduct: fuel consumed
                </td>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 25%">
                    {{$tt->consumed}}
                </td>

                <td style="border-right: 1px solid black; width: 25%; vertical-align: center;">
                    Balance in tank at end of Trip:
                </td>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 25%">
                    {{$tt->gas_remaining_balance}}
                </td>

                    </tr>

                </table>

            </div>
        </div>
    </div>




@endsection

@section('scripts')


 <script type="text/javascript">
     $(document).ready(function () {
         let set = 500;
         if($("#items_table_{{$rand}}").height() < set){
             let rem = set - $("#items_table_{{$rand}}").height();
             $("#adjuster").css('height',rem)
             @if(!\Illuminate\Support\Facades\Request::has('noPrint'))
             print();
             @endif
         }
     })
 </script>
@endsection