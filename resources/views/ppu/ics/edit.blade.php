@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Edit Inventory Custodian Slip</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <form id="edit_form">
            <div class="box box-success">
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                        <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                    </div>
                    <div class="row">
                        <input type="hidden" name="slug" id="slug" value="{{$trans->slug}}">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('ref_no',[
                                    'label' => 'ICS No:',
                                    'cols' => 3,
                                ],
                                $trans ?? null) !!}
                        {!! \App\Swep\ViewHelpers\__form2::select('account_code',[
                                'label' => 'Account Code:',
                                'cols' => 3,
                                'options' => \App\Swep\Helpers\Arrays::inventoryAccountCode(),
                                'id' => 'inventory-account-code',
                            ],
                                $trans ?? null) !!}
                        {!! \App\Swep\ViewHelpers\__form2::select('fund_cluster',[
                                                            'label' => 'Fund Cluster:',
                                                            'cols' => 3,
                                                            'options' => \App\Swep\Helpers\Arrays::fundSources(),
                                                        ],
                                $trans ?? null) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('invoice_number',[
                          'label' => 'Invoice No:',
                          'cols' => 3,
                          'id' => 'invoice_number',
                       ],
                                $trans ?? null) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('invoice_date',[
                           'label' => 'Invoice Date:',
                           'cols' => 3,
                           'type' => 'date',
                        ],
                                $trans ?? null) !!}
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
                                $trans ?? null) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                            'label' => 'Designation:',
                            'cols' => 4,
                            ],
                                $trans ?? null) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                                'label' => 'Prepared Date:',
                                'cols' => 4,
                                'type' => 'date'
                             ],
                                $trans ?? null) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                            'label' => 'To:',
                            'cols' => 4,
                            ],
                                $trans ?? null) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                            'label' => 'Designation:',
                            'cols' => 4,
                            ],
                                $trans ?? null) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('received_at',[
                                'label' => 'Received Date:',
                                'cols' => 4,
                                'type' => 'date'
                             ],
                                $trans ?? null) !!}
                    </div>
                </div>
            </div>

            <div class="box box-success">
                <div class="box-body">
                    <div class="row">
                        <div class="" id="tableContainer" style="margin-top: 50px">
                            <div class="col-md-12">
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
                                        <th style="width: 3%"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($trans->transDetails as $transDetail)
                                            <tr id="{{$transDetail->slug}}">
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][stock_no]" name="items['{{$transDetail->slug}}'][stock_no]" type="text" value="{{$transDetail->stock_no}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][unit]" name="items['{{$transDetail->slug}}'][unit]" type="text" value="{{$transDetail->unit}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][item]" name="items['{{$transDetail->slug}}'][item]" type="text" value="{{$transDetail->item}}"></td>
                                                <td><textarea class="input-sm" id="items['{{$transDetail->slug}}'][description]" name="items['{{$transDetail->slug}}'][description]" type="text">{{$transDetail->description}}</textarea></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][qty]" name="items['{{$transDetail->slug}}'][qty]" type="text" value="{{$transDetail->qty}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][unit_cost]" name="items['{{$transDetail->slug}}'][unit_cost]" type="text" value="{{$transDetail->unit_cost}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][total_cost]" name="items['{{$transDetail->slug}}'][total_cost]" type="text" value="{{$transDetail->total_cost}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][estimated_useful_life]" name="items['{{$transDetail->slug}}'][estimated_useful_life]" type="text" value="{{$transDetail->estimated_useful_life}}"></td>
                                                <td><input class="form-control" id="items['{{$transDetail->slug}}'][remarks]" name="items['{{$transDetail->slug}}'][remarks]" type="text" value="{{$transDetail->remarks}}"></td>
                                                <td><button type="button" class="btn btn-danger btn-sm delete-btn" data-slug="{{$transDetail->slug}}" onclick="deleteRow(this)"><i class="fa fa-times"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary" style="margin-right: 10px" id="saveBtn">Save</button>
                                    <a type="button" style="margin-right: 17px" class="btn btn-danger pull-right" id="backBtn" href="{{route('dashboard.ics.index')}}">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        function deleteRow(button) {
            const row = button.closest('tr');
            if (row) {
                row.remove();
            }
        }

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#edit_form');
            let uri = '{{route("dashboard.ics.update","slug")}}';
            uri = uri.replace('slug',$('#slug').val());
            loading_btn(form);

            $.ajax({
                url : uri,
                data: form.serialize(),
                type: 'PATCH',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    console.log(res);
                    toast('success','ICS Successfully updated.','Success!');
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
                            window.location.reload();
                        }
                        else {

                            window.location.reload();
                        }
                    })
                },
                error: function (res) {
                    toast('error',res.responseJSON.message,'Error!');
                }
            });
        });

        $("#inventory-account-code").select2();
    </script>
@endsection
