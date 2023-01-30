@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Prepare AQ <span class="pull-right">ABC: {{number_format($aq->abc,2)}} </span></h1>

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

                    <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-check"></i> Save</button>
                </div>

                <div class="box-body">

                    <button type="button" class="btn btn-xs btn-success pull-right" id="add_column_button" style="margin-bottom: 5px" data="{{\Illuminate\Support\Str::random(10)}}"><i class="fa fa-columns"></i> Add Column</button>
                    <table class="table-bordered table-striped table-condensed" style="width: 100%; overflow-y: auto" id="aq_table">
                        <thead id="items_head">
                        <tr>
                            <th>#</th>
                            <th>Qty Unit</th>
                            <th style="width: 400px;">Description of Articles</th>
                            @foreach($quotations as $quotation)
                                <th style="width: 200px;" class="th_supplier">
                                    <label class="no-margin">Supplier {{$loop->iteration}}</label>
                                    <button type="button" class="btn-danger btn btn-xs pull-right remove_supplier_button"><i class="fa fa-times"></i> </button>
                                </th>
                            @endforeach

                        </tr>
                        <tr class="bg-info">
                            <th colspan="3">Supplier Details</th>
                            @foreach($quotations as $quotation)
                                <th style="width: 200px;">
                                    {!! \App\Swep\ViewHelpers\__form2::textboxOnly('suppliers['.($loop->iteration + 2).']',[
                                        'class' => 'input-sm',
                                        'cols' => ' no-margin',
                                        'placeholder' => 'Supplier',
                                    ],$quotation['obj']->supplier_slug) !!}
                                </th>
                            @endforeach

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
                                    @foreach($quotations as $quotation)
                                        <td style="vertical-align: top" id="" >
                                            {!! \App\Swep\ViewHelpers\__form2::textboxOnly('offers['.($loop->iteration + 2).']['.$item->slug.'][amount]',[
                                                'class' => 'input-sm autonumber text-right',
                                                'step' => 'any',
                                                'cols' => ' no-margin',
                                                'placeholder' => 'Amount',
                                            ],$items[$item->slug][$quotation['obj']->slug]['obj']->amount ?? null) !!}

                                            <a href="#" tabindex="-1"><p class="no-margin text-info pull-right add_description_btn"><small>Add description</small></p></a>
                                            <div class="desc_container clearfix" style="display:{{($items[$item->slug][$quotation['obj']->slug]['obj']->description == '') ? 'none':'block'}}; ">
                                                {!! \App\Swep\ViewHelpers\__form2::textareaOnly('offers['.($loop->iteration + 2).']['.$item->slug.'][description]',[
                                                    'class' => 'input-sm',
                                                    'label' => 'Description:',
                                                    'container_class' => 'items_description',
                                                    'rows' => 3,
                                                ],$items[$item->slug][$quotation['obj']->slug]['obj']->description ?? null) !!}
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr class="bg-success">
                            <th colspan="3" onclick="showTotal()">TOTAL</th>
                            @foreach($quotations as $quotation)
                                <th class="text-right tfoot"></th>
                            @endforeach
                        </tr>
                        </tfoot>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="page-header-sm text-info text-strong" style="border-bottom: 1px solid #cedbe1">
                                PREPARED BY:
                            </p>
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('prepared_by',[
                                    'cols' => 12,
                                    'placeholder' => 'Full Name',
                                    'class' => 'input-sm',
                                ],$aq ?? null) !!}
                                <br><br>
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('prepared_by_position',[
                                    'cols' => 12,
                                    'placeholder' => 'Position',
                                    'class' => 'input-sm'
                                ],$aq ?? null) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <p class="page-header-sm text-info text-strong" style="border-bottom: 1px solid #cedbe1">
                                NOTED BY:
                            </p>
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('noted_by',[
                                    'cols' => 12,
                                    'placeholder' => 'Full Name',
                                    'class' => 'input-sm',
                                ],$aq ?? null) !!}
                                <br><br>
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('noted_by_position',[
                                    'cols' => 12,
                                    'placeholder' => 'Position',
                                    'class' => 'input-sm'
                                ],$aq ?? null) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <p class="page-header-sm text-info text-strong" style="border-bottom: 1px solid #cedbe1">
                                RECOMMENDING APPROVAL:
                            </p>
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('recommending_approval',[
                                    'cols' => 12,
                                    'placeholder' => 'Full Name',
                                    'class' => 'input-sm',
                                ],$aq ?? null) !!}
                                <br><br>
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('recommending_approval_position',[
                                    'cols' => 12,
                                    'placeholder' => 'Position',
                                    'class' => 'input-sm'
                                ],$aq ?? null) !!}
                            </div>
                        </div>


                    </div>
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
                $(this).append('<th class="th_supplier" style="vertical-align: top" id="td_'+btn.attr('data')+'">' +
                    '<label class="no-margin"></label><button type="button" class="btn-danger btn btn-xs pull-right remove_supplier_button"><i class="fa fa-times"></i> </button>' +
                    '</th>');
            })
            $("#items_head tr:nth-child(2)").each(function () {
                $(this).append('<th style="vertical-align: top" id="td_'+btn.attr('data')+'">'+$("#supplier").html()+'</th>');
            })
            $("#aq_table tfoot tr").each(function () {
                $(this).append('<th class="text-right tfoot"></th>');
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
            });

            $("#aq_table #items_head tr:first th").each(function () {
                $(this).find('label').html('Supplier '+($(this).index()-2));
            })

        }
        showTotal();
        function showTotal(){
            let totalArray = [];
            $("#aq_table tbody tr:first td").each(function () {
                totalArray[$(this).index()] = 0;
            })
            $("#aq_table tbody tr td").each(function () {
                if($(this).index() > 2){
                    let amt = $(this).find('.autonumber').val();
                    amt = amt.replace(',','');
                    totalArray[$(this).index()] = totalArray[$(this).index()] + parseFloat(amt);
                }
            })
            $.each(totalArray, function (i,v) {
                let index = i - 2;
                if( index > 0){
                    $("#aq_table tfoot tr th:eq("+index+")").html($.number(v,2));
                }
            })
        }
        $("#aq_form").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.aq.update",$aq->slug)}}',
                data : form.serialize(),
                type: 'PATCH',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    toastMessage('Changes were saved.');
                    remove_loading_btn(form);
                },
                error: function (res) {
                    console.log(res);
                    errored(form,res);
                }
            })
        })
        $("#aq_form").change(function () {
            $(this).submit();
            showTotal();
        })

        $("body").on('focusin','input',function () {
            $("input").each(function () {
                $(this).removeAttr('tabindex');
            })
            let elementTag = $(this).parent('div').parent().get(0).tagName;
            let t = $(this);

            let start = 10000;
            let tdIndex;
            let trIndex;
            if(elementTag === 'TD'){
                tdIndex = t.parent('div').parent('td').index();
                trIndex = t.parent('div').parent('td').parent('tr').index();
            }else{
                tdIndex = t.parent('div').parent('th').index() + 2;
                trIndex = -1;
            }
            $(this).attr('tabindex',start);
            $("#aq_table #items_body tr").each(function () {
                start++;
                if($(this).index() > trIndex){
                    $(this).find('td:eq('+tdIndex+') input').attr('tabindex',start);
                }
            })

        })
        @if(count($quotations) < 1)
            $("#add_column_button").click();
        @endif
        
        $("body").on("click",".remove_supplier_button",function () {
            let t = $(this);
            let index = t.parent('th').index();
            $("#aq_table #items_body tr").each(function () {
                $(this).find('td').eq(index).addClass('bg-danger');
            });
            t.parent('th').addClass('bg-danger');
            Swal.fire({
                title: 'Are you sure to remove this column?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $("#aq_table #items_body tr").each(function () {
                        $(this).find('td').eq(index).remove();
                    });
                    $("#aq_table tfoot tr").each(function () {
                        $(this).find('th').eq(index -2).remove();
                    });
                    $("#aq_table thead tr:eq(1)").find('th').eq(index-2).remove();
                    t.parent('th').remove();
                    $("#aq_form").submit();
                    indexing();
                }else{
                    $("#aq_table #items_body tr").each(function () {
                        $(this).find('td').eq(index).removeClass('bg-danger');
                    });
                    t.parent('th').removeClass('bg-danger');
                }
            })

        })
    </script>
@endsection