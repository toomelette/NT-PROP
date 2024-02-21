@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Prepare AQ Manual</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-solid">
            <form id="aq_form">
                <div class="box-header with-border">
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-primary btn-sm" id="submitForm_btn"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>

                <div class="box-body">
                    {!! \App\Swep\ViewHelpers\__form2::textboxOnly('date',[
                                                  'cols' => 2,
                                                  'type' => 'date',
                                                  'required' => 'required',
                                              ])!!}
                    <button type="button" class="btn btn-xs btn-success pull-right" id="add_column_button" style="margin-bottom: 5px" data="{{\Illuminate\Support\Str::random(10)}}"><i class="fa fa-columns"></i> Add Column</button>
                    <table class="table-bordered table-striped table-condensed" style="width: 100%; overflow-y: auto" id="aq_table">
                        <thead id="items_head">
                        <tr>
                            <th style="width: 30px;">#</th>
                            <th style="width: 60px;">Qty Unit</th>
                            <th style="width: 400px;">Description of Articles</th>
                            <th style="width: 200px;" class="th_supplier">
                                <label class="no-margin">Supplier </label>
                                <button type="button" class="btn-danger btn btn-xs pull-right remove_supplier_button"><i class="fa fa-times"></i> </button>
                            </th>
                        </tr>
                        <tr class="bg-info">
                            <th colspan="3">Supplier Details</th>
                            <th style="width: 200px;">
                                {!! \App\Swep\ViewHelpers\__form2::selectOnly('suppliers',[
                                    'class' => 'input-sm select2_supplier',
                                    'cols' => ' no-margin',
                                    'placeholder' => 'Supplier',
                                    'for' => 'supplier_slug',
                                    'options' => [],
                                    ]) !!}
                            </th>
                        </tr>
                        </thead>
                        <tbody id="items_body">
                        <tr>
                            <td class="text-center"></td>
                            <td></td>
                            <td>
                                <b></b>
                                <br>
                                <span style="white-space: pre-line" class="small"></span>
                            </td>
                            <td style="vertical-align: top" id="" >
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('offers_amount',[
                                    'class' => 'input-sm autonumber text-right',
                                    'step' => 'any',
                                    'cols' => ' no-margin',
                                    'placeholder' => 'Amount',
                                ]) !!}

                                <a href="#" tabindex="-1"><p class="no-margin text-info pull-right add_description_btn"><small>Add description</small></p></a>
                                <div class="desc_container clearfix">
                                    {!! \App\Swep\ViewHelpers\__form2::textareaOnly('offers_items_description',[
                                        'class' => 'input-sm',
                                        'label' => 'Description:',
                                        'container_class' => 'items_description',
                                        'rows' => 3,
                                    ]) !!}
                                </div>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr class="bg-success">
                            <th colspan="3">TOTAL</th>
                                <th class="text-right tfoot"></th>
                        </tr>
                        <tr class="footer-inputs" for="warranty">
                            <td colspan="3">Warranty:</td>
                            <td  style="width: 200px;" for="warranty">
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('suppliers_warranty',[
                                    'class' => 'input-sm',
                                    'cols' => ' no-margin',
                                    'placeholder' => 'Warranty',
                                    'for' => 'warranty',
                                ]) !!}
                            </td>
                        </tr>
                        <tr class="footer-inputs" for="price_validity">
                            <td colspan="3">Price Validity:</td>
                            <td style="width: 200px;" for="price_validity">
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('suppliers_price_validity',[
                                    'class' => 'input-sm',
                                    'cols' => ' no-margin',
                                    'placeholder' => 'Price Validity',
                                    'for' => 'price_validity',
                                ]) !!}
                            </td>
                        </tr>
                        <tr class="footer-inputs" for="has_attachments">
                            <td colspan="3">Has attachments:</td>
                            <td for="has_attachements">
                                <center>
                                    <input for="has_attachments" type="checkbox" name="{{'suppliers_has_attachments]'}}"></center>
                            </td>
                        </tr>
                        <tr class="footer-inputs" for="delivery_term">
                            <td colspan="3" for="delivery_term">Delivery Term:</td>
                            <td style="width: 200px;">
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('suppliers_delivery_term',[
                                    'class' => 'input-sm',
                                    'cols' => ' no-margin',
                                    'placeholder' => 'Delivery Term',
                                    'for' => 'delivery_term',
                                ]) !!}
                            </td>
                        </tr>
                        <tr class="footer-inputs" for="payment_term">
                            <td colspan="3" for="payement_term">Payment Term:</td>
                            <td style="width: 200px;">
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('suppliers_payment_term',[
                                    'class' => 'input-sm',
                                    'cols' => ' no-margin',
                                    'placeholder' => 'Payment Term',
                                    'for' => 'payment_term',
                                ]) !!}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 20px">
                            <p class="page-header-sm text-info text-strong" style="border-bottom: 1px solid #cedbe1">
                                REMARKS:
                            </p>
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('remarks',[
                                    'cols' => 12,
                                    'placeholder' => 'Remarks',
                                    'class' => 'input-sm',
                                ]) !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>


                        <div class="col-md-4">
                            <p class="page-header-sm text-info text-strong" style="border-bottom: 1px solid #cedbe1">
                                PREPARED BY:
                            </p>
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('prepared_by',[
                                    'cols' => 12,
                                    'placeholder' => 'Full Name',
                                    'class' => 'input-sm',
                                ]) !!}
                                <br><br>
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('prepared_by_position',[
                                    'cols' => 12,
                                    'placeholder' => 'Position',
                                    'class' => 'input-sm'
                                ]) !!}
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
                                ]) !!}
                                <br><br>
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('noted_by_position',[
                                    'cols' => 12,
                                    'placeholder' => 'Position',
                                    'class' => 'input-sm'
                                ]) !!}
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
                                ]) !!}
                                <br><br>
                                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('recommending_approval_position',[
                                    'cols' => 12,
                                    'placeholder' => 'Position',
                                    'class' => 'input-sm'
                                ]) !!}
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
        {!! \App\Swep\ViewHelpers\__form2::selectOnly('suppliers[][supplier_slug]',[
            'class' => 'input-sm sel2',
            'cols' => ' no-margin',
            'placeholder' => 'Supplier',
            'for' => 'supplier_slug',
            'options' => [],
        ]) !!}

    </div>

    <div hidden id="warranty">

        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('x',[
            'class' => 'input-sm',
            'cols' => ' no-margin',
            'placeholder' => 'Warranty',
            'for' => 'warranty',
        ]) !!}

    </div>

    <div hidden id="price_validity">

        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('x',[
            'class' => 'input-sm',
            'cols' => ' no-margin',
            'placeholder' => 'Price Validity',
            'for' => 'price_validity',
        ]) !!}

    </div>

    <div hidden id="delivery_term">

        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('x',[
            'class' => 'input-sm',
            'cols' => ' no-margin',
            'placeholder' => 'Delivery Term',
            'for' => 'delivery_term',
        ]) !!}

    </div>

    <div hidden id="payment_term">

        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('x',[
            'class' => 'input-sm',
            'cols' => ' no-margin',
            'placeholder' => 'Payment Term',
            'for' => 'payment_term',
        ]) !!}

    </div>

    <div hidden id="has_attachments">

        <center><input for="has_attachments" type="checkbox" name="x"></center>

    </div>
