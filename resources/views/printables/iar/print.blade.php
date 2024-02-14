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
                    {{ QrCode::size(50)->generate(route("dashboard.iar.index",$iar->slug)) }}
                </span>


                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin-top: 105px">
                    <tbody>

                    <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                        <td rowspan="2" style="width: 49%; border-right: 1px solid black">
                            <p style="font-size: 18px;"><strong>INSPECTION AND ACCEPTANCE REPORT</strong></p>
                        </td>

                        <td style="margin-top: 5px; width: 14%; border-right: 1px solid black; position: relative;">
                            IAR No:
                        </td>
                        <td style="" class="text-strong ">
                            {{$iar->ref_no}}
                        </td>
                    </tr>

                        <td style="margin-top: 5px; width: 14%; border-right: 1px solid black; border-top: 1px solid black; position: relative;">
                        Supplier:
                        </td>
                        <td style="border-top: 1px solid black;" class="text-strong ">
                            {{$iar->supplier}}
                        </td>

                    </tr>

                    </tbody>
                </table>

                <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >

                    <tr style="border: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 11%; vertical-align: top;">
                            Invoice Number:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black;  width: 35%">
                            {{$iar->invoice_number}}
                        </td>

                        <td style="border-right: 1px solid black; width: 13.9%; vertical-align: center;">
                            PO Number:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 40%">
                            {{$iar->po_number}}
                        </td>

                    </tr>


                    <tr style="border: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; vertical-align: center; width: 11%">
                            Invoice Date:
                        </td>
                        <td class="text-strong" style="vertical-align: center; width: 35%;">
                            {{$iar->invoice_date}}
                        </td>

                        <td style="border-right: 1px solid black; border-left: 1px solid black; vertical-align: top; width: 13.9%">
                            PO Date:
                        </td>
                        <td class="text-strong" style="vertical-align: top; width: 40%;">
                            {{$iar->po_date}}
                        </td>

                    </tr>

                    <tr style="border: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 11%; vertical-align: top;">
                            Requisitioning Office:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black;  width: 35%">
                            {{$rc->desc}}
                        </td>


                        <td style="border-right: 1px solid black; width: 13.9%; vertical-align: center;">
                            PR/JR No:
                        </td>
                        <td style="" class="text-strong ">
                            {{$iar->cross_ref_no}}
                        </td>
{{--                        @if($iar->cross_slug != "")--}}
{{--                            <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 40%">--}}
{{--                                {{$pr->ref_no}}--}}
{{--                            </td>--}}
{{--                        @else--}}
{{--                            <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 40%">--}}
{{--                                {{$iar->cross_ref_no}}--}}
{{--                            </td>--}}
{{--                        @endif--}}
                    </tr>
                    <tr style="border-right: 1px solid black; width: 100%;">
                        <td style="border-right: 1px solid black; border-left: 1px solid black; vertical-align: center; width: 14%">
                            Received and Inspected by:
                        </td>
                        <td class="text-strong" style="vertical-align: bottom; width: 14.1%;">
                            {{$iar->requested_by}}
                        </td>


                    </tr>

                </table>

                <table  id="items_table_{{$rand}}" style="font-family: Cambria,Arial; width: 100%; text-align: center;  border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                    <thead>
                    <tr class="text-strong" style="border: 1px solid black; width: 100%">
                        <td style="border: 1px solid black; width: 8%;">Stock No:</td>
                        <td style="border: 1px solid black; width: 8%;">Quantity</td>
                        <td style="border: 1px solid black; width: 8%;">Unit</td>
                        <td style="border: 1px solid black; width: 41%; text-align: center;">Description</td>
                        <td style="border: 1px solid black; width: 15%;">Unit Cost</td>
                        <td style="border: 1px solid black; width: 15%;">Total Cost</td>
                    </tr>
                    </thead>
                    <tbody style="font-family: Cambria,Arial;">
                            @php
                                $totalCost = 0;
                            @endphp
                        @foreach($iar->transDetails as $item)
                            <tr style="width: 100%">
                                <td style="vertical-align: top; width: 8%;">{{$item->stock_no}}</td>
                                <td style="vertical-align: top; width: 8%;">{{$item->qty}}</td>
                                <td style="vertical-align: top; width: 8%;">{{$item->unit}}</td>
                                <td class="" style="vertical-align: top; width: 41%; text-align: left;">
                                    <b style="font-size: 11px; font-weight: bold  ">{{$item->item}}</b>
                                    <span style="white-space: pre-line; font-style: italic" >
                                    {{$item->description}}
                                    </span>
                                </td>
                                <td style="vertical-align: top; width: 15%; text-align: justify; text-align: center">{{number_format($item->unit_cost,2)}}</td>
                                <td style="vertical-align: top; width: 15%; text-align: justify; text-align: right" >{{number_format($item->total_cost,2)}}</td>
                            </tr>
                            @php
                                $totalCost += $item->total_cost;
                            @endphp

                        @endforeach
                            <tr>
                                <td id="adjuster"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                    </tbody>
                </table>
                <table style="font-family: Cambria,Arial; width: 100%; border-left: 1px solid black; border-right: 1px solid black; ">
                    <td style=" width: 75%; text-align: right; border-right: 1px solid black;" class="text-strong">TOTAL </td>
                    <td style=" vertical-align: top; text-align: right" class="text-strong">{{number_format($totalCost,2)}}</td>
                </table>

                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; ">
                    <tr rowspan="2" style="width: 100%; border-right: 1px solid black">
                        <td style="text-align: center; width: 50%; font-size: 16px; border-right: 1px solid black"><strong>INSPECTION</strong></td>
                        <td style="text-align: center; font-size: 16px;"><strong>ACCEPTANCE</strong></td>
                    </tr>
                </table>


    <div style="font-family: Cambria,Arial; display: flex; border: 1px solid black">
                    <div style="flex: 1; text-align: left; "><br>
                        <h5 class="" style="margin-left: 5px; margin-bottom: 30px;"><b>Date Inspected:</b>
                                <span class="text-strong" style="margin-left: 30px; text-decoration: underline">
                              {{$iar->date_inspected}}
                            </span>
                        </h5><br>


                        <div style="display: flex; align-items: center;">
                                <input style="margin-left: 10px; margin-bottom: 15px;" type="checkbox" name="inspectionCheckbox" id="inspectionCheckbox">
                                <label style="font-weight:normal; margin-left: 10px; margin-bottom: 15px; margin-right: 10px;">Inspected, verified and found in order as to quantity and specifications</label>
                            </div>
                            <br>


                            <span class="" style="margin-left: 53px; margin-top: 15px">
                            _________________________________________________
                            </span><br>
                            <span style="margin-left: 51px">
                           <b>Inspection Office/Inspection Committee</b>
                            </span>
                        </div>

                        <div style="flex: 1; text-align: left; border-left: 1px solid black"><br>
                            <h5 class="" style="margin-left: 5px; margin-bottom: 10px;"><b>Date Received:</b>
                            <span class="" style="margin-left: 30px;">
                            _________________________________
                            </span>
                            </h5>
                            <input style="margin-left: 70px; margin-top: 25px" type="checkbox" name="inspectionCheckbox" id="inspectionCheckbox">
                            <label style="font-weight:normal; text-align: center; margin-left: 10px; margin-right: 10px" >Complete</label><br>
                            <input style="margin-left: 70px; margin-top: 10px" type="checkbox" name="inspectionCheckbox" id="inspectionCheckbox">
                            <label style="font-weight:normal; text-align: center; margin-left: 10px; margin-right: 10px; margin-bottom: 25px">Partial (Please specify quantity)</label>
                            <br><br>
                            <span style="margin-left: 127px; ">
                            <b>NOLI T. TINGSON</b><br>
                            <b style="margin-left: 130px; font-weight: normal">Supply Officer IV</b>

                            </span>

                        </div>
        </div>

@endsection

@section('scripts')


 <script type="text/javascript">
        $(document).ready(function () {
            let set = 380;
            if($("#items_table_{{$rand}}").height() < set){
                let rem = set - $("#items_table_{{$rand}}").height();
                $("#adjuster").css('height',rem)
                @if(!\Illuminate\Support\Facades\Request::has('noPrint'))
                print();
                // window.close();
                @endif
            }
        })
 </script>
@endsection