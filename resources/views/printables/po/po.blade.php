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
    <table style="width: 100%;">
        <tr>
            <td style="width: 20%">
                <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
            </td>
            <td style="font-size: 14px">
                <p class="no-margin">Republic of the Philippines</p>
                <p class="no-margin">Department of Agriculture</p>
                <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                <p class="no-margin">Araneta St., Singcang, Bacolod City, Tel No. 433-6891</p>
                <p class="no-margin" style="font-size: 20px"><strong>PURCHASE ORDER</strong></p>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid black;">
        <tr style="font-size: 14px">
            <td style="width: 50%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>Supplier: <span style="margin-left: 40px; font-size: 18px"><u>{{ strtoupper($order->supplier_name) }}</u></span></b>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>Address: <span style="margin-left: 40px; font-size: 14px"><u>{{$order->supplier_address}}</u></span></b>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>TIN: <span style="margin-left: 65px; font-size: 14px"><u>{{$order->supplier_tin}}</u></span></b>
                </div>
            </td>
            <td style="width: 50%">
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>P.O. No.: <span style="margin-left: 121px; font-size: 18px"><u>{{$order->ref_no}}</u></span></b>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>Date: <span style="margin-left: 140px; font-size: 18px"><u>{{\Illuminate\Support\Carbon::parse($order->created_at)->format('d F Y')}}</u></span></b>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <b>Mode of Procurement: <span style="margin-left: 40px; font-size: 18px"><u>{{$order->mode}}</u></span></b>
                </div>
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
                    Date of Delivery: <span style="margin-left: 44px; font-size: 14px"><u>{{\Illuminate\Support\Carbon::parse($order->delivery_date)->format('F d, Y')}}</u></span>
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
                    <b>PR No.: <span style="margin-left: 40px; font-size: 14px"><u>{{ strtoupper($trans->transaction->ref_no) }}</u></span></b>
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
    @if($trans->transaction->ref_book == 'PR')
        <table style="width: 100%;" class="tbl-bordered">
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
                    <tr style="">
                        <td class="text-center text-top" style="">{{$item->stock_no}}</td>
                        <td class="text-center text-top" style="">{{strtoupper($item->unit)}}</td>
                        <td class="text-center text-top" style=""><b>{{$item->item}}</b><br>{{strtoupper($item->description)}}</td>
                        <td class="text-center text-top" style="">{{$item->qty}}</td>
                        <td class="text-right" >
                            <b>{{number_format($item->unit_cost,2)}}</b>
                        </td>
                        <td class="text-right" >
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
    @else

    @endif
    <table style="width: 100%; border: 1px solid black;">
        <tr style="font-size: 14px">
            <td style="width: 65%; border: 1px solid black;">

            </td>
            <td style="width: 35%; border: 1px solid black;">
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                   Tax Base: <span style="margin-left: 100px; font-size: 12px">{{number_format($order->total_gross-((12 / 100) * $trans->transaction->abc),2)}}</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <span style="margin-left: 160px; font-size: 12px">{{number_format($order->tax_base_1,2)}}</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-start;">
                    <span style="margin-left: 167px; font-size: 12px">{{number_format($order->tax_base_2,2)}}</span>
                    <span style="margin-left: 60px; font-size: 12px">{{number_format($order->tax_base_1 + $order->tax_base_2,2)}}</span>
                </div>
            </td>
        </tr>
    </table>
    <br>
    <p class="text-left">After having carefully read and accepted your General Conditions, I/We quote you on the item at prices noted above.</p>
    <table style="width: 100%;">
        <tr>
            <td class="text-top">Canvassed by:</td>
            <td class="text-top">Owner/Authorized Representative:</td>
        </tr>
        <tr>
            <td class="text-top text-center">
                ___________________________________<br>
                Printed Name/ Signature
            </td>
            <td class="text-top text-center">
                ___________________________________<br>
                Printed Name/ Signature <br><br>
                ___________________________________<br>
                Tel. No. / Cellphone No. <br>
                <br>
                ___________________________________<br>
                Date
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