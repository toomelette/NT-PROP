
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
            <table style="width: 100%; font-family: 'Cambria',Times New Roman">
                <tr>
                    <td style="width: 20%">
                        <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
                    </td>
                    <td style="font-size: 16px">
                        <p class="no-margin">Republic of the Philippines</p>
                        <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                        <p class="no-margin">{{\App\Swep\Helpers\Values::headerAddress()}}, {{\App\Swep\Helpers\Values::headerTelephone()}}</p>
                        <p class="no-margin text-strong" style="font-size: 18px">MONTHLY REPORT OF OFFICIAL TRAVELS</p>
                    </td>
                </tr>
            </table>
            <h5 style="text-align: left; margin-left: 30px; font-family: 'Cambria',Times New Roman">
                <strong>Vehicle: {{$vehicle->make}} {{$vehicle->model1}} - {{$vehicle->plate_no}}</strong>
            </h5>
        <div>
            <table id="mainTable" style="margin-left: 30px; width: 95%; font-family: 'Cambria',Times New Roman">
                <thead>
                <tr>
                    <th style="text-align: center; width: 12%" >Date</th>
                    <th style="text-align: center; width: 12%" >Distance Travelled (KM)</th>
                    <th style="text-align: center; width: 12%" >Gasoline Consumed (L)</th>
                    <th style="text-align: center; width: 12%" >Oil Used (L)</th>
                    <th style="text-align: center; width: 12%" >Grease Used</th>
                    <th style="text-align: center; width: 40%" >Remarks</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $dtotal = 0;
                    $tconsumed = 0;
                @endphp
                @foreach($vehicle->tripTickets as $tripTicket)
                    @php
                        $dtotal += $tripTicket->distance_traveled;
                        $tconsumed += $tripTicket->consumed;
                        $v = "";
                    @endphp
                    <tr>
                        <td style="text-align: center; width: 12%">
                                <?php echo date('Y-m-d', strtotime($tripTicket->departure ?? null)); ?>
                        </td>
                        <td style="text-align: center; width: 12%" >{{$tripTicket->distance_traveled}}</td>
                        <td style="text-align: center; width: 12%" >{{$tripTicket->consumed}}</td>
                        <td style="text-align: center; width: 12%" >{{$tripTicket->gear_oil}}</td>
                        <td style="text-align: center; width: 12%" >{{$tripTicket->grease}}</td>
                        <td style="text-align: left; width: 40%"> {{$tripTicket->purpose}}
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
                <tr>
                    <td style="text-align: center; width: 9%;" >TOTAL</td>
                    <td style="text-align: center; width: 9%" >{{$dtotal}}</td>
                    <td style="text-align: center; width: 9%" >{{$tconsumed}}</td>
                </tr>
                </tbody>
            </table>

            <div style="font-family: Cambria,Arial; display: flex;">
                <div style="flex: 1; text-align: center; ">
                    <h5 class="" style="margin-left: 10px; margin-bottom: 10px; text-align: center;">
                        Prepared By:
                    </h5><br><br>
                    <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                        <b><u>{{$tripTicket->drivers->employee->fullname ?? null}}</u></b>
                    </td><br>
                    <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                        DRIVER II
                    </td><br>
                </div>
                <div style="font-family: Cambria,Arial; flex: 1; text-align: center; ">
                    <h5 class="" style="margin-left: 10px; margin-bottom: 10px; text-align: center; ">Approved by:</h5><br><br>
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