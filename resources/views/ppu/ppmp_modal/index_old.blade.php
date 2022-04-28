@php($rand = \Illuminate\Support\Str::random())
@extends('layouts.modal-content')

@section('modal-header')
    {{$pap->pap_code}} <i class="fa fa-chevron-right"></i> {{$pap->pap_title}}
@endsection

@section('modal-body')

    <div class="panel box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">
                <a data-toggle="collapse" id="collapse_trigger_{{$rand}}" data-parent="#accordion" href="#collapseOne_{{$rand}}" aria-expanded="true" class="" style="font-size: smaller">
                    PAP Details (Click here to view)
                </a>
            </h4>
        </div>
        <div id="collapseOne_{{$rand}}" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="box-body">
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-md-3">
                            <dl>
                                <dt>PAP Title:</dt>
                                <dd>{{$pap->pap_title}}</dd>
                            </dl>
                        </div>
                        <div class="col-md-3">
                            <dl>
                                <dt>Responsibility Center:</dt>
                                <dd>{{$pap->responsibilityCenter->desc}}</dd>
                            </dl>
                        </div>
                        <div class="col-md-3">
                            <dl>
                                <dt>Fiscal Year:</dt>
                                <dd>{{$pap->year}}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<div class="panel panel-default">
    <div class="panel-body">
        <p class="page-header-sm text-info" style="border-bottom: 1px solid #4b646f">
            Add PPMP
        </p>
        <form id="add_ppmp_form_{{$rand}}">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('gen_desc',[
                    'label' => 'General Description:*',
                    'cols' => 3,
                ]) !!}
                {!! \App\Swep\ViewHelpers\__form2::textbox('unit_cost',[
                    'label' => 'Unit cost:*',
                    'cols' => 1,
                    'class' => 'autonumber_'.$rand.' unit_cost_'.$rand,
                    'autocomplete' => 'off',
                 ]) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('qty',[
                    'label' => 'Quantity:*',
                    'cols' => 1,
                    'class' => 'qty_'.$rand,
                    'autocomplete' => 'off',
                 ]) !!}

                {!! \App\Swep\ViewHelpers\__form2::select('uom',[
                    'label' => 'Unit of meas.:*',
                    'cols' => 1,
                    'options' => \App\Swep\Helpers\PPUHelpers::ppmpSizes(),
                 ]) !!}

                {!! \App\Swep\ViewHelpers\__form2::select('mode_of_proc',[
                    'label' => 'Mode of Proc.:',
                    'cols' => 1,
                    'options' => \App\Swep\Helpers\Helper::modesOfProcurement(),
                ]) !!}
                {!! \App\Swep\ViewHelpers\__form2::select('budget_type',[
                    'label' => 'Fund Source:',
                    'cols' => 1,
                    'options' => ['COB' => 'COB','SIDA' => 'SIDA'],
                ]) !!}

                <div class="col-md-4">
                    <label>Schedule/Milestone of Activities: (Must be a number)</label>
                    <table class="milestone" style="width: 100%;">
                        <tbody><tr class="text-center">
                            <td>Jan</td>
                            <td>Feb</td>
                            <td>Mar</td>
                            <td>Apr</td>
                            <td>May</td>
                            <td>Jun</td>
                            <td>Jul</td>
                            <td>Aug</td>
                            <td>Sep</td>
                            <td>Oct</td>
                            <td>Nov</td>
                            <td>Dec</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="no-style-input qty_jan" value="" name="qty_jan" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_feb" value="" name="qty_feb" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_mar" value="" name="qty_mar" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_apr" value="" name="qty_apr" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_may" value="" name="qty_may" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_jun" value="" name="qty_jun" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_jul" value="" name="qty_jul" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_aug" value="" name="qty_aug" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_sep" value="" name="qty_sep" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_oct" value="" name="qty_oct" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_nov" value="" name="qty_nov" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="no-style-input qty_dec" value="" name="qty_dec" autocomplete="off">
                            </td>
                        </tr>
                        </tbody></table>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-md-offset-3" style="border-top: 1px solid grey">
                    <label>TOTAL: <span id="total_est_budget_{{$rand}}" style="font-size: larger"></span></label>
                </div>
                <div class="col-md-7">
                    <button type="submit" class="btn btn-primary btn-sm pull-right" ><i class="fa fa-check"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
            <div id="ppmp_table_container_{{$rand}}" style="display: none">
                <table class="table table-bordered table-striped table-hover" id="ppmp_table_{{$rand}}" style="width: 100% !important">
                    <thead>
                    <tr class="">
                        <th class="th-20">PPMP Code</th>
                        <th >General Description</th>
                        <th>PPMP Details</th>
                        <th>Milestone</th>
                        <th >Total Budget</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div id="tbl_loader_{{$rand}}">
                <center>
                    <img style="width: 100px" src="{{asset('images/loader.gif')}}">
                </center>
            </div>
    </div>
