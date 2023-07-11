@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')
    <div class="printable">

        <div style=" width: 100%; margin-bottom: 10px; overflow: auto">
            <div style="width: 15%; float: left">
                <center>
                    <img src="{{ asset('images/sra.png') }}" style="width:100px;">
                </center>
            </div>
            <div class="text-left" style="width: 85%; padding-top: 10px;">
                <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                <p class="no-margin">ADMINISTRATION AND FINANCE DEPARTMENT</p>
                <p class="no-margin text-strong">PROPERTY/PROCUREMENT/BUILDING AND TRANSPORT MAINTENANCE SECTION</p>
            </div>
        </div>

        <div style="width: 100%; overflow: auto">
            <div style="width: 49%; float: left">
                <p class="no-margin" style="font-weight: bold; font-size: 20px; padding-top: 8px">PPBTMS REQUEST FOR CANCELLATION</p>
            </div>
            <div style="width: 49%; float: right">
                <table style="width: 100%; border: 1px solid black;" >
                    <tr>

                        <td style="border: 1px solid black;">Request No.</td>
                        <td style="border: 1px solid black;"><b>{{$cr->request_no}}</b></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black;">
                            Date/Time
                        </td>
                        <td style="border: 1px solid black;">
                            {{\Carbon\Carbon::parse($cr->created_at)->format('M d, Y | h:i A')}}
                        </td>
                    </tr>
                </table>
            </div>

        </div>
        <br>
        <table style="width: 100%; border: #0a0a0a;">
            <tr>
                <td height="40" style="border: 1px solid black; width: 30%">Document Type</td>
                <td colspan="3" style="border: 1px solid black;">{{$cr->ref_book == "PR"? "PURCHASE REQUEST" : "JOB REQUEST"}}</td>
            </tr>
            <tr>
                <td height="40" style="border: 1px solid black; width: 30%">Reference Number</td>
                <td colspan="3" style="border: 1px solid black;">{{$cr->ref_number}}</td>
            </tr>

            <tr>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td height="40" style="border: 1px solid black; width: 30%">Total Amount</td>
                <td colspan="3" style="border: 1px solid black;">Php {{number_format($cr->total_amount,2)}}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; width: 30%" height="40">Reason</td>
                <td colspan="3" style="border: 1px solid black;">{{strtoupper($cr->reason)}}</td>
            </tr>
            <tr class="">
                <td style="vertical-align: bottom; border: 1px solid black; width: 30%" height="80">Signature of Requisitioner</td>
                <td style="vertical-align: bottom; border: 1px solid black; width: 30%">{{strtoupper($cr->requisitioner)}}</td>
                <td style="vertical-align: bottom; border: 1px solid black; width: 15%">Approved:</td>
                <td style="vertical-align: bottom; border: 1px solid black; width: 25%">
                    <span style="font-size: 14px"><b>NOLI T. TINGSON</b></span><br>
                    SUPPLY OFFICER IV
                </td>
            </tr>
        </table>
        <br>

    </div>
    <table style="width: 100%; font-size: 10px;">
        <tr>
            <td>
                {{\Carbon\Carbon::now()->format('Y')}}/PPBTMS | {{\Illuminate\Support\Facades\Auth::user()->username}} | {{\Illuminate\Support\Facades\Request::ip()}}
            </td>
            <td style="text-align: right">
                PLEASE PRINT AND SIGN 4 COPIES
            </td>
        </tr>
    </table>
    <hr style="border: 1px dashed grey" class="no-margin">
    <p class="no-margin" style="font-size: 8px"><i class="fa fa-scissors"></i> CUT HERE</p>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            print();
            // close();
        })
    </script>
@endsection