@php
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
                <p class="no-margin">Sugar Center Bldg., North Avenue, Diliman, Quezon City, Philippines 1101 TIN 000-784-336</p>
            </td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 65%">
                <br>
                ____________________<br>
                ____________________<br>
                ____________________
            </td>
            <td>
                Quotation No. <b>{{$trans->ref_no}}</b><br>
                {{$trans->transaction->ref_book}} No.: <b>{{$trans->transaction->ref_no}}</b><br>
                Date: <b>{{\Illuminate\Support\Carbon::parse($trans->rfq_created_at)->format('F d, Y')}}</b>
            </td>
        </tr>
    </table>
    <p class="text-justify">
        Please quote your lowest price on the item/s listed below, subject to the General Conditions, stating the shortest time of delivery and submit your quotation duly signed by your representative not later than <u><b>{{strtoupper(\Illuminate\Support\Carbon::parse($trans->rfq_deadline)->format('F d, Y'))}}</b></u>.
    </p>
    <table style="width: 100%;">
        <tr>
            <td style="width: 65%">
            </td>
            <td class="text-center">
                <div class="signature-container">
                    <div class="name-container">
                        <div class="signature-wrapper">
                            <img src="{{ asset('images/NoliTSign.png') }}" alt="Signature" class="signature-image">
                        </div>
                        <div class="name-info">
                            <span class="name"><u><b>NOLI T. TINGSON</b></u></span><br>
                            <span class="designation">Supply Officer IV</span>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: -20px;">
        <tr>
            <td style="width: 8%" class="text-top">
                NOTE:
            </td>
            <td class="text-justify">
                <ol>
                    <li>
                        SUPPLIERS SHALL QUOTE THEIR <b>LOWEST NET PRICE</b> ON THE ITEM/S LISTED BELOW, GIVING <b>FULL AND DETAILED DESCRIPTION</b> OF THEIR OFFERS TO BE SUBMITTED IN A <b>SEALED ENVELOPE</b> ADDRESSED TO THE SUGAR REGULATORY ADMINISTRATION.
                    </li>
                    <li>
                        DELIVERY PERIOD SHALL BE WITHIN ___  CALENDAR DAYS.
                    </li>
                    <li>
                        PRICE VALIDITY SHALL BE FOR A PERIOD OF ___ CALENDAR DAYS.
                    </li>
                    <li>
                        WARRANTY SHALL BE FOR A PERIOD OF SIX (6) MONTHS FOR SUPPLIES & MATERIALS, ONE (1) YEAR FOR EQUIPMENT, <b>FROM DATE OF ACCEPTANCE BY THE PROCURING ENTITY.</b>
                    </li>
                    <li>
                        <b>PHILGEPS CERTIFICATE OF REGISTRATION AND MEMBERSHIP SHALL BE ATTACHED UPON SUBMISSION OF THE QUOTATION.</b>
                    </li>
                    <li>
                        OTHER REQUIREMENTS SUCH AS THE UPDATED <b>MAYOR’S/BUSINESS PERMIT, INCOME/BUSINESS TAX RETURN, PROFESSIONAL LICENSE/CURRICULUM VITAE (CONSULTING SERVICES), PCAB LICENSE (INFRASTUCTURE PROJECTS) AND OMNIBUS SWORN STATEMENT (FOR SMALL VALUE PROCUREMENT WITH APPROVED BUDGET FOR THE CONTRACT OF ₱ 50,000.00 AND ABOVE)</b> SHALL BE SUBMITTED <b>PRIOR TO THE ISSUANCE OF NOTICE OF AWARD.</b>
                    </li>
                </ol>
            </td>
        </tr>
    </table>
    <table style="width: 100%" class="tbl-bordered">
        <thead>
            <tr>
                <th class="text-center" style="width:5%">ITEM #</th>
                <th class="text-center" style="width:5%">QTY.</th>
                <th class="text-center" style="width:5%">UNIT</th>
                @if($trans->transaction->ref_book == "JR")
                    <th class="text-center" style="width:35%">ITEM & DESCRIPTION</th>
                    <th class="text-center" style="width:30%">NATURE OF WORK</th>
                @elseif($trans->transaction->ref_book == "PR")
                    <th class="text-center" style="width:65%">ITEM & DESCRIPTION</th>
                @endif
                <th class="text-center" style="width:10%">ABC</th>
                <th class="text-center" style="width:10%">OFFER</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($trans->transaction->transDetails))
                @foreach($trans->transaction->transDetails as $item)
                    <tr>
                        <td class="text-center text-top" style="width: 8%">{{$loop->iteration}}</td>
                        <td class="text-center text-top" style="width: 5%">{{$item->qty}}</td>
                        <td class="text-center text-top" style="width: 10%">{{strtoupper($item->unit)}}</td>
                        @if($trans->transaction->ref_book == "JR")
                            <td>
                                <b>{{$item->item}}</b>
                                <span style="white-space: pre-line">
                                {{$item->description}}
                            </span>
                            </td>
                            @if($item->nature_of_work != null || $item->nature_of_work != "")
                                <td rowspan="{{ count($trans->transaction->transDetails) }}">
                                    <span style="white-space: pre-line">
                                        {{$item->nature_of_work}}
                                    </span>
                                </td>
                            @endif
                        @elseif($trans->transaction->ref_book == "PR")
                            <td>
                                <b>{{$item->item}}</b>
                                <span style="white-space: pre-line">
                                {{$item->description}}
                            </span>
                            </td>
                        @endif
                        <td class="text-right" >
                            @if($trans->transaction->abc >= 50000)
                                {{number_format($item->total_cost,2)}}
                            @endif
                        </td>
                        <td class="text-center text-top" style="width: 10%"><br>____________</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right text-strong">TOTAL</td>
                @if($trans->transaction->ref_book == "JR")
                    <td></td>
                    <td class="text-strong text-right">{{number_format($trans->transaction->abc,2)}}</td>
                @elseif($trans->transaction->ref_book == "PR")
                    <td class="text-strong text-right">{{number_format($trans->transaction->abc,2)}}</td>
                @endif
                <td></td>
            </tr>
        </tfoot>
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