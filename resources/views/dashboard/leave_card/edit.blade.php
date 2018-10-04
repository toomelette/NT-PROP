@extends('layouts.admin-master')

@section('content')
    
  <section class="content-header">
      <h1>Edit Leave</h1>
      <div class="pull-right" style="margin-top: -25px;">
        {!! __html::back_button([
          'dashboard.leave_card.index', 
          'dashboard.leave_card.show']) !!}
      </div>
  </section>

  <section class="content">

      <div class="box">
      
        <div class="box-header with-border">
          <h3 class="box-title">Form</h3>
          <div class="pull-right">
              <code>Fields with asterisks(*) are required</code>
          </div> 
        </div>

        <form role="form" method="POST" autocomplete="off" action="{{ route('dashboard.leave_card.update', $leave_card->slug) }}">

          <div class="box-body">

            @csrf    
            <input name="_method" value="PUT" type="hidden">

            {!! __form::select_static(
              '3', 'doc_type', 'Document Type *', old('doc_type') ? old('doc_type') : $leave_card->doc_type, __static::document_types_leave_card(), $errors->has('doc_type'), $errors->first('doc_type'), '', ''
            ) !!}

            <div class="col-md-12"></div>


            {{-- For leave --}}
            <div id="leave_div">

              {!! __form::select_dynamic(
                '3', 'employee_no', 'Employee *', old('employee_no') ? old('employee_no') : $leave_card->employee_no, $global_employees_all, 'employee_no', 'fullname', $errors->has('employee_no'), $errors->first('employee_no'), 'select2', ''
              ) !!}

              {!! __form::select_static(
                '3', 'leave_type', 'Leave Type *', old('leave_type') ? old('leave_type') : $leave_card->leave_type, __static::leave_types(), $errors->has('leave_type'), $errors->first('leave_type'), '', ''
              ) !!}

              {!! __form::select_static(
                '3', 'month', 'Month *', old('month') ? old('month') : $leave_card->month, __static::months(), $errors->has('month'), $errors->first('month'), '', ''
              ) !!}

              {!! __form::textbox(
                 '3', 'year', 'text', 'Year *', 'Year', old('year') ? old('year') : $leave_card->year, $errors->has('year'), $errors->first('year'), ''
              ) !!}

              <div class="col-md-12"></div>

              {!! __form::datepicker(
                '3', 'date_from',  'Date From *', old('date_from') ? old('date_from') : $leave_card->date_from, $errors->has('date_from'), $errors->first('date_from')
              ) !!}

              {!! __form::datepicker(
                '3', 'date_to',  'Date To *', old('date_to') ? old('date_to') : $leave_card->date_to, $errors->has('date_to'), $errors->first('date_to')
              ) !!}

              {!! __form::textbox(
                 '6', 'remarks', 'text', 'Remarks', 'Remarks', old('remarks') ? old('remarks') : $leave_card->remarks, $errors->has('remarks'), $errors->first('remarks'), ''
              ) !!}

            </div>



            {{-- For OT, UT, Tardy --}}
            <div id="others_div">

              {!! __form::select_dynamic(
                '3', 'employee_no', 'Employee *', old('employee_no') ? old('employee_no') : $leave_card->employee_no, $global_employees_all, 'employee_no', 'fullname', $errors->has('employee_no'), $errors->first('employee_no'), 'select2', ''
              ) !!}

              {!! __form::datepicker(
                '3', 'date',  'Date *', old('date') ? old('date') : $leave_card->date, $errors->has('date'), $errors->first('date')
              ) !!}

              {!! __form::textbox(
                 '3', 'hrs', 'number', 'Hours', 'Hours', old('hrs') ? old('hrs') : $leave_card->hrs, $errors->has('hrs'), $errors->first('hrs'), ''
              ) !!}

              {!! __form::textbox(
                 '3', 'mins', 'number', 'Minutes', 'Minutes', old('mins') ? old('mins') : $leave_card->mins, $errors->has('mins'), $errors->first('mins'), ''
              ) !!}

            </div>



          </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-default">Save <i class="fa fa-fw fa-save"></i></button>
          </div>

        </form>

      </div>




  </section>

@endsection





@section('modals')

  @if(Session::has('LC_CREATE_SUCCESS'))

    {!! __html::modal(
      'lc_create', '<i class="fa fa-fw fa-check"></i> Saved!', Session::get('LC_CREATE_SUCCESS')
    ) !!}
    
  @endif

@endsection 





@section('scripts')

  <script type="text/javascript">


    @if(Session::has('LC_CREATE_SUCCESS'))
      $('#lc_create').modal('show');
    @endif


    @if(old('doc_type') == 'LEAVE' || $leave_card->doc_type == 'LEAVE')
      $( document ).ready(function() {
        $('#leave_div').show();
        $("#leave_div :input").removeAttr("disabled");
        $('#others_div').hide();
        $("#others_div :input").attr("disabled", "disabled");
      });
    @elseif(old('doc_type') == 'OT' || old('doc_type') == 'UT' || old('doc_type') == 'TARDY' || $leave_card->doc_type == 'OT' || $leave_card->doc_type == 'UT' || $leave_card->doc_type == 'TARDY')
      $( document ).ready(function() {
        $('#others_div').show();
        $("#others_div :input").removeAttr("disabled");
        $('#leave_div').hide();
        $("#leave_div :input").attr("disabled", true);
      });
    @else
      $( document ).ready(function() {
        $('#leave_div').hide();
        $("#leave_div :input").attr("disabled", true);
        $('#others_div').hide();
        $("#others_div :input").attr("disabled", true);
      });
    @endif


    $(document).on("change", "#doc_type", function () {
      var val = $(this).val();
      if(val == "LEAVE"){
        $('#leave_div').show();
        $("#leave_div :input").removeAttr("disabled");
        $('#others_div').hide();
        $("#others_div :input").attr("disabled", true);
      }else if(val == ""){
        $('#leave_div').hide();
        $('#others_div').hide();
        $("#leave_div :input").attr("disabled", true);
        $("#others_div :input").attr("disabled", true);
      }else{
        $('#others_div').show();
        $("#others_div :input").removeAttr("disabled");
        $('#leave_div').hide();
        $("#leave_div :input").attr("disabled", true);
      }
    });


  </script> 
    
@endsection