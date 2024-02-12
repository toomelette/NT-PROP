@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Create Inventory Custodian Slip</h1>
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
                'fullname' => $data->firstname.' '.$data->lastname,
                'position' => $data->position,
            ];
        })->toJson();
    @endphp

    <section class="content">
        <div role="document">
            <form id="add_form">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
{{--                            {!! \App\Swep\ViewHelpers\__form2::textbox('ref_no',[--}}
{{--                                        'label' => 'ICS No:',--}}
{{--                                        'cols' => 3,--}}
{{--                                    ]) !!}--}}
                            {!! \App\Swep\ViewHelpers\__form2::select('account_code',[
                                    'label' => 'Account Code:',
                                    'cols' => 3,
                                    'options' => \App\Swep\Helpers\Arrays::inventoryAccountCode(),
                                    'id' => 'inventory-account-code',
                                ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('fund_cluster',[
                                                                'label' => 'Fund Cluster:',
                                                                'cols' => 3,
                                                                'options' => \App\Swep\Helpers\Arrays::fundSources(),
                                                            ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('invoice_number',[
                              'label' => 'Invoice No:',
                              'cols' => 3,
                              'id' => 'invoice_number',
                           ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('invoice_date',[
                               'label' => 'Invoice Date:',
                               'cols' => 3,
                               'type' => 'date',
                            ]) !!}
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                                'label' => 'From:',
                                'cols' => 4,
                                ],
                                'NOLI T. TINGSON') !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                                'label' => 'Designation:',
                                'cols' => 4,
                                ],
                                'Supply Officer IV') !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                                    'label' => 'Prepared Date:',
                                    'cols' => 4,
                                    'type' => 'date'
                                 ]) !!}


                            {!! \App\Swep\ViewHelpers\__form2::select('requested_by',[
                                       'label' => 'To:',
                                       'cols' => 4,
                                       'options' => [],
                                       'id' => 'requested_by',
                                   ]) !!}
{{--                            {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[--}}
{{--                                'label' => 'To:',--}}
{{--                                'cols' => 4,--}}
{{--                                ]) !!}--}}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                                        'label' => 'Designation:',
                                        'cols' => 4,
                                        'options' => [],
                                    ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('received_at',[
                                    'label' => 'Received Date:',
                                    'cols' => 4,
                                    'type' => 'date'
                                 ]) !!}
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('iar_no',[
                                       'label' => 'IAR Reference No:',
                                       'cols' => 3,
                                       'id' => 'iar_no'
                                   ]) !!}

                            <div class="" id="tableContainer" style="margin-top: 50px">
                                <div class="col-md-12">
                                    <button data-target="#trans_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=ics_items" style="margin-bottom: 5px; margin-top: 5px; margin-right: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>

                                    <table class="table table-bordered table-striped table-hover" id="trans_table" style="width: 100% !important">
                                        <thead>
                                        <tr class="">
                                            <th>Stock No.</th>
                                            <th>Unit</th>
                                            <th>Item</th>
                                            <th>Description</th>
                                            <th>Qty</th>
                                            <th>Unit Cost</th>
                                            <th>Total Cost</th>
                                            <th>Useful Life</th>
                                            <th>Prop. No.</th>
                                            <th>Nature of Work</th>
                                            <th style="width: 3%"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @include('dynamic_rows.ics_items')
                                        </tbody>
                                    </table>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">

        var data = {!!$employeesCollection!!};


        function deleteRow(button) {
            const row = button.closest('tr');
            if (row) {
                row.remove();
            }
        }

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#add_form');
            let uri = '{{route("dashboard.ics.store")}}';
            loading_btn(form);

            $.ajax({
                url : uri,
                data: form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    console.log(res);
                    toast('success','ICS Successfully created.','Success!');
                    succeed(form,true,true);
                    Swal.fire({
                        title: 'Successfully created',
                        icon: 'success',
                        html:
                            'Click the print button below to print.',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText:
                            '<i class="fa fa-print"></i> Print',
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                        cancelButtonText:
                            'Dismiss',
                        cancelButtonAriaLabel: 'Thumbs down'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let link = "{{route('dashboard.ics.print','slug')}}";
                            link = link.replace('slug',res.slug);
                            window.open(link, '_blank');
                        }
                    });
                    form.reset();
                },
                error: function (res) {
                    toast('error',res.responseJSON.message,'Error!');
                }
            });
        });

        $(function(){
            $('#iar_no').keypress(function (event){
                if (event.keyCode === 13) {
                    let uri = '{{route("dashboard.ics.findIAR", 'refNumber') }}';
                    uri = uri.replace('refNumber',$(this).val());
                    $.ajax({
                        url : uri,
                        type: 'GET',
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success: function (res) {
                            console.log(res);
                            let tableHtml = '<tbody>';
                            for(let i=0; i<res.transDetails.length; i++){
                                let stock = res.transDetails[i].stock_no;
                                stock = stock === null ? '' : stock;
                                let propNo = res.transDetails[i].property_no == null ? "" : res.transDetails[i].property_no;
                                let natureOfWork = res.transDetails[i].nature_of_work == null ? "" : res.transDetails[i].nature_of_work;
                                tableHtml += '<tr id='+res.transDetails[i].slug+'>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][stock_no]" name="items['+res.transDetails[i].slug+'][stock_no]" type="text" value="' + stock + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][unit]" name="items['+res.transDetails[i].slug+'][unit]" type="text" value="' + res.transDetails[i].unit + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][itemName]" name="items['+res.transDetails[i].slug+'][itemName]" type="text" value="' +  res.transDetails[i].item + '"></td>' +
                                    '<td><textarea class="input-sm" id="items['+res.transDetails[i].slug+'][description]" name="items['+res.transDetails[i].slug+'][description]" type="text">'+  res.transDetails[i].description +'</textarea></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][qty]" name="items['+res.transDetails[i].slug+'][qty]" type="text" value="' + res.transDetails[i].qty + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][unit_cost]" name="items['+res.transDetails[i].slug+'][unit_cost]" type="text" value="' + res.transDetails[i].unit_cost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][total_cost]" name="items['+res.transDetails[i].slug+'][total_cost]" type="text" value="' + res.transDetails[i].total_cost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][estimated_useful_life]" name="items['+res.transDetails[i].slug+'][estimated_useful_life]" type="text" value="' +  res.transDetails[i].estimated_useful_life + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][property_no]" name="items['+res.transDetails[i].slug+'][property_no]" type="text" value="' + propNo + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][nature_of_work]" name="items['+res.transDetails[i].slug+'][nature_of_work]" type="text" value="' + natureOfWork + '"></td>' +
                                    '<td><button type=\'button\' class=\'btn btn-danger btn-sm delete-btn\' data-slug='+res.transDetails[i].slug+' onclick="deleteRow(this)"><i class=\'fa fa-times\'></i></button></td>' +
                                    '</tr>';
                            }
                            tableHtml += '</tbody>';
                            $('#trans_table').append(tableHtml).removeClass('hidden');
                        },
                        error: function (res) {
                            toast('error',res.responseJSON.message,'Error!');
                            console.log(res);
                        }
                    })
                }
            });
        });

        $(".select2_item").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","articles")}}',
                dataType: 'json',
                delay : 250,
            },
            placeholder: 'Select item',
        });

        $('.select2_item').on('select2:select', function (e) {
            let t = $(this);
            let parentTrId = t.parents('tr').attr('id');
            let data = e.params.data;

            $("#"+parentTrId+" [for='stock_no']").val(data.id);
            $("#"+parentTrId+" [for='uom']").val(data.populate.uom);
            $("#"+parentTrId+" [for='itemName']").val(data.text);
        });

        $("#requested_by").select2({
            data : data,
        });

        $("#requested_by").change(function (){
            let value = $(this).val();
            if(value != ''){
                let index = data.findIndex( object => {
                    return object.id == value;
                });
                // $("input[name='acctemployee_no']").val(data[index].employee_no);
                // $("input[name='acctemployee_fname']").val(data[index].fullname);
                $("input[name='requested_by_designation']").val(data[index].position);
            }else{
                // $("input[name='acctemployee_no']").val('');
                // $("input[name='acctemployee_fname']").val('');
                $("input[name='requested_by_designation']").val('');
            }
        });


        $("#inventory-account-code").select2();
    </script>
@endsection