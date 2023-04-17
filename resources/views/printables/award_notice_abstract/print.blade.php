@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')
    <div class="printable">

        <div style="width: 100%;">
            <div class="" style="margin-bottom: 100px; padding-top: 10px;">
                <div>
                    <img src="{{ asset('images/sra.png') }}" style="width:100px; float: left">
                </div>
                <div style="float: left; text-align: left; margin-left: 15px">
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Republic of the Philippines</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Department of Agriculture</p>
                    <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
                    <p class="no-margin" style="font-size: 14px; margin-bottom: -4px">Araneta St., Singcang, Bacolod City, Tel No. 433-6891</p>
                    <p style="font-size: 18px;"><strong>AWARD NOTICE ABSTRACT</strong></p>
                </div>
                <span class="" style="float: right">
                    {{ QrCode::size(50)->generate(route("dashboard.awardNoticeAbstract.print",$ana->slug)) }}
                </span>
            </div>

            <table style="width: 100%; border: #0a0a0a;">
                <tr style="border: 1px solid black; width: 100%;">
                    <td style="border: 1px solid black; width: 10%;">Title:</td>
                    <td colspan="3"><strong>{{$ana->title}}</strong></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="border: 1px solid black; width: 100%;">
                    <td style="border: 1px solid black; width: 10%;">Category:</td>
                    <td colspan="3"><strong>{{$ana->category}}</strong></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="border: 1px solid black; width: 100%;">
                    <td style="border: 1px solid black; width: 16%;">Organization Name:</td>
                    <td style="width: 34%;"><strong>Sugar Reg. Admin. - Visayas</strong></td>
                    <td style="border: 1px solid black;">{{$ana->ref_book == "PR"?"PR Number" : "JR Number"}}</td>
                    <td style="text-align: right"><strong>{{$ana->ref_number}}</strong></td>
                </tr>
                <tr style="border: 1px solid black; width: 100%;">
                    <td style="border: 1px solid black; width: 16%;">Contact Name:</td>
                    <td style="width: 34%;"><strong>Brando D. Noroña</strong></td>
                    <td style="border: 1px solid black;">Approved Budget (Php):</td>
                    <td style="text-align: right"><strong>{{number_format($ana->approved_budget,2)}}</strong></td>
                </tr>
                <tr style="border-left: 1px solid black; border-right: 1px solid black; width: 100%;">
                    <td style="border-right: 1px solid black;">Award Notice Number:</td>
                    <td colspan="3" style="width: 34%;"><strong>{{$ana->award_notice_number}}</strong></td>
                </tr>
                <tr style="border-left: 1px solid black; border-right: 1px solid black; width: 100%;">
                    <td style="border-right: 1px solid black; width: 18%;">Title of Notice:</td>
                    <td colspan="3" style="width: 34%;"><strong>{{$ana->title_of_notice}}</strong></td>
                </tr>
                <tr style="border-left: 1px solid black; border-right: 1px solid black; width: 100%;">
                    <td style="border-right: 1px solid black;">Supplier Information:</td>
                    <td colspan="4" style="width: 34%;"></td>
                </tr>
                <tr style="border: 1px solid black; width: 100%;">
                    <td rowspan="2" style="border: 1px solid black; width: 16%;">Awardee:</td>
                    <td rowspan="2" style="width: 34%;"><strong>{{$ana->awardee}}</strong></td>
                    <td style="border: 1px solid black;">Award Date:</td>
                    <td style="text-align: right"><strong>{{ date('F j, Y', strtotime($ana->award_date))}}</strong></td>
                </tr>
                <tr style="border: 1px solid black; width: 100%;">
                    <td style="border: 1px solid black;">Registry Number:</td>
                    <td style="text-align: right"><strong>{{$ana->registry_number}}</strong></td>
                </tr>
                <tr style="border: 1px solid black; width: 100%;">
                    <td rowspan="2" style="border: 1px solid black; width: 16%;">Address:<br><br><br></td>
                    <td rowspan="2" style="width: 34%;"><strong>{{$ana->awardee_address}}</strong><br><br><br</td>
                    <td style="border: 1px solid black;">Contract Amount (Php):</td>
                    <td style="text-align: right"><strong>{{number_format($ana->contract_amount,2)}}</strong></td>
                </tr>
                <tr style="border: 1px solid black; width: 100%;">
                    <td colspan="4" rowspan="7" style="border: 1px solid black; vertical-align: top;">Remarks:
                    <br><br>
                        <span style="display: block; text-align: center;"><strong>{{$ana->remarks}}</strong></span>

                    </td>
                </tr>
                <tr style="border-left: 1px solid black; border-right: 1px solid black; width: 100%;">
                    <td style="width: 16%;">Contact Person:</td>
                    <td style="border-left: 1px solid black; width: 34%;"><strong>{{$ana->contact_person}}</strong></td>
                </tr>
                <tr style="border-left: 1px solid black; border-right: 1px solid black; width: 100%;">
                    <td style="width: 16%;">Address:<br><br><br><br></td>
                    <td style="border-left: 1px solid black; width: 34%;"><strong>{{$ana->contact_person_address}}</strong><br><br><br><br></td>
                </tr>
                <tr style="border-left: 1px solid black; border-right: 1px solid black; width: 100%;">
                    <td style="width: 16%;">Phone Number (1):</td>
                    <td style="border-left: 1px solid black; width: 34%;"><strong>{{$ana->phone_number_1}}</strong></td>
                </tr>
                <tr style="border-left: 1px solid black; border-right: 1px solid black; width: 100%;">
                    <td style="width: 16%;">Phone Number (2):</td>
                    <td style="border-left: 1px solid black; width: 34%;"><strong>{{$ana->phone_number_2}}</strong></td>
                </tr>
                <tr style="border-left: 1px solid black; border-right: 1px solid black; width: 100%;">
                    <td style="width: 16%;">Fax Number:</td>
                    <td style="border-left: 1px solid black; width: 34%;"><strong>{{$ana->fax_number}}</strong></td>
                </tr>
                <tr style="border-left: 1px solid black; border-right: 1px solid black; width: 100%;">
                    <td style="width: 16%;">Corporate Title:</td>
                    <td style="border-left: 1px solid black; width: 34%;"><strong>{{$ana->corporate_title}}</strong></td>
                </tr>
                <tr style="border: 1px solid black; width: 100%;">
                    <td style="border: 1px solid black; width: 16%;">Reason for Award:</td>
                    <td colspan="3" style="width: 34%;"><strong>{{$ana->reason_for_award}}</strong></td>
                </tr>
            </table>
        </div>
        <br>
        <br>
        <br>
        <br>
        <div class="col-12" style="float: right; margin-right: 80px">
            <p class="no-margin" style="font-size: 16px"><strong>DAVID JOHN THADDEUS P. ALBA</strong></p>
            <p class="no-margin">Administrator</p>
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