</div>
@endsection

@section('modal-footer')

@endsection

@section('scripts')
    <script type="text/javascript">


        const autonumericElement_{{$rand}} =  AutoNumeric.multiple('.autonumber_{{$rand}}');

        var unit_cost_{{$rand}} = 0;
        var qty_{{$rand}} = 0;
        $('body').on('change','.unit_cost_{{$rand}}',function () {
            if($(this).val() != ''){
                unit_cost_{{$rand}} = $(this).val().replaceAll(',','');

            }
            let t = $(this);
            let body_parent = t.parent('div').parent('div').parent('div');
            $("#total_est_budget_{{$rand}}").html(formatToCurrency(unit_cost_{{$rand}}*qty_{{$rand}}));
            {{--$("#total_est_budget_{{$rand}}").change();--}}
        })
        $('body').on('change','.qty_{{$rand}}',function () {
            if($(this).val() != '') {
                qty_{{$rand}} = $(this).val();
            }
            let t = $(this);
            let body_parent = t.parent('div').parent('div').parent('div');
            $("#total_est_budget_{{$rand}}").html(formatToCurrency(unit_cost_{{$rand}}*qty_{{$rand}}));
            {{--$("#total_est_budget_{{$rand}}").change();--}}
        })
        var active_{{$rand}} = '';
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            ppmp_tbl_{{$rand}} = $("#ppmp_table_{{$rand}}").DataTable({
                "ajax" : '{{route("dashboard.ppmp_modal.index")}}?pap_code={{$pap->slug}}',
                "columns": [
                    { "data": "ppmp_code" },
                    { "data": "gen_desc" },
                    { "data": "details" },
                    { "data": "milestone" },
                    { "data": "total_budget" },

                    { "data": "action" }
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],
                "columnDefs":[
                    {
                        "targets" : [0],
                        "class" : 'w-8p'
                    },
                    {
                        "targets" : 2,
                        "class" : 'w-20p'
                    },
                    {
                        "targets" : 3,
                        "class" : 'w-20p'
                    },
                    {
                        "targets" : 4,
                        "class" : 'text-right w-16p',
                    },
                    {
                        "targets" : 5,
                        "orderable" : false,
                        "class" : 'action2'
                    },
                ],
                "responsive": false,
                'dom' : 'lBfrtip',
                "processing": true,
                "serverSide": true,
                "initComplete": function( settings, json ) {
                    style_datatable("#"+settings.sTableId);
                    $('#tbl_loader_{{$rand}}').fadeOut(function(){
                        $("#ppmp_table_container_{{$rand}}").fadeIn();
                        if(find != ''){
                            ppmp_tbl_{{$rand}}.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            ppmp_tbl_{{$rand}}.search(this.value).draw();
                        }
                    });
                },

                "language":
                    {
                        "processing": "<center><img style='width: 70px' src='{{asset("images/loader.gif")}}'></center>",
                    },
                "drawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();
                    $('[data-toggle="modal"]').tooltip();
                    if(active_{{$rand}} != ''){
                        $("#ppmp_table_{{$rand}} #"+active_{{$rand}}).addClass('success');
                    }
                }
            });

            setTimeout(function () {
                $("#collapse_trigger_<?php echo e($rand); ?>").trigger('click');
            },1500)
        })

        $("#add_ppmp_form_{{$rand}}").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.ppmp_modal.store")}}',
                data : form.serialize()+"&pap_code={{$pap->pap_code}}",
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,false);
                    active_{{$rand}} = res.slug;
                    ppmp_tbl_{{$rand}}.draw(true);
                    $("#total_est_budget_{{$rand}}").html('');
                },
                error: function (res) {
                    errored(form, res);
                }
            })
        });

        $("#ppmp_table_{{$rand}}").on("click",'.edit_ppmp_btn',function () {
            let btn = $(this);
            load_modal2(btn);
            $.ajax({
                url : btn.attr('uri'),
                type: 'GET',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                   populate_modal2(btn,res);
                },
                error: function (res) {
                    populate_modal2_error(res)
                }
            })
        })

    </script>
@endsection

