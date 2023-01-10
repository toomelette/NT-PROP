@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>Requests for Quotation</h1>
</section>
@endsection
@section('content2')

<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Requests for Quotation</h3>
            <button class="btn btn-primary btn-sm pull-right" type="button" data-toggle="modal" data-target="#add_rfq_modal"><i class="fa fa-plus"></i> Create RFQ</button>
        </div>
        <div class="box-body">
        </div>
    </div>

</section>


@endsection


@section('modals')
    <div class="modal fade" id="add_rfq_modal" tabindex="-1" role="dialog" aria-labelledby="add_rfq_modal_label">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="add_rfq_form">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Prepare QRF</h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="radio">
                                      <label>
                                          <input type="radio" name="prJr" class="radioPrJr" target="prEntry" value="pr">
                                          Purchase Request (PR)
                                      </label>
                                  </div>
                              </div>
                              {!! \App\Swep\ViewHelpers\__form2::textbox('prNo',[
                                  'label' => 'Enter PR No.:',
                                  'cols' => 12,
                                  'id' => 'pr_entry',
                                  'for' => 'prEntry',
                                  'class' => 'numberEntry',
                              ]) !!}
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="radio">
                                      <label>
                                          <input type="radio" name="prJr" class="radioPrJr" target="jrEntry" value="jr">
                                          Job Request (JR)
                                      </label>
                                  </div>
                              </div>
                              {!! \App\Swep\ViewHelpers\__form2::textbox('jrNo',[
                                  'label' => 'Enter JR No.:',
                                  'cols' => 12,
                                  'id' => 'pr_entry',
                                  'for' => 'jrEntry',
                                  'class' => 'numberEntry',
                              ]) !!}
                          </div>
                      </div>
                  </div>
                  <hr>
                  <div class="row">
                      {!! \App\Swep\ViewHelpers\__form2::textbox('deadline',[
                          'type' => 'date',
                          'label' => 'Set deadline: ',
                          'cols' => 6,
                      ]) !!}
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
              </div>
          </form>
        </div>
      </div>
    </div>    
@endsection

@section('scripts')
<script type="text/javascript">
    $(".numberEntry").change(function () {
        let input = $(this);
        let form = $("#add_rfq_form");
        $.ajax({
            url : '{{route("dashboard.ajax.post","rfq_prNo")}}',
            data : form.serialize(),
            type: 'POST',
            headers: {
                {!! __html::token_header() !!}
            },
            success: function (res) {
                succeed(form,false,false);
                console.log(res);
            },
            error: function (res) {
                console.log(res);
                errored(form,res);
            }
        })
    })

    $("#add_rfq_form").submit(function (e) {
        e.preventDefault()
        let form = $(this);
        loading_btn(form);
        $.ajax({
            url : '{{route("dashboard.rfq.store")}}',
            data : form.serialize(),
            type: 'POST',
            headers: {
                {!! __html::token_header() !!}
            },
            success: function (res) {
                active = res.slug;
                rfq_tbl.draw(false);
                succeed(form,true,true);
            },
            error: function (res) {
                errored(form,res);
            }
        })
    })

    $("document").ready(function () {
        $(".numberEntry").each(function () {
            $(this).attr('disabled','disabled');
        })
    })

    $("body").on("change",".radioPrJr",function () {
        let t = $(this);
        let target = t.attr('target');
        $(".numberEntry").each(function () {
            $(this).attr('disabled','disabled');
        })
        $("input[for='"+target+"']").removeAttr('disabled');

    })
</script>
@endsection