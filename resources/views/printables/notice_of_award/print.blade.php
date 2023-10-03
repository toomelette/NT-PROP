@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')
    <div class="printable">

        <div style="width: 100%; font-family: 'Cambria',Times New Roman">
            <div class="" style="margin-bottom: 100px; padding-top: 10px;">
                <div>
                    <img src="{{ asset('images/sra.png') }}" style="width:100px; float: left">
                </div>
                <div style="float: left; text-align: left; margin-left: 15px">
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Republic of the Philippines</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Department of Agriculture</p>
                    <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Araneta St., Singcang, Bacolod City, Tel No. 433-6891</p>
                    <p style="font-size: 18px;"><strong>NOTICE OF AWARD</strong></p>
                </div>
            </div>
            <div class="row" style="font-family: 'Cambria',Times New Roman;">
                <h4 style="float: left; margin-left: 40px;">{{$noa->document_no}}</h4>
            </div>
            <div class="row" style="font-family: 'Cambria',Times New Roman;">
                <h4 style="float: left; margin-left: 40px;">{{\Illuminate\Support\Carbon::parse($noa->date)->format('F d Y')}}</h4>
            </div>
            <div class="row" style="font-family: 'Cambria',Times New Roman;">
                <h3 class="text-strong">NOTICE OF AWARD</h3>
            </div>
            <br>
            <div class="row" style="float: left; text-align: left; font-family: 'Cambria', Times New Roman;">
                <h4 class="text-strong" style="margin-left: 40px;">{{$noa->supplier}}</h4>
                <h4 style="margin-left: 40px;">{{$noa->supplier_address}}</h4>
            </div>
            <div class="clearfix"></div>
            <div class="row" style="font-family: 'Cambria',Times New Roman;">
                <table style="margin-left: 30px;">
                    <tbody>
                        <tr>
                            <td rowspan="3" style="text-align: start"><h4 class="text-strong">ATTENTION:</h4></td>
                        </tr>
                        <tr>
                            <td>sdsad</td>
                        </tr>
                        <tr>
                            <td>sdsad</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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