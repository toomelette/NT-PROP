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
          <label>Transactions per Responsibility Center</label>
          <hr class="no-margin">
          <div style="max-height: 355px;overflow-x: hidden; padding-top: 15px" id="">
            <div class="nav-tabs-custom" id="prContainer">
              <table class="table table-bordered">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Responsibility Center</th>
                  <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                  $prTotal = 0;
                  $number = 0;
                  usort($trans_by_resp_center_bar, function($a, $b) {
                        return $b->count - $a->count;
                    });
                @endphp
                @foreach($trans_by_resp_center_bar as $rc)
                  @php
                    $prTotal += $rc->count;
                    $number++;
                  @endphp
                  <tr>
                    <td>{{ $number }}</td>
                    <td class="text-strong">{{ $rc->name }}</td>
                    <td>{{ $rc->count }}</td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <label>Total Transactions: <span class="text-strong">{{$prTotal}}</span></label>
        </div>
      </div>
    </div>

    <div class="col-lg-6" style="padding: 0">
      <div class="col-lg-12 col-xs-12">
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
        <div class="small-box bg-yellow">
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
        <div class="small-box bg-red">
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
            <h3>{{$trans_rfq}}</h3>
            <p>RFQ</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </div>
      </div>
    </div>
  </div>



  <div class="row">
    {!! __chart::div_flot_bar('12', 'trans_by_resp_center_bar' ,'Transaction Graph Report') !!}
    {{--{!! __chart::div_flot_bar('6', 'emp_by_dept_bar' ,'Employee by Department') !!}--}}
{{--    {!! __chart::div_flot_donut('6', 'pr_by_dept_donut' , 'PR by Department') !!}--}}
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

      {{--{!!
          __chart::js_flot_bar('emp_by_dept_bar',
           '["AFD", '. $get_emp_by_dept['AFD'] .'],
            ["IAD", '. $get_emp_by_dept['IAD'] .'],
            ["PPD", '. $get_emp_by_dept['PPD'] .'],
            ["RDE", '. $get_emp_by_dept['RDE'] .'],
            ["RD", '. $get_emp_by_dept['RD'] .'],
            ["LEGAL", '. $get_emp_by_dept['LEGAL'] .']'
        ) 
      !!}--}}

      {{--{!!
          __chart::js_flot_donut('emp_by_gender_donut',
              '[
                { label: "Female", data: '. $count_female_emp  .' , color: "#BF3F3F" },
                { label: "Male", data: '. $count_male_emp  .', color: "#3F7FBF" },
              ]
          ') 
      !!}--}}
      


      // Chart label Formatter
      function labelFormatter(label, series) {
          return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
            + label
            + '<br>'
            + Math.round(series.percent) + '%</div>'
      }


    });

</script>

@endsection