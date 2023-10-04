@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')
    <div class="printable">

        <div style="width: 96%; font-family: 'Cambria',Times New Roman">
            <div class="" style="margin-bottom: 100px; padding-top: 10px;">
                <div>
                    <img src="{{ asset('images/sra.png') }}" style="width:150px; float: left">
                </div>
                <div style="float: left; text-align: left; margin-left: 15px">
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Republic of the Philippines</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Department of Agriculture</p>
                    <p class="no-margin text-strong" style="font-size: 16px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Sugar Center Bldg., North Avenue, Diliman, Quezon City, Philippines 1101</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">TIN 000-784-336</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Website: http://www.sra.gov.ph</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Email Address: srahead@sra.gov.ph</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Tel No. (632) 8929-3633, (632) 3455-2135, (632) 3455-3376</p>
                </div>
            </div>
            <div class="clearfix"></div>
            <br>
            <div class="row" style="font-family: 'Cambria',Times New Roman;">
                <h4 style="float: left; margin-left: 40px;">{{$noa->document_no}}</h4>
            </div>
            <div class="row" style="font-family: 'Cambria',Times New Roman;">
                <h4 style="float: left; margin-left: 40px;">{{\Illuminate\Support\Carbon::parse($noa->date)->format('F d Y')}}</h4>
            </div>
            <div class="row" style="font-family: 'Cambria',Times New Roman;">
                <h3 class="text-strong">NOTICE TO PROCEED</h3>
            </div>
            <br>
            <div class="row" style="float: left; text-align: left; font-family: 'Cambria', Times New Roman;">
                <h4 class="text-strong no-margin" style="margin-left: 40px; text-transform: uppercase;">{{$noa->supplier}}</h4>
                <h4 class="no-margin" style="margin-left: 40px;">{{$noa->supplier_address}}</h4>
            </div>
            <div class="clearfix"></div>
            <div class="row" style="font-family: 'Cambria',Times New Roman; margin-top: 20px;">
                <table style="margin-left: 30px;">
                    <tbody>
                        <tr>
                            <td rowspan="3"><h4 class="text-strong " style="text-align: start;margin-top: -20px;">ATTENTION:</h4></td>
                        </tr>
                        <tr>
                            <td class="text-strong no-margin">
                                <p class="text-strong" style="margin-left: 10px; text-transform: uppercase; font-size: 18px">
                                    {{$noa->supplier_representative}}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td class="no-margin" style="font-size: 16px;">
                                <p style="margin-top: -15px; margin-left: 10px">
                                    {{$noa->supplier_representative_position}}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row" style="float: left; text-align: left; font-family: 'Cambria', Times New Roman; width: 100%">
                <h4 class="" style="margin-left: 40px; width: 100%; text-align: justify">{!! nl2br(e($noa->contents)) !!}</h4>
            </div>
            <br>
            <div class="row" style="float: left; text-align: left; font-family: 'Cambria', Times New Roman;">
                <h4 style="margin-left: 40px;">Very truly yours,</h4>
                <br><br>
                <h4 class="text-strong" style="margin-left: 40px; text-transform: uppercase;">{{$noa->approved_by}}</h4>
                <h4 style="margin-left: 40px;">{{$noa->approved_by_designation}}</h4>
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