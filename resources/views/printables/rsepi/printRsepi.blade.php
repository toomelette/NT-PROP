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
    @foreach($data as $account_code => $groupedByFunds)
        @php
            $groupedByFunds->sort();
            $inventoryTypeTotal = 0;
        @endphp

        @foreach($groupedByFunds as $fund => $rows)
            <div class="page-breaks">
                <table style="width: 100%; margin-left: -120px; font-family: 'Cambria',Times New Roman">
                    <tr>
                        <td style="width: 20%">
                            <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
                        </td>
                        <td style="font-size: 20px">
                            <p class="no-margin text-strong">REPORT ON THE PHYSICAL COUNT OF PROPERTY, PLANT & EQUIPMENT</p>
                            <p class="no-margin text-strong" style="font-size: 18px">{{$accountCodes[$account_code] ?? ''}}</p>
                            <p class="no-margin">As at SUGAR REGULATORY ADMINISTRATION, {{\App\Swep\Helpers\Values::headerAddress()}}</p>
                            <p class="no-margin" style="font-size: 16px">As of {{ \Carbon\Carbon::parse($asOf)->format('F d, Y') }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach

        <h5 style="text-align: left; margin-left: 30px; font-family: 'Cambria',Times New Roman">
            @switch($view)
                @case('per_employee')
                    <strong>Employee: {{$employees[$account_code] ?? ''}} - {{$account_code}}</strong>
                @break
                @case('per_account_code')
                    <strong>Inventory Type: {{$account_code}} - {{$accountCodes[$account_code] ?? ''}}</strong>
                @break

                @default
                @break
            @endswitch
        </h5>

        <div>
            <h5 style="text-align: left; margin-left: 30px; font-family: 'Cambria',Times New Roman"><strong>Fund Cluster: {{$fund}}</strong></h5>

            <table id="mainTable" style="margin-left: 30px; width: 95%; font-family: 'Cambria',Times New Roman">
                <thead >
                <tr>
                    <th style="text-align: center; width: 8%" rowspan="2">DATE</th>
                    <th style="text-align: center; width: 8%" rowspan="2">ICS/RRSP NO.</th>
                    <th style="text-align: center; width: 8%" rowspan="2">PROPERTY NO.</th>
                    <th style="text-align: center; width: 8%" rowspan="2">DESCRIPTION</th>
                    <th style="text-align: center; width: 8%" rowspan="2">USEFUL LIFE</th>
                    <th style="text-align: center; width: 8%" rowspan="2">ISSUED QTY.</th>
                    <th style="text-align: center; width: 8%" rowspan="2">ISSUED OFFICE</th>
                    <th style="text-align: center; width: 8%" colspan="2">RETURNED QTY.</th>
                    <th style="text-align: center; width: 8%" rowspan="2">RETURNED OFFICE</th>
                    <th style="text-align: center; width: 8%" colspan="2">RE-ISSUED QTY.</th>
                    <th style="text-align: center; width: 8%" rowspan="2">RE-ISSUED OFFICE</th>
                    <th style="text-align: center; width: 8%" colspan="2">DISPOSED QTY.</th>
                    <th style="text-align: center; width: 8%" rowspan="2">BALANCE OFFICE</th>
                    <th style="text-align: center; width: 8%" rowspan="2">AMOUNT</th>
                    <th style="text-align: center; width: 8%" rowspan="2">REMARKS</th>
                </tr>
                <tr>
                    <th style="text-align: center;">QTY</th>
                    <th style="text-align: center;">VALUE</th>
                </tr>
                </thead>
                <tbody id="tableBody">

                <tr>

                    @switch($view)
                        @case('per_employee')
                            <td colspan="4" class="text-strong">SUB TOTAL: {{$employees[$account_code] ?? ''}} {{$account_code}} - {{$accountCodes[$account_code] ?? ''}} - {{$fund}}</td>
                            @break
                        @case('per_account_code')
                            <td colspan="4" class="text-strong">SUB TOTAL OF ACCT. {{$account_code}} - {{$accountCodes[$account_code] ?? ''}} - {{$fund}}</td>
                            @break
                        @default
                            @break
                    @endswitch

                </tr>
                </tbody>
            </table>`
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