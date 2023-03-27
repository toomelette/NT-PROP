@extends('printables.print_layouts.print_layout_main')

@section('styles')
@endsection

@section('wrapper')
    <div class="printable">

        <div style=" width: 100%; margin-bottom: 10px; overflow: auto">
            <div style="width: 25%; float: left">
                <center>
                    <img src="{{ asset('images/sra.png') }}" style="width:100px;">
                </center>
            </div>
            <div style="width: 75%; float: right">
                <p class="no-margin text-strong">SUGAR REGULATORY ADMINISTRATION</p>
                <p class="no-margin">ADMIN AND FINANCE DEPARTMENT</p>
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
                <td height="40" style="border: 1px solid black;">Document Type</td>
                <td style="border: 1px solid black;">{{$cr->ref_book == "PR"? "PURCHASE REQUEST" : "JOB REQUEST"}}</td>
            </tr>
            <tr>
                <td height="40" style="border: 1px solid black;">Reference Number</td>
                <td style="border: 1px solid black;">{{$cr->ref_number}}</td>
            </tr>
        </table>

        <br>
        <table style="width: 100%; border: #0a0a0a;">
            <tr>
                <td height="40" style="border: 1px solid black;">Total Amount</td>
                <td style="border: 1px solid black;">Php {{$cr->total_amount}}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black;" height="40">Reason</td>
                <td style="border: 1px solid black;">{{strtoupper($cr->reason)}}</td>
            </tr>
            <tr class="">
                <td style="vertical-align: bottom; border: 1px solid black;" height="80">Requisitioner</td>
                <td style="vertical-align: bottom; border: 1px solid black;">{{strtoupper($cr->requisitioner)}}</td>
            </tr>
        </table>
        <br>

    </div>
    <table style="width: 100%; margin-top: 5px; font-size: 10px;">
        <tr>
            <td>
                {{\Carbon\Carbon::now()->format('Y')}}/PPBTMS | {{\Illuminate\Support\Facades\Auth::user()->username}} | {{\Illuminate\Support\Facades\Request::ip()}}
            </td>
            <td style="text-align: right">
                PLEASE PRINT AND SIGN 5 COPIES
            </td>
        </tr>
    </table>
    <br>
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