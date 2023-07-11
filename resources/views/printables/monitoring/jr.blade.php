@php
   $rand = \Illuminate\Support\Str::random();
@endphp
@extends('printables.print_layouts.print_layout_main')

@section('wrapper')
    <div class="" style="margin-bottom: 100px; padding-top: 10px; font-family: Cambria,Arial;">
        <div>
            <img src="{{ asset('images/sra.png') }}" style="width:100px; float: left">
        </div>
        <div style="float: left; text-align: left; margin-left: 15px">
            <p class="no-margin" style="font-size: 14px; margin-bottom: -4px; margin-top: 8px">Republic of the Philippines</p>
            <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">SUGAR REGULATORY ADMINISTRATION</p>
            <p class="no-margin" style="font-size: 14px;">Araneta St., Singcang, Bacolod City, Tel No. 433-6891</p>
            <p class="no-margin text-strong" style="font-size: 14px; margin-bottom: -4px">
                PROPERTY/PROCUREMENT/BUILDING & TRANSPORT MAINTENANCE SECTION
            </p>
        </div>
    </div>
    <h3 style="font-family: Cambria" class="text-left">JR MONITORING</h3>
    <h4 style="font-family: Cambria" class="text-strong text-left">
        {{$resp_center->desc ?? null}}
        @if(($request->has('year') && $request->year != '') && $request->year != \Illuminate\Support\Carbon::now()->format('Y'))
        , {{$request->year}}
        @endif
    </h4>
    <table style="width: 100%; font-family: Cambria" class="tbl tbl-bordered tbl-minimal">
        <thead>
        <tr>
            <th class="text-center" style="width: 30px"></th>
            <th class="text-center">JR No.</th>
            <th class="text-center">PAP Code</th>
            <th class="text-center">Amount</th>
            <th class="text-center">Date Created</th>
            <th class="text-center">Date Received</th>
            <th class="text-center">RFQ Date</th>
            <th class="text-center">AQ Date</th>
            <th class="text-center">RBAC Reso Date</th>
            <th class="text-center">NOA Date</th>
            <th class="text-center">PO/JO Date</th>
        </tr>
        </thead>
        <tbody>
            @if(!empty($transactions))
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="text-center">{{$transaction->ref_no}}</td>
                        <td class="text-center">{{$transaction->pap_code}}</td>
                        <td class="text-right">{{number_format($transaction->abc,2)}}</td>
                        <td class="text-center">{{\App\Swep\Helpers\Helper::dateFormat($transaction->date,'M. d, Y')}}</td>
                        <td class="text-center">{{\App\Swep\Helpers\Helper::dateFormat($transaction->received_at,'M. d, Y')}}</td>
                        <td class="text-center">{{\App\Swep\Helpers\Helper::dateFormat($transaction->rfq->created_at ?? null,'M. d, Y')}}</td>
                        <td class="text-center">{{\App\Swep\Helpers\Helper::dateFormat($transaction->aq->created_at ?? null,'M. d, Y')}}</td>
                        <td class="text-center"></td>
                        <td class="text-center">{{\App\Swep\Helpers\Helper::dateFormat($transaction->anaPr->award_date ?? null,'M. d, Y')}}</td>
                        <td class="text-center"></td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td class="text-strong" colspan="2">Total</td>>
                <td class="text-strong">{{number_format($transactions->sum('abc'),2)}}</td>
                <td colspan="7"></td>
            </tr>
        </tbody>
    </table>
@endsection

@section('scripts')
    <script type="text/javascript">
        print();

    </script>
@endsection