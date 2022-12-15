@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>{{$pap->pap_code}} <i class="fa fa-caret-right"></i> {{$pap->pap_title}}</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-solid">
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">PPMP</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Purchase Requests</a></li>
                        <li><a href="#tab_3" data-toggle="tab">Job Requests</a></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                Dropdown <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                            </ul>
                        </li>
                        <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th rowspan="2">CODE</th>
                                        <th rowspan="2">General Description</th>
                                        <th rowspan="2">Unit Cost</th>
                                        <th rowspan="2">Qty</th>
                                        <th rowspan="2">Size</th>
                                        <th rowspan="2">Total Est. Budget</th>
                                        <th rowspan="2">Mode of Proc.</th>
                                        <th rowspan="2">Source of fund</th>
                                        <th colspan="12" class="text-center">Milestone</th>
                                    </tr>
                                <tr>
                                    @foreach(\App\Swep\Helpers\Arrays::milestones() as $month)
                                        <td class="small">{{$month}}</td>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($pap->ppmps) && $pap->ppmps()->count() > 0)
                                        <tr class="info">
                                            <td>{{$pap->pap_code}}</td>
                                            <td colspan="19">{{$pap->pap_title}}</td>
                                        </tr>
                                        @php
                                            $grandTotal = 0;
                                        @endphp
                                        @foreach($pap->ppmps as $ppmp)
                                            @php
                                                $grandTotal = $grandTotal + $ppmp->estTotalCost;
                                            @endphp
                                            <tr>
                                                <td></td>
                                                <td>{{$ppmp->article->article ?? null}}</td>
                                                <td class="text-right">{{number_format($ppmp->unitCost,2)}}</td>
                                                <td class="text-center">{{$ppmp->qty}}</td>
                                                <td>{{$ppmp->article->uom ?? null}}</td>
                                                <td class="text-right">{{number_format($ppmp->estTotalCost,2)}}</td>
                                                <td class="text-center">{{\App\Swep\Helpers\Helper::toSentence($ppmp->modeOfProc)}}</td>
                                                <td class="text-center">{{$ppmp->sourceOfFund}}</td>
                                                @foreach(\App\Swep\Helpers\Arrays::milestones() as $month)
                                                    <td class="small text-center">{{$ppmp->{'qty_'.strtolower($month)} }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr class="">
                                            <td></td>
                                            <td colspan="4" class="text-strong">TOTAL {{strtoupper($pap->pap_title)}}</td>
                                            <td class="text-strong text-right">{{number_format($grandTotal,2)}}</td>
                                            <td colspan="14"></td>
                                        </tr>
                                    @else
                                        <tr class="warning">
                                            <td colspan="20" class="text-center">No data found.</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="tab_2">
                            Purchase Requests
                            <table id="prs_table" class="table table-condensed table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>PR No.</th>
                                        <th>PR Date.</th>
                                        <th>SAI No.</th>
                                        <th>SAI Date</th>
                                        <th style="width: 50%;">Items</th>
                                        <th style="width: 10%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($pap->prs) && $pap->prs()->count() > 0)
                                        @foreach($pap->prs as $pr)
                                            <tr>
                                                <td>{{$pr->prNo}}</td>
                                                <td>{{$pr->prDate}}</td>
                                                <td>{{$pr->saiNo}}</td>
                                                <td>{{$pr->saiDate}}</td>
                                                <td>
                                                    <table class="milestone pr_items" style="width: 100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Unit</th>
                                                                <th>Item, Desc</th>
                                                                <th>Unit Cost</th>
                                                                <th>Total Cost</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(!empty($pr->items) && $pr->items()->count() > 0)
                                                                @foreach($pr->items as $item)
                                                                    <tr>
                                                                        <td>{{number_format( $item->qty )}}</td>
                                                                        <td>{{$item->article->article ?? 'N/A'}}, {{$item->description}}</td>
                                                                        <td class="text-right">{{number_format($item->unitCost)}}</td>
                                                                        <td class="text-right">{{number_format( $item->qty * $item->unitCost , 2)}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td style="width: 30px">
                                                    <a class="btn btn-default btn-sm">View PR</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="tab_3">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                            It has survived not only five centuries, but also the leap into electronic typesetting,
                            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                            sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                            like Aldus PageMaker including versions of Lorem Ipsum.
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </section>


@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        $("#prs_table").DataTable();
    </script>
@endsection