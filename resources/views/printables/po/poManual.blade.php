@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('printables.print_layouts.print_layout_main')

@section('wrapper')
    <style>
        .signature-container {
            position: relative;
        }

        .name-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80px; /* adjust this value to set the height of the signature container */
            position: relative;
        }

        .signature-wrapper {
            position: absolute;
            top: -40px; /* adjust this value to move the signature up or down */
            left: 50px;
        }

        .signature-image {
            width: 35%; /* adjust this value to set the width of the signature image */
        }

        @font-face {
            font-family: 'Cambria';
            src: url({{ storage_path("fonts/cambria.ttf") }}) format("truetype");
            font-weight: 700;
            font-style: normal;
        }
        .page-breaks {
            page-break-after: always;
        }
    </style>
    <table style="width: 100%; margin-left: -40px; font-family: 'Cambria',Times New Roman">
        <tr>
            <td style="width: 20%">
                <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
            </td>
            <td style="font-size: 14px">
                <p class="no-margin">Republic of the Philippines</p>
                <p class="no-margin">Department of Agriculture</p>
                <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                <p class="no-margin">{{\App\Swep\Helpers\Values::headerAddress()}}, {{\App\Swep\Helpers\Values::headerTelephone()}}</p>
                <p class="no-margin" style="font-size: 30px"><strong>PURCHASE ORDER</strong></p>
            </td>
        </tr>
    </table>
    <table style="font-weight: bold; border-collapse: collapse; width: 100%; border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-family: 'Cambria',Times New Roman">
        <tbody>
        <tr>
            <td style="border: 1px solid black; padding: 8px; width: 10%">Supplier:</td>
            <td style="border: 1px solid black; padding: 8px; width: 40%; font-size: 16px;">{{ strtoupper($order->supplier_name) }}</td>
            <td style="border: 1px solid black; padding: 8px; width: 15%">P.O. No.</td>
            <td style="border: 1px solid black; padding: 8px; width: 35%; font-size: 16px;">{{$order->ref_no}}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px; ">Address:</td>
            <td style="border: 1px solid black; padding: 8px; font-size: 14px;">{{$order->supplier_address}}</td>
            <td style="border: 1px solid black; padding: 8px; ">Date:</td>
            <td style="border: 1px solid black; padding: 8px; font-size: 14px;">{{\Illuminate\Support\Carbon::parse($order->date)->format('d F Y')}}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px; ">TIN:</td>
            <td style="border: 1px solid black; padding: 8px; font-size: 14px;">{{$order->supplier_tin}}</td>
            <td style="border: 1px solid black; padding: 8px; ">Mode of Procurement:</td>
            <td style="border: 1px solid black; padding: 8px; font-size: 14px;">{{$order->mode}}</td>
        </tr>
        </tbody>
    </table>

    <table style="width: 100%; border-right: 1px solid black; border-left: 1px solid black; font-family: 'Cambria',Times New Roman">
        <tr style="font-size: 14px">
            <td style="">
                <div>
                    Gentlemen:<br>
                    <b><span style="margin-left: 40px; font-size: 14px">
                          Please furnish this office the following articles subject to the terms and conditions contained herein
                        </span></b>
                </div>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid black; font-family: 'Cambria',Times New Roman">
        <tr style="font-size: 14px">
            <td style="width: 50%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    Place of Delivery: <span style="margin-left: 40px; font-size: 14px"><u>{{ strtoupper($order->place_of_delivery) }}</u></span>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    Date of Delivery: <span style="margin-left: 44px; font-size: 14px"><u>{{$order->delivery_date!=null?\Illuminate\Support\Carbon::parse($order->delivery_date)->format('F d, Y'):""}}</u></span>
                </div>
            </td>
            <td style="width: 50%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    Delivery Term: <span style="margin-left: 40px; font-size: 14px"><u>{{ strtoupper($order->delivery_term) }}</u></span>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    Payment Term: <span style="margin-left: 36px; font-size: 14px"><u>{{ strtoupper($order->payment_term) }}</u></span>
                </div>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid black; font-family: 'Cambria',Times New Roman">
        <tr style="font-size: 14px">
            <td style="width: 33%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start; color: #0a53be">
                    <b>PR No.:
                        <span style="margin-left: 40px; font-size: 14px">
                            <u>
                                {{ strtoupper($trans->cross_ref_no) }}
                            </u>
                        </span>
                    </b>
                </div>
            </td>
            <td style="width: 33%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start; background-color: yellow">
                    <b>Charge to:
                        <span style="margin-left: 40px; font-size: 14px">
                            <u>
                                 {{ strtoupper($trans->pap_code) }}
                            </u>
                        </span>
                    </b>
                </div>
            </td>
            <td style="width: 33%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>For:
                        <span style="margin-left: 10px; font-size: 14px">
                            <u>
                                @if($order->mode == 'Public Bidding')
                                    @foreach($poDetails as $poD)
                                        @foreach($rc as $res)
                                            @if($res->rc_code == $poD->resp_center)
                                                {{ strtoupper($res->desc) }},
                                            @endif
                                        @endforeach
                                    @endforeach
                                @else
                                    {{ strtoupper($rc->desc) }}
                                @endif
                            </u>
                        </span>
                    </b>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>
                        <span style="margin-left: 36px; font-size: 14px">
                            <u>
                                {{ strtoupper($trans->requested_by) }}
                            </u>
                        </span>
                    </b>
                </div>
            </td>
        </tr>
    </table>
    <table id="items_table_{{$rand}}" style="width: 100%; height: 390px; font-family: 'Cambria',Times New Roman; border-left: 1px solid black; border-right: 1px solid black">
        <thead>
        <tr>
            <th class="text-center" style="border: 1px solid black; width:15%; font-size: 16px;">Stock No.</th>
            <th class="text-center" style="border: 1px solid black; width:10%; font-size: 16px;">Unit</th>
            <th class="text-center" style="border: 1px solid black; width:40%; font-size: 16px;">Description</th>
            <th class="text-center" style="border: 1px solid black; width:10%; font-size: 16px;">Qty</th>
            <th class="text-center" style="border: 1px solid black; width:15%; font-size: 16px;">Unit Cost</th>
            <th class="text-center" style="border: 1px solid black; width:10%; font-size: 16px;">Amount</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($td))
            @php
                $nowCount = 0;
            @endphp
            @foreach($td as $item)
                @php
                    $nowCount = $nowCount + 1;
                @endphp
                <tr style="height: 10%">
                    <td class="text-center " style="vertical-align: top;">{{$item->stock_no}}</td>
                    <td class="text-center " style="vertical-align: top;">{{strtoupper($item->unit)}}</td>
                    <td class="" style="vertical-align: top;">
                        <b>{{$item->item}}</b>
                        <br>
                        {!! nl2br(e(preg_replace('/\*(\s*)/', '*', $item->description))) !!}
                    </td>
                    <td class="text-center " style="vertical-align: top;">{{$item->qty}}</td>
                    <td class="text-right" style="vertical-align: top;">
                        <b>{{number_format($item->unit_cost,2)}}</b>
                    </td>
                    <td class="text-right" style="vertical-align: top;">
                        <b>{{number_format($item->total_cost,2)}}</b>
                    </td>
                </tr>
            @endforeach
        @endif
            <tr>
                <td id="adjuster"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5" class="text-right text-strong" style="border: 1px solid black">TOTAL (GROSS)</td>
            <td class="text-strong text-right" style="border: 1px solid black;">
                {{number_format($order->total_gross,2)}}
            </td>
        </tr>
        </tfoot>
    </table>

    <table style="width: 100%; font-family: 'Cambria',Times New Roman">
        <tr style="font-size: 14px">
            <td style="width: 65%; border-left: 1px solid black; border-right: 1px solid black; font-size: 12px">
                {{$order->remarks}}
            </td>
            <td style="width: 35%; border-left: 1px solid black; border-right: 1px solid black;">
                <table class="tbl-no-pad" style="width: 100%;">
                    <tr>
                        <td>Tax Base:</td>
                        @if($supplier->is_vat == true)
                            <td style="text-align: right;">{{number_format($order->total_gross/1.12,2)}}</td>
                        @else
                            <td style="text-align: right;">{{number_format($order->total_gross,2)}}</td>
                        @endif
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">{{$order->vat}}%</td>
                        <td style="text-align: right;">{{number_format($order->tax_base_1,2)}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">{{$order->withholding_tax}}%</td>
                        <td style="text-align: right;">{{number_format($order->tax_base_2,2)}}</td>
                        <td style="text-align: right;">{{number_format($order->tax_base_1 + $order->tax_base_2,2)}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid black; font-family: 'Cambria',Times New Roman">
        <tr style="font-size: 14px">
            <td class="text-strong" style="width: 80%; border: 1px solid black;">
                <table class="">
                    <tr>
                        <td class="text-strong" style="width: 30%;">(Total Amount in Words)</td>
                        <td class="text-strong text-center" style="font-size: 14px; width: 70%; word-wrap:break-word;">
                            <u>{{strtoupper($order->total_in_words) }}</u>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="text-strong text-right" style="width: 20%; border: 1px solid black;">
                {{number_format($order->total,2)}}
            </td>
        </tr>
    </table>
    <div style="height: 240px; font-family: 'Cambria',Times New Roman">
        <table style="width: 100%;">
            <tr style="font-size: 14px">
                <td class="text-strong" style="border-left: 1px solid black; border-right: 1px solid black;">
                    <div style="display: flex; align-items: center; justify-content: flex-start;">
                        <b><span style="margin-left: 40px; font-size: 14px">In case failure to make delivery within the time specified above, a penalty of one-tenth
                        (1/10) of one percent for every day of delay shall be imposed.</span></b>
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 50%; float: left; border-collapse: collapse; font-family: 'Cambria',Times New Roman">
            <tbody>
            <tr>
                <td style="border-left: 1px solid black; padding: 8px; text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black; padding: 8px; text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black; padding: 8px; text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black; padding: 8px; text-align: center;">&nbsp;</td>
            </tr>
            </tbody>
        </table>
        <table style="width: 50%; float: right; border-collapse: collapse; font-family: 'Cambria',Times New Roman">
            <tbody>
            <tr>
                <td style="border-right: 1px solid black; padding: 8px; text-align: center; font-size: 14px"><b>Very truly yours,</b></td>
            </tr>
            <tr>
                <td style="border-right: 1px solid black; padding: 8px; text-align: center;"></td>
            </tr>
            <tr>
                <td style="border-right: 1px solid black; padding: 8px; text-align: center; font-size: 16px"><b>{{$order->authorized_official}}</b></td>
            </tr>
            <tr>
                <td style="border-right: 1px solid black; padding: 8px; text-align: center; font-size: 14px"><u>{{$order->authorized_official_designation}}</u></td>
            </tr>
            </tbody>
        </table>
        <table style="width: 50%; float: right; border-collapse: collapse; font-family: 'Cambria',Times New Roman">
            <tbody>
            <tr>
                <td style="border-right: 1px solid black; padding: 8px; text-align: center; font-size: 14px">&nbsp;</td>
            </tr>
            <tr>
                <td style="border-right: 1px solid black; padding: 8px; text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border-right: 1px solid black; padding: 8px; text-align: center; font-size: 16px">&nbsp;</td>
            </tr>
            <tr>
                <td style="border-right: 1px solid black; padding: 8px; text-align: center; font-size: 16px">&nbsp;</td>
            </tr>
            <tr>
                <td style="border-right: 1px solid black; padding: 8px; text-align: center; font-size: 14px">&nbsp;</td>
            </tr>
            </tbody>
        </table>

        <table style="width: 50%; float: left; border-collapse: collapse; font-family: 'Cambria',Times New Roman">
            <tbody>
            <tr>
                <td style="border-left: 1px solid black; padding: 8px; font-size: 14px"><b>Conforme:</b></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black; padding: 8px; font-size: 14px">&nbsp;</td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black; padding: 8px; text-align: center; font-size: 14px"><b>(Signature Over Printer Name of Supplier)</b></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black; padding: 8px; text-align: center; font-size: 14px">________________________________</td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black; padding: 8px; text-align: center; font-size: 14px"><b>Date</b></td>
            </tr>
            </tbody>
        </table>
    </div>
    <table style="width: 100%; margin-top: -20px; border: 1px solid black; font-family: 'Cambria',Times New Roman">
        <tr style="font-size: 14px">
            <td style="width:70%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <span style="font-size: 14px">Funds Available:</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <span style="margin-left: 120px; margin-top: 30px; font-size: 16px"><b>{{$order->funds_available}}</b>
                    </span>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <span style="margin-left: 135px; font-size: 14px"><u>{{$order->funds_available_designation}}</u></span>
                </div>
            </td>
            <td style="width: 30%">
                <div style="margin-top: -10px; margin-bottom: 35px;">
                    BUR No. <span style="margin-left: 40px; font-size: 14px">________________</span>
                </div>
                <div style="">
                    Amount: <span style="margin-left: 36px; font-size: 14px">________________</span>
                </div>
            </td>
        </tr>
    </table>
    <div class="qms-right" style="font-size: 12px;">
        <p class="no-margin">FM-AFD-PPS-003,Rev.00</p>
        <p class="no-margin">Effectivity Date: March 12, 2015</p>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            print();
            // close();
            let set = 625;
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