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
    <br>
    <br>
    <br>
    <table style="width: 100%; border:1px solid black; font-family: 'Cambria',Arial">
        <tr>
            <td style="width: 30%">
                <img src="{{ asset('images/sra.png') }}" style="width:100px; float: right">
            </td>
            <td class="text-center">
                    <p class="no-margin text-strong" style="font-size: 25px">PURCHASE REQUEST</p>
                    <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin text-strong">Araneta St., Singcang, Bacolod City</p>
                    <p class="no-margin text-strong">Tel No. 433-6891</p>
            </td>
            <td style="width: 30%">

            </td>
        </tr>
    </table>
    <table style="width: 100%; padding-bottom: 20px; font-family: 'Cambria',Arial" class="" >
        <tr>
            <td style="width: 15%;" class="b-left text-strong">Department:</td>
            <td class="b-bottom text-strong" style="width: 35%;"> {{$pr->rc->department}} </td>
            <td class="b-left text-strong">PR No.:</td>
            <td class="text-strong b-bottom"> {{$pr->ref_no}} </td>
            <td class="text-strong ">Date:</td>
            <td class="b-bottom text-strong b-right"> {{\Illuminate\Support\Carbon::parse($pr->date)->format('M. d, Y')}} </td>
        </tr>
        <tr>
            <td style="width: 15%;" class="b-left text-strong" >Section/Unit:</td>
            <td style="width: 35%;" class="b-bottom text-strong"> {{$pr->rc->division}} {{(!empty($pr->rc->section)) ? ' - '.$pr->rc->section : null}} </td>
            <td class="b-left text-strong">SAI No.:</td>
            <td class="b-bottom text-strong">{{$pr->sai}}</td>
            <td class="text-strong">Date:</td>
            <td class="b-right b-bottom text-strong">{{($pr->saiDate == ''?'':\Illuminate\Support\Carbon::parse($pr->sai_date)->format('M. d, Y'))}}</td>
        </tr>
        <tr>
            <td colspan="2" class="b-left"></td>
            <td colspan="4" class="b-left b-right"></td>
        </tr>
    </table>
    <table style="width: 100%; font-family: 'Cambria',Arial" class="tbl-bordered-v" id="items_table_{{$rand}}">
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
            @if(!empty($pr->transDetails))
                @foreach($pr->transDetails as $item)
                    <tr>
                        <td class="text-center" style="vertical-align: top">{{$item->stock_no}}</td>
                        <td class="text-center" style="vertical-align: top">{{strtoupper($item->unit ?? '')}}</td>
                        <td>
                            <b>{{$item->item ?? 'Article not found.'}}</b>
                            <br>
                            <i><span style="white-space: pre-line">{{$item->description}}</span></i>
                        </td>
                        <td class="text-center" style="vertical-align: top">{{$item->qty}} </td>
                        <td class="text-right" style="vertical-align: top">{{number_format($item->unit_cost,2)}}</td>
                        <td class="text-right" style="vertical-align: top">{{number_format($item->total_cost,2)}}</td>
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
                <td colspan="4" class="b-top">CHARGE TO: <b>{{$pr->pap_code}}</b> - {{\Illuminate\Support\Str::limit($pr->pap->pap_title ?? '-',80,'...')}}</td>
                <td style="border-top: 1px solid black"  class="text-strong text-right">TOTAL</td>
                <td style="border-top: 1px solid black" class="text-strong text-right">
                    {{number_format($pr->transDetails()->sum('total_cost'),2)}}
                </td>
            </tr>
        </tbody>

    </table>
    <table style="width: 100%; border-left: 1px solid black;border-right: 1px solid black; font-family: 'Cambria',Arial">
        <tr>
            <td>
                Purpose: {{$pr->purpose}}
            </td>
        </tr>
        @if($pr->cancelled_at != null)
            <tr>
                <td class="b-top " style="color: red">
                    CANCELLED: {{Carbon::parse($pr->cancelled_at)->format('M. d, Y | h:i A')}} --- {{$pr->cancellation_reason}}
                </td>
            </tr>
        @endif
    </table>
    <table class="tbl-bordered" style="width: 100%; font-family: 'Cambria',Arial">
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
            <td class="text-strong text-center" style="width: 45%">{{$pr->requested_by}}</td>
            <td class="text-strong text-center">{{$pr->approved_by}}</td>
        </tr>
        <tr>
            <td>Designation:</td>
            <td class="text-center">{{$pr->requested_by_designation}}</td>
            <td class="text-center">{{$pr->approved_by_designation}}</td>
        </tr>
    </table>
    <table style="width: 100%; font-family: Cambria; font-size: 10px" class="tbl-bordered">
        <tr>
            <td>BUDGET <br><br></td>
            <td class="text-top" style="width: 11%">PPBTMS</td>
            <td class="text-top" style="width: 11%">RBAC</td>
            <td class="text-top" style="width: 11%">PPBTMS</td>
            <td class="text-top" style="width: 11%">ACCOUNTING</td>
            <td class="text-top" style="width: 11%">BUGDET</td>
            <td class="text-top" style="width: 11%">MGR3</td>
            <td class="text-top" style="width: 11%">COA</td>
            <td class="text-top" style="width: 11%">PPBTMS</td>
        </tr>
    </table>
@endsection

@section('scripts')
    <script type="text/javascript">
            $(document).ready(function () {
                let set = 625;
                if($("#items_table_{{$rand}}").height() < set){
                    let rem = set - $("#items_table_{{$rand}}").height();
                    $("#adjuster").css('height',rem)
                    @if(!\Illuminate\Support\Facades\Request::has('noPrint'))
                    print();
                    // window.close();
                    @endif
                }
            })

        // window.onafterprint = function(){
        //     window.close();
        // }
    </script>
@endsection