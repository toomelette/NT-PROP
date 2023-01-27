@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Prepare AQ</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-solid">
            <form id="aq_form">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{$aq->ref_book}}: {{$aq->ref_no}}
                    </h3>
                    <span class="pull-right">ABC: {{number_format($aq->abc,2)}} </span>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

                <div class="box-body">

                    <button type="button" class="btn btn-xs btn-success pull-right" id="add_column_button" style="margin-bottom: 5px" data="{{\Illuminate\Support\Str::random(10)}}"><i class="fa fa-columns"></i> Add Column</button>
                    <table class="table-bordered table-striped table-condensed" style="width: 100%; overflow-y: auto" id="aq_table">
                        <thead id="items_head">
                        <tr>
                            <th>#</th>
                            <th>Qty Unit</th>
                            <th>Description of Articles</th>
                            <th style="width: 200px;" class="th_supplier">Supplier 1</th>
                        </tr>
                        <tr class="bg-info">
                            <th colspan="3">Supplier Details</th>
                            <th style="width: 200px;">
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('suppliers[3]',[
                                    'class' => 'input-sm',
                                    'cols' => ' no-margin',
                                    'placeholder' => 'Supplier',
                                ]) !!}
                            </th>
                        </tr>
                        </thead>
                        <tbody id="items_body">
                        @if(!empty($aq->transaction->transDetails))
                            @foreach($aq->transaction->transDetails as $item)
                                <tr data="{{$item->slug}}">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->qty}} {{strtoupper($item->unit)}}</td>
                                    <td>
                                        <b>{{$item->item}}</b>
                                        <br>
                                        <span style="white-space: pre-line" class="small">{{$item->description}}</span>
                                    </td>
                                    <td style="vertical-align: top" id="" >
                                        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('offers[3]['.$item->slug.'][amount]',[
                                            'class' => 'input-sm autonumber text-right',
                                            'step' => 'any',
                                            'cols' => ' no-margin',
                                            'placeholder' => 'Amount',
                                        ]) !!}


                                        <a href="#" tabindex="-1"><p class="no-margin text-info pull-right add_description_btn"><small>Add description</small></p></a>
                                        <div class="desc_container clearfix" style="display:none; ">

                                            {!! \App\Swep\ViewHelpers\__form2::textareaOnly('offers[3]['.$item->slug.'][description]',[
                                                'class' => 'input-sm',
                                                'label' => 'Description:',
                                                'container_class' => 'items_description',
                                                'rows' => 3,
                                            ]) !!}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                </div>
            </form>
        </div>

    </section>

    <div hidden id="populate">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items[][qty]',[
            'class' => 'input-sm autonumber text-right',
            'step' => 'any',
            'cols' => ' no-margin',
            'placeholder' => 'Amount',
        ]) !!}


        <a href="#" tabindex="-1"><p class="no-margin text-info pull-right add_description_btn"><small>Add description</small></p></a>
        <div class="desc_container clearfix" style="display:none; ">
            {!! \App\Swep\ViewHelpers\__form2::textareaOnly('items[][description]',[
                'class' => 'input-sm',
                'label' => 'Description:',
                'container_class' => 'items_description',
                'rows' => 3,
            ]) !!}
        </div>
    </div>

    <div hidden id="supplier">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items[][qty]',[
            'class' => 'input-sm',
            'cols' => ' no-margin',
            'placeholder' => 'Supplier',
        ]) !!}
    </div>

@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        $("#add_column_button").click(function () {
            let rows = $('#items_body tr').length;
            let btn = $(this);
            let html = $("#populate").html();
            $("#items_head tr:first").each(function () {
                $(this).append('<th class="th_supplier" style="vertical-align: top" id="td_'+btn.attr('data')+'"></th>');
            })
            $("#items_head tr:nth-child(2)").each(function () {
                $(this).append('<th style="vertical-align: top" id="td_'+btn.attr('data')+'">'+$("#supplier").html()+'</th>');
            })

            $("#items_body tr").each(function () {
                $(this).append('<td style="vertical-align: top" id="td_'+btn.attr('data')+'">'+html+'</td>');
            })



            $("#td_"+btn.attr('data')+" .autonumber").each(function(){
                new AutoNumeric(this, autonum_settings);
            });

            let random = Math.floor(Math.random() * 10000000 + 1);
            btn.attr('data','btn_'+random);
            indexing();
        })
        $("body").on("click",".add_description_btn",function () {
            $(this).parent('a').siblings('.desc_container').fadeIn();
        })

        function indexing() {
            let len = $("#aq_table #items_head tr:first .th_supplier").length;
            let currentIndex = len+2;

            $("#items_body tr").each(function () {
                let t = $(this);
                let itemId = t.attr('data');
                t.children('td:eq('+currentIndex+')').find('input.autonumber ').attr('name','offers['+currentIndex+']['+itemId+'][amount]');
                t.children('td:eq('+currentIndex+')').find('textarea').attr('name','offers['+currentIndex+']['+itemId+'][description]');
                $("#items_head tr:eq(1) th:eq("+(len)+")").find('input').attr('name','suppliers['+currentIndex+']');
            })
        }
        $("#aq_form").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                url : '{{route("dashboard.aq.update",$aq->slug)}}',
                data : form.serialize(),
                type: 'PATCH',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    console.log(res);
                },
                error: function (res) {
                    console.log(res);
                    errored(form,res);
                }
            })
        })
    </script>
@endsection