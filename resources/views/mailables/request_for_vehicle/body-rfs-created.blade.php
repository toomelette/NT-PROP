@extends('mailables.email_notifier.mail')
@section('email-content')
    <p style="font-weight: normal; font-size: 14px">
        <strong>Request for Shuttle service successfully created. Request No: {{$r->request_no}}</strong></p>
    <hr>
    <small style="color: #4289fc">Request Details:</small>
    <table id='details' border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <tr>
            <td class="td-style">
                <p style="margin: 0;">Date|Time From:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{\App\Swep\Helpers\Helper::dateFormat($r->from,'F. d, Y | h:i A')}}</p>
            </td>
        </tr>
        @if(!empty($r->to))
            <tr>
                <td class="td-style">
                    <p style="margin: 0;">Date|Time To:</p>
                </td>
                <td class="td-style">
                    <p style="margin: 0;" class="bold">{{\App\Swep\Helpers\Helper::dateFormat($r->to,'F. d, Y | h:i A')}}</p>
                </td>
            </tr>
        @endif
        <tr>
            <td class="td-style">
                <p style="margin: 0;">Destination:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{$r->destination}}</p>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                <p style="margin: 0;">Purpose:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{$r->purpose}}</p>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                <p style="margin: 0;">Authorized Passenger(s):</p>
            </td>
            <td class="td-style">
                <ul style="padding-left: 0px">
                   @forelse($r->passengers as $passenger)
                    <li>{{$passenger->name}}</li>
                    @empty
                   @endforelse
                </ul>
            </td>
        </tr>


        <tr>
            <td class="td-style">
                Requisitioner:
            </td>
            <td class="td-style bold">
                {{$r->requested_by}} - <i><small>{{$r->requested_by_position}}</small></i>
            </td>
        </tr>
        <tr>
            <td class="td-style">
                <p style="margin: 0;">Dept/Div/Sec:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{$r->responsibilityCenter->desc ?? '-'}}</p>
            </td>
        </tr>

        <tr>
            <td class="td-style">
                <p style="margin: 0;">Created at:</p>
            </td>
            <td class="td-style">
                <p style="margin: 0;" class="bold">{{$r->created_at}}</p>
            </td>
        </tr>
{{--        <tr>--}}
{{--            <td colspan="2" style="padding-top: 20px">--}}
{{--                <p style="">This is auto generated message.</p>--}}
{{--                <p style="margin: 0"> <strong>NOLI T. TINGSON</strong> </p>--}}
{{--                <small>Supply Officer IV</small>--}}
{{--            </td>--}}
{{--        </tr>--}}

{{--        <tr>--}}
{{--            <td colspan="2" style="padding-top: 20px">--}}
{{--                <a style="display: none" href="#?find=LINK">Click here for details.</a>--}}
{{--            </td>--}}
{{--        </tr>--}}
    </table>
@endsection