@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')
    <style>
        table.main>tbody>tr>td {
            background-color: #982323;
            border-collapse: collapse;
            padding-top: 100px;
        }

    </style>
    <div style="font-family: Cambria">
        <table class="mains" style="width: 100%">
            <tbody>
            <tr>
                <td style="width: 9.6cm;background-color: #ca1d1d; margin-left: 0.2cm">
                    <table class="tbl-not-padded" style="margin-top: 0.2cm; margin-bottom: 0.2cm">
                        <tr>
                            <td style="width: 0.2cm"></td>
                            <td style="width: 9.2cm">
                                <table style="background-color: white; width: 100%;">
                                    <tr>
                                        <td>
                                            <table style="width: 100%">
                                                <tr>
                                                    <td style="width: 50px">
                                                        <img src="{{ asset('images/sra_old.png') }}" style="width:40px; float: left">
                                                    </td>
                                                    <td class="" style="font-size: 10px">
                                                        <p class="no-margin">Republic of the Philippines</p>
                                                        <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                                                        <p class="no-margin" style="font-size: 10px">{{\App\Swep\Helpers\Values::headerAddress()}}</p>
                                                        <p class="no-margin" style="font-size: 10px">{{\App\Swep\Helpers\Values::headerTelephone()}}</p>
                                                    </td>
                                                </tr>
                                            </table>
                                            <p class="no-margin text-strong text-center">GOVERNMENT PROPERTY</p>
                                            <p class="text-center" style="font-size: 10px">COA Circular No. 80-124, JANUARY 1980</p>
                                            <table style="width: 100%" class="tbl-not-padded">
                                                <tr>
                                                    <td>Accountable Person</td>
                                                    <td>:</td>
                                                    <td class="b-bottom">{{strtoupper($ics->requested_by)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Article/Item</td>
                                                    <td>:</td>
                                                    <td class="b-bottom">{{$ics->article}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Acquisition Date</td>
                                                    <td>:</td>
                                                    <td class="b-bottom">{{\App\Swep\Helpers\Helper::dateFormat($ics->dateacquired,'m/d/Y')}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Acquisition Cost</td>
                                                    <td>:</td>
                                                    <td class="b-bottom">{{number_format($ics->acquiredcost,2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>ICS No.</td>
                                                    <td>:</td>
                                                    <td class="b-bottom">{{$par->propertyno}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Condition</td>
                                                    <td>:</td>
                                                    <td class="b-bottom">{{$par->condition}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="b-bottom">
                                                        Validated:
                                                        <br><br>
                                                    </td>
                                                    <td></td>
                                                    <td class="b-bottom"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">Date</td>
                                                    <td></td>
                                                    <td class="text-center">Property Custodian</td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 0.2cm"></td>
                        </tr>
                    </table>

                </td>
                <td style="">

                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
           print();
            // close();
        })
    </script>
@endsection