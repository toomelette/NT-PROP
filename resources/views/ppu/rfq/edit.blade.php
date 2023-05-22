@php($rand = \Illuminate\Support\Str::random())
@extends('layouts.modal-content')

@section('modal-header')
<label>EDIT RFQ No. {{$trans->ref_no}}</label>
@endsection

@section('modal-body')

    <div class="row">
        {{--<div class="col-md-9">
            <div class="bs-example">
                <div class="embed-responsive embed-responsive-16by9" style="height: 1019.938px;">
                    <iframe class="embed-responsive-item" src="{{route('dashboard.my_'.strtolower($trans->transaction->ref_book).'.print',$trans->transaction->slug)}}?noPrint=true"></iframe>
                </div>
            </div>
        </div>--}}
        <div class="col-md-12">
            <form id="edit_rfq_form_{{$rand}}" data="{{$trans->slug}}">
                <div class="row">
                    <input class="hidden" type="text" id="slugEdit" name="slugEdit" value="{{$trans->slug}}"/>
                    <input class="hidden" type="text" id="itemSlugEdit" name="itemSlugEdit"/>
                    <div class="row" id="divRows">
                        <div class="col-md-12">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_deadline',[
                                'label' => 'Deadline:',
                                'cols' => 4,
                                'type' => 'date',
                            ],
                            $trans ?? null
                            ) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_s_name',[
                            'label' => 'Signatory Name:',
                            'cols' => 4,
                            ],
                            $trans ?? null
                            ) !!}
                                {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_s_position',[
                                    'label' => 'Signatory Position:',
                                    'cols' => 4,
                                ],
                                $trans ?? null
                                ) !!}

                            <div class="col-md-12">
                                <div class="" id="tableContainer_edit" style="margin-top: 50px">
                                    <table class="table table-bordered table-striped table-hover" id="trans_table_edit" style="width: 100% !important">
                                        <thead>
                                        <tr class="">
                                            <th>Stock No.</th>
                                            <th>Unit</th>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Unit Cost</th>
                                            <th>Total Cost</th>
                                            <th width="3%"></th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary" id="saveBtnEdit">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<fieldset {{(!empty($trans->rfq)) ? 'disabled':''}}>
                    <p class="page-header-sm text-info" style="border-bottom: 1px solid #cedbe1">
                        Create RFQ
                    </p>
                    <p class="text-danger">{{(!empty($trans->rfq)) ? 'Exisiting RFQ was found.':''}}</p>
                    <div class="row">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_deadline',[
                            'label' => 'Deadline:',
                            'cols' => 12,
                            'type' => 'date',
                        ],
                        $trans ?? null
                        ) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_s_name',[
                            'label' => 'Signatory Name:',
                            'cols' => 12,
                        ],
                        $trans ?? null
                        ) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_s_position',[
                            'label' => 'Signatory Position:',
                            'cols' => 12,
                        ],
                        $trans ?? null
                        ) !!}
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-check"></i> Save</button>
                </fieldset>--}}
            </form>

        </div>
    </div>
@endsection

@section('modal-footer')

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            let slug = $('input[name="slugEdit"]').val();
            let uri = '{{route("dashboard.rfq.findTransByRefNumber", ["refNumber", "refBook", "edit", "id"]) }}';
            uri = uri.replace('id',slug);
            $.ajax({
                url : uri,
                type: 'GET',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    $('#trans_table_edit tbody').remove();
                    let slugs = '';
                    let tableHtml = '<tbody>';
                    for(let i=0; i<res.transDetails.length; i++){
                        let num1 = parseFloat(res.transDetails[i].unit_cost);
                        let num2 = parseFloat(res.transDetails[i].total_cost);
                        num1 = isNaN(num1) ? 0 : num1;
                        num2 = isNaN(num2) ? 0 : num2;
                        let stock = res.transDetails[i].stock_no;
                        stock = stock === null ? '' : stock;
                        slugs += res.transDetails[i].slug + '~';
                        tableHtml += '<tr id='+res.transDetails[i].slug+'><td>' + stock + '</td><td>' + res.transDetails[i].unit + '</td><td>' + res.transDetails[i].item + '</td><td>' + res.transDetails[i].qty + '</td><td>' + num1.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td>' + num2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td><button type=\'button\' class=\'btn btn-danger btn-sm delete-btn\' data-slug='+res.transDetails[i].slug+' onclick="deleteRow(this)"><i class=\'fa fa-times\'></i></button></td></tr>';
                    }
                    tableHtml += '</tbody></table>';
                    slugs = slugs.slice(0, -1); // Remove the last '~' character
                    $('#itemSlugEdit').val(slugs);

                    $('#trans_table_edit').append(tableHtml).removeClass('hidden');
                    console.log(res);
                },
                error: function (res) {
                    toast('error',res.responseJSON.message,'Error!');
                    console.log(res);
                }
            })
        });

        $('#saveBtnEdit').click(function(e) {
            e.preventDefault();
            let form = $('#edit_rfq_form_{{$rand}}');
            let uri = '{{route("dashboard.rfq.update","slugEdit")}}';
            uri = uri.replace('slugEdit',form.attr('data'));
            loading_btn(form);
            $.ajax({
                type: 'PATCH',
                url: uri,
                data: form.serialize(),
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function(res) {
                    console.log(res);
                    $('#printIframe').attr('src',res.route);
                    $('#trans_table_edit tbody').remove();
                    $('#slugEdit').val('');
                    $('#itemSlugEdit').val('');
                    succeed(form,true,true);
                    all_rqf_tbl_active = res.slug;
                    all_rqf_tbl.draw(false);
                    toast('success','RFQ successfully updated.','Success!');
                    Swal.fire({
                        title: 'RFQ Successfully Updated',
                        icon: 'success',
                        html:
                            'Click the print button below to print RFQ.<br>You may also view RFQs on RFQ Tab of this page or by navigating to PRs and JRs.',
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
                            let link = "{{route('dashboard.rfq.print','slug')}}";
                            link = link.replace('slug',res.slug);
                            window.open(link, '_blank');
                        }
                    })
                },
                error: function(res) {
                    // Display an alert with the error message
                    toast('error',res.responseJSON.message,'Error!');
                }
            });
        });

        /*$("#edit_rfq_form_{{$rand}}").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            let uri = '{{route("dashboard.rfq.update","slug")}}';
            uri = uri.replace('slug',form.attr('data'));
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
                     all_rqf_tbl_active = res.slug;
                    all_rqf_tbl.draw(false);
                    toast('info','RFQ successfully updated.','Updated');
                },
                error: function (res) {
                    errored(form,res);
                }
            })

        })*/
    </script>
@endsection

