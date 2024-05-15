@php
@endphp
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
    </style>
    @forelse($articles as $stock_no => $icsDetails)
        <div class="page-breaks">
            <table style="width: 100%; margin-left: -120px; font-family: 'Cambria',Times New Roman">
                <tr>
                    <td style="width: 20%">
                        <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
                    </td>
                    <td style="font-size: 20px">
                        <p class="no-margin text-strong">REPORT ON THE PHYSICAL COUNT OF PROPERTY, PLANT & EQUIPMENT</p>
                        <p class="no-margin">As at SUGAR REGULATORY ADMINISTRATION, {{\App\Swep\Helpers\Values::headerAddress()}}</p>
{{--                        <p class="no-margin" style="font-size: 16px">As of {{ \Carbon\Carbon::parse($asOf)->format('F d, Y') }}</p>--}}
                    </td>
                </tr>
            </table>
            <table>
                <tr style="width: 100%; font-size: 15px ">
{{--                    <td style="width: 50%"> Entity Name: <strong></strong></td>--}}
                    <td style="text-align: center; width: 50%"> Semi-Expendable Property: <strong>{{$articlesList[$stock_no]->article ?? 'N/A'}} | {{$stock_no}}</strong></td>

                </tr>




            </table>

            <table id="mainTable" style="margin-left: 30px; width: 95%; font-family: 'Cambria',Times New Roman">
                <thead >
                <tr>
                    <th style="text-align: center; width: 6%" rowspan="2">DATE</th>
                    <th style="text-align: center; width: 15%" colspan="2">REFERENCE</th>
                    <th style="text-align: center; width: 20%" rowspan="2">ITEM DESCRIPTION</th>
                    <th style="text-align: center; width: 5%" rowspan="2">USEFUL LIFE</th>
                    <th style="text-align: center; width: 10%" colspan="2">ISSUED</th>
                    <th style="text-align: center; width: 10%" colspan="2">RETURNED</th>
                    <th style="text-align: center; width: 5%" rowspan="2">DISPOSED QTY</th>
                    <th style="text-align: center; width: 5%" rowspan="2">BALANCE QTY</th>
                    <th style="text-align: center; width: 5%" rowspan="2">AMOUNT</th>
                    <th style="text-align: center; width: 20%" rowspan="2">REMARKS</th>
                </tr>
                <tr>
                    <th style="text-align: center;">ICS/RRSP NO.</th>
                    <th style="text-align: center;">PROPERTY NO</th>
                    <th style="text-align: center;">QTY</th>
                    <th style="text-align: center;">OFFICE/OFFICER</th>
                    <th style="text-align: center;">QTY</th>
                    <th style="text-align: center;">OFFICE/OFFICER</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $runningBal = 0;
                @endphp
                @forelse($icsDetails as $icsDetail)
                    <tr>
                        <td style="text-align: center; width: 6%" >{{$icsDetail->transaction->po_date ?? null}}</td>
                        <td style="text-align: center; width: 15%" > {{$icsDetail->transaction->ref_no ?? null}}</td>
                        <td style="text-align: center; width: 20%" > {{$icsDetail->property_no ?? null}}</td>
                        <td style="text-align: center; width: 5%" > {{$icsDetail->description ?? null}}</td>
                        <td style="text-align: center; width: 10%" > {{$icsDetail->estimated_useful_life ?? null}}</td>
                        <td style="text-align: center; width: 10%" > {{$icsDetail->qty ?? null}}</td>
                        <td style="text-align: center; width: 5%" > {{$icsDetail->transaction->requested_by ?? null}}</td>
                        <td style="text-align: center; width: 5%" > </td>
                        <td style="text-align: center; width: 5%" ></td>
                        <td style="text-align: center; width: 20%" ></td>
                        <td style="text-align: center; width: 5%" > {{$runningBal = $runningBal + $icsDetail->qty}} </td>
                        <td style="text-align: center; width: 5%" >  {{$icsDetail->total_cost ?? null}}</td>
                        <td style="text-align: center; width: 20%" >  {{$icsDetail->remarks ?? null}}</td>
                    </tr>
                @empty
                @endforelse
                <tr>
                    <td colspan="10" style="text-align: right"><b>TOTAL</b></td>
                    <td>{{$runningBal}}</td>
                    <td>{{$icsDetails->sum('total_cost')}}</td>
                </tr>
                </tbody>
            </table>
        </div>




    @empty
        <p>No data</p>
    @endforelse

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            // print();
            // close();
        })
    </script>
@endsection