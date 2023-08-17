@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Edit Property Acknowledgement Receipt</h1>
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
                                                                     ],
                                                                    $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('article',[
                                                                'label' => 'Article:',
                                                                'cols' => 4
                                                                ],
                                        $par ?? null) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textarea('description',[
                                      'cols' => 6,
                                      'label' => 'Description: ',
                                      'rows' => 2
                                    ],
                                        $par ?? null) !!}
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
                                {!! \App\Swep\ViewHelpers\__form2::textbox('serialno',[
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
                                {!! \App\Swep\ViewHelpers\__form2::select('respcenter',[
                                    'label' => 'Resp. Center:',
                                    'cols' => 8,
                                    'options' => \App\Swep\Helpers\PPUHelpers::respCentersArray(),
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
                        active = res.id;
                        par_tbl.draw(false);
                        succeed(form,true,false);
                        toast('success','PAR successfully added.','Success!');
                    },
                    error: function (res) {
                        errored(form,res);
                    }
                })
            });
        })
    </script>
@endsection