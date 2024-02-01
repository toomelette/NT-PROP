@extends('mailables.email_notifier.mail')
@section('email-content')
    <p style="font-weight: normal; font-size: 14px">
        An <strong>Award Notice Abstract (ANA)</strong> for your <strong>{{$transaction->ref_book}} No. {{$transaction->ref_no}}</strong> has been created.
    </p>
    <hr>
    <small style="color: #4289fc">Award Notice Abstract (ANA) Details:</small>
    <table class="details-table" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">

        <tr>
            <td class="td-style" style="width: 22%">
                <p style="margin: 0;">ANA No:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{$ana->award_notice_number}}</p>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                <p style="margin: 0;">Award Date:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{\App\Swep\Helpers\Helper::dateFormat($ana->award_date,'M. d, Y')}}</p>
            </td>
        </tr>

    </table>

    <hr>
    <small style="color: #4289fc">{{\App\Swep\Helpers\Arrays::acronym($transaction->ref_book)}} Details:</small>
    <table id='details' class="details-table" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <tr>
            <td class="td-style" style="width: 22%">
                <p style="margin: 0;">{{$transaction->ref_book}} Date:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{\App\Swep\Helpers\Helper::dateFormat($transaction->date,'M. d, Y')}}</p>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                <p style="margin: 0;">Amount:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{number_format($transaction->abc,2)}}</p>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                <p style="margin: 0;">Purpose:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{$transaction->purpose}}</p>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                Requisitioner:
            </td>
            <td class="td-style bold">
                {{$transaction->requested_by}} - <i><small>{{$transaction->requested_by_designation}}</small></i>
            </td>
        </tr>

        <tr>
            <td class="td-style">
                <p style="margin: 0;">Date Received:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{\App\Swep\Helpers\Helper::dateFormat($transaction->received_at,'M. d, Y')}}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 20px">
                <p style="">This is auto generated message.</p>
                {{--<p style="margin: 0"> <strong>NOLI T. TINGSON</strong> </p>
                <small>Supply Officer IV</small>--}}
                <p style="margin: 0"> <strong>HAZEL ROSE MARIANO</strong> </p>
                <small>Supply Officer III</small>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding-top: 20px">
                <a href="http://ppbtms.sra.gov.ph/dashboard/my_{{strtolower($transaction->ref_book)}}?find={{$transaction->ref_no}}" target="_blank">Click here for details.</a>
            </td>
        </tr>
    </table>
@endsection