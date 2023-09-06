@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Edit Property Acknowledgement Receipt</h1>
    </section>
@endsection
@section('content2')
    <section class="content">
        <div role="document">
            <form id="edit_form">
                <input class="hidden" type="text" id="slug" name="slug" value="{{$par->slug}}"/>
                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('dateacquired',[
                                    'label' => 'Date Acquired:',
                                    'cols' => 2,
                                    'type' => 'date'
                                 ],
                                $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('article',[
                                      'cols' => 5,
                                      'label' => 'Update Article:',
                                      'class' => 'select2_article',
                                      'autocomplete' => 'off',
                                      'options' => [],
                                  ]) !!}
                                <div class="form-group col-md-5 article_old">
                                    <label for="article_old">Article:</label>
                                    <input class="form-control " name="article_old" type="text" value="{{$par->article}}" placeholder="Article OLD">
                                </div>
                                {!! \App\Swep\ViewHelpers\__form2::textarea('description',[
                                      'cols' => 12,
                                      'label' => 'Description: ',
                                      'rows' => 2
                                    ],
                                        $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('invtacctcode',[
                                    'label' => 'Inventory Account Code:',
                                    'cols' => 4,
                                    'options' => \App\Swep\Helpers\Arrays::inventoryAccountCode(),
                                    'id' => 'inventory-account-code',
                                ],$par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('ref_book',[
                                    'label' => 'Reference Book:',
                                    'cols' => 2,
                                    'options' => \App\Swep\Helpers\Arrays::refBook(),
                                ],$par ?? null) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('ppe_serial_no',[
                                    'label' => 'PPE Serial No.:',
                                    'cols' => 2,
                                ],$par ?? null) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('ppe_model',[
                                        'label' => 'PPE Model:',
                                        'cols' => 4,
                                ],$par ?? null) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('sub_major_account_group',[
                                                                'label' => 'Sub-Major Acct. Group:',
                                                                'cols' => 4
                                                                ],
                                        $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('general_ledger_account',[
                                                                'label' => 'General Ledger Account:',
                                                                'cols' => 4
                                                                ],
                                        $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('location',[
                                                                'label' => 'Location:',
                                                                'cols' => 4,
                                                                'options' => \App\Swep\Helpers\Arrays::location(),
                                                            ],
                                                            $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('serial_no',[
                                                                'label' => 'Serial No.:',
                                                                'cols' => 4
                                                                ],
                                                            $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('propertyno',[
                                                                'label' => 'Property No.:',
                                                                'cols' => 4
                                                                ],
                                                            $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('fund_cluster',[
                                                                'label' => 'Fund Cluster:',
                                                                'cols' => 4,
                                                                'options' => \App\Swep\Helpers\Arrays::fundSources(),
                                                            ],
                                                            $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('invtacctcode',[
                                                                'label' => 'Inv. Acc. Code:',
                                                                'cols' => 4
                                                                ],
                                                            $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('respcenter',[
                                    'label' => 'Resp. Center:',
                                    'cols' => 8,
                                    'options' => \App\Swep\Helpers\PPUHelpers::respCentersArray(),
                                ],
                                $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('office',[
                                                                'label' => 'Office:',
                                                                'cols' => 4
                                                                ],
                                $par ?? null) !!}
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
                                    ],
                                $par ?? null) !!}
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
                                                                ],
                                                            $par ?? null) !!}
                                <div class="form-group col-md-4 uom_2">
                                    <label for="uom_2">OLD Unit:</label>
                                    <input class="form-control " name="uom_2" type="text" value="{{$par->uom}}" placeholder="Unit">
                                </div>
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
                                {!! \App\Swep\ViewHelpers\__form2::select('acquiredmode',[
                                'label' => 'Acquisition Mode:',
                                'cols' => 4,
                                'options' => \App\Swep\Helpers\Arrays::acquisitionMode(),
                            ],
                            $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::select('condition',[
                                'label' => 'Condition:',
                                'cols' => 4,
                                'options' => \App\Swep\Helpers\Arrays::condition(),
                            ],
                            $par ?? null) !!}
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-left: 20px" id="saveBtn">Update</button>
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
            $("#edit_form").submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let uri = '{{route("dashboard.par.update","slug")}}';
                uri = uri.replace('slug',$('#slug').val());
                loading_btn(form);
                $.ajax({
                    url : uri,
                    data : form.serialize(),
                    type: 'PATCH',
                    headers: {
                        {!! __html::token_header() !!}
                    },
                    success: function (res) {
                        succeed(form,true,true);
                        toast('info','PAR successfully updated.','Updated');
                        setTimeout(function() {
                            window.location.href = $("#backBtn").attr("href");
                        }, 3000);
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
                dropdownParent: $('#edit_form'),
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