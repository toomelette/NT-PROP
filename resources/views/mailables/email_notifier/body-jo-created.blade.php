@extends('mailables.email_notifier.mail')
@section('email-content')
    <p style="font-weight: normal; font-size: 14px">
        A <strong>Job Order (JO)</strong> for your <strong>{{$prOrJr->ref_book}} No. {{$prOrJr->ref_no}}</strong> has been created.
    </p>
    <hr>
    <small style="color: #4289fc">Job Order (JO) Details:</small>
    <table class="details-table" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">

        <tr>
            <td class="td-style" style="width: 22%">
                <p style="margin: 0;">PO No:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{$jo->ref_no}}</p>
            </td>
        </tr>
        {{--        <tr>--}}
        {{--            <td class="td-style">--}}
        {{--                <p style="margin: 0;">Award Date:</p>--}}
        {{--            </td>--}}
        {{--            <td class="td-style">--}}
        {{--                <p style="margin: 0;" class="bold">{{\App\Swep\Helpers\Helper::dateFormat($ana->award_date,'M. d, Y')}}</p>--}}
        {{--            </td>--}}
        {{--        </tr>--}}

    </table>

    <hr>
    <small style="color: #4289fc">{{\App\Swep\Helpers\Arrays::acronym($prOrJr->ref_book)}} Details:</small>
    <table id='details' class="details-table" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <tr>
            <td class="td-style" style="width: 22%">
                <p style="margin: 0;">{{$prOrJr->ref_book}} Date:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{\App\Swep\Helpers\Helper::dateFormat($prOrJr->date,'M. d, Y')}}</p>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                <p style="margin: 0;">Amount:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{number_format($prOrJr->abc,2)}}</p>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                <p style="margin: 0;">Purpose:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{$prOrJr->purpose}}</p>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                Requisitioner:
            </td>
            <td class="td-style bold">
                {{$prOrJr->requested_by}} - <i><small>{{$prOrJr->requested_by_designation}}</small></i>
            </td>
        </tr>

        <tr>
            <td class="td-style">
                <p style="margin: 0;">Date Received:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{\App\Swep\Helpers\Helper::dateFormat($prOrJr->received_at,'M. d, Y')}}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 20px">
                <p style="">This is auto generated message.</p>
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