@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        var sel2_supplier_options = {
            ajax: {
                url: '{{route("dashboard.ajax.get","suppliers")}}',
                dataType: 'json',
                delay : 250,
            },
            // dropdownParent: $("#add_pr_modal"),
            placeholder: 'Select item',
        };

        $("#add_column_button").click(function () {
            let rows = $('#items_body tr').length;
            let btn = $(this);
            let html = $("#populate").html();
            $("#items_head tr:first").each(function () {
                $(this).append('<th class="th_supplier" style="vertical-align: top; width: 100px" id="td_'+btn.attr('data')+'">' +
                    '<label class="no-margin"></label><button type="button" class="btn-danger btn btn-xs pull-right remove_supplier_button"><i class="fa fa-times"></i> </button>' +
                    '</th>');
            })
            $("#items_head tr:nth-child(2)").each(function () {
                $(this).append('<th style="vertical-align: top;" id="th_'+btn.attr('data')+'">'+$("#supplier").html()+'</th>');
            })
            $("#aq_table tfoot tr:first").each(function () {
                $(this).append('<th class="text-right tfoot"></th>');
            })

            $("#items_body tr").each(function () {
                $(this).append('<td style="vertical-align: top" id="td_'+btn.attr('data')+'">'+html+'</td>');
            })

            $("#td_"+btn.attr('data')+" .autonumber").each(function(){
                new AutoNumeric(this, autonum_settings);
            });

            $("#th_"+btn.attr('data')+" .sel2").each(function(){
                $(this).select2(sel2_supplier_options);
            });

            $(".footer-inputs").each(function () {
                let t = $(this);
                t.append('<td>'+$("#"+t.attr('for')).html()+'</td>');
            })

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
                t.children('td').each(function () {
                    if($(this).index() > 2){
                        $(this).find('input.autonumber').attr('name','offers['+$(this).index()+']['+itemId+'][amount]');
                        $(this).find('textarea').attr('name','offers['+$(this).index()+']['+itemId+'][description]');
                    }
                })
            });

            $("#items_head tr:eq(1)").each(function () {
                let t = $(this);
                t.children('th:not(:first)').each(function () {
                    $(this).find('select[for="supplier_slug"]').attr('name','suppliers['+($(this).index()+2)+'][supplier_slug]');
                })
            });
            $("#items_head tr:first").each(function () {
                let t = $(this);
                t.children('th').each(function () {
                    if($(this).index() > 2){
                        $(this).find('label').html('Supplier '+($(this).index()-2));
                    }
                })
            });

            $(".footer-inputs").each(function () {
                let t = $(this);
                t.children('td:not(:first)').each(function () {
                    $(this).find('input').attr('name','suppliers['+($(this).index()+2)+']['+$(this).parents('tr').attr('for')+']');
                })
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

        $("#aq_form").change(function () {
            //$(this).submit();
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

                    $("#aq_table tfoot .footer-inputs").each(function () {
                        $(this).find('td').eq(index - 2).remove();
                    });
                    t.parent('th').remove();
                    indexing();
                    $("#aq_form").submit();

                }else{
                    $("#aq_table #items_body tr").each(function () {
                        $(this).find('td').eq(index).removeClass('bg-danger');
                    });
                    t.parent('th').removeClass('bg-danger');
                }
            })
        })

        $(".select2_supplier").select2(sel2_supplier_options);
    </script>
@endsection