@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Create Inventory Custodian Slip</h1>
    </section>
@endsection
@section('content2')
    <section class="content">
        <div role="document">
            <form id="add_form">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('ref_no',[
                                        'label' => 'ICS No:',
                                        'cols' => 3,
                                    ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                                        'label' => 'Entity Name:',
                                        'cols' => 3,
                                    ]) !!}
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
                            <div class="form-group col-md-6 supplier">
                                <label for="awardee">Supplier: </label>
                                {!! Form::select('supplier', $suppliers, null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('po_number',[
                                'label' => 'PO No:',
                                'cols' => 3,
                             ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('po_date',[
                               'label' => 'PO Date:',
                               'cols' => 3,
                               'type' => 'date',
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
                            {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                                'label' => 'To:',
                                'cols' => 4,
                                ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                                'label' => 'Designation:',
                                'cols' => 4,
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
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function(){
            $('#iar_no').keypress(function (event){
                if (event.keyCode === 13) {

                }
            });
        });

        $("#inventory-account-code").select2();
    </script>
@endsection