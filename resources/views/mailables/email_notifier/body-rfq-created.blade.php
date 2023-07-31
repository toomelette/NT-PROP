@extends('mailables.email_notifier.mail')
@section('email-content')
    <p style="font-weight: normal; font-size: 14px">
        A <strong>Request for Quotation (RFQ)</strong> for your <strong>{{$transaction->ref_book}} No. {{$transaction->ref_no}}</strong> has been created.
    </p>
    <hr>
    <small style="color: #4289fc">{{\App\Swep\Helpers\Arrays::acronym($rfq->ref_book)}} Details:</small>
    <table class="details-table" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">

        <tr>
            <td class="td-style" style="width: 22%">
                <p style="margin: 0;">{{$rfq->ref_book}} No:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{$rfq->ref_no}}</p>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                <p style="margin: 0;">{{$rfq->ref_book}} Date:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{\App\Swep\Helpers\Helper::dateFormat($rfq->created_at,'M. d, Y')}}</p>
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
                <p style="">This is auto generated message. No Signature Required.</p>
                <p style="margin: 0"> <strong>NOLI T. TINGSON</strong> </p>
                <small>Supply Officer IV</small>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding-top: 20px">
                <a href="#?find=LINK">Click here for details.</a>
            </td>
        </tr>
    </table>
@endsection