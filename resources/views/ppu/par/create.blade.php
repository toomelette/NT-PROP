@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Create Property Acknowledgement Receipt</h1>
    </section>
@endsection
@section('content2')
    <section class="content">
        <div role="document">
            <form id="add_form">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('dateacquired',[
                                                                        'label' => 'Date Acquired:',
                                                                        'cols' => 2,
                                                                        'type' => 'date'
                                                                     ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('article',[
                                                              'cols' => 4,
                                                              'label' => 'Select Article:',
                                                              'class' => 'select2_article',
                                                              'autocomplete' => 'off',
                                                              'options' => [],
                                                          ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textarea('description',[
                                      'cols' => 6,
                                      'label' => 'Description: ',
                                      'rows' => 2
                                    ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('invtacctcode',[
                                    'label' => 'Inventory Account Code:',
                                    'cols' => 4,
                                    'options' => \App\Swep\Helpers\Arrays::inventoryAccountCode(),
                                ]) !!}
                                <div class="clearfix"></div>
                                {!! \App\Swep\ViewHelpers\__form2::textbox('sub_major_account_group',[
                                                                'label' => 'Sub-Major Acct. Group:',
                                                                'cols' => 4
                                                                ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('general_ledger_account',[
                                                                'label' => 'General Ledger Account:',
                                                                'cols' => 4
                                                                ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('location',[
                                                                'label' => 'Location:',
                                                                'cols' => 4,
                                                                'options' => \App\Swep\Helpers\Arrays::location(),
                                                            ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('serial_no',[
                                                                'label' => 'Serial No.:',
                                                                'cols' => 4
                                                                ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('propertyno',[
                                                                'label' => 'Property No.:',
                                                                'cols' => 4
                                                                ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('fund_cluster',[
                                                                'label' => 'Fund Cluster:',
                                                                'cols' => 4,
                                                                'options' => \App\Swep\Helpers\Arrays::fundSources(),
                                                            ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('respcenter',[
                                    'label' => 'Resp. Center:',
                                    'cols' => 8,
                                    'options' => \App\Swep\Helpers\PPUHelpers::respCentersArray(),
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('acctemployee_no',[
                                    'label' => 'Emp. No.:',
                                    'cols' => 4,
                                    ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('acctemployee_fname',[
                                                                'label' => 'Acct. Officer:',
                                                                'cols' => 4,
                                                                ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('acctemployee_post',[
                                    'label' => 'Position:',
                                    'cols' => 4,
                                    ]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! \App\Swep\ViewHelpers\__form2::select('uom',[
                                                                'label' => 'Unit:',
                                                                'cols' => 4,
                                                                'options' => \App\Swep\Helpers\Arrays::unitsOfMeasurement(),
                                                                ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('acquiredcost',[
                                    'label' => 'Acquired Cost:',
                                    'cols' => 4,
                                    ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('qtypercard',[
                                    'label' => 'Qty Per Card:',
                                    'cols' => 4,
                                    ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('onhandqty',[
                                    'label' => 'Qty Onhand:',
                                    'cols' => 4,
                                    ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('shortqty',[
                                    'label' => 'Short Qty:',
                                    'cols' => 4,
                                    ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('shortvalue',[
                                    'label' => 'Short Value:',
                                    'cols' => 4,
                                    ]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
                                                                'label' => 'Remarks:',
                                                                'cols' => 8,
                                                                ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('supplier',[
                                    'label' => 'Supplier:',
                                    'cols' => 4,
                                    ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('invoiceno',[
                                    'label' => 'Invoice No.:',
                                    'cols' => 4,
                                    ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('invoicedate',[
                                            'label' => 'Invoice Date:',
                                            'cols' => 4,
                                            'type' => 'date'
                                         ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('pono',[
                                    'label' => 'P.O. No.:',
                                    'cols' => 4,
                                    ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('podate',[
                                            'label' => 'P.O. Date:',
                                            'cols' => 4,
                                            'type' => 'date'
                                         ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('acquiredmode',[
                                'label' => 'Acquisition Mode:',
                                'cols' => 4,
                                'options' => \App\Swep\Helpers\Arrays::acquisitionMode(),
                            ]) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('condition',[
                                'label' => 'Condition:',
                                'cols' => 4,
                                'options' => \App\Swep\Helpers\Arrays::condition(),
                            ]) !!}
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-left: 20px" id="saveBtn">Save</button>
                                    <a type="button" class="btn btn-danger pull-right" id="backBtn" href="{{route('dashboard.par.index')}}">Back to list</a>
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
        let active;
        $(document).ready(function () {
            $("select[name='location']").change(function() {
                var selectedValue = $(this).val();
                var dateValue = $("input[name='dateacquired']").val();
                var year = (new Date(dateValue)).getFullYear();
                let propertyNo = year+'-'+$("input[name='sub_major_account_group']").val()+'-'+$("input[name='general_ledger_account']").val()+'-'+$("input[name='serial_no']").val()+'-'+selectedValue;
                $("input[name='propertyno']").val(propertyNo);
            });

            $("select[name='invtacctcode']").change(function() {
                var selectedValue = $(this).val();
                // Make an AJAX request
                let uri = '{{route("dashboard.par.getInventoryAccountCode","slug")}}';
                uri = uri.replace('slug',selectedValue);
                $.ajax({
                    url: uri,
                    method: "GET",
                    data: { selectedValue: selectedValue },
                    success: function(response) {
                        $("input[name='serial_no']").val(response[1]);
                        $("input[name='sub_major_account_group']").val(response[0].sub_major_account_group);
                        $("input[name='general_ledger_account']").val(response[0].general_ledger_account);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error("AJAX error:", error);
                    }
                });
            });

            $("#add_form").submit(function (e) {
                e.preventDefault();
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
                        succeed(form,true,false);
                        toast('success','PAR successfully added.','Success!');
                    },
                    error: function (res) {
                        errored(form,res);
                    }
                })
            });

            $(".select2_article").select2({
                ajax: {
                    url: '{{route("dashboard.ajax.get","articles")}}',
                    dataType: 'json',
                    delay : 250,
                },
                dropdownParent: $('#add_form'),
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
                console.log(data);
                $.each(data.populate,function (i, item) {
                    /*$("#select[name='"+i+"']").val(item).trigger('change');
                    $("#input[name='"+i+"']").val(item).trigger('change');*/
                })
            });

        })
    </script>
@endsection