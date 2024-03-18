@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    $rand = \Illuminate\Support\Str::random();
@endphp

@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')
    <div class="printable">
        <div style="width: 99%;">
            <div class="" style="padding-top: 50px;">
                <div>
                    <img src="{{ asset('images/sra.png') }}" style="width:100px; float: left">
                </div>
                <div style="font-family: Cambria,Arial; float: left; text-align: left; margin-left: 15px; margin-top: 10px">
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Republic of the Philippines</p>
                    <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">{{\App\Swep\Helpers\Values::headerAddress()}}, {{\App\Swep\Helpers\Values::headerTelephone()}}</p>
                    <p class="no-margin text-strong" style="font-size: 14px;">
                        PROPERTY/PROCUREMENT/BUILDING & TRANSPORT MAINTENANCE SECTION
                    </p>
                </div>

                {{--<span class="" style="float: right; margin-right: 20px;">
                    {{ QrCode::size(50)->generate(route("dashboard.ics.index",$ics->slug)) }}
                </span>--}}

                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin-top: 110px">
                    <tbody style="margin: 0; padding: 0">
                        <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                            <td rowspan="5" style="font-size: 25px; width: 50%; border-right: 1px solid black">
                                <strong>INVENTORY CUSTODIAN SLIP</strong>
                            </td>
                        </tr>
                        {{--<tr style="margin-top: 5px; border-bottom: 1px solid black;">
                            <td style="border-right: 1px solid black; vertical-align: center; width: 15%; ">
                                ICS No:
                            </td>
                            <td style="border-right: 1px solid black; vertical-align: center;">
                                <span class="text-strong">{{$ics->ref_no}}</span>
                            </td>
                        </tr>
                        --}}{{--<tr style="border-bottom: 1px solid black;">
                            <td style="border-right: 1px solid black; vertical-align: center;">
                               Resp. Center:
                            </td>
                            <td style="border-right: 1px solid black; vertical-align: center;">
                               <span class="text-strong">{{$rc->desc}}</span>
                            </td>
                        </tr>--}}{{--
                        <tr style="border-bottom: 1px solid black;">
                            <td style="border-right: 1px solid black; vertical-align: center;">
                               Fund Cluster:
                            </td>
                            <td style="border-right: 1px solid black; vertical-align: center;">
                                <span class="text-strong">{{$ics->fund_cluster}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid black; vertical-align: center;">
                                Account Code:
                            </td>
                            <td style="border-right: 1px solid black; vertical-align: center;">
                               <span class="text-strong">{{$ics->account_code}}</span>
                            </td>
                        </tr>--}}
                    </tbody>
                </table>
                <table id="items_table_{{$rand}}" style="font-family: Cambria,Arial; width: 100%; text-align: center; border-left: 1px solid black; border-right: 1px solid black;">
                    <thead>
                    <tr class="text-strong" style="border-top: 1px solid black; width: 100%">
                        <td style="border-right: 1px solid black; border-bottom: 1px solid black; width: 8%;">ICS No.</td>
                        <td style="border-right: 1px solid black; border-bottom: 1px solid black; width: 4%;">Fund Cluster</td>
                        <td style="border-right: 1px solid black; border-bottom: 1px solid black; width: 6%;">Account Code</td>
                        <td style="border-right: 1px solid black; border-bottom: 1px solid black; width: 8%;">Quantity</td>
                        <td style="border-right: 1px solid black; border-bottom: 1px solid black; width: 8%;">Unit</td>
                        <td style="border-right: 1px solid black; border-bottom: 1px solid black; width: 8%">Unit Cost</td>
                        <td style="border-right: 1px solid black; border-bottom: 1px solid black; width: 8%; text-align: center">Total Cost</td>
                        <td style="border-right: 1px solid black; border-bottom: 1px solid black; width: 30%; text-align: center;">Description</td>
                        <td style="border-right: 1px solid black; border-bottom: 1px solid black; width: 8%; text-align: center;">Stock No.</td>
                        <td style="border-right: 1px solid black; border-bottom: 1px solid black; width: 8%; text-align: center;">Estimated Useful Life</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($icsS as $ics)
                        @foreach($ics->transDetails as $item)
                            <tr>
                                <td style="vertical-align: top;">{{$ics->ref_no}}</td>
                                <td style="vertical-align: top;">{{$ics->fund_cluster}}</td>
                                <td style="vertical-align: top;">{{$ics->account_code}}</td>
                                <td style="vertical-align: top;">{{$item->qty}}</td>
                                <td style="vertical-align: top;">{{$item->unit}}</td>
                                <td style=" vertical-align: top; text-align: right">{{number_format($item->unit_cost,2)}}</td>
                                <td style="vertical-align: top; text-align: right">{{number_format($item->total_cost,2)}}</td>
                                <td class="" style="vertical-align: top; text-align: left;">
                                    <b style="font-size: 12px;">{{$item->item}}</b><br>
                                    <span style="white-space: pre-line; font-size: 10px; font-style: italic" >
                                    {{$item->description ? $item->description : ""}}
                                    </span>
                                </td>
                                <td style="vertical-align: top; ">{{$item->stock_no}}</td>
                                <td style="vertical-align: top; ">{{$item->estimated_useful_life == null ? "" : $item->estimated_useful_life}}</td>

                            </tr>
                        @endforeach
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
            </div>

            <div style="display: flex; font-size: 14px; font-family: Cambria,Arial; border-top: 1px solid black; border-bottom: 1px solid black;">
                <div style="flex: 1; padding: 10px; border-right: 1px solid black;  border-left: 1px solid black;  box-sizing: border-box; text-align: center;">
                    <p style="text-align: left;">Received from:</p>
                    <p><br></p>
                    <p style="margin: 0; padding:0; font-size: 16px;"><strong><u>NOLI T. TINGSON</u></strong></p>
                    <p style="margin: 0; padding:0">Supply Officer IV</p>
                    <p>_______________________________</p>
                    <p style="margin-top: -10px;">Date</p>
                </div>
                <div style="flex: 1; padding: 10px; border-right: 1px solid black; box-sizing: border-box; text-align: center;">
                    <p style="text-align: left;">Received by:</p>
                    <p><br></p>
                    <p style="margin: 0; padding:0; font-size: 16px;"><strong><u>{{$employee}}</u></strong></p>
                    <p style="margin: 0; padding:0">{{$position}}</p>
                    <p>_______________________________</p>
                    <p style="margin-top: -10px;">Date</p>
                </div>
            </div>
            <div class="qms-right" style="font-size: 12px">
                <p class="no-margin">FM-AFD-PPS-003,Rev.00</p>
                <p class="no-margin">Effectivity Date: March 12, 2015</p>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                print();
                // close();
                let set = 520;
                if($("#items_table_{{$rand}}").height() < set){
                    let rem = set - $("#items_table_{{$rand}}").height();
                    $("#adjuster").css('height',rem);
                    @if(!\Illuminate\Support\Facades\Request::has('noPrint'))
                    print();
                    // window.close();
                    @endif
                }
            })
        </script>
    @endsection