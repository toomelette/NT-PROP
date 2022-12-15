@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Manage PAP</h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Projects Activities and Programs</h3>
                <div class="pull-right">
                    <button class="btn btn-primary btn-sm" data-target="#add_modal" data-toggle="modal"><i class="fa fa-plus"></i> Add PAP</button>
                </div>
            </div>
            <div class="box-body">
                <div id="pap_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="pap_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th class="th-20">PAP Code</th>
                            <th >PAP Title</th>
                            <th>PS</th>
                            <th>CO</th>
                            <th>MOOE</th>
                            <th >Total budget</th>
                            <th >Procurement</th>
                            <th >Details</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="tbl_loader">
                    <center>
                        <img style="width: 100px" src="{{asset('images/loader.gif')}}">
                    </center>
                </div>
            </div>
        </div>
    </section>


@endsection


@section('modals')
    <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="add_modalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="add_pap_form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="add_modalLabel">New PAP</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::select('year',[
                                'label' => 'Year:',
                                'cols' => 6,
                                'options' => 'year',
                            ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('budget_type',[
                                'label' => 'Budget Type:',
                                'cols' => 6,
                                'options' => \App\Swep\Helpers\Arrays::fundSources(),
                            ]) !!}

                        </div>
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                                'label' => 'Responsibility Center:',
                                'cols' => 12,
                                'options' => \App\Swep\Helpers\PPUHelpers::respCentersArray(),
                            ]) !!}
                        </div>
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('pap_title',[
                                'label' => 'PAP Title:*',
                                'cols' => 12,
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textarea('pap_desc',[
                                'label' => 'PAP Description:*',
                                'cols' => 12,
                            ]) !!}
                        </div>

                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('ps',[
                                'label' => 'PS:',
                                'cols' => 4,
                                'class' => 'text-right autonum'
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('co',[
                                'label' => 'Capital Outlay:',
                                'cols' => 4,
                                'class' => 'text-right autonum'
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('mooe',[
                                'label' => 'MOOE:',
                                'cols' => 4,
                                'class' => 'text-right autonum'
                            ]) !!}
                        </div>
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('pcent_share',[
                                'label' => 'Percent Share:*',
                                'cols' => 4,
                                'type' => 'number',
                                'step' => '0.01',
                            ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('type',[
                                'label' => 'Type:',
                                'cols' => 4,
                                'options' => \App\Swep\Helpers\Arrays::papTypes()
                            ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('status',[
                                'label' => 'Status:',
                                'cols' => 4,
                                'options' => \App\Swep\Helpers\Arrays::activeInactive()
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
    {!! \App\Swep\ViewHelpers\__html::blank_modal('ppmp_modal',90) !!}
    {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_ppmp_modal',95,'200px') !!}
    {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_pap_modal','') !!}
@endsection

@section('scripts')
    <script type="text/javascript">
        $("#add_pap_form").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.pap.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                   succeed(form,true,false);
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })


        
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable
            active = '';
            pap_tbl = $("#pap_table").DataTable({
                "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}',
                "columns": [
                    { "data": "pap_code" },
                    { "data": "pap_title" },
                    { "data": "ps" },
                    { "data": "co" },
                    { "data": "mooe" },
                    { "data": "totalBudget" },
                    { "data": "procurement" },
                    { "data": "pcent_share" },
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
                        "targets" : [2,3,4,5,6],
                        "class" : 'w-8p text-right'
                    },
                    {
                        "targets" : 8,
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
                    $('#tbl_loader').fadeOut(function(){
                        $("#"+settings.sTableId+"_container").fadeIn();
                        if(find != ''){
                            pap_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            pap_tbl.search(this.value).draw();
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
                        $("#"+settings.sTableId+" #"+active).addClass('success');
                    }
                }
            });
        })

        $("body").on("click",".edit_pap_btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.pap.edit","slug")}}';
            uri = uri.replace('slug',btn.attr('data'));
            $.ajax({
                url : uri,
                type: 'GET',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    populate_modal2(btn,res);
                },
                error: function (res) {
                    populate_modal2_error(res);
                }
            })
        });
    </script>
@endsection