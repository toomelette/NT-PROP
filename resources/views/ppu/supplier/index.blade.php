@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Suppliers</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Suppliers</h3>
                <button class="btn btn-primary btn-sm pull-right" type="button" data-toggle="modal" data-target="#add_supplier_modal"><i class="fa fa-plus"></i> Add Supplier</button>
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
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="suppliers_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="suppliers_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>TIN</th>
                                    <th>Contact Person</th>
                                    <th>Phone Number</th>
                                    <th>Designation</th>
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
        {!! \App\Swep\ViewHelpers\__html::blank_modal('edit_modal','lg') !!}
    </section>

@endsection

@section('modals')
<div class="modal fade" id="add_supplier_modal" tabindex="-1" role="dialog" aria-labelledby="add_supplier_modal_label">
  <div class="modal-dialog modal-lg" role="document">
    <form id="add_supplier_form">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">New Supplier</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    {!! \App\Swep\ViewHelpers\__form2::textbox('name',[
                        'label' => 'Name:',
                        'cols' => 3,
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('address',[
                        'label' => 'Address:',
                        'cols' => 3,
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('office_contact_number',[
                        'label' => 'Office Tel/Phone Number:',
                        'cols' => 3,
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('tin',[
                        'label' => 'TIN:',
                        'cols' => 3,
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('contact_person',[
                        'label' => 'Contact Person:',
                        'cols' => 3,
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('contact_person_address',[
                        'label' => 'Address:',
                        'cols' => 3,
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('phone_number_1',[
                        'label' => 'Primary Phone Number:',
                        'cols' => 3,
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('phone_number_2',[
                        'label' => 'Secondary Phone Number:',
                        'cols' => 3,
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('fax_number',[
                        'label' => 'Fax Number:',
                        'cols' => 3,
                    ]) !!}
                    {!! \App\Swep\ViewHelpers\__form2::textbox('designation',[
                        'label' => 'Designation:',
                        'cols' => 3,
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
        let active;
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            suppliers_tbl = $("#suppliers_table").DataTable({
                "ajax" : '{{route("dashboard.supplier.index")}}',
                "columns": [
                    { "data": "name" },
                    { "data": "address" },
                    { "data": "office_contact_number" },
                    { "data": "tin" },
                    { "data": "contact_person" },
                    { "data": "phone_number_1" },
                    { "data": "designation" },
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
                        $("#suppliers_table_container").fadeIn();
                        if(find != ''){
                            suppliers_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            suppliers_tbl.search(this.value).draw();
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
                                $("#suppliers_table #"+item).addClass('success');
                            })
                        }
                        $("#suppliers_table #"+active).addClass('success');
                    }
                }
            });
        })
        

        $("#add_supplier_form").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.supplier.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    active = res.id;
                    suppliers_tbl.draw(false);
                    succeed(form,true,false);
                    toast('success','Supplier successfully added.','Success!');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })

        $("body").on("click",".edit_btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.supplier.edit","slug")}}';
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
        })
    </script>
@endsection