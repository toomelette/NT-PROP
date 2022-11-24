@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('printables.print_layouts.print_layout_main')

@section('wrapper')
    <table style="width: 100%; border:1px solid black">
        <tr>
            <td style="width: 30%">
                <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
            </td>
            <td class="text-center">
                    <p class="no-margin text-strong" style="font-size: 25px">PURCHASE REQUEST</p>
                    <p class="no-margin">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin">Araneta St., Singcang, Bacolod City</p>
                    <p class="no-margin">Telefax No. (034) 434-5123</p>
            </td>
            <td style="width: 30%">

            </td>
        </tr>
    </table>
    <table style="width: 100%; padding-bottom: 20px" class="" >
        <tr>
            <td style="width: 15%;" class="b-left text-strong">Department:</td>
            <td class="b-bottom text-strong" style="width: 35%;"> {{$pr->rc->department}} </td>
            <td class="b-left text-strong">PR No.:</td>
            <td class="text-strong b-bottom"> {{$pr->prNo}} </td>
            <td class="text-strong ">Date:</td>
            <td class="b-bottom text-strong b-right"> {{\Illuminate\Support\Carbon::parse($pr->prDate)->format('F d, Y')}} </td>
        </tr>
        <tr>
            <td style="width: 15%;" class="b-left text-strong" >Section/Unit:</td>
            <td style="width: 35%;" class="b-bottom text-strong"> {{$pr->rc->division}} {{(!empty($pr->rc->section)) ? ' - '.$pr->rc->section : null}} </td>
            <td class="b-left text-strong">SAI No.:</td>
            <td class="b-bottom text-strong">{{$pr->sai}}</td>
            <td class="text-strong">Date:</td>
            <td class="b-right b-bottom text-strong">{{\Illuminate\Support\Carbon::parse($pr->saiDate)->format('F d, Y')}}</td>
        </tr>
        <tr>
            <td colspan="2" class="b-left"></td>
            <td colspan="4" class="b-left b-right"></td>
        </tr>
    </table>
    <table style="width: 100%;" class="tbl-bordered-v" id="items_table_{{$rand}}">
        <thead>
            <tr>
                <th style="width: 10%;">Stock No.</th>
                <th>Unit</th>
                <th>Item Description</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($pr->items))
                @foreach($pr->items as $item)
                    <tr>
                        <td class="text-center">{{$item->stockNo}}</td>
                        <td class="text-center">{{$item->article->uom ?? ''}}</td>
                        <td>
                            <b>{{$item->article->article ?? 'Article not found.'}}</b>
                            <br>
                            <i>{{$item->description}}</i>
                        </td>
                        <td class="text-center">{{number_format($item->qty)}} </td>
                        <td class="text-right">{{number_format($item->unitCost,2)}}</td>
                        <td class="text-right">{{number_format($item->totalCost,2)}}</td>
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
            <tr>
                <td colspan="4" class="b-top">CHARGE TO: <b>{{$pr->papCode}} - {{\Illuminate\Support\Str::limit($pr->pap->pap_title ?? '-',80,'...')}}</b></td>
                <td style="border-top: 1px solid black"  class="text-strong text-right">TOTAL</td>
                <td style="border-top: 1px solid black" class="text-strong text-right">
                    {{number_format($pr->items()->sum('totalCost'),2)}}
                </td>
            </tr>
        </tbody>

    </table>
    <table style="width: 100%; border-left: 1px solid black;border-right: 1px solid black">
        <tr>
            <td>
                Purpose: {{$pr->purpose}}
            </td>
        </tr>
    </table>
    <table class="tbl-bordered" style="width: 100%">
        <tr>
            <td rowspan="2">Signature</td>
            <td>Requested by:</td>
            <td>Approved by:</td>
        </tr>
        <tr>
            <td><br></td>
            <td></td>
        </tr>
        <tr>
            <td>Printed Name:</td>
            <td class="text-strong text-center">{{$pr->requestedBy}}</td>
            <td class="text-strong text-center">{{$pr->approvedBy}}</td>
        </tr>
        <tr>
            <td>Designation:</td>
            <td class="text-center">{{$pr->requestedByDesignation}}</td>
            <td class="text-center">{{$pr->approvedByDesignation}}</td>
        </tr>
    </table>
@endsection

@section('scripts')
    <script type="text/javascript">

        $(document).ready(function () {
            let set = 650;
            if($("#items_table_{{$rand}}").height() < set){
                let rem = set - $("#items_table_{{$rand}}").height();
                $("#adjuster").css('height',rem)
                print();
            }
        })
        window.onafterprint = function(){
            window.close();
        }
    </script>
@endsection