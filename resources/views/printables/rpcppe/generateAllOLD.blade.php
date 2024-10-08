
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
    @foreach($accountCodes as $accountCode)
        @foreach($fundClusters as $fund_clusters)
            @php
                $contentCount = 0;
                $totalAcquiredCost = 0;
                $grandtotalAcquiredCost = 0;
                $accountCodeRecordDescription = "";
            @endphp
            @foreach($rpciObj as $rpci)
                @if($rpci->invtacctcode === $accountCode)
                    @if($rpci->fund_cluster === $fund_clusters)
                        @php
                            $contentCount = $contentCount + 1;
                        @endphp
                    @endif
                @endif
            @endforeach
            @if($contentCount > 0)
                <div class="page-breaks">
                    <table style="width: 100%; margin-left: -120px; font-family: 'Cambria',Times New Roman">
                        <tr>
                            <td style="width: 20%">
                                <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
                            </td>
                            <td style="font-size: 20px">
                                <p class="no-margin text-strong">REPORT ON THE PHYSICAL COUNT OF PROPERTY, PLANT & EQUIPMENT</p>
                                @foreach($accountCodeRecords as $accountCodeRecord)
                                    @if($accountCodeRecord->code === $accountCode)
                                        <p class="no-margin text-strong" style="font-size: 18px">{{$accountCodeRecord->description}}</p>
                                        @php
                                            $accountCodeRecordDescription = $accountCodeRecord->description;
                                        @endphp
                                    @endif
                                @endforeach
                                <p class="no-margin">As at SUGAR REGULATORY ADMINISTRATION, {{\App\Swep\Helpers\Values::headerAddress()}}</p>
                                <p class="no-margin" style="font-size: 16px">As of {{ \Carbon\Carbon::parse($asOf)->format('F d, Y') }}</p>
                            </td>
                        </tr>
                    </table>
                    @foreach($accountCodeRecords as $accountCodeRecord)
                        @if($accountCodeRecord->code === $accountCode)
                            <h5 style="text-align: left; margin-left: 30px; font-family: 'Cambria',Times New Roman"><strong>Inventory Type: {{$accountCode}} - {{$accountCodeRecord->description}}</strong></h5>
                        @endif
                    @endforeach
                    <h5 style="text-align: left; margin-left: 30px; font-family: 'Cambria',Times New Roman"><strong>Fund Cluster: {{$fund_clusters}}</strong></h5>
                    <table style="margin-left: 25px; width: 95%; font-size: 14px; font-family: 'Cambria',Times New Roman">
                        <tr>
                            <td rowspan="2" valign="top">
                                <strong>For which</strong>
                            </td>
                            <td style="text-align: center;"><strong><u>NOLI T. TINGSON</u></strong></td>
                            <td style="text-align: center;"><strong><u>Supply Officer IV</u></strong></td>
                            <td style="text-align: center;"><strong><u>SRA - Visayas</u></strong></td>
                            <td rowspan="2" valign="top">
                                is accountable, having assumed such accountability on
                                <strong>
                                    <u>October 28, 2022</u>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">(Name of Accountable Officer)</td>
                            <td style="text-align: center;">(Official Designation)</td>
                            <td style="text-align: center;">(Bureau or Office)</td>
                        </tr>
                    </table>

                    <table id="mainTable" style="margin-left: 30px; width: 95%; font-family: 'Cambria',Times New Roman">
                        <thead >
                        <tr>
                            <th style="text-align: center; width: 10%" rowspan="2">ARTICLE</th>
                            <th style="text-align: center; width: 26%" rowspan="2">DESCRIPTION</th>
                            <th style="text-align: center; width: 12%" rowspan="2">PROPERTY NO.</th>
                            <th style="text-align: center; width: 5%" rowspan="2">UNIT OF MEASURE</th>
                            <th style="text-align: center; width: 5%" rowspan="2">UNIT VALUE</th>
                            <th style="text-align: center; width: 5%" rowspan="2">BALANCE PER CARD (QUANTITY)</th>
                            <th style="text-align: center; width: 5%" rowspan="2">ON HAND PER COUNT (QUANTITY)</th>
                            <th style="text-align: center; width: 10%" colspan="2">SHORTAGE/OVER</th>
                            <th style="text-align: center; width: 7%" rowspan="2">DATE ACQUIRED</th>
                            <th style="text-align: center; width: 15%" rowspan="2">REMARKS</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;">QTY</th>
                            <th style="text-align: center;">VALUE</th>
                        </tr>
                        </thead>
                        <tbody id="tableBody">
                        @php
                        $tableRows = ''; // Initialize an empty variable to store HTML content
                        $subtotalDisplayed = false; // Flag to track if subtotal row has been displayed
                        @endphp

                        @foreach($rpciObj as $rpci)
                            @if($rpci->invtacctcode === $accountCode)
                                @if($rpci->fund_cluster === $fund_clusters)
                                    @php
                                        $tableRows .= '<tr>';
                                        $tableRows .= '<td>'.$rpci->article.'</td>';
                                        $tableRows .= '<td>'.$rpci->description.'</td>';
                                        $tableRows .= '<td style="text-align: center;">'.$rpci->propertyno.'</td>';
                                        $tableRows .= '<td style="text-align: center;">'.$rpci->uom.'</td>';
                                        $tableRows .= '<td style="text-align: right;">'.number_format($rpci->acquiredcost, 2).'</td>';
                                        $tableRows .= '<td style="text-align: center;">'.$rpci->qtypercard.'</td>';
                                        $tableRows .= '<td style="text-align: center;">'.$rpci->onhandqty.'</td>';
                                        $tableRows .= '<td style="text-align: center;">'.$rpci->shortqty.'</td>';
                                        $tableRows .= '<td style="text-align: center;">'.$rpci->shortvalue.'</td>';
                                        $tableRows .= '<td style="text-align: center;">'.$rpci->dateacquired.'</td>';
                                        $tableRows .= '<td>'.$rpci->remarks.'</td>';
                                        $tableRows .= '</tr>';

                                        $totalAcquiredCost += $rpci->acquiredcost;
                                        $contentCount++;
                                    @endphp
                                @endif
                                @php
                                    $grandtotalAcquiredCost += $rpci->acquiredcost;
                                @endphp
                            @endif
                        @endforeach

                        @if($contentCount > 0)
                            {!! $tableRows !!} <!-- Output the stored table rows -->
                            <tr>
                                <td></td>
                                <td colspan="3">
                                    <strong>SUB TOTAL - ACCT. {{$accountCode}} IN {{$fund_clusters}}</strong>
                                </td>
                                <td style="text-align: right;">
                                    {{ number_format($totalAcquiredCost, 2) }}
                                </td>
                                <td colspan="6"></td>
                            </tr>
                        @endif
                        {{--@foreach($rpciObj as $rpci)
                            @if($rpci->invtacctcode === $accountCode)
                                @if($rpci->fund_cluster === $fund_clusters)
                                    <tr>
                                        <td>{{$rpci->article}}</td>
                                        <td>{{$rpci->description}}</td>
                                        <td style="text-align: center;">{{$rpci->propertyno}}</td>
                                        <td style="text-align: center;">{{$rpci->uom}}</td>
                                        <td style="text-align: right;">{{ number_format($rpci->acquiredcost, 2) }}</td>
                                        <td style="text-align: center;">{{$rpci->qtypercard}}</td>
                                        <td style="text-align: center;">{{$rpci->onhandqty}}</td>
                                        <td style="text-align: center;">{{$rpci->shortqty}}</td>
                                        <td style="text-align: center;">{{$rpci->shortvalue}}</td>
                                        <td style="text-align: center;">{{$rpci->dateacquired}}</td>
                                        <td>{{$rpci->remarks}}</td>
                                    </tr>
                                    @php
                                        $totalAcquiredCost += $rpci->acquiredcost;
                                        $contentCount = $contentCount + 1;
                                    @endphp
                                    //this must be displayed after the very last tr
                                    <tr>
                                        <td></td>
                                        <td colspan="3">
                                            <strong>SUB TOTAL - ACCT. {{$accountCode}} IN {{$fund_clusters}}</strong>
                                        </td>
                                        <td style="text-align: right;">
                                            {{ number_format($totalAcquiredCost, 2) }}
                                        </td>
                                        <td colspan="6"></td>
                                    </tr>
                                @endif
                                @php
                                    $grandtotalAcquiredCost += $rpci->acquiredcost;
                                @endphp
                            @endif
                        @endforeach--}}
                        </tbody>
                        {{--<tfoot class="">
                            <tr>
                                <td></td>
                                <td colspan="3">
                                    <strong>SUB TOTAL - ACCT. {{$accountCode}} IN {{$fund_clusters}}</strong>
                                </td>
                                <td style="text-align: right;">
                                    {{ number_format($totalAcquiredCost, 2) }}
                                </td>
                                <td colspan="6"></td>
                            </tr>
                        </tfoot>--}}
                    </table>
                    <br>
                    <table id="mainTable" style="margin-left: 30px; width: 95%; font-family: 'Cambria',Times New Roman">
                        <tfoot class="">
                        <tr>
                            <td>
                                <strong>
                                    GRAND TOTAL OF ACCT. {{$accountCode}} - {{$accountCodeRecordDescription}}
                                     : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                     {{ number_format($grandtotalAcquiredCost, 2) }}
                                </strong>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        @endforeach
    @endforeach
    {{--<table id="mainTable1" style="margin-left: 30px; width: 95%; margin-top: -50px; font-family: 'Cambria',Times New Roman">
        <thead >
        <tr>
            <th style="text-align: center; width: 10%"></th>
            <th style="text-align: center; width: 26%"></th>
            <th style="text-align: center; width: 12%"></th>
            <th style="text-align: center; width: 5%"></th>
            <th style="text-align: center; width: 5%"></th>
            <th style="text-align: center; width: 5%"></th>
            <th style="text-align: center; width: 5%"></th>
            <th style="text-align: center; width: 10%"></th>
            <th style="text-align: center; width: 7%"></th>
            <th style="text-align: center; width: 15%"></th>
        </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot class="">
            <tr>
                <td></td>
                <td colspan="3">
                    <strong>adasadasdasdksufioasugd</strong>
                </td>
                <td style="text-align: right;">
                    {{ number_format(0, 2) }}
                </td>
                <td colspan="6"></td>
            </tr>
        </tfoot>
    </table>--}}

    <div class="page-breaks">
        <table style="width: 100%; margin-left: -120px; font-family: 'Cambria',Times New Roman">
            <tr>
                <td style="width: 20%">
                    <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
                </td>
                <td style="font-size: 20px">
                    <p class="no-margin text-strong">REPORT ON THE PHYSICAL COUNT OF PROPERTY, PLANT & EQUIPMENT</p>
                    @foreach($accountCodeRecords as $accountCodeRecord)
                        @if($accountCodeRecord->code === $accountCode)
                            <p class="no-margin text-strong" style="font-size: 18px">{{$accountCodeRecord->description}}</p>
                        @endif
                    @endforeach
                    <p class="no-margin">As at SUGAR REGULATORY ADMINISTRATION, BACOLOD CITY</p>
                </td>
            </tr>
        </table>

        <table id="mainTable" style="margin-left: 30px; width: 95%; margin-top: 20px; font-family: 'Cambria',Times New Roman">
            <thead>
                <tr>
                    <th style="text-align: center;">ACCT CODE</th>
                    <th style="text-align: center;">ACCT TITLE</th>
                    @foreach($fundClusters as $fund_clusters)
                        <th style="text-align: center;">{{$fund_clusters}}</th>
                    @endforeach
                    <th style="text-align: center;">TOTAL</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            @php
                $overallClusterTotals  = [];
            @endphp
            @foreach($accountCodeRecords as $accountCodeRecord)
                    @php
                        $clusterTotals = [];
                        $rowTotal = 0;
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{$accountCodeRecord->code}}</td>
                        <td style="">{{$accountCodeRecord->description}}</td>
                        @foreach($rpciObj as $rpci)
                            @if($rpci->invtacctcode === $accountCodeRecord->code)
                                @foreach($fundClusters as $fund_clusters1)
                                    @if($rpci->fund_cluster === $fund_clusters1)
                                        @php
                                            $cluster = $fund_clusters1;
                                            $totalAcquiredCost = isset($clusterTotals[$cluster]) ? $clusterTotals[$cluster] : 0;
                                            $totalAcquiredCost += $rpci->acquiredcost;
                                            $clusterTotals[$cluster] = $totalAcquiredCost;

                                            // Accumulate overall total for each cluster
                                            $overallTotal = isset($overallClusterTotals[$cluster]) ? $overallClusterTotals[$cluster] : 0;
                                            $overallTotal += $rpci->acquiredcost;
                                            $overallClusterTotals[$cluster] = $overallTotal;

                                            // Accumulate row total
                                            $rowTotal += $rpci->acquiredcost;
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        @foreach($fundClusters as $fund_clusters)
                            <td style="text-align: right;">{{ number_format(isset($clusterTotals[$fund_clusters]) ? $clusterTotals[$fund_clusters] : 0, 2) }}</td>
                        @endforeach
                        <td style="text-align: right;">{{ number_format($rowTotal, 2) }}</td>
                    </tr>
            @endforeach
            <tr>
                <td style="text-align: center;"></td>
                <td style=""><strong>Total</strong></td>
                @php
                    $rowTotal1 = 0; // Initialize row total for the overall total row
                @endphp
                @foreach($fundClusters as $fund_clusters)
                    <td style="text-align: right;">
                        @php
                            $total = isset($overallClusterTotals[$fund_clusters]) ? $overallClusterTotals[$fund_clusters] : 0;
                            $rowTotal1 += $total;
                        @endphp
                        {{ number_format($total, 2) }}
                    </td>
                @endforeach
                <td style="text-align: right;">{{ number_format($rowTotal1, 2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            print();
            // close();
        })
    </script>
@endsection