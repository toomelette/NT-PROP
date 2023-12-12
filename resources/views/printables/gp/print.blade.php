@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    $rand = \Illuminate\Support\Str::random();

@endphp

@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')

    <div class="printable">
        <div style="width: 100%;">
            <div class="" style="margin-bottom: 100px; padding-top: 20px;">
                <div>
                    <img src="{{ asset('images/sra.png') }}" style="width:100px; float: left">
                </div>
                <div style="float: left; font-family: cambria; text-align: left; margin-left: 15px; margin-top: 10px">
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Republic of the Philippines</p>
                    <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Araneta St., Singcang, Bacolod City, Tel No. 433-6891</p>
                    <p class="no-margin text-strong" style="font-size: 14px;">
                        PROPERTY/PROCUREMENT/BUILDING & TRANSPORT MAINTENANCE SECTION
                    </p>
                </div>

                <span class="" style="float: right">
                    {{ QrCode::size(50)->generate(route("dashboard.gp.index",$gp->slug)) }}
                </span>

                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin-top: 105px">
                    <tbody>

                    <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                        <td rowspan="2" style="width: 49%; border-right: 1px solid black; font-size: 35px">
                            <strong>GATE PASS</strong>
                        </td>
                        <td style="margin-top: 5px; width: 14%; border-right: 1px solid black; border-top: 1px solid black; position: relative;">
                            Gate Pass No.:
                        </td>
                        <td style="border-top: 1px solid black;" class="text-strong ">
                            {{$gp->gp_number}}
                        </td>


                    </tr>
                        <td style="margin-top: 5px; width: 14%; border-right: 1px solid black; border-top: 1px solid black; position: relative;">
                            Date:
                        </td>
                        <td style="border-top: 1px solid black; " class="text-strong ">
                            {{$gp->date}}
                        </td>


                    </tr>
                    </tbody>
                </table>

                <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >


                    <tr style="border: 1px solid black; width: 100%;">

                        <td rowspan="2" style="border-right: 1px solid black; width: 13.4%; vertical-align: center;">
                            Bearer:
                        </td>
                        <td rowspan="2" class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 34.7%">
                            {{$gp->bearer}}
                        </td>

                        <td style="border-right: 1px solid black; width: 13.75%; vertical-align: center;">
                            Originated From:
                        </td>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 36.3%">
                            {{$gp->originated_from}}
                        </td>
                    </tr>

                </table>

                <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a; " >

                    <tr style=" border-left: 1px solid black; width: 100%;">

                        <td style="border-right: 1px solid black; width: 13.4%; text-align: center; font-size: 20px">
                            <strong>ITEMS</strong>
                        </td>

                    </tr>

                </table>

                <table  id="items_table_{{$rand}}" style="font-family: Cambria,Arial; width: 100%; text-align: center;  border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">

                    <thead>
                    <tr class="text-strong" style="width: 100%; ">
                        <td style="border: 1px solid black; width: 30%;">Qty</td>
                        <td style="border: 1px solid black; width: 70%;">Item/Details</td>
                    </tr>
                    </thead>

                    <tbody style="font-family: Cambria,Arial;">

                    @foreach($gp->GatePassDetails as $item)
                        <tr style="width: 100%">
                            <td style="vertical-align: top; width: 20%;">{{$item->qty}}</td>
                            <td class="" style="vertical-align: top; width: 41%; text-align: left;">
                                <b style="font-size: 11px; font-weight: normal  ">{{$item->item}}</b><br>
                                @if($item->description)
                                    <ul style="">
                                        @foreach(explode("\n", $item->description) as $line)
                                            <li><i>{{ $line }}</i></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>

                        </tr>

                    @endforeach
                    <tr>
                        <td id="adjuster"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>

                </table>

                <div style="font-family: Cambria,Arial; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    <h5 class="" style="margin-left: 5px; margin-bottom: 10px; text-align: left; float: left">
                       NOTE: Above named property is the accountability of the borrower unless the Property and Procurement Unit is duly informed of its return.
                    </h5><br><br>
                    <h5 class="" style="padding-left: 5px; margin-left: 5px; margin: 0; float: left">
                        JUSTIFICATION: Supplies intended for delivery to <span class="text-strong"; style="text-decoration: underline">{{$gp->originated_from}}</span> requisitioners.
                    </h5><br><br>
                </div>

                <div style="font-family: Cambria,Arial; display: flex; border-right: 1px solid black; border-bottom: 1px solid black">


                    <div style="flex: 1; text-align: center; border-left: 1px solid black">
                        <h5 class="" style="margin-left: 5px; margin-bottom: 10px; text-align: justify; float: left">Bearer:</h5><br><br><br>

                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            <b><u>{{$gp->bearer}}</u></b>
                        </td><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            Received items as stated above:
                        </td><br>
                        <h5 class="" style="margin-left: 5px; float: left">Date:</h5>

                    </div>

                    <div style="flex: 1; text-align: center; border-left: 1px solid black">
                        <h5 class="" style="margin-left: 5px; margin-bottom: 10px; text-align: justify; float: left">Security Guard</h5><br><br><br>

                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            <b><u>{{$gp->guard_on_duty}}</u></b>
                        </td><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            Guard on Duty
                        </td><br>
                        <h5 class="" style="margin-left: 5px; float: left">Date:</h5>

                    </div>


                    <div style="flex: 1; text-align: center; border-left: 1px solid black">
                        <h5 class="" style="margin-left: 5px; margin-bottom: 10px; text-align: justify; float: left">Approved By:</h5><br><br><br>

                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            <b><u>{{$gp->approved_by}}</u></b>
                        </td><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            {{$gp->approved_by_designation}}
                        </td><br>
                        <h5 class="" style="margin-left: 5px; float: left">Date:</h5>

                    </div>


                </div>



    @endsection

    @section('scripts')


     <script type="text/javascript">
         $(document).ready(function () {
             let set = 500;
             if($("#items_table_{{$rand}}").height() < set){
                 let rem = set - $("#items_table_{{$rand}}").height();
                 $("#adjuster").css('height',rem)
                 @if(!\Illuminate\Support\Facades\Request::has('noPrint'))
                 print();
                 // window.close();
                 @endif
             }
         })
     </script>
    @endsection