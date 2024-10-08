@php
    use Carbon\Carbon;
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
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
            <div style="position: relative; margin-bottom: 10px; margin-top: 10px">
                <div class="" style="margin-bottom: 116px; padding-top: 10px;">
                    <div>
                        <img src="{{ asset('images/sra.png') }}" style="width:100px; float: left">
                    </div>
                    <div style="float: left; text-align: left; margin-left: 15px">
                        <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Republic of the Philippines</p>
                        <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Department of Agriculture</p>
                        <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
                        <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">{{\App\Swep\Helpers\Values::headerAddress()}}</p>
                        <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">{{\App\Swep\Helpers\Values::headerTelephone()}}</p>
                        <p style="font-size: 18px;"><strong>ABSTRACT OF QUOTATION</strong></p>
                    </div>
                </div>
                <div style="position: absolute; bottom: 0; right: 0; text-align: right;">
                    @if($trans->is_locked)
                        {{ QrCode::size(60)->generate(route("dashboard.aq.print",$trans->slug)) }}
                    @endif
                    <p class="no-margin">Page {{$loop->iteration}} of {{count($pages)}}</p>

                    <h4 class="no-margin text-strong">AQ. No. {{$trans->ref_no}}</h4>
                    DATE: {{Carbon::createFromFormat('Y-m-d', $trans->date)->format('F j, Y')}}
                </div>
                @if($prjr->ref_book == "PR")
                    <h5 class="no-margin" style="text-align: left;"><strong>Purchase Request No. {{$prjr->ref_no}}</strong></h5>
                @elseif($prjr->ref_book == "JR")
                    <h5 class="no-margin" style="text-align: left;"><strong>Job Request No. {{$prjr->ref_no}}</strong></h5>
                @else

                    <h5 class="no-margin" style="text-align: left;"><strong>PR/JR Request No. {{$trans->cross_ref_no}}</strong></h5>
                @endif
            </div>
            <table class="tbl-bordered" style="width: 100%;">
                <thead>
                <tr>
                    <th class="text-center" rowspan="4" style="width: 5%; word-break: break-word">Item No.</th>
                    <th class="text-center" rowspan="4" style="width: 2%;">Qty</th>
                    <th class="text-center" rowspan="4" style="width: 5%;">Unit</th>
                    <th class="text-center">Description of Articles</th>
                    @if(count($quotations) > 0)
                        @php
                            $ii = 0;
                        @endphp
                        @foreach($quotations as $quotation)
                            @php
                                $ii = $ii + 1;
                            @endphp
                            <th class="text-center" style="width: 15%">
                                SUPPLIER/DEALER
                            </th>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <th class="text-center">{{$department->desc}}</th>
                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <th class="text-center">
                                {{collect($suppliers)->where('slug', $quotation['obj']->supplier_slug)->first()['name'] }}
                            </th>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <th class="text-center">{{$prjr->requested_by }}</th>
                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <th class="text-center">
                                {{collect($suppliers)->where('slug', $quotation['obj']->supplier_slug)->first()['address'] }}
                            </th>
                        @endforeach
                    @endif
                </tr>
                @php
                    $nowCount = 0;
                @endphp
                <tr>
                    <th class="">
                    {{--@foreach($trans->transaction->transDetails as $item)--}}
                        @foreach($transDetails as $item)
                        @php
                            $nowCount = $nowCount + 1;
                        @endphp

                                @if($prjr->ref_book == "JR")
                                    @if($nowCount == 1)
                                        @php
                                            $nature_of_work_str = implode('. ', array_filter($nature_of_work_arr));
                                        @endphp
                                        {{ $nature_of_work_str }}
                                    @endif
                                @endif
                    @endforeach
                    </th>
                    @if(count($quotations) > 0)
                        @foreach($quotations as $quotation)
                            <th class="text-center">

                            </th>
                        @endforeach
                    @endif
                </tr>
                </thead>
                <tbody>
                {{--@if(!empty($trans->transaction->transDetails))
                    @foreach($trans->transaction->transDetails as $item)--}}
                @if(!empty($transDetails))
                    @foreach($transDetails as $item)
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
                            <td style="vertical-align: top;">
                                {{$item->item}}
                                @if($item->description != '')
                                    <br>
                                    <br>
                                    <span style="white-space: pre-line; " ><i>{{$item->description}}</i></span>
                                @endif
                            </td>
                            @if(count($quotations) > 0)
                                @foreach($quotations as $quotation)
                                    <td style="vertical-align: top">
                                        <p class="text-center no-margin text-strong">
                                            {{(($items[$item->slug][$quotation['obj']->slug]['obj']->amount ?? null) > 0  ? '₱ '. number_format($items[$item->slug][$quotation['obj']->slug]['obj']->amount,2) : '')}}
                                        </p>
                                        <br>
                                        <p class="text-center" style="white-space: pre-line">{{$items[$item->slug][$quotation['obj']->slug]['obj']->description ?? null}}</p>
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
                    <td id="target-cell"><i>Warranty:</i></td>
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
                    @if($trans->document_type == "MANUAL AQ")
                        <td>Approved Budget: Php {{number_format($trans->abc,2)}}</td>
                    @else
                        <td>Approved Budget: Php {{number_format($trans->transaction->abc,2)}}</td>
                    @endif
                    <td colspan="{{count($quotations)}}" class="text-center">
                        <i>Please see supplier's quotation</i>
                    </td>
                </tr>
                </tbody>
            </table>

            <br>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                @if(!empty($trans->remarks))
                    <div class="" style="display: inline-block; border: 1px solid black; width: 250px;">
                        <p style="font-size: 10px;"><strong>Remarks/Certification:</strong></p>
                        <p style="font-size: 10px; margin-bottom: 50px">{{$trans->remarks}}</p>
                    </div>
                @endif

                <div style="text-align: left">
                    <p><strong>Prepared by:</strong></p>
                    <br><br>
                    <p class="no-margin text-strong" style="font-size: 18px"><u>{{strtoupper($trans->prepared_by)}}</u></p>
                    {{$trans->prepared_by_position}}
                </div>
                <div style="display: inline-block; text-align: left">
                    <p><strong>Noted by:</strong></p>
                    <br><br>
                    <p class="no-margin text-strong" style="font-size: 18px"><u>{{strtoupper($trans->noted_by)}}</u></p>
                    {{$trans->noted_by_position}}
                </div>
                <div style="display: inline-block; text-align: left;">
                    <p><strong>Recommending Approval:</strong></p>
                    <br><br>
                    <p class="no-margin text-strong" style="font-size: 18px"><u>{{strtoupper($trans->recommending_approval)}}</u></p>
                    {{$trans->recommending_approval_position}}
                </div>
            </div>
{{--
            <table style="width: 100%; font-size: 14px">
                <tr>
                    <td style="width: 30%">Prepared by:</td>
                    <td style="width: 40%">Noted by:</td>
                    <td style="width: 30%">Recommending Approval:</td>
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
            </table>--}}
            <div class="qms-right" style="font-size: 12px; margin-top: 20px;">
                <p class="no-margin">FM-AFD-PPS-003,Rev.00</p>
                <p class="no-margin">Effectivity Date: March 12, 2015</p>
            </div>
        </div>
            @if($trans->project_id == 2)
            <div  class="page-breaks">
                <div style="position: relative; margin-bottom: 10px; margin-top: 10px">
                    <br>
                    <br>
                    <div style="align-items: center;">
                        <div style="text-align: center">
                            <p class="no-margin text-strong">Approved by:</p>
                            <p class="no-margin text-strong" style="font-size: 18px">BIDDING & AWARDS COMMITTEE</p>
                        </div>
                    </div>

                    <br>
                    <br>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="text-align: center">
                            <p class="no-margin">________________________</p>
                            <p class="no-margin text-strong" style="font-size: 18px">MARCO D. SORIANO</p>
                            <span class="text-strong">Member</span>
                        </div>
                        <div style="display: inline-block; text-align: center">
                            <div style="text-align: center">
                                <p class="no-margin">________________________</p>
                                <p class="no-margin text-strong" style="font-size: 18px">DIGNA D. GONZALES</p>
                                <span class="text-strong">Member</span>
                            </div>
                        </div>
                        <div style="display: inline-block; text-align: left;">
                            <div style="text-align: center">
                                <p class="no-margin">__________________________</p>
                                <p class="no-margin text-strong" style="font-size: 18px">BLESILDA S. GREGORIO</p>
                                <span class="text-strong">Member</span>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>
                    <div style="align-items: center;">
                        <div class="col-md-6" style="text-align: center">
                            <p class="no-margin">________________________</p>
                            <p class="no-margin text-strong" style="font-size: 18px">ATTY. ARVIN C. BEÑAS</p>
                            <span class="text-strong">Vice-Chairman</span>
                        </div>
                        <div class="col-md-6"  style="text-align: center">
                            <p class="no-margin">________________________</p>
                            <p class="no-margin text-strong" style="font-size: 18px">ATTY. RONALD RIMANDO</p>
                            <span class="text-strong">Chairperson</span>
                        </div>
                    </div>

                    <div class="qms-right" style="font-size: 12px; margin-top: 20px;">
                        <p class="no-margin">FM-AFD-PPS-003,Rev.00</p>
                        <p class="no-margin">Effectivity Date: March 12, 2015</p>
                    </div>
                </div>
            </div>
            @endif
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