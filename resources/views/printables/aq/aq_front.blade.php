@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('printables.print_layouts.print_layout_main')

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
<div style="font-family: 'Cambria',Arial">
    @if(count($pages) > 0)
        @foreach($pages as $quotations)
        <div  class="page-breaks">
            <table style="width: 100%">
                <tr style="font-size: 15px">
                    <td style="width: 90%;" class="text-center">
                        <p class="no-margin">SUGAR REGULATORY ADMINISTRATION</p>
                        <p class="no-margin">Araneta St., Singcang, Bacolod City</p>
                        <p class="no-margin">Tel No. 433-4962, Fax No. 4353758</p>
                        <p class="text-strong">ABSTRACT OF QUOTATIONS</p>
                    </td>
                    <td class="text-right" style="vertical-align: bottom">
                        <p>Page {{$loop->iteration}} of {{count($pages)}}</p>
                        <h4 class="no-margin text-strong">AQ. No.: {{$trans->ref_no}}</h4>
                        DATE: DATEEEEE
                    </td>
                </tr>
            </table>


            <table class="tbl-bordered" style="width: 100%;">
                <thead>
                <tr>
                    <th class="text-center" rowspan="3" style="width: 40px; word-break: break-word">Item No.</th>
                    <th class="text-center" rowspan="3" style="width: 50px;">Qty</th>
                    <th class="text-center" rowspan="3" style="width: 80px;">Unit</th>
                    <th class="text-center">Description of Articles</th>
                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <th class="text-center">
                                SUPPLIER/DEALER
                            </th>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <th class="text-center">DEPARTMENT</th>
                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <th class="text-center">
                                {{$quotation['obj']->supplier_slug}}
                            </th>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <th class="text-center">REQUISITIONER</th>
                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <th class="text-center">
                                Address
                            </th>
                        @endforeach
                    @endif
                </tr>
                </thead>
                <tbody>
                @if(!empty($trans->transaction->transDetails))
                    @foreach($trans->transaction->transDetails as $item)
                        <tr>
                            <td class="text-center" style="vertical-align: top">
                                {{$loop->iteration}}
                            </td>
                            <td class="text-center" style="vertical-align: top">
                                {{$item->qty}}
                            </td>
                            <td class="text-center" style="vertical-align: top">
                                {{strtoupper($item->unit)}}
                            </td>
                            <td style="vertical-align: top">
                                {{$item->item}}
                                @if($item->description != '')
                                    <br>
                                    <span style="white-space: pre-line; " ><i>{{$item->description}}</i></span>
                                @endif
                            </td>
                            @if(count($quotations) > 0)
                                @foreach($quotations as $quotation)
                                    <td style="vertical-align: top">
                                        <p class="text-center no-margin text-strong">
                                            {{(($items[$item->slug][$quotation['obj']->slug]['obj']->amount ?? null) != null ? '₱ '. number_format($items[$item->slug][$quotation['obj']->slug]['obj']->amount,2) : '')}}
                                        </p>

                                        <span style="white-space: pre-line">{{$items[$item->slug][$quotation['obj']->slug]['obj']->description ?? null}}</span>
                                    </td>
                                @endforeach
                            @endif
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><i>Warranty:</i></td>
                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <td class="text-center">
                                {{$quotation['obj']->warranty}}
                            </td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><i>Price Validity:</i></td>
                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <td class="text-center">
                                {{$quotation['obj']->price_validity}}
                            </td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><br></td>

                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <td class="text-center">
                                {{($quotation['obj']->has_attachments == 1 ? '(PLEASE SEE ATTACHED)' : '')}}
                            </td>
                        @endforeach
                    @endif

                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><i>Delivery Term:</i></td>
                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <td class="text-center">
                                {{$quotation['obj']->delivery_term}}
                            </td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><i>Payment Term:</i></td>
                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <td class="text-center">
                                {{$quotation['obj']->payment_term}}
                            </td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Approved Budget: Php {{number_format($trans->transaction->abc,2)}}</td>
                    <td colspan="{{count($quotations)}}" class="text-center">
                        <i>Please see supplier's quotation</i>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>
            <table style="width: 100%; font-size: 14px">
                <tr>
                    <td style="width: 33%">Prepared by:</td>
                    <td style="width: 33%">Noted by:</td>
                    <td style="width: 33%">Recommending Approval:</td>
                </tr>
                <tr>
                    <td><br><br></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <b><u>{{$trans->prepared_by}}</u></b><br>
                        {{$trans->prepared_by_position}}
                    </td>
                    <td>
                        <b><u>{{$trans->noted_by}}</u></b><br>
                        {{$trans->noted_by_position}}
                    </td>
                    <td>
                        <b><u>{{$trans->recommending_approval}}</u></b><br>
                        {{$trans->recommending_approval_position}}
                    </td>
                </tr>
            </table>
        </div>

    @endforeach
    @else
        <h1>No quotations available</h1>
    @endif
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        @if(!\Illuminate\Support\Facades\Request::has('noPrint'))
        print();
        @endif

    </script>
@endsection