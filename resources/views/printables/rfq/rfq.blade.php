@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('printables.print_layouts.print_layout_main')

@section('wrapper')
    <div style="font-family: Arial" class="text-left" id="rfq_template">
        <table style="width: 100%;">
            <tr>
                <td style="width: 30%">
                    <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
                </td>
                <td style="font-size: 14px">
                    <p class="no-margin">Republic of the Philippines</p>
                    <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin">{{\App\Swep\Helpers\Values::headerAddress()}}</p>
                    <p class="no-margin">{{\App\Swep\Helpers\Values::headerTelephone()}}</p>
                </td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 14px">
            <tr class="tr-no-padding">
                <td style="width: 70%">

                </td>
                <td class="text-center">
                    <p class="no-margin b-bottom">{{Carbon::parse($rfq->created_at)->format('F d, Y')}}</p>
                </td>
            </tr>
            <tr class="tr-no-padding">
                <td></td>
                <td class="text-center">
                    <p class="no-margin">Date</p>
                </td>
            </tr>
        </table>
        <p class="text-center text-strong no-margin" style="font-size: 18px">REQUEST FOR QUOTATION</p>
        _____________________<br>
        _____________________
        <p class="no-margin" style="text-align: justify; width: 100%; text-justify: inter-word">
            <span class="indent-lg"></span>
            Please quote your LOWEST NET PRICE to the Government on the items listed below, giving full
            and detailed description of your offers, stating the shortest time of delivery and submit your quotation in a sealed
            envelope, addressed to the Sugar Regulatory Administration.
        </p>
        <table style="width: 100%; font-size: 14px">
            <tbody>
                <tr>
                    <td style="vertical-align: top">
                        <p class="no-margin">
                            Deadline of Submission: <b>Not later than <u>{{\Illuminate\Support\Carbon::parse($rfq->deadline)->format('F d, Y')}}</u> </b>
                        </p>
                        <p class="no-margin">
                            Note: <i>Return this form duly accomplished in a sealed envelope.</i>
                            <br>
                            <span class="indent"></span><span class="indent"></span> <i>For canvassing only.</i>
                        </p>
                    </td>
                    <td style="width: 40%; vertical-align: top">
                        <p class="no-margin">Very truly yours,</p>
                        <br>
                        <br>
                        <p class="no-margin">
                            <u><b>NOLI T. TINGSON</b></u>
                        </p>
                        <p class="no-margin" style="font-size: 13px">Supply Officer IV</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%;table-layout:fixed;" class="desc-table">
            <thead>
                <colgroup>
                    <col span="3" style="width: 6%;">
                </colgroup>
                <tr class="tr-no-padding">
                    <th class="b-top b-bottom b-right" colspan="3">PR NO: {{$rfq->pr->prNo}}</th>
                    <th rowspan="2" class="b-top b-bottom text-center b-right">DESCRIPTION</th>
                    <th rowspan="2" class="b-top b-bottom text-center" style="width: 20%">Offer</th>
                </tr>
                <tr class="tr-no-padding">
                    <th class="b-right b-bottom text-center">No.</th>
                    <th class="b-right b-bottom text-center">Qty.</th>
                    <th class="b-right b-bottom text-center">Unit</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="b-right"></td>
                    <td class="b-right"></td>
                    <td class="b-right"></td>
                    <td class="b-right">
                        <p class="no-margin text-center text-strong" style="font-size: 11px"> <u> For: {{$rfq->pr->rc->desc}}</u></p>
                        <p class="no-margin text-center text-strong">{{$rfq->pr->requestedBy}}</p>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>





        <p class="no-margin">
            Please indicate:
        </p>
        <p class="no-margin" style="font-size: 13px">
            In case of purchase of goods: TIN: _______________ Place of Delivery:  ________________ Date of Delivery: ______________
        </p>
        <p class="no-margin" style="font-size: 13px">
            <span class="indent-lg"></span>Delivery Term ________________________ Payment Term: ___________________________________
        </p>
        <p class="no-margin b-bottom" style="font-size: 13px">
            In case of labor: TIN: _______________ Completion Period:  ____________________ Warranty Period: ___________________
        </p>

        <p class="no-margin text-strong">
            SUGAR REGULATORY ADMINISTRATION
        </p>

        <div>
            <p class="no-margin" style="text-align: justify; width: 100%; text-justify: inter-word">
                <span class="indent-lg"></span> This is to certify  that the price/s quoted  above is  the lowest  we can offer,taxes included and that no monetary consideration,gift or in hand whatever is involved in case part or whole items quoted will be awarded to us.<br>
                The prices  quoted is  good  until _____________________ and can be delivered within ______________________ working calendar days from receipt of the Purchase Order.
            </p>
        </div>
        <br><br>
        <table style="width: 100%; font-size: 14px">
            <thead>
                <tr class="tr-no-padding">
                    <th class="text-center bb-top">Canvassed by:
                    <th class="text-center bb-top">Date</th>
                    <th class="text-center bb-top">(Owner/Authorized Rep.)</th>
                    <th class="text-center bb-top">Telephone No.</th>
                    <th class="text-center bb-top">Date</th>
                </tr>
            </thead>
        </table>
    </div>


    <table style="width: 100%;table-layout:fixed; " class="desc-table b-bottom items-table" >
        <colgroup>
            <col span="3" style="width: 6%;">
        </colgroup>

        <tbody>
        @if(!empty($rfq->pr->items))
            @foreach($rfq->pr->items as $item)
                <tr class="item" id="{{$item->slug}}">
                    <td class="b-right text-center text-top">{{$loop->iteration}}</td>
                    <td class="b-right text-center text-top">{{number_format($item->qty)}}</td>
                    <td class="b-right text-center text-top">{{$item->article->uom}}</td>
                    <td class="b-right" >
                        <p class="text-strong no-margin">{{$item->article->article}}</p>
                        <span style="white-space: pre-line" id="ct">
                                {{$item->description}}
                            </span>
                    </td>
                    <td style="width: 20%" class="text-center text-top">
                        _________________
                    </td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
    <div class="qms-right" style="font-size: 12px;">
        <p class="no-margin">FM-AFD-PPS-015,Rev.01</p>
        <p class="no-margin">Effectivity Date: March 02, 2022</p>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

        $(document).ready(function () {
            let set = 625;
            if($("#items_table_{{$rand}}").height() < set){
                let rem = set - $("#items_table_{{$rand}}").height();
                $("#adjuster").css('height',rem)
                print();
            }
        })
        window.onafterprint = function(){
            window.close();
        }
        var template = $("#rfq_template").clone().wrap('<p/>').parent().html();
        var max = 291;
        var itemsArray = [];
        $(".item").each(function () {
            let t = $(this);
            let height = t.height();
            itemsArray.push({
                'html' : t.html(),
                'height' : height,
            });
        })
        var byPages = [];
        var counter = 0;
        var pageNo = 1;
        $.each(itemsArray, function (i,v) {
            if(counter === 0){
                //first item
                byPages[pageNo] = {
                    items : [],
                    totalHeight : v.height,
                }
                byPages[pageNo]['items'][i] = v;

            }else{
                //not first item
                if(byPages[pageNo]['totalHeight'] + v.height > max){
                    //should be next page
                    pageNo++;
                    byPages[pageNo] = {
                        items : [],
                        totalHeight : v.height,
                    }
                    byPages[pageNo]['items'][i] = v;
                }else{
                    //the same page
                    byPages[pageNo] = {
                        items : [],
                        totalHeight : v.height,
                    }
                    byPages[pageNo]['items'][i] = v;
                }
                byPages[pageNo]['totalHeight'] = byPages[pageNo]['totalHeight'] + v.height;
            }
            counter++;
        })

        console.log(byPages);

    </script>
@endsection