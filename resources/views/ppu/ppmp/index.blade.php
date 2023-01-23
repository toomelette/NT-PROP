@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>PPMP</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">PPMP</h3>
                <button class="btn btn-primary btn-sm pull-right" type="button" data-toggle="modal" data-target="#add_ppmp_modal"><i class="fa fa-plus"></i> Add PPMP item</button>
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
                        <div id="ppmp_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="ppmp_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>Dept</th>
                                    <th>Div/Sec</th>
                                    <th>PAP Code</th>
                                    <th>Article</th>
                                    <th>Details</th>
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
    {!! \App\Swep\ViewHelpers\__html::blank_modal('subaccount_modal','65') !!}
    <div class="modal fade" id="add_ppmp_modal" tabindex="-1" role="dialog" aria-labelledby="add_ppmp_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="add_ppmp_form">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Add PPMP Item</h4>
          </div>
          <div class="modal-body">
              <div class="well well-sm">
                  <div class="row">
                      <div class="col-md-2">
                          <p class="no-margin">Year:</p>
                          <p class="text-strong" for="year"></p>
                      </div>
                      <div class="col-md-3">
                          <p class="no-margin">PAP Code:</p>
                          <p class="text-strong" for="pap_code"></p>
                      </div>
                      <div class="col-md-7">
                          <p class="no-margin">PAP Title:</p>
                          <p class="text-strong" for="pap_title"></p>
                      </div>

                  </div>
              </div>
              <hr style="border: 1px dashed #1b7e5a; margin-top: 3px;margin-bottom: 10px">

              <div class="row">
                  {!! \App\Swep\ViewHelpers\__form2::select('papCode',[
                        'cols' => 7,
                        'label' => 'PAP Code:',
                        'options' => [],
                        'class' => 'select2_papCode',
                    ]
                    ) !!}

                  {!! \App\Swep\ViewHelpers\__form2::select('sourceOfFund',[
                      'cols' => 5,
                      'label' => 'Source of Fund',
                      'options' => \App\Swep\Helpers\Helper::fundSources(),
                  ]
                  ) !!}
              </div>
              <div class="row">
                  {!! \App\Swep\ViewHelpers\__form2::select('stockNo',[
                      'cols' => 12,
                      'label' => 'General Description',
                      'class' => 'select2_article',
                      'autocomplete' => 'off',
                      'options' => [],
                  ]
                  ) !!}
              </div>
              <div class="row">
                  {!! \App\Swep\ViewHelpers\__form2::textbox('unitCost',[
                      'cols' => 4,
                      'label' => 'Unit Cost:',
                      'class' => 'text-right autonum unit_cost unit_costXqty',
                      'autocomplete' => 'off',
                  ]
                  ) !!}
                  {!! \App\Swep\ViewHelpers\__form2::textbox('qty',[
                      'cols' => 4,
                      'label' => 'Quantity:',
                      'type' => 'number',
                      'class' => 'text-right qty unit_costXqty',
                  ]) !!}
                  {!! \App\Swep\ViewHelpers\__form2::select('uom',[
                      'cols' => 4,
                      'label' => 'Unit:',
                      'options' => \App\Swep\Helpers\PPUHelpers::ppmpSizes(),
                      'readonly' => 'readonly',
                  ]) !!}
              </div>
              <div class="row">
                  {!! \App\Swep\ViewHelpers\__form2::textbox('estTotalCost',[
                      'id' => 'total_est_budget',
                      'cols' => 4,
                      'label' => 'Total estimated budget:',
                      'class' => 'total_est_budget',
                      'readonly' => 'readonly',
                  ]) !!}


                  {!! \App\Swep\ViewHelpers\__form2::select('modeOfProc',[
                      'cols' => 4,
                      'label' => 'Mode of Procurement',
                      'options' => \App\Swep\Helpers\Helper::modesOfProcurement(),
                      'readonly' => 'readonly',
                  ]) !!}
                  {!! \App\Swep\ViewHelpers\__form2::select('budgetType',[
                      'label' => 'Budget type:*',
                      'cols' => 4,
                      'options' => \App\Swep\Helpers\Helper::budgetTypes(),
                      'readonly' => 'readonly',
                  ]) !!}
              </div>

              <div class="row">
                  <div class="col-md-12">
                      <label>Schedule/Milestone of Activities: (Must be a number)</label>
                      <table class="milestone" style="width: 100%;">
                          <tr class="text-center">
                              @foreach(\App\Swep\Helpers\Helper::milestones() as $month)
                                  <td>{{$month}}</td>
                              @endforeach
                          </tr>
                          <tr>
                              @foreach(\App\Swep\Helpers\Helper::milestones() as $month)
                                  <td>
                                      @php($column = 'qty_'.strtolower($month))
                                      <input type="text" class="no-style-input qty_{{strtolower($month)}}"  value="" name="qty_{{strtolower($month)}}" autocomplete="off">
                                  </td>
                              @endforeach
                          </tr>
                      </table>
                      <br>
                  </div>

              </div>
              <div class="row">
                  {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
                      'cols' => 12,
                      'label' => 'Remark (brief description of the Program/Project):',
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




    {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_ppmp_modal','') !!}
    {!! \App\Swep\ViewHelpers\__html::blank_modal('add_ppmp_subaccount_modal','') !!}

<div class="modal fade" id="add_article_modal" tabindex="-1" role="dialog" aria-labelledby="add_article_modal_label">
    <div class="modal-dialog modal-sm" role="document">
        <form id="add_article_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add article</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('article',[
                            'label' => 'Article name:',
                            'cols' => 12,
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
@endsection

@section('scripts')
    <script type="text/javascript">
        $(".dt_filter").change(function () {
            filterDT(ppmp_tbl);
        })
    </script>
    <script type="text/javascript">
        var active;
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            ppmp_tbl = $("#ppmp_table").DataTable({
                "ajax" : '{{route("dashboard.ppmp.index")}}',
                "columns": [
                    { "data": "dept" },
                    { "data": "div" },
                    { "data": "papCode" },
                    { "data": "article" },
                    { "data": "cost" },
                    { "data": "action" }
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],
                "columnDefs":[
                    {
                        "targets" : [0,2],
                        "class" : 'w-8p'
                    },
                    {
                        "targets" : 1,
                        "class" : 'w-16p'
                    },
                    {
                        "targets" : 4,
                        "class" : 'w-20p',
                    },
                    {
                        "targets" : 5,
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
                    $('#tbl_loader').fadeOut(function(){
                        $("#ppmp_table_container").fadeIn();
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
                                $("#ppmp_table #"+item).addClass('success');
                            })
                        }
                        $("#ppmp_table #"+active).addClass('success');
                    }
                }
            });

            $(".add_row_btn").trigger('click');

        })

        $(".select2_papCode").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","pap_codes")}}',
                dataType: 'json',
                delay : 250,

            },
            dropdownParent: $('#add_ppmp_modal'),
            placeholder: 'Type PAP Code/Title/Description',
        });
        $(".select2_article").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","articles")}}',
                dataType: 'json',
                delay : 250,
            },
            dropdownParent: $('#add_ppmp_modal'),
            placeholder: 'Select item',
            language : {
                "noResults": function(){

                    return "No item found. Click <button type='button' data-target='#add_article_modal' data-toggle='modal' class='btn btn-success btn-xs add'>Add item</button> to add your desired item to the database.";
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        });
        $('.select2_article').on('select2:select', function (e) {
            let data = e.params.data;
            $.each(data.populate,function (i, item) {
                $("#add_ppmp_modal select[name='"+i+"']").val(item).trigger('change');
                $("#add_ppmp_modal input[name='"+i+"']").val(item).trigger('change');
            })
        });
        $('.select2_papCode').on('select2:select', function (e) {
            let data = e.params.data;
            $("#add_ppmp_modal p[for='year']").html(data.year);
            $("#add_ppmp_modal p[for='pap_code']").html(data.pap_code);
            $("#add_ppmp_modal p[for='pap_title']").html(data.pap_title);
        });
        $("body").on("change",".unit_cost",function () {
            let t = $(this);
            let parentModalId = t.parents('.modal').attr('id');
            let val = parseFloat($(this).val().replaceAll(',',''));
            if(val < 50000){
                $("#"+parentModalId+" [name='budgetType']").val('MOOE');
            }else{
                $("#"+parentModalId+" [name='budgetType']").val('CO');
            }
        });

        $("body").on("change",".unit_costXqty",function () {
            let t = $(this);
            let parentModalId = t.parents('.modal').attr('id');
            let unitCost = parseFloat($("#"+parentModalId+" [name='unitCost']").val().replaceAll(',',''));
            let qty = parseFloat($("#"+parentModalId+" [name='qty']").val());
            $("#"+parentModalId+" [name='estTotalCost']").val($.number(unitCost *qty,2));
        })

        $("#add_article_form").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.ppmp.index")}}?with=addArticle',
                data : form.serialize(),
                type: 'GET',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,true);
                    toast('success','You may now search the article using the General Description field.','Article added successfully');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })
        $("#add_ppmp_form").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.ppmp.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    active = res.slug;
                    ppmp_tbl.draw(false);
                    succeed(form,true,false);
                    toast('success','PPMP Item successfully added.','Success');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })


        $("body").on("click",".edit_ppmp_btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.ppmp.edit","slug")}}';
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

        $("#filter_form [name='dept']").change(function () {
            let t = $(this);
            $("#filter_form select[name='div'] optgroup").each(function () {
                $(this).show();
            })
            $("#filter_form select[name='sec'] optgroup").each(function () {
                $(this).show();
            })
            if(t.val() !== ''){
                $("#filter_form [name='div'] optgroup:not([label='"+t.val()+"'])").each(function () {
                    $(this).hide();
                })
                $("#filter_form [name='sec'] optgroup:not([dept='"+t.val()+"'])").each(function () {
                    $(this).hide();
                })
            }
        })

        $("#filter_form [name='div']").change(function () {
            let t = $(this);
            let d = $("#filter_form [name='dept']");
            $("#filter_form select[name='sec'] optgroup").each(function () {
                $(this).show();
            })
            if(t.val() !== ''){
                $("#filter_form [name='sec'] optgroup:not([div='"+t.val()+"'])").each(function () {
                    $(this).hide();
                })
            }
        })
        
        $("body").on("click",".subaccount_btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.ppmp_subaccounts.index")}}';
            uri = uri.replace('slug',btn.attr('data'));
            $.ajax({
                url : uri,
                data : {slug: btn.attr('data')},
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