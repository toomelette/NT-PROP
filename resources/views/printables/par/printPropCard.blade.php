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
                    {{ QrCode::size(50)->generate(route("dashboard.par.propCard","slug")) }}
                </span>

                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin-top: 105px">
                    <tbody>

                    <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                        <td rowspan="2" style="width: 30%; border-right: 1px solid black; text-align: center; font-size: 30px">
                            <strong>PROPERTY CARD</strong>
                        </td>
                    </tr>

                    </tbody>
                </table>

                <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">

                    <tbody>
                    <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">

                        <td  style="margin-top: 5px; width: 15%; border-right: 1px solid black; border-top: 1px solid black; position: relative;">
                            Property, Plant & Equipment:
                        </td>
                        <td style="border-top: 1px solid black; width: 55%; border-right: 1px solid black;" class="text-strong ">
                            {{$pc->article}}
                        </td>

                        <td style="margin-top: 5px; width: 40%; border-top: 1px solid black; position: relative;">
                            Property No:
                        </td>


                    </tr>

                        <td style="margin-top: 5px; width: 15%; border-right: 1px solid black; border-top: 1px solid black; position: relative;">
                            Description:
                        </td>
                        <td style="border-top: 1px solid black; width: 55%; border-right: 1px solid black;" class="text-strong ">
                            {{$pc->description}}
                        </td>

                        <td style="width: 40%; border-right: 1px solid black; vertical-align: center;" class="text-strong ">
                            {{$pc->property_no}}
                        </td>

                    </tr>
                    </tbody>

                </table>

                <table  id="items2" style="font-family: Cambria,Arial; width: 100%; text-align: center;  border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">

                    <thead>
                    <tr class="text-strong" style="width: 100%; ">
                        <td style="border: 1px solid black; width: 14%;">Date</td>
                        <td style="border: 1px solid black; width: 8%;">Ref No</td>
                        <td style="border: 1px solid black; width: 10%;">Receipt Qty</td>
                        <td style="border: 1px solid black; width: 8%;">Qty</td>
                        <td style="border: 1px solid black; width: 20%;">Issue/Transfer/Disposal</td>
                        <td style="border: 1px solid black; width: 8%;">Bal Qty</td>
                        <td style="border: 1px solid black; width: 12%;">Amount</td>
                        <td style="border: 1px solid black; width: 20%;">Remarks</td>
                    </tr>
                    </thead>

                    <tbody style="font-family: Cambria,Arial;">
                    @php
                        $total = 0;
                    @endphp

                    @foreach($pc->PropertyCardDetails as $item)
                        @php
                            $total += $item->amount;
                        @endphp
                        <tr style="width: 100%">
                            <td style="vertical-align: top; width: 14%;">{{$item->date}}</td>
                            <td style="vertical-align: top; width: 8%;">{{$item->ref_no}}</td>
                            <td style="vertical-align: top; width: 10%;">{{$item->receipt_qty}}</td>
                            <td style="vertical-align: top; width: 8%;">{{$item->qty}}</td>
                            <td style="vertical-align: top; width: 20%;">{{$item->purpose}}</td>
                            <td style="vertical-align: top; width: 8%;">{{$item->bal_qty}}</td>
                            <td style="vertical-align: top; width: 12%;">{{$item->amount}}</td>
                            <td style="vertical-align: top; width: 20%;">{{$item->remarks}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td id="adjuster"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>

                </table>

                <table style="font-family: Cambria,Arial; width: 100%; border-left: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; ">
                    <td style=" width: 75%; text-align: right; border-right: 1px solid black;" class="text-strong">TOTAL AMOUNT</td>
                    <td style=" vertical-align: top; text-align: right" class="text-strong">{{number_format($total,2)}}</td>
                </table>

                <div style="font-family: Cambria,Arial; display: flex; border-right: 1px solid black; border-bottom: 1px solid black">

                    <div style="flex: 1; text-align: center; border-left: 1px solid black">
                        <h5 class="" style="margin-left: 5px; margin-bottom: 10px; text-align: justify; float: left">Prepared By:</h5><br><br>

                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            <b><u>{{$pc->prepared_by}}</u></b>
                        </td><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            {{$pc->prepared_by_designation}}
                        </td><br>
                        <h5 class="" style="margin-left: 5px; float: left">Date:</h5>

                    </div>

                    <div style="flex: 1; text-align: center; border-left: 1px solid black">
                        <h5 class="" style="margin-left: 5px; margin-bottom: 10px; text-align: justify; float: left">Noted By:</h5><br><br>

                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            <b><u>{{$pc->noted_by}}</u></b>
                        </td><br>
                        <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                            {{$pc->noted_by_designation}}
                        </td><br>
                        <h5 class="" style="margin-left: 5px; float: left">Date:</h5>

                    </div>

                </div>

    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            let set = 550;
            if($("#items2").height() < set){
                let rem = set - $("#items2").height();
                $("#adjuster").css('height',rem)
                @if(!\Illuminate\Support\Facades\Request::has('noPrint'))
                print();
                // window.close();
                @endif
            }
        })
    </script>
@endsection