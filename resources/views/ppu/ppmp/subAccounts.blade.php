@php($rand = \Illuminate\Support\Str::random())
@extends('layouts.modal-content')

@section('modal-header')
    {{$ppmp->article->article ?? null}}
@endsection

@section('modal-body')
    <div class="row">
        <div class="col-md-10">
            <div class="well well-sm">
                <div class="row">
                    <div class="col-md-2">
                        <p class="no-margin">Dept:</p>
                        <p class="text-strong">
                            {{$ppmp->pap->responsibilityCenter->department}}
                            {!! ($ppmp->pap->responsibilityCenter->division != '' ? '<br> '.$ppmp->pap->responsibilityCenter->division : '')!!}
                            {!!  ($ppmp->pap->responsibilityCenter->section != '' ? '<br> '.$ppmp->pap->responsibilityCenter->section : '')!!}
                        </p>
                    </div>
                    <div class="col-md-10">
                        <p class="no-margin">PAP:</p>
                        <p class="text-strong">
                            {{$ppmp->papCode}} <br>
                            {{$ppmp->pap->pap_title}}
                        </p>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-sm btn-primary pull-right add_ppmp_subaccount_btn_{{$rand}}" data-toggle="modal" data-target="#add_ppmp_subaccount_modal"><i class="fa fa-plus"></i> Add PPMP Item</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div id="ppmp_subaccount_table_{{$rand}}_container" style="display: none">
                <table class="table table-bordered table-striped table-hover" id="ppmp_subaccount_table_{{$rand}}" style="width: 100% !important">
                    <thead>
                    <tr class="">
                        <th>Article</th>
                        <th>Details</th>
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
        //-----DATATABLES-----//
        //Initialize DataTable
        active_{{$rand}} = '';
        ppmp_subaccount_tbl_{{$rand}} = $("#ppmp_subaccount_table_{{$rand}}").DataTable({
            "ajax" : '{{route("dashboard.ppmp_subaccounts.index")}}?parentPpmp={{$ppmp->slug}}',
            "columns": [
                { "data": "article" },
                { "data": "cost" },
                { "data": "action" }
            ],
            "buttons": [
                {!! __js::dt_buttons() !!}
            ],
            "columnDefs":[

                {
                    "targets" : 1,
                    "class" : 'w-20p',
                },
                {
                    "targets" : 2,
                    "orderable" : false,
                    "class" : 'action4'
                },
            ],
            "responsive": false,
            'dom' : 'lBfrtip',
            "processing": true,
            "serverSide": true,
            "initComplete": function( settings, json ) {
                style_datatable("#"+settings.sTableId);
                $('#tbl_loader_{{$rand}}').fadeOut(function(){
                    $("#ppmp_subaccount_table_{{$rand}}_container").fadeIn();
                    if(find != ''){
                        ppmp_subaccount_tbl_{{$rand}}.search(find).draw();
                    }
                });
                //Need to press enter to search
                $('#'+settings.sTableId+'_filter input').unbind();
                $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                    if (e.keyCode == 13) {
                        ppmp_subaccount_tbl_{{$rand}}.search(this.value).draw();
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
                    if(Array.isArray(active_{{$rand}}) == true){
                        $.each(active_{{$rand}},function (i,item) {
                            $("#ppmp_subaccount_table_{{$rand}} #"+item).addClass('success');
                        })
                    }
                    $("#ppmp_subaccount_table_{{$rand}} #"+active_{{$rand}}).addClass('success');
                }
            }
        });

        $("body").on("click",".add_ppmp_subaccount_btn_{{$rand}}",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.ppmp_subaccounts.create")}}?parentPpmp={{$ppmp->slug}}&passed_rand={{$rand}}';
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
        })


        $("#ppmp_subaccount_table_{{$rand}}_container").on("click",".edit_ppmp_subaccount_btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.ppmp_subaccounts.edit","slug")}}?passed_rand={{$rand}}';
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
        })

    </script>
@endsection

