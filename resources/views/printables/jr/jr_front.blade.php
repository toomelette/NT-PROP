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
                <p class="no-margin text-strong" style="font-size: 25px">JOB REQUEST</p>
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
            <td class="b-bottom text-strong" style="width: 35%;"> {{$jr->rc->department ?? null}} </td>
            <td class="b-left text-strong">J.R. No.:</td>
            <td class="text-strong b-bottom b-right"> {{$jr->jrNo}} </td>

        </tr>
        <tr>
            <td style="width: 15%;" class="b-left text-strong" >Section/Unit:</td>
            <td style="width: 35%;" class="b-bottom text-strong"> {{$jr->rc->division ?? null}} {{(!empty($jr->rc->section)) ? ' - '.$jr->rc->section : null}} </td>
            <td class="b-left text-strong">Date:</td>
            <td class="b-bottom text-strong b-right">{{$jr->jrDate}}</td>
        </tr>
        <tr>
            <td colspan="2" class="b-left"></td>
            <td colspan="2" class="b-left b-right"></td>
        </tr>
    </table>
    <table style="width: 100%;" class="tbl-bordered-v" id="items_table_{{$rand}}">
        <thead>
        <tr>
            <th style="width: 10%;">Property No.</th>
            <th>Unit</th>
            <th>Item Description</th>
            <th>Quantity</th>
            <th>Nature of Work</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($jr->items))
            @foreach($jr->items as $item)
                <tr>
                    <td class="text-center">{{$item->propertyNo}}</td>
                    <td class="text-center">{{strtoupper($item->uom)}}</td>
                    <td>
                        <b>{{$item->item}}</b>
                        <br>
                        <i>{{$item->description}}</i>
                    </td>
                    <td class="text-center">{{number_format($item->qty)}} </td>
                    <td class="text-right">{{$item->natureOfWork}}</td>
                </tr>

            @endforeach
        @endif
        <tr>
            <td id="adjuster"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

        </tr>
        <tr>
            <td colspan="3" class="b-top">
                CHARGE TO: <b>{{$jr->papCode}} - {{\Illuminate\Support\Str::limit($jr->pap->pap_title ?? '-',80,'...')}}</b>
            </td>
            <td class="text-right b-top text-strong">ABC:</td>
            <td style="border-top: 1px solid black"  class="text-strong text-right"> {{number_format($jr->abc,2)}}</td>


        </tr>
        </tbody>

    </table>
    <table style="width: 100%; border-left: 1px solid black;border-right: 1px solid black" class="tbl-minimal">
        <tr>
            <td colspan="3" class="b-right">To be certified by General Services / MIS / Authorized Personnel in case of repair and replacement of parts.</td>
        </tr>
        <tr>
            <td colspan="3" class="text-strong text-center b-right" style="letter-spacing: 5px">CERTIFICATION</td>
        </tr>
        <tr>
            <td  style="width: 10%"></td>
            <td>
                I hereby certify that the repair and replacement of parts of the items described above are necessary in the interest of public service and that all defects and/or damages were caused due to wear and tear and not through fault, negligence or carelessness of the accountable/responsible officer/employee.
            </td>
            <td  class="b-right" style="width: 10%;">

            </td >
        </tr>
    </table>
    <table style="width: 100%;" class="tbl-no-pad">
        <tr>
            <td style="width: 75%" class="b-left"></td>
            <td class="text-strong text-center b-right b-bottom"><br>{{$jr->certifiedBy}}</td>
        </tr>
        <tr>
            <td class="b-left"></td>
            <td class="text-center b-right">(Signature over printed name)</td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid black;border-right: 1px solid black">
        <tr>
            <td class="b-top">
                Purpose: {{$jr->purpose}}
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
            <td class="text-strong text-center">{{$jr->requestedBy}}</td>
            <td class="text-strong text-center">{{$jr->approvedBy}}</td>
        </tr>
        <tr>
            <td>Designation:</td>
            <td class="text-center">{{$jr->requestedByDesignation}}</td>
            <td class="text-center">{{$jr->approvedByDesignation}}</td>
        </tr>
    </table>
@endsection

@section('scripts')
    <script type="text/javascript">

        $(document).ready(function () {
            let set = 530;
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