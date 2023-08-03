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
@foreach($accountCodes as $accountCode)
    <div class="page-breaks">
        <table style="width: 100%; margin-left: 0; font-family: 'Cambria',Times New Roman">
            <tr>
                <td>

                </td>
                <td style="font-size: 20px">
                    <p class="no-margin text-strong">INVENTORY COUNT FORM</p>
                    @foreach($accountCodeRecords as $accountCodeRecord)
                        @if($accountCodeRecord->code === $accountCode)
                            <p class="no-margin text-strong" style="font-size: 18px">{{$accountCodeRecord->description}}</p>
                        @endif
                    @endforeach
                </td>
            </tr>
        </table>
        <h5 style="text-align: left; margin-left: 30px;"><strong>Location: {{$location->name}}</strong></h5>
        @foreach($accountCodeRecords as $accountCodeRecord)
            @if($accountCodeRecord->code === $accountCode)
            <h5 style="text-align: left; margin-left: 30px;"><strong>Inventory Type: {{$accountCode}} - {{$accountCodeRecord->description}}</strong></h5>
            @endif
        @endforeach

        <table id="mainTable" style="margin-left: 30px; width: 95%; font-family: 'Cambria',Times New Roman">
            <thead >
            <tr>
                <th style="text-align: center; width: 10%" rowspan="2">Article/Item/Description</th>
                <th style="text-align: center; width: 26%" rowspan="2">Accountable Person</th>
                <th style="text-align: center; width: 7%" rowspan="2">Date Acquired</th>
                <th style="text-align: center; width: 12%" rowspan="2">Old Property No. Assigned</th>
                <th style="text-align: center; width: 12%" rowspan="2">New Property No. Assigned (To be filled up during validation)</th>
                <th style="text-align: center; width: 5%" rowspan="2">Unit of Measure</th>
                <th style="text-align: center; width: 5%" rowspan="2">Unit Value</th>
                <th style="text-align: center; width: 5%" rowspan="2">Quantity per Property Card</th>
                <th style="text-align: center; width: 5%" rowspan="2">Quantity per Physical Count</th>
                <th style="text-align: center; width: 10%" rowspan="2">Location/Whereabouts</th>
                <th style="text-align: center; width: 15%" rowspan="2">Condition</th>
                <th style="text-align: center; width: 15%" rowspan="2">REMARKS</th>
            </tr>
            </thead>
            <tbody>
            @php
                $totalAcquiredCost = 0;
            @endphp
            @foreach($rpciObj as $rpci)
                @if($rpci->invtacctcode === $accountCode)
                    <tr>
                        <td>{{$rpci->article}} - {{$rpci->description}} / </td>
                        <td style="text-align: center;">{{$rpci->acctemployee_fname}}</td>
                        <td style="text-align: center; width: 10%">{{$rpci->dateacquired}}</td>
                        <td style="text-align: center;">{{$rpci->old_property_no}}</td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;">{{$rpci->uom}}</td>
                        <td style="text-align: right;">{{ number_format($rpci->acquiredcost, 2) }}</td>
                        <td style="text-align: center;">{{$rpci->qtypercard}}</td>
                        <td style="text-align: center;">{{$rpci->onhandqty}}</td>
                        <td style="text-align: center;">{{$location->name}}</td>
                        <td></td>
                        <td>{{$rpci->remarks}}</td>
                    </tr>
                    @php
                        $totalAcquiredCost += $rpci->acquiredcost;
                    @endphp
                @endif
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td colspan="3">
                    <strong>GRAND TOTAL OF ACCT. {{$accountCode}}</strong>
                </td>
                <td style="text-align: right;">
                    {{ number_format($totalAcquiredCost, 2) }}
                </td>
                <td colspan="6"></td>
            </tr>
            </tfoot>
        </table>
    </div>
    @endforeach
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            print();
            // close();
        })
    </script>
@endsection