@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Inventory Custodian Slip</h1>
    </section>
@endsection
@section('content2')
    @php
        $employees = \App\Models\Employee::query()
        ->where(function ($query) {
            $query->where('locations', '=', 'VISAYAS')
                ->orWhere('locations', '=', 'LUZON/MINDANAO');
        })
        ->where('is_active', '=', 'ACTIVE')
        ->orderBy('fullname', 'asc')
        ->get();

       $employeesCollection = $employees->map(function ($data){
            return [
                'id' => $data->employee_no,
                'text' => $data->firstname.' '.$data->lastname.' - '.$data->employee_no,
                'employee_no' => $data->employee_no,
                'firstname' =>  $data->firstname,
                'middlename' =>  $data->middlename,
                'lastname' =>  $data->lastname,
                'fullname' => $data->firstname.' '.$data->lastname,
                'position' => $data->position,
            ];
        })->toJson();
    @endphp
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Manage Inventory Custodian Slip</h3>
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#ics-by-employee"><i class="fa fa-print"></i> ICS by Employee</button>
                    <a class="btn btn-primary btn-sm" href="{{route('dashboard.ics.create')}}" > <i class="fa fa-plus"></i> Create</a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id="ics_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="ics_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>ICS Number</th>
                                    <th>Entity Name</th>
                                    <th>PO No.</th>
                                    <th>Invoice No.</th>
                                    <th>Account Code</th>
                                    <th>Fund Cluster</th>
                                    <th>Total</th>
                                    <th>Date</th>
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
    <div class="modal fade" id="ics-by-employee" aria-labelledby="ics-by-employee">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form id="ics-by-employee-form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">ICS by Employee</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::select('select-employee',[
                                    'label' => 'Employee:',
                                    'cols' => 12,
                                    'options' => [],
                                    'id' => 'select-employee',
                                ]) !!}
                            <div class="hidden">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('employee',[
                                'label' => '',
                                'cols' => 12,
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        let active;
        var data = {!!$employeesCollection!!};
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            ics_tbl = $("#ics_table").DataTable({
                "ajax" : '{{route("dashboard.ics.index")}}',
                "columns": [
                    { "data": "ref_no" },
                    { "data": "requested_by" },
                    { "data": "po_number" },
                    { "data": "invoice_number" },
                    { "data": "account_code" },
                    { "data": "fund_cluster" },
                    { "data": "abc" },
                    { "data": "date" },
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
                        $("#ics_table_container").fadeIn();
                        if(find != ''){
                            ics_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            ics_tbl.search(this.value).draw();
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
                                $("#ics_table #"+item).addClass('success');
                            })
                        }
                        $("#ics_table #"+active).addClass('success');
                    }
                }

            });

            $("#select-employee").select2({
                data : data,
            });

            $("#select-employee").change(function (){
                let value = $(this).val();
                if(value != ''){
                    let index = data.findIndex( object => {
                        return object.id == value;
                    });
                    var middleName = data[index].middlename;
                    var middleInitial = middleName != null ? middleName.charAt(0) : "";
                    var mI = middleInitial != "" ? middleInitial + '. ' : "";
                    $("input[name='acctemployee_fname']").val(data[index].firstname +' '+ mI + data[index].lastname);
                }else{
                    $("input[name='acctemployee_fname']").val('');
                }
            });

            $("#ics-by-employee-form").submit(function (e){
                e.preventDefault();
                let url = '{{route("dashboard.ics.index")}}?ics_by_employee=true&';
                let form = $(this);
                window.open(url+form.serialize(), '_blank');
            })
        })
    </script>
@endsection