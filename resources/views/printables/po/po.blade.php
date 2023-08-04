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
    <table style="width: 100%; margin-left: -40px;">
        <tr>
            <td style="width: 20%">
                <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
            </td>
            <td style="font-size: 14px">
                <p class="no-margin">Republic of the Philippines</p>
                <p class="no-margin">Department of Agriculture</p>
                <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                <p class="no-margin">Araneta St., Singcang, Bacolod City, Tel No. 433-6891</p>
                <p class="no-margin" style="font-size: 30px"><strong>PURCHASE ORDER</strong></p>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid black; margin-top: 20px">
        <tr style="font-size: 14px">
            <td style="width: 50%; border: 1px solid black;">
                <table width="100%" class="tbl-no-pad">
                    <tr>
                        <td class="text-strong">Supplier:</td>
                        <td class="text-strong" style="font-size: 18px; text-align: center"><u>{{ strtoupper($order->supplier_name) }}</u></td>
                    </tr>
                    <tr>
                        <td class="text-strong">Address:</td>
                        <td class="text-strong" style="font-size: 14px; text-align: center"><u>{{$order->supplier_address}}</u></td>
                    </tr>
                    <tr>
                        <td class="text-strong">TIN:</td>
                        <td class="text-strong" style="font-size: 14px; text-align: center"><u>{{$order->supplier_tin}}</u></td>
                    </tr>
                </table>
                {{--<div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>Supplier: <span style="margin-left: 40px; font-size: 18px"><u>{{ strtoupper($order->supplier_name) }}</u></span></b>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>Address: <span style="margin-left: 40px; font-size: 14px"><u>{{$order->supplier_address}}</u></span></b>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>TIN: <span style="margin-left: 65px; font-size: 14px"><u>{{$order->supplier_tin}}</u></span></b>
                </div>--}}
            </td>
            <td style="width: 50%">
                <table width="100%" class="tbl-no-pad">
                    <tr>
                        <td class="text-strong">P.O. No.</td>
                        <td class="text-strong" style="font-size: 18px; text-align: center"><u>{{$order->ref_no}}</u></td>
                    </tr>
                    <tr>
                        <td class="text-strong">Date:</td>
                        <td class="text-strong" style="font-size: 14px; text-align: center"><u>{{\Illuminate\Support\Carbon::parse($order->date)->format('d F Y')}}</u></td>
                    </tr>
                    <tr>
                        <td class="text-strong">Mode of Procurement:</td>
                        <td class="text-strong" style="font-size: 14px; text-align: center"><u>{{$order->mode}}</u></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid black;">
        <tr style="font-size: 14px">
            <td style="border: 1px solid black;">
                <div>
                    Gentlemen:<br>
                    <b><span style="margin-left: 40px; font-size: 14px">
                          Please furnish this office the following articles subject to the terms and conditions contained herein
                        </span></b>
                </div>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid black;">
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
    <table style="width: 100%; border: 1px solid black;">
        <tr style="font-size: 14px">
            <td style="width: 33%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start; color: #0a53be">
                    <b>{{$trans->transaction->ref_book == 'PR'?'PR No.:':'JR No.:'}} <span style="margin-left: 40px; font-size: 14px"><u>{{ strtoupper($trans->transaction->ref_no) }}</u></span></b>
                </div>
            </td>
            <td style="width: 33%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start; background-color: yellow">
                    <b>Charge to: <span style="margin-left: 40px; font-size: 14px"><u>{{ strtoupper($trans->transaction->pap_code) }}</u></span></b>
                </div>
            </td>
            <td style="width: 33%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>For: <span style="margin-left: 10px; font-size: 14px"><u>{{ strtoupper($rc->desc) }}</u></span></b>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b><span style="margin-left: 36px; font-size: 14px"><u>{{ strtoupper($trans->transaction->requested_by) }}</u></span></b>
                </div>
            </td>
        </tr>
    </table>
    <table style="width: 100%; height: 390px;" class="tbl-bordered">
        <thead>
        <tr>
            <th class="text-center" style="width:10%; font-size: 16px;">Stock No.</th>
            <th class="text-center" style="width:10%; font-size: 16px;">Unit</th>
            <th class="text-center" style="width:50%; font-size: 16px;">Description</th>
            <th class="text-center" style="width:10%; font-size: 16px;">Qty</th>
            <th class="text-center" style="width:10%; font-size: 16px;">Unit Cost</th>
            <th class="text-center" style="width:10%; font-size: 16px;">Amount</th>
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
                    <td class="text-center " style="vertical-align: top;"><b>{{$item->item}}</b><br>{{strtoupper($item->description)}}</td>
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
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5" class="text-right text-strong">TOTAL (GROSS)</td>
            <td class="text-strong text-right">
                {{number_format($order->total_gross,2)}}
            </td>
        </tr>
        </tfoot>
    </table>

    <table style="width: 100%; border: 1px solid black;">
        <tr style="font-size: 14px">
            <td style="width: 65%; border: 1px solid black;">

            </td>
            <td style="width: 35%; border: 1px solid black;">
                <table width="100%" class="tbl-no-pad">
                    <tr>
                        <td>Tax Base:</td>
                        <td style="text-align: right;">{{number_format($order->total_gross/1.12,2)}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">{{$supplier->is_vat?"5%":"3%"}}</td>
                        <td style="text-align: right;">{{number_format($order->tax_base_1,2)}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">1%</td>
                        <td style="text-align: right;">{{number_format($order->tax_base_2,2)}}</td>
                        <td style="text-align: right;">{{number_format($order->tax_base_1 + $order->tax_base_2,2)}}</td>
                    </tr>
                </table>
                {{--<div style="display: flex; align-items: center; justify-content: flex-start;">
                    Tax Base: <span style="font-size: 12px">{{number_format($order->total_gross/1.12,2)}}</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <span style="font-size: 12px">{{number_format($order->tax_base_1,2)}}</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <span style="font-size: 12px">{{number_format($order->tax_base_2,2)}}</span>
                    <span style="font-size: 12px">{{number_format($order->tax_base_1 + $order->tax_base_2,2)}}</span>
                </div>--}}
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid black;">
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
    <div style="height: 240px">
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
        <table style="width: 100%; border-left: 1px solid black; border-right: 1px solid black;">
            <tr style="font-size: 14px">
                <td style="width: 50%;">

                </td>
                <td style="width: 50%">
                    <div style="display: flex; align-items: center; justify-content: flex-start;">
                        <b><span style="margin-left: 160px; font-size: 14px">Very truly yours,</span></b>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <span style="margin-left: 130px; margin-top: 30px; font-size: 16px"><b>{{$order->authorized_official}}</b>
                    </span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: flex-start;">
                        <span style="margin-left: 125px; font-size: 14px"><u>{{$order->authorized_official_designation}}</u></span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: flex-start;">
                        <b><span style="margin-left: 140px; font-size: 14px">(Authorized Official)</span></b>
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 100%; margin-top: -30px; border-left: 1px solid black; border-right: 1px solid black;">
            <tr style="font-size: 14px">
                <td style="width: 50%;">
                    <div style="display: flex; align-items: center; justify-content: flex-start;">
                        <b><span style="margin-left: 40px; font-size: 14px">Conforme:</span></b>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <span style="margin-left: 120px; margin-top: 30px; font-size: 16px">
                    </span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: flex-start;">
                        <b><span style="margin-left: 70px; font-size: 14px">(Signature Over Printer Name of Supplier)</span></b>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: flex-start; margin-top: -20px; ">
                    <span style="margin-left: 70px; margin-top: 30px; font-size: 18px"><b>___________________________</b>
                    </span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: flex-start;">
                        <b><span style="margin-left: 170px; font-size: 14px">Date</span></b>
                    </div>
                </td>
                <td style="width: 50%">
                </td>
            </tr>
        </table>
    </div>
    <table style="width: 100%; border: 1px solid black;">
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
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b><span style="margin-left: 133px; font-size: 14px">Chief Accountant</span></b>
                </div>
            </td>
            <td style="width: 30%">
                <div style="margin-top: -35px; margin-bottom: 35px;">
                    BUR No. <span style="margin-left: 40px; font-size: 14px">________________</span>
                </div>
                <div style="">
                    Amount: <span style="margin-left: 36px; font-size: 14px">________________</span>
                </div>
            </td>
        </tr>
    </table>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            print();
            // close();
        })
    </script>
@endsection