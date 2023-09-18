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
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Araneta St., Singcang, Bacolod City, Tel No. 433-6891</p>
                    <p class="no-margin text-strong" style="font-size: 14px;">
                        PROPERTY/PROCUREMENT/BUILDING & TRANSPORT MAINTENANCE SECTION
                    </p>
                </div>

                <span class="" style="float: right">
                    {{ QrCode::size(50)->generate(route("dashboard.ris.index",$ris->slug)) }}
                </span>


                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin-top: 105px">
                    <tbody>

                    <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                        <td rowspan="2" style="width: 49%; border-right: 1px solid black; font-size: 24px">
                            <strong>Requisition and Issue Slip</strong>
                        </td>

                        <td style="margin-top: 5px; width: 14%; border-right: 1px solid black; position: relative;">
                            RIS No.:
                        </td>
                        <td style="" class="text-strong ">
                            {{$ris->ref_no}}
                        </td>
                    </tr>

                        <td style="margin-top: 5px; width: 14%; border-right: 1px solid black; border-top: 1px solid black; position: relative;">
                            RIS Date:
                        </td>
                        <td style="border-top: 1px solid black;" class="text-strong ">
                            {{$ris->date}}
                        </td>

                    </tr>

                    </tbody>
                </table>

                <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >

                    <tr style="border: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 14%; vertical-align: center;">
                            PAP Code:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 35%">
                            {{$ris->pap_code}}
                        </td>
                        <td style="border-right: 1px solid black; width: 14%; vertical-align: center;">
                            Department:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 35%">
                            {{$rc->desc}}
                        </td>
                    </tr>

                    <tr style=" border-right: 1px solid black; border-left: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 12.5%; vertical-align: top;">
                            SAI No.:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black;  width: 12.5%">
                            {{$ris->sai}}
                        </td>

                        <td style="border-right: 1px solid black; vertical-align: center; width: 10%">
                            SAI Date:
                        </td>
                        <td class="text-strong" style="vertical-align: top; width: 40%;">
                            {{$ris->sai_date}}
                        </td>

                    </tr>

                </table>

                <table  id="items_table_{{$rand}}" style="font-family: Cambria,Arial; width: 100%; text-align: center;  border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                    <thead>
                    <tr class="text-strong" style="border: 1px solid black; width: 100%">
                        <td style="border: 1px solid black; width: 8%;">Stock No:</td>
                        <td style="border: 1px solid black; width: 8%;">Unit</td>
                        <td style="border: 1px solid black; width: 30%; text-align: center;">Item</td>
                        <td style="border: 1px solid black; width: 8%;">Quantity</td>
                        <td style="border: 1px solid black; width: 8%;">Actual Quantity</td>
                        <td style="border: 1px solid black; width: 25%;">Remarks</td>
                    </tr>
                    </thead>
                    <tbody style="font-family: Cambria,Arial;">

                        @foreach($ris->transDetails as $item)
                            <tr style="width: 100%">
                                <td style="vertical-align: top; width: 8%;">{{$item->stock_no}}</td>
                                <td style="vertical-align: top; width: 8%;">{{$item->unit}}</td>
                                <td class="" style="vertical-align: top; width: 30%; text-align: left;">
                                    <b style="font-size: 11px; font-weight: normal  ">{{$item->item}}</b><br>
                                    <span style="font-size: 9px; font-style: italic" >
                                    {{$item->description ? $item->description : ""}}
                                    </span>
                                </td>
                                <td style="vertical-align: top; width: 8%;">{{$item->qty}}</td>
                                <td style="vertical-align: top; width: 8%;">{{$item->actual_qty}}</td>
                                <td style="vertical-align: top; width: 25%; text-align: left;">{{($item->remarks)}}</td>
                            </tr>

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


                <table style="font-family: Cambria,Arial; width: 100%; border-left: 1px solid black;  border-right: 1px solid black; ">
                    <tr rowspan="2" style="width: 100%; border-right: 1px solid black">
                        <td style="border-right: 1px solid black; width: 15%; vertical-align: top;">
                            <b>Purpose: </b>
                        </td>
                        <td class="" style="border-right: 1px solid black; vertical-align: top;  width: 85%">
                            {{$ris->purpose}}
                        </td>
                    </tr>
                </table>

        <div style="font-family: Cambria,Arial; display: flex; border: 1px solid black">
            <div style="flex: 1; text-align: center; ">
                <h5 class="" style="margin-left: 5px; margin-bottom: 30px;"><b>Requested by:</b></h5><br>

                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    <b>{{$ris->requested_by}}</b>
                </td><br>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    {{$ris->requested_by_designation}}
                </td>

            </div>

            <div style="flex: 1; text-align: center; border-left: 1px solid black">
                <h5 class="" style="margin-left: 5px; margin-bottom: 10px;"><b>Approved by:</b></h5><br><br>

                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    <b>{{$ris->approved_by}}</b>
                </td><br>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    {{$ris->approved_by_designation}}
                </td>



            </div>

            <div style="flex: 1; text-align: center; border-left: 1px solid black">
                <h5 class="" style="margin-left: 5px; margin-bottom: 10px;"><b>Issued by:</b></h5><br><br>

                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    <b>{{$ris->prepared_by}}</b>
                </td><br>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    {{$ris->prepared_by_position}}
                </td>

            </div>

            <div style="flex: 1; text-align: center; border-left: 1px solid black">
                <h5 class="" style="margin-left: 5px; margin-bottom: 10px;"><b>Received by:</b></h5><br><br>

                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    <b>{{$ris->certified_by}}</b>
                </td><br>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    {{$ris->certified_by_designation}}
                </td>

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