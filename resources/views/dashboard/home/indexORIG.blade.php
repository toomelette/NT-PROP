@extends('layouts.admin-master')

@section('content')

{{--<section class="content-header">
    <h1>Dashboard</h1>
</section>--}}

<section class="content">


  <div class="row">
    <div class="col-md-6">
      <div class="panel">
        <div class="panel-body">
          <label>Procurement per Responsibility Center</label>
          <hr class="no-margin">
          <div style="max-height: 355px;overflow-x: hidden; padding-top: 15px" id="">
            <div class="nav-tabs-custom" id="prContainer">
              <table class="table table-bordered">
                <thead>
                <tr>
                  {{--<th>No.</th>--}}
                  <th>Responsibility Center</th>
                  <th style="text-align: center;">PR</th>
                  <th style="text-align: center;">PO</th>
                  <th style="text-align: center;">JR</th>
                  <th style="text-align: center;">JO</th>
                </tr>
                </thead>
                <tbody>
                @php
                  $prTotal = 0;
                  $jrTotal = 0;
                  $poTotal = 0;
                  $joTotal = 0;
                  //$number = 0;
                  usort($trans_by_resp_center_pr_jr, function($a, $b) {
                        return $b->count - $a->count;
                    });
                @endphp
                @foreach($trans_by_resp_center_pr_jr as $rc)
                  @php
                    $prTotal += $rc->count;
                    $jrTotal += $rc->countJR;
                    $poTotal += $rc->countPO;
                    $joTotal += $rc->countJO;
                    //$number++;
                  @endphp
                  <tr>
                   {{-- <td>--}}{{--{{ $number }}--}}{{--</td>--}}
                    <td class="text-strong">{{ $rc->name }}</td>
                    <td style="text-align: right;">{{ number_format($rc->count,2) }}</td>
                    <td style="text-align: right;">{{ number_format($rc->countPO,2) }}</td>
                    <td style="text-align: right;">{{ number_format($rc->countJR,2) }}</td>
                    <td style="text-align: right;">{{ number_format($rc->countJO,2) }}</td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <table class="table">
                <thead>
                <tr>
                  <th style="width:5%">
                    Total:
                  </th>
                  <th style="text-align: right">
                    PR {{ number_format($prTotal,2) }}
                  </th>
                  <th style="text-align: right">
                    PO {{ number_format($poTotal,2) }}
                  </th>
                  <th style="text-align: right;">
                    JR {{ number_format($jrTotal,2) }}
                  </th>
                  <th style="text-align: right;">
                    JO {{ number_format($joTotal,2) }}
                  </th>
                </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6" style="padding: 0">
      <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{$trans_pr}}</h3>
            <p>Purchase Request</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{$trans_jr}}</h3>
            <p>Job Request</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{$trans_rfq}}</h3>
            <p>RFQ</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{$trans_aq}}</h3>
            <p>AQ</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{$trans_po}}</h3>
            <p>Purchase Order</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{$trans_jo}}</h3>
            <p>Job Order</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </div>
      </div>

      <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{$trans_pr_cancelled}}</h3>
            <p>Cancelled PR</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </div>
      </div>

      <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-orange">
          <div class="inner">
            <h3>{{$trans_jr_cancelled}}</h3>
            <p>Cancelled JR</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    {!! __chart::div_flot_bar('12', 'trans_by_resp_center_bar' ,'PR & JR Graph Report') !!}
  </div>
</section>
@endsection
@section('scripts')
<script>
    $(function () {
      {!!
          __chart::js_flot_bar('trans_by_resp_center_bar',
           '["OB", '. collect($trans_by_resp_center_bar)->where('name', 'OB')->first()->count .'],
            ["IAD", '. collect($trans_by_resp_center_bar)->where('name', 'IAD')->first()->count .'],
            ["LEGAL", '. collect($trans_by_resp_center_bar)->where('name', 'LEGAL')->first()->count .'],
            ["PPSPD", '. collect($trans_by_resp_center_bar)->where('name', 'PPSPD')->first()->count .'],
            ["AFD-LM", '. collect($trans_by_resp_center_bar)->where('name', 'AFD-LM')->first()->count .'],
            ["AFD-VIS", '. collect($trans_by_resp_center_bar)->where('name', 'AFD-VIS')->first()->count .'],
            ["RDE-LM", '. collect($trans_by_resp_center_bar)->where('name', 'RDE-LM')->first()->count .'],
            ["RDE-VIS", '. collect($trans_by_resp_center_bar)->where('name', 'RDE-VIS')->first()->count .'],
            ["RD-LM", '. collect($trans_by_resp_center_bar)->where('name', 'RD-LM')->first()->count .'],
            ["RD-VIS", '. collect($trans_by_resp_center_bar)->where('name', 'RD-VIS')->first()->count .'],
            ["GAD", '. collect($trans_by_resp_center_bar)->where('name', 'GAD')->first()->count .'],
            ["SIDA-BFP", '. collect($trans_by_resp_center_bar)->where('name', 'SIDA-BFP')->first()->count .'],
            ["SIDA-SCP", '. collect($trans_by_resp_center_bar)->where('name', 'SIDA-SCP')->first()->count .'],
            ["SIDA-HRD", '. collect($trans_by_resp_center_bar)->where('name', 'SIDA-HRD')->first()->count .'],
            ["SIDA- FMR", '. collect($trans_by_resp_center_bar)->where('name', 'SIDA- FMR')->first()->count .'],
            ["SIDA-R&D", '. collect($trans_by_resp_center_bar)->where('name', 'SIDA-R&D')->first()->count .']
            '
        )
      !!}
    });

    // Chart label Formatter
    function labelFormatter(label, series) {
      return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
              + label
              + '<br>'
              + Math.round(series.percent) + '%</div>'
    }
</script>

@endsection