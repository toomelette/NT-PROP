@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')
    <div class="printable">
        <div style="width: 100%;">
            <div class="" style="margin-bottom: 100px; padding-top: 10px;">
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
                    {{ QrCode::size(50)->generate(route("dashboard.iar.index",$iar->slug)) }}
                </span>


                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin-top: 90px">
                    <tbody>
                    <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                        <td rowspan="2" style="width: 55%; border-right: 1px solid black">
                            <p style="font-size: 20px;"><strong>INSPECTION AND ACCEPTANCE REPORT</strong></p>
                        </td>
                        <td style="margin-top: 5px; justify-content: center; float: left;">
                            <p>IAR No: <span class="text-strong">{{$iar->ref_no}}</span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid black; vertical-align: center;">
                            <p>Supplier: <span class="text-strong">{{$iar->supplier}}</span></p>
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
                            {{$iar->po_number}}
                        </td>
                        <td style="border-right: 1px solid black; vertical-align: top; width: 12.5%">
                            PO Date:
                        </td>
                        <td class="text-strong" style="vertical-align: top; width: 12.5%;">
                        {{$iar->po_date}}
                        </td>

                    </tr>

                    <tr style="border: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 12.5%; vertical-align: top;">
                        Invoice Number:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black;  width: 12.5%">
                        {{$iar->invoice_number}}
                        </td>
                        <td style="border-right: 1px solid black; vertical-align: top; width: 12.5%">
                        Invoice Date:
                        </td>
                        <td class="text-strong" style="vertical-align: top; width: 12.5%;">
                        {{$iar->invoice_date}}
                        </td>

                    </tr>

                    <tr style="border: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 12.5%; vertical-align: top;">
                            Requisitioning Office/Department:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black;  width: 12.5%">
                            {{$rc->desc}}
                        </td>
                        <td style="border-right: 1px solid black; vertical-align: top; width: 12.5%">
                            Requested By:
                        </td>
                        <td class="text-strong" style="vertical-align: top; width: 12.5%;">
                            {{$iar->requested_by}}
                        </td>

                    </tr>

                    <tr style="border: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 12.5%; vertical-align: top;">
                            PR/JR No:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top;  width: 12.5%">
                            {{$pr->ref_no}}
                        </td>

                    </tr>

                </table>

                <table style="font-family: Cambria,Arial; width: 100%; text-align: center; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                    <thead>
                    <tr class="text-strong" style="border: 1px solid black; width: 100%">
                        <td style="border: 1px solid black; width: 8%;">Stock No:</td>
                        <td style="border: 1px solid black; width: 8%;">Quantity</td>
                        <td style="border: 1px solid black; width: 8%;">Unit</td>
                        <td style="border: 1px solid black; width: 41%; text-align: center;">Description</td>
                        <td style="border: 1px solid black; width: 15%">Unit Cost</td>
                        <td style="border: 1px solid black; width: 15%; text-align: center">Total Cost</td>
                    </tr>
                    </thead>
                    <tbody style="height: 350px">
                            @php
                                $totalCost = 0;
                            @endphp
                        @foreach($iar->transDetails as $item)
                            <tr class="text-strong" style="width: 100%">
                                <td style="vertical-align: top; width: 8%;">{{$item->stock_no}}</td>
                                <td style="vertical-align: top; width: 8%;">{{$item->qty}}</td>
                                <td style="vertical-align: top; width: 8%;">{{$item->unit}}</td>
                                <td class="" style="vertical-align: top; width: 41%; text-align: left;">
                                    <b style="font-size: 12px;">{{$item->item}}</b><br>
                                    <span style="white-space: pre-line; font-size: 10px; font-style: italic" >
                                    {{$item->description ? $item->description : ""}}
                                    </span>
                                </td>
                                <td class="text-strong" style="vertical-align: top; width: 15%;">{{$item->unit_cost,2}}</td>
                                <td style="vertical-align: top; width: 15%; text-align: right" class="text-strong">{{number_format($item->total_cost,2)}}</td>
                            </tr>
                            @php
                                $totalCost += $item->total_cost;
                            @endphp

                        @endforeach
                    </tbody>
                </table>
                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                    <td style="border: 1px solid black; width: 75%; text-align: right">TOTAL </td>
                    <td style="border: 1px solid black; vertical-align: top; text-align: right" class="text-strong">{{number_format($totalCost,2)}}</td>
                </table>

                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                    <tr rowspan="2" style="width: 60%; border-right: 1px solid black">
                        <td style="text-align: center; font-size: 20px;"><strong>INSPECTION</strong></td>
                        <td style="text-align: center; font-size: 20px;"><strong>ACCEPTANCE</strong></td>
                    </tr>
                </table>


    <div style="font-family: Cambria,Arial; display: flex; border: 1px solid black">
                    <div style="flex: 1; text-align: left; "><br>
                        <h5 class="text-strong" style="margin-left: 5px; margin-bottom: 30px;"><b>Date Inspected:</b> <span class="text-strong" style="margin-left: 30px; text-decoration: underline;">
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $iar->date_inspected)->format('F d, Y')}}
                        </span></h5><br>


                        <div style="display: flex; align-items: center;">
                                <input style="margin-left: 10px; margin-bottom: 15px;" type="checkbox" name="inspectionCheckbox" id="inspectionCheckbox">
                                <label style="margin-left: 10px; margin-bottom: 15px; margin-right: 10px;">Inspected, verified and found in order as to quantity and specifications</label>
                            </div>
                            <br>


                            <span class="text-strong" style="margin-left: 53px; margin-top: 10px">
                            _________________________________________________
                            </span><br>
                            <span style="margin-left: 51px">
                           <b>Inspection Office/Inspection Committee</b>
                            </span>
                        </div>

                        <div style="flex: 1; text-align: left; border-left: 1px solid black"><br>
                            <h5 class="text-strong" style="margin-left: 5px; margin-bottom: 30px;"><b>Date Received:</b>
                            <span class="text-strong" style="margin-left: 30px;">
                            _________________________________
                            </span>
                            </h5>

                            <input style="margin-left: 70px" type="checkbox" name="inspectionCheckbox" id="inspectionCheckbox">
                            <label style="text-align: center; margin-left: 10px; margin-right: 10px">Complete</label><br><br>
                            <input style="margin-left: 70px" type="checkbox" name="inspectionCheckbox" id="inspectionCheckbox">
                            <label style="text-align: center; margin-left: 10px; margin-right: 10px">Partial (Please specify quantity)</label><br><br>

                            <span class="text-strong" style="margin-left: 60px;">
                            _________________________________________________
                            </span><br>
                            <span style="margin-left: 140px; margin-bottom: 30px">
                           <b>Property Officer</b>
                            </span>
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