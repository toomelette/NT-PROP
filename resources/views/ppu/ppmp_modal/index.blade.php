@php($rand = \Illuminate\Support\Str::random())
@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>PAP: {{$pap->pap_code}} <i class="fa fa-chevron-right"></i> {{$pap->pap_title}}</h1>
    </section>

    <section class="content">
        <div class="panel box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" id="collapse_trigger_{{$rand}}" data-parent="#accordion" href="#collapseOne_{{$rand}}" aria-expanded="true" class="" style="font-size: smaller">
                        PAP Details (Click here to view)
                    </a>
                </h4>
            </div>
            <div id="collapseOne_{{$rand}}" class="panel-collapse collapse" aria-expanded="true" style="">
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
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">PPMP</h3>
                <button class="btn btn-primary btn-sm pull-right" type="button" data-toggle="modal" data-target="#add_item_modal"><i class="fa fa-plus"></i> Add item</button>
            </div>
            <div class="box-body">
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
            </div>

        </div>

    </section>


@endsection


@section('modals')

    <div class="modal fade" id="add_item_modal" tabindex="-1" role="dialog" aria-labelledby="add_item_modalLabel">
      <div class="modal-dialog" style="width: 95%" role="document">
        <div class="modal-content">
            <form id="add_ppmp_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add_item_modalLabel">PPMP - <b>PAP Code: {{$pap->pap_code}} <i class="fa fa-chevron-right"></i> {{$pap->pap_title}}</b> | <i>{{$pap->responsibilityCenter->desc}}</i></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="form-group budget_type" >
                                    <label for="inputEmail3" class="col-sm-2 control-label" style="width: auto; vertical-align: middle">Budget Type:</label>
                                    <div class="col-sm-3">
                                        <select name="budget_type" class="form-control">
                                            {!! \App\Swep\ViewHelpers\__html::populate_options(\App\Swep\Helpers\Helper::budgetTypes()) !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn-sm pull-right btn-success btn add_row_btn" style="margin-bottom: 15px" type="button" target_table="#add_table"><i class="fa fa-plus"></i> Add row</button>
                        </div>
                    </div>
                    <hr style="margin: 10px 0">

                    <table id="add_table" class="ppmp_input" style="width: 100%;">
                        <thead>
                        <tr>
                            <th rowspan="2">General Description</th>
                            <th rowspan="2">Unit Cost</th>
                            <th rowspan="2">Qty</th>
                            <th rowspan="2">Size</th>
                            <th rowspan="2">Est. Budget</th>
                            <th rowspan="2">Mode of Proc.</th>
                            <th rowspan="2">Source of Fund</th>
                            <th colspan="12">Schedule/Milestone of Activities</th>
                            <th colspan="12"></th>
                        </tr>
                        <tr>
                            <th>Jan</th>
                            <th>Feb</th>
                            <th>Mar</th>
                            <th>Apr</th>
                            <th>May</th>
                            <th>Jun</th>
                            <th>Jul</th>
                            <th>Aug</th>
                            <th>Sep</th>
                            <th>Oct</th>
                            <th>Nov</th>
                            <th>Dec</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>
        </div>
      </div>
    </div>

    {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_ppmp_modal','') !!}
    {!! \App\Swep\ViewHelpers\__html::blank_modal('show_ppmp_modal','') !!}

    {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_history',75) !!}
@endsection

@section('scripts')
    <script type="text/javascript">
        function createTypeahead(el) {
            el.typeahead({
                ajax : "{{ route('dashboard.ppmp_modal.index') }}?typeahead=true",
                onSelect:function (result) {
                    // console.log(result);
                },
                lookup: function (i) {
                    // console.log(i);
                }
            });
        }
    </script>
    <script type="text/javascript">

        $("body").on("click",".gen_desc_typeahead",function () {
            createTypeahead($(this));
        })

        $(".add_row_btn").click(function () {
            let children = $('#add_table tbody').children('tr').length;
            if(children >= 30){
                notify('You cannot add more than '+children+' rows.','warning');
            }else{
                let btn = $(this);
                wait_this_button(btn);
                let target_table = $(this).attr('target_table');
                $.ajax({
                    url : '{{route("dashboard.ajax.get","add_ppmp_row")}}',
                    type: 'GET',
                    headers: {
                        {!! __html::token_header() !!}
                    },
                    success: function (res) {
                       $(target_table+' tbody').append(res);
                       unwait_this_button(btn)
                    },
                    error: function (res) {
                        console.log(res);
                        unwait_this_button(btn)
                    }
                })
            }
        });
        const autonumericElement_{{$rand}} =  AutoNumeric.multiple('.autonumber_{{$rand}}');


        $('body').on('change','.mult',function () {
            let uc_element = $(".unit_cost[rand='"+$(this).attr('rand')+"']");
            let qty_element = $(".qty[rand='"+$(this).attr('rand')+"']");
            let total_element = $(".total[rand='"+$(this).attr('rand')+"']");

            let uc = 0;
            if(uc_element.val() != ''){
                uc = uc_element.val().replaceAll(',','');
            }
            total_element.val(formatToCurrency(uc*qty_element.val()));
        });

        var active;
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            ppmp_tbl = $("#ppmp_table_{{$rand}}").DataTable({
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
                        "class" : 'w-16p',
                    },
                    {
                        "targets" : 5,
                        "orderable" : false,
                        "class" : 'action3'
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
                            ppmp_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            ppmp_tbl.search(this.value).draw();
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
                    if(active != ''){
                        if(Array.isArray(active) == true){
                            $.each(active,function (i,item) {
                                $("#ppmp_table_{{$rand}} #"+item).addClass('success');
                            })
                        }
                        $("#ppmp_table_{{$rand}} #"+active).addClass('success');
                    }
                }
            });

            $(".add_row_btn").trigger('click');

        })

        $("#add_ppmp_form").submit(function (e) {
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
                    console.log(res);
                    succeed(form,true,false);
                    active = res.slug;
                    ppmp_tbl.draw(false);
                    notify('PPMP successfully added.');
                    $("#add_table tbody").html('');
                    $(".add_row_btn").trigger('click');
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

        $("#ppmp_table_{{$rand}}").on("click",'.show_ppmp_btn',function (e) {
            if (window.event.ctrlKey) {
                e.stopPropagation();
                $("#edit_history .modal-content").html(modal_loader);
                $("#edit_history").modal('show');
                let btn = $(this);
                $.ajax({
                    url : '{{route("dashboard.view_edit_history")}}',
                    data : {model:btn.attr('edit_history_model'),id:btn.attr('edit_history_id')},
                    type: 'GET',
                    headers: {
                        {!! __html::token_header() !!}
                    },
                    success: function (res) {
                        $("#edit_history #modal_loader").fadeOut(function() {
                            $("#edit_history .modal-content").html(res);
                            $('.datepicker').each(function(){
                                $(this).datepicker({
                                    autoclose: true,
                                    dateFormat: "mm/dd/yy",
                                    orientation: "bottom"
                                });
                            });
                            $("ol.sortable").sortable();
                        });
                    },
                    error: function (res) {
                        console.log(res);
                    }
                })
                return;
            }

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
        });

        $("body").on("click",".remove_row_btn",function () {
            let children = $(this).parents('#add_table tbody').children('tr').length;
            if(children <= 1){
                notify('Table must have at least one row.','warning');
            }else{
                $(this).parents('tr').remove()
            }
        })

    </script>



@endsection