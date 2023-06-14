@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Articles</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Articles</h3>
                <button class="btn btn-primary btn-sm pull-right" type="button" data-toggle="modal" data-target="#add_article_modal"><i class="fa fa-plus"></i> Add Article</button>
            </div>
            <div class="box-body">
                <div class="panel">
                    <div class="box box-sm box-default box-solid collapsed-box">
                        <div class="box-header with-border filter-box">
                            <p class="no-margin"><i class="fa fa-filter"></i> Advanced Filters <small id="filter-notifier" class="label bg-blue blink"></small></p>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool advanced_filters_toggler" data-widget="collapse"><i class="fa fa-plus"></i>
                                </button>
                            </div>

                        </div>

                        <div class="box-body" style="display: none">
                            <form id="filter_form">
                                <div class="row">
                                    {!! \App\Swep\ViewHelpers\__form2::select('dept',[
                                        'cols' => 2,
                                        'label' => 'Department:',
                                        'container_class' => 'dt_filter-parent-div',
                                        'class' => 'dt_filter filters',
                                        'options' => \App\Swep\Helpers\Arrays::departments(),
                                    ]) !!}

                                    <div class="form-group dt_filter-parent-div col-md-2 div">
                                        <label for="div">Division:</label>
                                        <select name="div" class="form-control dt_filter filters">
                                            <option value="" selected="">Select</option>
                                            @if(count(\App\Swep\Helpers\Arrays::groupedDivisions()) > 0)
                                                @foreach(\App\Swep\Helpers\Arrays::groupedDivisions() as $dept => $div)
                                                    <optgroup label="{{$dept}}">
                                                        @if(is_array($div))
                                                            @foreach($div as $item)
                                                                <option dept="{{$dept}}" value="{{$item}}">{{$item}}</option>
                                                            @endforeach
                                                        @endif
                                                    </optgroup>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group dt_filter-parent-div col-md-2 div">
                                        <label for="div">Section:</label>
                                        <select name="sec" class="form-control dt_filter filters">
                                            <option value="" selected="">Select</option>
                                            @if(count(\App\Swep\Helpers\Arrays::groupedSections()) > 0)
                                                @foreach(\App\Swep\Helpers\Arrays::groupedSections() as $deptDiv => $sec)
                                                    <optgroup label="{{$deptDiv}}" dept="{{explode(' | ',$deptDiv)[0]}}" div="{{explode(' | ',$deptDiv)[1]}}">
                                                        @if(is_array($sec))
                                                            @foreach($sec as $item)
                                                                <option  value="{{$item}}">{{$item}}</option>
                                                            @endforeach
                                                        @endif
                                                    </optgroup>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>


                                    {!! \App\Swep\ViewHelpers\__form2::select('budgetType',[
                                        'cols' => 2,
                                        'label' => 'Budget type:',
                                        'container_class' => 'dt_filter-parent-div',
                                        'class' => 'dt_filter filters',
                                        'options' => \App\Swep\Helpers\Arrays::budgetTypes(),
                                    ]) !!}
                                    {!! \App\Swep\ViewHelpers\__form2::select('modeOfProc',[
                                        'cols' => 2,
                                        'label' => 'Mode of Procurement:',
                                        'container_class' => 'dt_filter-parent-div',
                                        'class' => 'dt_filter filters',
                                        'options' => \App\Swep\Helpers\Arrays::modesOfProcurement(),
                                    ]) !!}
                                    <div class="col-md-2 dt_filter-parent-div">
                                        <label>Location:</label>
                                        <select name="locations" class="form-control dt_filter filter_locations filters select22">
                                            <option value="">Don't filter</option>
                                            <option value="VISAYAS">VISAYAS</option><option value="LUZON/MINDANAO">LUZON/MINDANAO</option><option value="COS-VISAYAS">COS (VISAYAS)</option><option value="COS-LUZMIN">COS (LUZ/MIN)</option><option value="RETIREE">RETIREE</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="articles_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="articles_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>Article</th>
                                    <th>Stock No.</th>
                                    <th>UOM</th>
                                    <th>Unit Price</th>
                                    <th>Type</th>
                                    <th>Mode of Proc.</th>
                                    <th>Acct. Code</th>
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
            </div>
        </div>
    </section>


@endsection


@section('modals')
<div class="modal fade" id="add_article_modal" tabindex="-1" role="dialog" aria-labelledby="add_article_modal_label">
  <div class="modal-dialog" role="document">
    <form id="add_article_form">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">New article</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    {!! \App\Swep\ViewHelpers\__form2::textbox('article',[
                        'label' => 'Article:',
                        'cols' => 7,
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::select('type',[
                        'label' => 'Type:',
                        'cols' => 5,
                        'options' => \App\Swep\Helpers\Arrays::inventoryTypes(),
                    ]) !!}
                </div>
                <div class="row">
                    {!! \App\Swep\ViewHelpers\__form2::textbox('unitPrice',[
                        'label' => 'Unit Price:',
                        'cols' => 4,
                        'class' => 'text-right autonum',
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::select('uom',[
                        'label' => 'Unit:',
                        'cols' => 3,
                        'options' => \App\Swep\Helpers\Arrays::unitsOfMeasurement(),
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::select('modeOfProc',[
                        'label' => 'Mode of Proc:',
                        'cols' => 5,
                        'options' => \App\Swep\Helpers\Arrays::modesOfProcurement(),
                    ]) !!}
                </div>

                <div class="row">
                    {!! \App\Swep\ViewHelpers\__form2::textbox('acctCode',[
                            'label' => 'Acct. Code:',
                            'cols' => 4,
                            'class' => '',
                        ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('estimated_useful_life',[
                        'label' => 'Estimated Useful Life:',
                        'cols' => 4,
                    ]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </form>
  </div>
</div>
    {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_article_modal','') !!}
@endsection

@section('scripts')
    <script type="text/javascript">
        var active;
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            articles_tbl = $("#articles_table").DataTable({
                "ajax" : '{{route("dashboard.articles.index")}}',
                "columns": [
                    { "data": "article" },
                    { "data": "stockNo" },
                    { "data": "uom" },
                    { "data": "unitPrice" },
                    { "data": "type" },
                    { "data": "modeOfProc" },
                    { "data": "acctCode" },
                    { "data": "action" }
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],
                "columnDefs":[
                    {
                        "targets" : [1,2],
                        "class" : 'w-8p'
                    },

                    {
                        "targets" : 3,
                        "class" : 'text-right',
                    },
                    {
                        "targets" : 7,
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
                        $("#articles_table_container").fadeIn();
                        if(find != ''){
                            articles_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            articles_tbl.search(this.value).draw();
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
                                $("#articles_table #"+item).addClass('success');
                            })
                        }
                        $("#articles_table #"+active).addClass('success');
                    }
                }
            });

            $(".add_row_btn").trigger('click');

        })
        

        $("#add_article_form").submit(function (e) {
            e.preventDefault()
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.articles.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    active = res.id;
                    articles_tbl.draw(false);
                    succeed(form,true,false);
                    toast('success','Article successfully added.','Success!');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })

        $("body").on("click",".edit_article_btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.articles.edit","slug")}}';
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