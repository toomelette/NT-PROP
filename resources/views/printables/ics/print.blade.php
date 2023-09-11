@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')
    <div class="printable">
        <div style="width: 100%;">
            <div class="" style="padding-top: 10px;">
                <div>
                    <img src="{{ asset('images/sra.png') }}" style="width:100px; float: left">
                </div>
                <div style="float: left; text-align: left; margin-left: 15px; margin-top: 10px">
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Republic of the Philippines</p>
                    <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Araneta St., Singcang, Bacolod City, Tel No. 433-6891</p>
                    <p class="no-margin text-strong" style="font-size: 14px;">
                        PROPERTY/PROCUREMENT/BUILDING & TRANSPORT MAINTENANCE SECTION
                    </p>
                </div>

                <span class="" style="float: right">
                    {{ QrCode::size(50)->generate(route("dashboard.ics.index",$ics->slug)) }}
                </span>

                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin-top: 90px">
                    <tbody style="margin: 0; padding: 0">
                        <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                            <td rowspan="4" style="font-size: 20px; width: 55%; border-right: 1px solid black">
                                <strong>Inventory Custodian Slip</strong>
                            </td>
                            <td style="margin-top: 5px; justify-content: center; float: left;">
                                ICS No: <span class="text-strong">{{$ics->ref_no}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid black; vertical-align: center;">
                               Entity Name: <span class="text-strong">{{$rc->desc}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid black; vertical-align: center;">
                               Fund Cluster: <span class="text-strong">{{$ics->fund_cluster}}</span>
                            </td>
                        </tr><tr>
                            <td style="border-right: 1px solid black; vertical-align: center;">
                                Account Code: <span class="text-strong">{{$ics->account_code}}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table style="width: 100%; border: #0a0a0a;">

                    <tr style="border: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 12.5%; vertical-align: top;">
                            PO Number:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top;  width: 12.5%">
                            {{$ics->po_number}}
                        </td>
                        <td style="border-right: 1px solid black; vertical-align: top; width: 12.5%">
                            PO Date:
                        </td>
                        <td class="text-strong" style="vertical-align: top; width: 12.5%;">
                        {{$ics->po_date}}
                        </td>

                    </tr>

                    <tr style="border: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 12.5%; vertical-align: top;">
                        Invoice Number:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black;  width: 12.5%">
                        {{$ics->invoice_number}}
                        </td>
                        <td style="border-right: 1px solid black; vertical-align: top; width: 12.5%">
                        Invoice Date:
                        </td>
                        <td class="text-strong" style="vertical-align: top; width: 12.5%;">
                        {{$ics->invoice_date}}
                        </td>

                    </tr>

                    <tr style="border: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 12.5%; vertical-align: top;">
                            Supplier:
                        </td>
                        <td colspan="3" class="text-strong" style="border-right: 1px solid black;  width: 12.5%">
                           {{$ics->supplier}}
                        </td>

                    </tr>

                </table>

                <table style="font-family: Cambria,Arial; width: 100%; text-align: center; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                    <thead>
                    <tr class="text-strong" style="width: 100%">
                        <td style="border: 1px solid black; width: 8%;">Quantity</td>
                        <td style="border: 1px solid black; width: 8%;">Unit</td>
                        <td style="border: 1px solid black; width: 10%">Unit Cost</td>
                        <td style="border: 1px solid black; width: 10%; text-align: center">Total Cost</td>
                        <td style="border: 1px solid black; width: 40%; text-align: center;">Description</td>
                        <td style="border: 1px solid black; width: 10%; text-align: center;">Inventory Item No.</td>
                        <td style="border: 1px solid black; width: 10%; text-align: center;">Estimated Useful Life</td>
                    </tr>
                    </thead>
                    <tbody style="height: 350px; width: 100%; border: solid black 1px">
                            @php
                                $totalCost = 0;
                            @endphp
                        @foreach($ics->transDetails as $item)
                            <tr class="text-strong">
                                <td style="border: solid black 1px; vertical-align: top;">{{$item->qty}}</td>
                                <td style="border: solid black 1px; vertical-align: top;">{{$item->unit}}</td>
                                <td class="text-strong" style="border: solid black 1px; vertical-align: top; text-align: right">{{number_format($item->unit_cost,2)}}</td>
                                <td style="border: solid black 1px; vertical-align: top; text-align: right" class="text-strong">{{number_format($item->total_cost,2)}}</td>
                                <td class="" style="border: solid black 1px; vertical-align: top; text-align: left;">
                                    <b style="font-size: 12px;">{{$item->item}}</b><br>
                                    <span style="white-space: pre-line; font-size: 10px; font-style: italic" >
                                    {{$item->description ? $item->description : ""}}
                                    </span>
                                </td>
                                <td style="border: solid black 1px; vertical-align: top; ">{{$item->stock_no}}</td>
                                <td style="border: solid black 1px; vertical-align: top; ">{{$item->estimated_useful_life}}</td>
                            </tr>
                            @php
                                $totalCost += $item->total_cost;
                            @endphp

                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display: flex; font-size: 14px;">
                <div style="flex: 1; padding: 10px; border: 1px solid black; box-sizing: border-box; text-align: center;">
                    <p style="text-align: left;">Received from:</p>
                    <p><br></p>
                    <p style="margin: 0; padding:0; font-size: 16px;"><strong><u>{{$ics->approved_by}}</u></strong></p>
                    <p style="margin: 0; padding:0">Signature Over Printed Name</p>
                    <p style="margin: 0; padding:0"><strong>{{$ics->approved_by_designation}}</strong></p>
                    <p style="margin: 0; padding:0">Position/Office</p><br>
                    <p>_______________________________</p>
                    <p style="margin-top: -10px;">Date</p>
                </div>
                <div style="flex: 1; padding: 10px; border: 1px solid black; box-sizing: border-box; text-align: center;">
                    <p style="text-align: left;">Received by:</p>
                    <p><br></p>
                    <p style="margin: 0; padding:0; font-size: 16px;"><strong><u>{{$ics->requested_by}}</u></strong></p>
                    <p style="margin: 0; padding:0">Signature Over Printed Name</p>
                    <p style="margin: 0; padding:0"><strong>{{$ics->requested_by_designation}}</strong></p>
                    <p style="margin: 0; padding:0">Position/Office</p><br>
                    <p>_______________________________</p>
                    <p style="margin-top: -10px;">Date</p>
                </div>
            </div>
        </div>
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