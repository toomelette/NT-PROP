@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('printables.print_layouts.print_layout_main')

@section('wrapper')
<div style="font-family: 'Cambria'">
    <table style="width: 100%">
        <tr style="font-size: 15px">
            <td style="width: 90%;" class="text-center">
                <p class="no-margin">SUGAR REGULATORY ADMINISTRATION</p>
                <p class="no-margin">Araneta St., Singcang, Bacolod City</p>
                <p class="no-margin">Tel No. 433-4962, Fax No. 4353758</p>
                <p>ABSTRACT OF QUOTATIONS</p>
            </td>
            <td class="text-right" style="vertical-align: bottom">
                <h4 class="no-margin text-strong">AQ. No.: {{$trans->ref_no}}</h4>
                DATE: DATEEEEE
            </td>
        </tr>
    </table>


    <table class="tbl-bordered" style="width: 100%;">
        <thead>
            <tr>
                <th class="text-center" rowspan="3" style="width: 50px; word-break: break-word">Item No.</th>
                <th class="text-center" rowspan="3">Qty</th>
                <th class="text-center" rowspan="3">Unit</th>
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
                        <td class="text-center">
                            {{$loop->iteration}}
                        </td>
                        <td class="text-center">
                            {{$item->qty}}
                        </td>
                        <td class="text-center">
                            {{strtoupper($item->unit)}}
                        </td>
                        <td>
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
                <td>Warranty:</td>
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
                <td>Price Validity:</td>
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
                <td>Delivery Term:</td>
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
                <td>Payment Term:</td>
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
@endsection

@section('scripts')
    <script type="text/javascript">



    </script>
@endsection