@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Property Acknowledgement Receipt</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Property Acknowledgement Receipt</h3>
                <a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#add_modal"> <i class="fa fa-plus"></i> Create</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="par_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="par_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>Property No</th>
                                    <th>Article</th>
                                    <th>Description</th>
                                    <th>UOM</th>
                                    <th>Qty Onhand</th>
                                    <th>Acquired Cost</th>
                                    <th>Date Acquired</th>
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
    <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="add_modal_label">
        <div class="modal-dialog modal-lg" role="document">
            <form id="add_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Create Property Acknowledgement Receipt</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('dateacquired',[
                                        'label' => 'Date Acquired:',
                                        'cols' => 4,
                                        'type' => 'date'
                                     ],
                                    $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('sub_major_account_group',[
                                                            'label' => 'Sub-Major Acct. Group:',
                                                            'cols' => 4,
                                                            'options' => \App\Swep\Helpers\Arrays::subMajorAccountGroup(),
                                                        ],
                                                        $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('general_ledger_account',[
                                                            'label' => 'General Ledger Account:',
                                                            'cols' => 4,
                                                            'options' => \App\Swep\Helpers\Arrays::generalLedgerAccount(),
                                                        ],
                                                        $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('location',[
                                                            'label' => 'Location:',
                                                            'cols' => 4,
                                                            'options' => \App\Swep\Helpers\Arrays::location(),
                                                        ],
                                                        $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('serialno',[
                                                            'label' => 'Serial No.:',
                                                            'cols' => 4,
                                                            'readonly' => 'readonly'
                                                            ],
                                                        $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('propertyno',[
                                                            'label' => 'Property No.:',
                                                            'cols' => 4,
                                                            'readonly' => 'readonly'
                                                            ],
                                                        $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('fund_cluster',[
                                                            'label' => 'Fund Cluster:',
                                                            'cols' => 4,
                                                            'options' => \App\Swep\Helpers\Arrays::fundSources(),
                                                        ],
                                                        $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('respcenter',[
                                'label' => 'Resp. Center:',
                                'cols' => 8,
                                'options' => \App\Swep\Helpers\PPUHelpers::respCentersArray(),
                            ],
                            $par ?? null) !!}
                            <div class="form-group col-md-12 employee_name ">
                                <label for="employee_name">Search Employee:*</label>
                                <input autocomplete="off" class="form-control " id="employee_name" name="employee_name" type="text" value="" placeholder="Name of employee"><ul class="typeahead dropdown-menu"></ul>
                            </div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('acctemployee_fname',[
                                'label' => 'Acct. Officer:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('acctemployee_post',[
                                'label' => 'Position:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('acctemployee_no',[
                                'label' => 'Emp. No.:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('article',[
                                  'cols' => 6,
                                  'label' => 'Article:',
                                  'class' => 'select2_article',
                                  'autocomplete' => 'off',
                                  'options' => [],
                              ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textarea('description',[
                                  'cols' => 6,
                                  'label' => 'Description: ',
                                  'rows' => 2
                                ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('uom',[
                                'label' => 'Unit:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('acquiredcost',[
                                'label' => 'Acquired Cost:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('qtypercard',[
                                'label' => 'Qty Per Card:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('onhandqty',[
                                'label' => 'Qty Onhand:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('shortqty',[
                                'label' => 'Short Qty:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('shortvalue',[
                                'label' => 'Short Value:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
                                'label' => 'Remarks:',
                                'cols' => 8,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('supplier',[
                                'label' => 'Supplier:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('invoiceno',[
                                'label' => 'Invoice No.:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('invoicedate',[
                                        'label' => 'Invoice Date:',
                                        'cols' => 4,
                                        'type' => 'date'
                                     ],
                                    $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('pono',[
                                'label' => 'P.O. No.:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('podate',[
                                        'label' => 'P.O. Date:',
                                        'cols' => 4,
                                        'type' => 'date'
                                     ],
                                    $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('invtacctcode',[
                                'label' => 'Inv. Account Code:',
                                'cols' => 4,
                                ],
                            $par ?? null) !!}

                            {!! \App\Swep\ViewHelpers\__form2::select('acquiredmode',[
                                'label' => 'Acquisition Mode:',
                                'cols' => 4,
                                'options' => \App\Swep\Helpers\Arrays::acquisitionMode(),
                            ],
                            $par ?? null) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_modal','lg') !!}
@endsection

@section('scripts')
    <script type="text/javascript">
        let active;
        $(document).ready(function () {
            $("#add_form").submit(function (e) {
                e.preventDefault()
                let form = $(this);
                loading_btn(form);
                $.ajax({
                    url : '{{route("dashboard.par.store")}}',
                    data : form.serialize(),
                    type: 'POST',
                    headers: {
                        {!! __html::token_header() !!}
                    },
                    success: function (res) {
                        active = res.id;
                        par_tbl.draw(false);
                        succeed(form,true,false);
                        toast('success','PAR successfully added.','Success!');
                    },
                    error: function (res) {
                        errored(form,res);
                    }
                })
            })

            $(".select2_article").select2({
                ajax: {
                    url: '{{route("dashboard.ajax.get","articles")}}',
                    dataType: 'json',
                    delay : 250,
                },
                dropdownParent: $('#add_modal'),
                placeholder: 'Select item',
                language : {
                    "noResults": function(){

                        return "No item found.";
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });

            $('.select2_article').on('select2:select', function (e) {
                let data = e.params.data;
                $.each(data.populate,function (i, item) {
                    $("#add_modal select[name='"+i+"']").val(item).trigger('change');
                    $("#add_modal input[name='"+i+"']").val(item).trigger('change');
                })
            });

            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            par_tbl = $("#par_table").DataTable({
                "ajax" : '{{route("dashboard.par.index")}}',
                "columns": [
                    { "data": "propertyno" },
                    { "data": "article" },
                    { "data": "description" },
                    { "data": "uom" },
                    { "data": "onhandqty" },
                    { "data": "acquiredcost" },
                    { "data": "dateacquired" },
                    { "data": "action" }
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],
                "responsive": false,
                'dom' : 'lBfrtip',
                "processing": true,
                "serverSide": true,
                "initComplete": function( settings, json ) {
                    style_datatable("#"+settings.sTableId);
                    $('#tbl_loader').fadeOut(function(){
                        $("#par_table_container").fadeIn();
                        if(find != ''){
                            par_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            par_tbl.search(this.value).draw();
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
                                $("#par_table #"+item).addClass('success');
                            })
                        }
                        $("#par_table #"+active).addClass('success');
                    }
                }
            });

            $("body").on("click",".edit_btn",function () {
                let btn = $(this);
                load_modal2(btn);
                let uri = '{{route("dashboard.par.edit","slug")}}';
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
                        console.log(res);
                        populate_modal2_error(res);
                    }
                })
            });
        })
    </script>
@endsection