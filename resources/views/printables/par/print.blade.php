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
                <div style="float: left; text-align: left; margin-left: 15px">
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Republic of the Philippines</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Department of Agriculture</p>
                    <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Araneta St., Singcang, Bacolod City, Tel No. 433-6891</p>
                    <p style="font-size: 20px;"><strong>PROPERTY ACKNOWLEDGEMENT RECEIPT</strong></p>
                </div>
            </div>

            <table style="width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                <thead>
                    <tr class="text-strong" style="border: 1px solid black;">
                        <td style="border: 1px solid black;">Qty</td>
                        <td style="border: 1px solid black;">Unit</td>
                        <td style="border: 1px solid black; width: 50%;">Description</td>
                        <td style="border: 1px solid black;">Date Acquired</td>
                        <td style="border: 1px solid black;">Property Number</td>
                        <td style="border: 1px solid black;">Amount</td>
                    </tr>
                </thead>
                <tbody style="height: 450px">
                    <tr style="height: 10px">
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"><i>For:</i></td>
                        <td class="text-strong" style="vertical-align: top; font-size: 16px">{{$par->respcenter}}</td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">{{$par->qtypercard}}</td>
                        <td style="vertical-align: top;">{{$par->uom}}</td>
                        <td class="text-strong" style="vertical-align: top; font-size: 16px">{{$par->description}}</td>
                        <td style="vertical-align: top;">{{ date('F j, Y', strtotime($par->dateacquired))}}</td>
                        <td class="text-strong" style="vertical-align: top; font-size: 14px">{{$par->propertyno}}</td>
                        <td style="vertical-align: top;">{{number_format($par->acquiredcost,2)}}</td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
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
                <tbody style="">
                    <tr>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">
                            Supplier:
                        </td>
                        <td class="text-strong" style="vertical-align: top; font-size: 16px">
                            {{$par->supplier}}
                        </td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">
                            Invoice Nos./Date:
                        </td>
                        <td class="text-strong" style="vertical-align: top; font-size: 16px">
                            {{$par->invoiceno}}/{{ date('F j, Y', strtotime($par->invoicedate))}}
                        </td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">
                            Cost:
                        </td>
                        <td class="text-strong" style="vertical-align: top; font-size: 16px">
                            {{number_format($par->acquiredcost,2)}}
                        </td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">
                           P.O. No./Date:
                        </td>
                        <td class="text-strong" style="vertical-align: top; font-size: 16px">
                            {{$par->pono}}/{{ date('F j, Y', strtotime($par->podate))}}
                        </td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">
                            Account Code:
                        </td>
                        <td class="text-strong" style="vertical-align: top; font-size: 16px">
                           {{$par->invtacctcode}}
                        </td>
                        <td style="vertical-align: top;"></td>
                        <td style="vertical-align: top;"></td>
                    </tr>
                </tbody>
            </table>
            <div style="display: flex; border: 1px solid black">
                <div style="flex: 1; text-align: left; ">
                    <h5 class="text-strong" style="margin-left: 5px; margin-bottom: 30px;"><i>Received by:</i></h5>
                    <span class="text-strong" style="margin-left: 50px;">
                        <u>{{$par->acctemployee_fname}}</u>
                    </span><br>
                    <span style="margin-left: 40px">
                       <i>Signature Over Printer Name</i>
                    </span><br>
                    <span class="text-strong" style="margin-left: 50px;">
                        <u>{{$par->acctemployee_post}}</u>
                    </span><br>
                    <span style="margin-left: 70px">
                       <i>Position / Office</i>
                    </span><br>
                    <span class="text-strong" style="margin-left: 50px;">
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
                       <i>Signature Over Printer Name</i>
                    </span><br>
                    <span class="text-strong" style="margin-left: 70px;">
                        <u>Supply Officer IV</u>
                    </span><br>
                    <span style="margin-left: 70px">
                       <i>Position / Office</i>
                    </span><br>
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