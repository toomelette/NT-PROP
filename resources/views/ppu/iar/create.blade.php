@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Inspection Acceptance Report</h1>
    </section>
@endsection
@section('content2')

    <section class="content col-md-12">

        <div role="document">
        <form id="add_form">

        <div class="box box-success">
            <div class="box-body">

                {!! \App\Swep\ViewHelpers\__form2::textbox('po_date',[
                   'label' => 'PO Date',
                   'cols' => 2,
                   'type' => 'date',
                ]) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                    'label' => 'PO No:',
                    'cols' => 3,
                 ]) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('invoice_date',[
                   'label' => 'Invoice Date',
                   'cols' => 2,
                   'type' => 'date',
                ]) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('invoice_number',[
                  'label' => 'Invoice No:',
                  'cols' => 3,
                  'id' => 'invoice_number',
               ]) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('date_inspected',[
                 'label' => 'Date Inspected:',
                 'cols' => 2,
                 'id' => 'date_inspected',
                 'type' => 'date',
              ]) !!}


            </div>
        </div>

        <div class="box box-success">
            <div class="box-body">

                {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_name',[
                  'label' => 'Supplier:',
                  'cols' => 3,
                  'id' => 'supplier_name'
               ]) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('resp_center',[
                   'label' => 'Requisitioning Office/Department',
                   'cols' => 3,
                   'id' => 'resp_center'
                ]) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('ref_no',[
                   'label' => 'PR/JR No:',
                   'cols' => 3,
                   'id' => 'ref_no'
                ]) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                   'label' => 'Requested by:',
                   'cols' => 3,
                   'id' => 'requested_by'
                ]) !!}



            </div>
        </div>

        <div class="box box-success">
            <div class="box-body">

                <div class="" id="tableContainer" style="margin-top: 50px">
                    <table class="table table-bordered table-striped table-hover hidden" id="trans_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th>Stock No.</th>
                            <th>Unit</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit Cost</th>
                            <th>Total Cost</th>
                            <th>Prop. No.</th>
                            <th>Nature of Work</th>
                            <th style="width: 3%"></th>
                        </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>


        <div class="pull-right">
            <button type="button" class="btn btn-primary" id="saveBtn">Save</button>

        </div>
        </form>
        </div>
    </section>
@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#add_form');
            let uri = '{{route("dashboard.iar.store")}}';
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
                    toast('success','IAR Successfully created.','Success!');
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
                            let link = "{{route('dashboard.iar.print','slug')}}";
                            link = link.replace('slug',res.slug);
                            window.open(link, '_blank');
                        }
                    })
                },
                error: function (res) {
                    toast('error',res.responseJSON.message,'Error!');
                }
            });


        });

        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        $('input[name="ref_number"]').unbind().bind('keyup', function(e) {
            if($('input[name="ref_number"]').val() === ''){
                toast('error','Reference Number cannot be empty','Invalid!');
            }
            else {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    let uri = '{{route("dashboard.iar.findTransByRefNumber", "refNumber") }}';
                    uri = uri.replace('refNumber',$(this).val());
                    $.ajax({
                        url : uri,
                        type: 'GET',
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success: function (res) {
                            console.log(res);

                         $("#supplier_name").val(res.order.supplier_name);
                         $("#resp_center").val(res.rc.department);
                         $("#ref_no").val(res.trans.ref_no);
                         $("#requested_by").val(res.trans.requested_by);

                            $('#trans_table tbody').remove();
                            let tableHtml = '<tbody>';
                            for(let i=0; i<res.transDetails.length; i++){
                                let stock = res.transDetails[i].stock_no;
                                stock = stock === null ? '' : stock;
                                let propNo = res.transDetails[i].property_no == null ? "" : res.transDetails[i].property_no;
                                let natureOfWork = res.transDetails[i].nature_of_work == null ? "" : res.transDetails[i].nature_of_work;
                                tableHtml += '<tr id='+res.transDetails[i].slug+'>' +
                                '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][stock_no]" name="items['+res.transDetails[i].slug+'][stock_no]" type="text" value="' + stock + '"></td>' +
                                '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][unit]" name="items['+res.transDetails[i].slug+'][unit]" type="text" value="' + res.transDetails[i].unit + '"></td>' +
                                '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][item]" name="items['+res.transDetails[i].slug+'][item]" type="text" value="' +  res.transDetails[i].item + '"></td>' +
                                '<td><textarea class="input-sm" id="items['+res.transDetails[i].slug+'][description]" name="items['+res.transDetails[i].slug+'][description]" type="text">'+ res.transDetails[i].description +'</textarea></td>' +
                                '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][qty]" name="items['+res.transDetails[i].slug+'][qty]" type="text" value="' + res.transDetails[i].qty + '"></td>' +
                                '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][unit_cost]" name="items['+res.transDetails[i].slug+'][unit_cost]" type="text" value="' + res.transDetails[i].unit_cost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '"></td>' +
                                '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][total_cost]" name="items['+res.transDetails[i].slug+'][total_cost]" type="text" value="' + res.transDetails[i].total_cost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '"></td>' +
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
                        }
                    })
                    }
                }
        });

    </script>
@endsection

