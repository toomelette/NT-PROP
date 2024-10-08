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
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">{{\App\Swep\Helpers\Values::headerAddress()}}, {{\App\Swep\Helpers\Values::headerTelephone()}}</p>
                    <p class="no-margin text-strong" style="font-size: 14px;">
                        PROPERTY/PROCUREMENT/BUILDING & TRANSPORT MAINTENANCE SECTION
                    </p>
                </div>

                <span class="" style="float: right">
                    {{ QrCode::size(50)->generate(route("dashboard.wmr.index",$wmr->slug)) }}
                </span>

        <table style="font-family: Cambria,Arial; width: 100%; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin-top: 105px">
            <tbody>

            <tr style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                <td rowspan="2" style="width: 49%; border-right: 1px solid black; font-size: 25px">
                    <strong>WASTE MATERIALS REPORT</strong>
                </td>

                <td style="margin-top: 5px; width: 13%; border-right: 1px solid black; position: relative;">
                    Date:
                </td>
                <td style="border-right: 1px solid black; width: 15%;" class="text-strong ">
                  {{$wmr->date}}
                </td>
                <td style="margin-top: 5px;  border-right: 1px solid black; border-bottom: 1px solid black; position: relative;">
                    WMR No.: <b>{{$wmr->wm_number}}</b>
                </td>

            </tr>

            <td style="margin-top: 5px; width: 15%; border-right: 1px solid black; border-top: 1px solid black; position: relative;">
                Taken From:
            </td>
            <td colspan="2" style="border-top: 1px solid black;" class="text-strong ">
                {{$wmr->taken_from}}
            </td>


            </tbody>
        </table>

        <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >


            <tr style="border: 1px solid black; width: 100%;">

                <td rowspan="2" style="border-right: 1px solid black; width: 13.4%; vertical-align: center;">
                    Place of Storage:
                </td>
                <td rowspan="2" class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 34.7%">
                    {{$wmr->storage}}
                </td>

                <td style="border-right: 1px solid black; width: 14.75%; vertical-align: center;">
                    Taken Through:
                </td>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: center;  width: 35.3%">
                    {{$wmr->taken_through}}
                </td>
            </tr>

        </table>

        <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >

            <tr style=" border-left: 1px solid black; width: 100%;">

                <td style="border-right: 1px solid black; width: 13.4%; text-align: center; font-size: 20px">
                    <strong>ITEMS FOR DISPOSAL</strong>
                </td>

            </tr>

        </table>

        <table  id="items_table_{{$rand}}" style="font-family: Cambria,Arial; width: 100%; text-align: center;  border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">

            <thead>
            <tr class="text-strong" style="width: 100%">
                <td style="border: 1px solid black; width: 10%;">Stock No</td>
                <td style="border: 1px solid black; width: 10%;">Unit</td>
                <td style="border: 1px solid black; width: 10%;">Qty</td>
                <td style="border: 1px solid black; width: 30%;">Item/Description</td>
                <td style="border: 1px solid black; width: 12%;">O.R. No.</td>
                <td style="border: 1px solid black; width: 10%;">Amount</td>
            </tr>
            </thead>

            <tbody style="font-family: Cambria,Arial;">

            @foreach($wmr->wasteDetails as $item)
                <tr style="width: 100%">
                    <td style="vertical-align: top; width: 10%;">{{$item->stock_no}}</td>
                    <td style="vertical-align: top; width: 10%;">{{$item->unit}}</td>
                    <td style="vertical-align: top; width: 10%;">{{$item->qty}}</td>
                    <td class="" style="vertical-align: top; width: 30%; text-align: left;">
                        <b style="font-size: 13px; font-weight: normal;">{{$item->item}}</b><br>
                        <span style="white-space: pre-line; font-size: 11px; font-style: italic" >
                                    {{$item->description}}
                        </span>
                    </td>
                    <td style="vertical-align: top; width: 12%;">{{$item->or_no}}</td>
                    <td style="vertical-align: top; width: 10%;">{{number_format($item->amount, 2, '.', ',')}}</td>
                </tr>

            @endforeach
            <tr>
                <td id="adjuster"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>

        </table>

        <div style="font-family: Cambria,Arial; display: flex; border-right: 1px solid black; border-bottom: 1px solid black">

            <div style="font-family: Cambria,Arial; flex: 1; text-align: center; border-left: 1px solid black">
                <h5 class="" style="margin-left: 5px; margin-bottom: 10px; text-align: justify; float: left">Certified Correct:</h5><br><br><br>

                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    <b><u>{{$wmr->certified_by}}</u></b>
                </td><br>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    {{$wmr->certified_by_designation}}
                </td><br>
                <h5 class="" style="margin-left: 5px; float: left">Date:</h5>

            </div>

            <div style="font-family: Cambria,Arial; flex: 1; text-align: center; border-left: 1px solid black">
                <h5 class="" style="margin-left: 5px; margin-bottom: 10px; text-align: justify; float: left">Disposal Approved:</h5><br><br><br>

                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    <b><u>{{$wmr->approved_by}}</u></b>
                </td><br>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    {{$wmr->approved_by_designation}}
                </td><br>
                <h5 class="" style="margin-left: 5px; float: left">Date:</h5>

            </div>

        </div>

        <table style="font-family: Cambria,Arial;  width: 100%; border: #0a0a0a;" >

            <tr style=" border-left: 1px solid black; width: 100%;">

                <td style="border-right: 1px solid black; width: 13.4%; text-align: center; font-size: 20px">
                    <strong>CERTIFICATE OF INSPECTION</strong>
                </td>

            </tr>

        </table>

        <div style="font-family: Cambria,Arial; border: 1px solid black">
            <h5 class="" style="margin-left: 5px; margin-bottom: 10px; float: left">
                I hereby certify that this property was disposed of as follow:
            </h5><br><br>
            <h5 class="" style="padding-left: 20px; margin-left: 20px; margin: 0; float: left">
                Item __________________________ Destroyed
            </h5><br>
            <h5 class="" style="padding-left: 20px; margin-left: 20px; margin: 0; float: left">
                Item __________________________ Sold at private sale
            </h5><br>
            <h5 class="" style="padding-left: 20px; margin-left: 20px; margin: 0; float: left">
                Item __________________________ Sold at public auction
            </h5><br>
            <h5 class="" style="padding-left: 20px; margin-left: 20px; margin: 0; float: left">
                Item __________________________ Transferred without cost to __________________
            </h5><br><br>
        </div>

        <div style="font-family: Cambria,Arial; display: flex; border-right: 1px solid black; border-bottom: 1px solid black">

            <div style="flex: 1; text-align: center; border-left: 1px solid black">
                <h5 class="" style="margin-left: 5px; margin-bottom: 10px; text-align: justify; float: left">Property Inspector:</h5><br><br><br>

                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    <b><u>{{$wmr->inspected_by}}</u></b>
                </td><br>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    {{$wmr->inspected_by_designation}}
                </td><br>
                <h5 class="" style="margin-left: 5px; float: left">Date:</h5>

            </div>

            <div style="font-family: Cambria,Arial; flex: 1; text-align: center; border-left: 1px solid black">
                <h5 class="" style="margin-left: 5px; margin-bottom: 10px; text-align: justify; float: left">Witness to disposition/returned by:</h5><br><br><br>

                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    <b><u>{{$wmr->witnessed_by}}</u></b>
                </td><br>
                <td class="text-strong" style="border-right: 1px solid black; vertical-align: top; ">
                    {{$wmr->witnessed_by_designation}}
                </td><br>
                <h5 class="" style="margin-left: 5px; float: left">Date:</h5>

            </div>

        </div>

                <div class="qms-right" style="font-size: 12px">
                    <p class="no-margin">FM-AFD-PPS-003,Rev.00</p>
                    <p class="no-margin">Effectivity Date: March 12, 2015</p>
                </div>



    @endsection

    @section('scripts')


     <script type="text/javascript">
         $(document).ready(function () {
             let set = 240;
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