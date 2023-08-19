@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

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
    </style>
    <div class="printable" style="font-family: Cambria,Arial;">

        <div style="width: 100%;">
            <div class="" style="margin-bottom: 100px; padding-top: 10px; font-family: Cambria,Arial;">
                <div>
                    <img src="{{ asset('images/sra.png') }}" style="width:100px; float: left">
                </div>
                <div style="float: left; text-align: left; margin-left: 15px">
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px; margin-top: 8px">Republic of the Philippines</p>
                    <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin" style="font-size: 14px;">Araneta St., Singcang, Bacolod City, Tel No. 433-6891</p>
                    <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">
                        PROPERTY/PROCUREMENT/BUILDING & TRANSPORT MAINTENANCE SECTION
                    </p>
                </div>
            </div>
            <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                <tbody>
                    <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                        <td rowspan="2" style="width: 60%; border-right: 1px solid black">
                            <p style="font-size: 20px;"><strong>PROPERTY ACKNOWLEDGMENT RECEIPT</strong></p>
                        </td>
                        <td style="margin-top: 5px; justify-content: center; float: left;">
                            <p>PAR NO.: <span class="text-strong">{{$par->par_code}}</span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="margin-top: -15px;justify-content: center; float: left;">
                            <p>Date: <span class="text-strong" style="margin-left: 17px">{{ \Carbon\Carbon::now()->format('F j, Y') }}</span></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="font-family: Cambria,Arial;  width: 100%; border-left: 1px solid black; border-right: 1px solid black">
                <tbody>
                    <tr style="border: 1px solid black">
                        <td style="width: 15%; border-right: 1px solid black;">
                            Resp. Center:
                        </td>
                        <td>
                            <strong>{{$respCenter->desc}}</strong>
                        </td>
                    </tr>
                    <tr style="border: 1px solid black">
                        <td style="width: 15%; border-right: 1px solid black;">
                            Accountable Officer:
                        </td>
                        <td>
                            <strong>{{$par->acctemployee_fname}}</strong>
                        </td>
                    </tr>
                    <tr style="border: 1px solid black">
                        <td style="width: 15%; border-right: 1px solid black;">
                            Position:
                        </td>
                        <td>
                            <strong>{{$par->acctemployee_post}}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>


            <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                <thead>
                    <tr class="text-strong" style="border: 1px solid black;">
                        <td style="border: 1px solid black;">Qty</td>
                        <td style="border: 1px solid black;">Unit</td>
                        <td style="border: 1px solid black; width: 40%;">Description</td>
                        <td style="border: 1px solid black;">Date Acquired</td>
                        <td style="border: 1px solid black; width: 20%;">Property Number</td>
                        <td style="border: 1px solid black; width: 16%; text-align: right">Amount</td>
                    </tr>
                </thead>
                <tbody style="height: 350px">
                    <tr>
                        <td style="vertical-align: top;">{{$par->qtypercard}}</td>
                        <td style="vertical-align: top;">{{$par->uom}}</td>
                        <td class="" style="vertical-align: top;">
                            <b>{{$par->article}}</b><br>
                            <span style="white-space: pre-line; font-style: italic" >
                            {{$par->description}}
                            </span>
                        </td>
                        <td style="vertical-align: top;">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $par->dateacquired)->format('m/d/Y') }}{{--{{ date('F j, Y', strtotime($par->dateacquired))}}--}}</td>
                        <td class="text-strong" style="vertical-align: top;">{{$par->propertyno}}</td>
                        <td style="vertical-align: top; text-align: right" class="text-strong">{{number_format($par->acquiredcost,2)}}</td>
                    </tr>
                </tbody>
            </table>
            <table style="font-family: Cambria,Arial; width: 100%; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                <thead>
                    <tr>
                        <td style="color: white; width: 5%;">Qty</td>
                        <td style="color: white; width: 5%;">Unit</td>
                        <td style="color: white; width: 15%;">Description</td>
                        <td style="color: white">Date Acquired</td>
                        <td style="color: white">Property Number</td>
                        <td style="color: white">Amount</td>
                    </tr>
                </thead>
                <tbody style="border: 1px solid black">
                    <tr style="border: 1px solid black">
                        <td style="border-right: 1px solid black; width: 15%; vertical-align: top;">
                            Suppliers:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top;  width: 34%">
                            {{$par->supplier}}
                        </td>
                        <td style="border-right: 1px solid black; vertical-align: top;">
                            P.O. No./Date:
                        </td>
                        <td class="text-strong" style="vertical-align: top; width: 15%;">
                            {{$par->pono}}/{{ date('F j, Y', strtotime($par->podate))}}
                        </td>
                    </tr>
                    <tr style="border: 1px solid black">
                        <td style="border-right: 1px solid black; vertical-align: top;">
                            Invoice Nos./Date:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top;">
                            {{$par->invoiceno}}/{{ date('F j, Y', strtotime($par->invoicedate))}}
                        </td>
                        <td style="border-right: 1px solid black; vertical-align: top;">
                            Account Code:
                        </td>
                        <td class="text-strong" style="vertical-align: top;">
                            {{$par->invtacctcode}}
                        </td>
                    </tr>
                    <tr style="border: 1px solid black">
                        <td style="border-right: 1px solid black;vertical-align: top;">
                            Cost:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top;">
                            {{number_format($par->acquiredcost,2)}}
                        </td>
                        <td style="border-right: 1px solid black; vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                    </tr>
                </tbody>
            </table>
            <div style="font-family: Cambria,Arial; display: flex; border: 1px solid black">
                <div style="flex: 1; text-align: left; ">
                    <h5 class="text-strong" style="margin-left: 5px; margin-bottom: 30px;"><i>Received by:</i></h5>
                    <span class="text-strong" style="margin-left: 50px;">
                        <u>{{$par->acctemployee_fname}}</u>
                    </span><br>
                    <span style="margin-left: 50px">
                       <i>{{$par->acctemployee_post}}</i>
                    </span><br>
                    <br>
                    <span class="text-strong" style="margin-left: 70px;">
                        ___________________
                    </span><br>
                    <span style="margin-left: 100px">
                       <i>Date</i>
                    </span>
                </div>
                <div  style="flex: 1">
                    <h5 class="text-strong" style="margin-left: -100px; margin-bottom: 30px;"><i>Received from:</i></h5>
                    <span class="text-strong" style="margin-left: 70px;">
                        <u>NOLI T. TINGSON</u>
                    </span><br>
                    <span style="margin-left: 70px">
                       <i>Supply Officer IV</i>
                    </span><br>
                    <br>
                    <span class="text-strong" style="margin-left: 70px;">
                        ___________________
                    </span><br>
                    <span style="margin-left: 60px">
                       <i>Date</i>
                    </span>
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