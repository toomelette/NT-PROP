
@extends('printables.print_layouts.print_layout_main')

@section('wrapper')
    <style>
        @font-face {
            font-family: 'Cambria';
            src: url({{ storage_path("fonts/cambria.ttf") }}) format("truetype");
            font-weight: 700;
            font-style: normal;
        }
        .page-breaks {
            page-break-after: always;
        }

        #mainTable {
            border-collapse: collapse;
        }

        #mainTable, #mainTable th, #mainTable td {
            border: 1px solid black;
        }

        #mainTable1 {
            border-collapse: collapse;
        }

        #mainTable1, #mainTable1 th, #mainTable1 td {
            border: 1px solid black;
        }

        .footer {
            display: none; /* Initially hide the footer */
        }
    </style>

    @foreach($vehicles as $vehicle)
        <div class="page-breaks">
            <table style="width: 100%; margin-left: -120px; font-family: 'Cambria',Times New Roman">
                <tr>
                    <td style="width: 20%">
                        <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
                    </td>
                    <td style="font-size: 20px">
                        <p class="no-margin">Republic of the Philippines</p>
                        <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                        <p class="no-margin">{{\App\Swep\Helpers\Values::headerAddress()}}, {{\App\Swep\Helpers\Values::headerTelephone()}}</p>
                        <p class="no-margin text-strong" style="font-size: 18px">REPORT OF FUEL CONSUMPTION</p>
                    </td>
                </tr>
            </table>
            <h5 style="text-align: left; margin-left: 30px; font-family: 'Cambria',Times New Roman">
                <strong>Vehicle: {{$vehicle->make}} {{$vehicle->model1}} - {{$vehicle->plate_no}}</strong>
            </h5>
            <div>
                <table id="mainTable" style="margin-left: 30px; width: 95%; font-family: 'Cambria',Times New Roman">
                    <thead >
                    <tr>
                        <th style="text-align: center; width: 9%" >Departure</th>
                        <th style="text-align: center; width: 9%" >Arrival</th>
                        <th style="text-align: center; width: 7%" >Driver</th>
                        <th style="text-align: center; width: 7%" >Odometer from</th>
                        <th style="text-align: center; width: 7%" >Odometer to</th>
                        <th style="text-align: center; width: 7%" >(A) Total distance travelled</th>
                        <th style="text-align: center; width: 7%" >(B) Total fuel used</th>
                        <th style="text-align: center; width: 7%" >(C) Distance Travelled per Liter [C=A/B]</th>
                        <th style="text-align: center; width: 7%" >(D) Normal Travel KM. per Liter</th>
                        <th style="text-align: center; width: 6%" >(E) Total Liters Consumed [E=A/D*1.1]</th>
                        <th style="text-align: center; width: 6%" >(F) Excess[F=B-E]</th>
                        <th style="text-align: center; width: 23%" >Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $request = \Illuminate\Support\Facades\Request::capture();
                        $running_odo = \App\Models\TripTicket::query()
                            ->where('date','<',$request->date_start)
                            ->where('vehicle','=',$vehicle->slug)
                            ->sum('distance_traveled');

                        $running_odo = $running_odo + $vehicle->odometer;
                    @endphp


                    @foreach($vehicle->tripTickets as $tripTicket)

                        <tr>
                            <td style="text-align: center; width: 9%" >{{$tripTicket->departure}}</td>
                            <td style="text-align: center; width: 9%" >{{$tripTicket->return}}</td>
                            <td style="text-align: center; width: 7%" >{{$tripTicket->drivers->employee->fullname}}</td>
                            <td style="text-align: center; width: 7%"> {{$running_odo}}</td>
                            @php
                                $running_odo = $running_odo + $tripTicket->distance_traveled;
                            @endphp
                            <td style="text-align: center; width: 8%"> {{$running_odo}} </td>
                            <td style="text-align: center; width: 7%" >{{$a = $tripTicket->distance_traveled}}</td>
                            <td style="text-align: center; width: 7%" >{{$b = $tripTicket->consumed}}</td>
                            <td style="text-align: center; width: 7%" >
                                @if($tripTicket->consumed != null or $tripTicket->consumed != 0)
                                {{number_format($tripTicket->distance_traveled / $tripTicket->consumed, 2) }}
                                @endif
                            </td>
                            <td style="text-align: center; width: 7%" >{{$d = $vehicle->normal_usage}}</td>
                            <td style="text-align: center; width: 6%" >{{number_format($e = $a / $d * 1.1, 2)}}</td>
                            <td style="text-align: center; width: 6%" >{{number_format($b - $e, 2) }}</td>
                            <td style="width: 23%">
                                {{$tripTicket->purpose}}
                                <br><b>Passengers: </b>{{$tripTicket->passengers}}

                                @if($tripTicket->gas_issued != 0)
                                    <br><b>Gas Issued (L): </b>{{$tripTicket->gas_issued}}
                                @endif

                                @if($tripTicket->purchased != 0)
                                    <br><b>Gas Purchased (L): </b>{{$tripTicket->purchased}}
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div style="font-family: Cambria,Arial; display: flex;">
                    <div style="flex: 1; text-align: center; ">
                        <h5 class="" style="margin-left: 10px; margin-bottom: 10px; text-align: center;">
                            Prepared By:
                        </h5><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            <b><u>{{$tripTicket->drivers->employee->fullname ?? null}}</u></b>
                        </td><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            DRIVER
                        </td><br>
                    </div>
                    <div style="font-family: Cambria,Arial; flex: 1; text-align: center; ">
                        <h5 class="" style="margin-left: 10px; margin-bottom: 10px; text-align: center; ">Approved by:</h5><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            <b><u>{{$tripTicket->approved_by ?? null}}</u></b>
                        </td><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            {{$tripTicket->approved_by_designation ?? null}}
                        </td><br>
                    </div>
                </div>

            </div>
        </div>
    @endforeach

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            print();
        })
    </script>
@endsection