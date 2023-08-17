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
                            {!! \App\Swep\ViewHelpers\__form2::textbox('dateacquired',[
                                        'label' => 'Date Acquired:',
                                        'cols' => 2,
                                        'type' => 'date'
                                     ],
                                    $par ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('article',[
                                                              'cols' => 4,
                                                              'label' => 'Article:',
                                                              'class' => 'select2_article',
                                                              'autocomplete' => 'off',
                                                              'options' => [],
                                                          ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textarea('description',[
                                  'cols' => 6,
                                  'label' => 'Description: ',
                                  'rows' => 2
                                ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('sub_major_account_group',[
                                                            'label' => 'Sub-Major Acct. Group:',
                                                            'cols' => 4,
                                                            'readonly' => 'readonly'
                                                            ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('general_ledger_account',[
                                                            'label' => 'General Ledger Account:',
                                                            'cols' => 4,
                                                            'readonly' => 'readonly'
                                                            ]) !!}
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
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="row">

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
                    alert(i + '/' + item);
                    $("#select[name='"+i+"']").val(item).trigger('change');
                    $("#input[name='"+i+"']").val(item).trigger('change');
                })
            });

        })
    </script>
@endsection