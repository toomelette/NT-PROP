@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Property Card</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <div role="document">
{{--            <pre>{{ print_r($propCardDetails, true) }}</pre>--}}
            <form id="add_form">

                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create Property Card</h3>
                            <button class="btn btn-primary btn-sm pull-right" id="saveBtn" type="button">
                                <i class="fa fa-check"></i> Save
                            </button>
                            <button class="btn btn-primary btn-sm pull-right" href="{{route('dashboard.par.printPropCard','slug')}}" id="printPropCard" target="_blank" style="margin-right: 5px" type="button">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>

                        <div class="box-body">
                            <input class="hidden" type="text" id="slug" name="slug" value="{{$par->slug}}"/>
                            <input class="hidden" type="text" id="propSlug" name="propSlug" value="{{$propCard->slug}}"/>

                            {!! \App\Swep\ViewHelpers\__form2::textbox('article',[
                                  'cols' => 4,
                                  'label' => 'Article',
                              ],
                                    $par ?? null) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('propertyno',[
                                    'label' => 'Property Number',
                                    'cols' => 4
                                ],
                                     $par ?? null) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textarea('description',[
                                  'cols' => 4,
                                  'label' => 'Description ',
                                  'rows' => 2
                                ],
                                    $par ?? null) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('prepared_by',[
                                  'cols' => 3,
                                  'label' => 'Prepared By:',
                                ],
                                    $propCard ?? null) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('prepared_by_designation',[
                                 'cols' => 3,
                                 'label' => 'Designation:',
                               ],
                                    $propCard ?? null) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('noted_by',[
                                  'cols' => 3,
                                  'label' => 'Noted By:',
                                ],
                                    $propCard ?? null) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('noted_by_designation',[
                                 'cols' => 3,
                                 'label' => 'Designation:',
                               ],
                                    $propCard ?? null) !!}



                        </div>
                    </div>

                    <div class="box box-success">
                        <div class="box-body">

                            <fieldset id="add1_form">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                                  'cols' => 3,
                                  'label' => 'Date',
                                  'type' => 'date',
                                ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('ref_no',[
                                        'cols' => 3,
                                        'label' => 'Reference Number',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('receipt_qty',[
                                        'cols' => 3,
                                        'label' => 'Receipt Quantity',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('qty',[
                                      'cols' => 3,
                                      'label' => 'Quantity',
                                  ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('purpose',[
                                      'cols' => 3,
                                      'label' => 'Issue/Transfer/Disposal',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('bal_qty',[
                                        'cols' => 3,
                                        'label' => 'Balance Quantity',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('amount',[
                                        'cols' => 3,
                                        'label' => 'Amount',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textarea('remarks',[
                                        'cols' => 3,
                                        'label' => 'Remarks',
                                    ]) !!}
                            </fieldset>

                            <button style="margin-bottom: 5px; margin-top: 5px; margin-right: 5px" type="button" id="add_button" class="btn btn-xs btn-success pull-right add_button">
                                <i class="fa fa-plus"></i> Add item
                            </button>
                        </div>
                    </div>

                <div class="box box-success">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="pc_items_table" class="table-bordered table table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 7%">Date</th>
                                    <th style="width: 7%">Reference No</th>
                                    <th style="width: 10%">Receipt Quantity</th>
                                    <th style="width: 7%">Quantity</th>
                                    <th style="width: 20%">Issue/Transfer/Disposal</th>
                                    <th style="width: 8%">Bal Qty</th>
                                    <th style="width: 8%">Amount</th>
                                    <th style="width: 20%">Remarks</th>
                                    <th style="width: 3%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($propCard->propertyCardDetails != null)
                                    @php
                                    $total = 0;
                                    @endphp
                                    @foreach($propCard->propertyCardDetails as $item)
                                        @php
                                            $total += $item->amount;
                                        @endphp
                                        <tr id="{{ $item->transaction_slug }}">
                                            <td><input class="form-control" id="items2['{{ $item->transaction_slug }}'][date]" name="items2['{{ $item->transaction_slug }}'][date]" type="date" value="{{ $item->date }}"></td>
                                            <td><input class="form-control" id="items2['{{ $item->transaction_slug }}'][ref_no]" name="items2['{{ $item->transaction_slug }}'][ref_no]" type="text" value="{{ $item->ref_no }}"></td>
                                            <td><input class="form-control" id="items2['{{ $item->transaction_slug }}'][receipt_qty]" name="items2['{{ $item->transaction_slug }}'][receipt_qty]" type="text" value="{{ $item->receipt_qty }}"></td>
                                            <td><input class="form-control" id="items2['{{ $item->transaction_slug }}'][qty]" name="items2['{{ $item->transaction_slug }}'][qty]" type="text" value="{{ $item->qty }}"></td>
                                            <td><input class="form-control" id="items2['{{ $item->transaction_slug }}'][purpose]" name="items2['{{ $item->transaction_slug }}'][purpose]" type="text" value="{{ $item->purpose }}"></td>
                                            <td><input class="form-control" id="items2['{{ $item->transaction_slug }}'][bal_qty]" name="items2['{{ $item->transaction_slug }}'][bal_qty]" type="text" value="{{ $item->bal_qty }}"></td>
                                            <td><input class="form-control" id="items2['{{ $item->transaction_slug }}'][amount]" name="items2['{{ $item->transaction_slug }}'][amount]" type="text" value="{{ $item->amount }}"></td>
                                            <td><textarea class="form-control" id="items2['{{ $item->transaction_slug }}'][remarks]" name="items2['{{ $item->transaction_slug }}'][remarks]" type="text">{{ $item->remarks }}</textarea></td>
                                            <td><button type="button" class="btn btn-danger btn-sm delete-btn" data-slug="{{ $item->transaction_slug }}" onclick="deleteRow(this)"><i class="fa fa-times"></i></button></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#add_button').click(function () {
                let fieldset = $('#add1_form');
                let date = fieldset.find('input[name="date"]').val();
                let ref_no = fieldset.find('input[name="ref_no"]').val();
                let receipt_qty = fieldset.find('input[name="receipt_qty"]').val();
                let qty = fieldset.find('input[name="qty"]').val();
                let purpose = fieldset.find('input[name="purpose"]').val();
                let bal_qty = fieldset.find('input[name="bal_qty"]').val();
                let amount = fieldset.find('input[name="amount"]').val();
                let remarks = fieldset.find('textarea[name="remarks"]').val();

                let table = $('#pc_items_table');
                let id = makeid(10);

                table.append('<tr id="item_' + id + '" style="width: 100%">\n' +
                    '    <td style="width: 5%">\n' +
                    '        <div class="col-md- items[' + id + '][date]">\n' +
                    '            <input value="' + date + '" placeholder="" for="date" class="form-control single input-sm" name="items[' + id + '][date]" type="date" value="" autocomplete="" readonly="">\n' +
                    '        </div>\n' +
                    '    </td>\n' +
                    '    <td style="width: 10%">\n' +
                    '        <div class="  col-md- items[' + id + '][ref_no]">\n' +
                    '            <input value="' + ref_no + '" placeholder="" for="ref_no" class="form-control single input-sm" name="items[' + id + '][ref_no]" type="text" value="" autocomplete="" readonly="">\n' +
                    '        </div>\n' +
                    '    </td>\n' +
                    '    <td style="width: 10%">\n' +
                    '        <div class="  col-md- items[' + id + '][receipt_qty]">\n' +
                    '            <input value="' + receipt_qty + '" placeholder="" for="receipt_qty" class="form-control single input-sm" name="items[' + id + '][receipt_qty]" type="text" value="" autocomplete="" readonly="">\n' +
                    '        </div>\n' +
                    '    </td>\n' +
                    '    <td style="width: 10%">\n' +
                    '        <div class="  col-md- items[' + id + '][qty]">\n' +
                    '            <input value="' + qty + '" placeholder="" for="qty" class="form-control single input-sm" name="items[' + id + '][qty]" type="text" value="" autocomplete="" readonly="">\n' +
                    '        </div>\n' +
                    '    </td>\n' +
                    '    <td style="width: 10%">\n' +
                    '        <div class="  col-md- items[' + id + '][purpose]">\n' +
                    '            <input value="' + purpose + '" placeholder="" for="purpose" class="form-control single input-sm" name="items[' + id + '][purpose]" type="text" value="" autocomplete="" readonly="">\n' +
                    '        </div>\n' +
                    '    </td>\n' +
                    '    <td style="width: 10%">\n' +
                    '        <div class="  col-md- items[' + id + '][bal_qty]">\n' +
                    '            <input value="' + bal_qty + '" placeholder="" for="bal_qty" class="form-control single input-sm" name="items[' + id + '][bal_qty]" type="text" value="" autocomplete="" readonly="">\n' +
                    '        </div>\n' +
                    '    </td>\n' +
                    '    <td style="width: 10%">\n' +
                    '        <div class="  col-md- items[' + id + '][amount]">\n' +
                    '            <input value="' + amount + '" placeholder="" for="amount" class="form-control single input-sm" name="items[' + id + '][amount]" type="text" value="" autocomplete="" readonly="">\n' +
                    '        </div>\n' +
                    '    </td>\n' +
                    '    <td style="width: 10%">\n' +
                    '        <div class="  col-md- items[' + id + '][remarks]">\n' +
                    '            <input value="' + remarks + '"  placeholder="" for="remarks" class="form-control single input-sm" name="items[' + id + '][remarks]" type="text" value="" autocomplete="" readonly="">\n' +
                    '        </div>\n' +
                    '    </td>\n' +
                    // '    <td style="width: 10%">\n' +
                    // '        <div class="  col-md- items[' + id + '][remarks]">\n' +
                    // '            <input value="' + total_amount + '"  placeholder="" for="total amount" class="form-control single input-sm" name="items[' + id + '][total_amount]" type="text" value="" autocomplete="" readonly="">\n' +
                    // '        </div>\n' +
                    // '    </td>\n' +
                    '    <td>\n' +
                    '        <button tabindex="-1" data="S01QH" type="button" class="btn btn-danger btn-sm remove_row_btn"><i class="fa fa-times"></i></button>\n' +
                    '    </td>\n' +
                    '</tr>');
            });
        });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                let form = $('#add_form');
                let uri = '{{route("dashboard.par.savePropCard","slug")}}';
                loading_btn(form);

                $.ajax({
                    url : uri,
                    data: form.serialize(),
                    type: 'POST',
                    headers: {
                        {!! __html::token_header() !!}
                    },
                    success: function (res) {
                        succeed(form,true,false);
                        $(".remove_row_btn").each(function () {
                            $(this).click();
                        })
                        $(".add_button").click();
                        toast('success','Property Card Successfully created.','Success!');
                        Swal.fire({
                            title: 'Property Card Successfully created',
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
                                let link = "{{route('dashboard.par.printPropCard','slug')}}";
                                link = link.replace('slug',res.slug);
                                window.open(link, '_blank');
                            }
                        })
                    },
                    error: function (res) {
                        toast('error',res.responseJSON.message,'Error!');
                    }
                });
            });

        $('#printPropCard').click(function (e) {
            e.preventDefault();

            let printLink = "{{ route('dashboard.par.printPropCard', 'slug') }}";
            printLink = printLink.replace('slug',$("#propSlug").val());

            window.open(printLink, '_blank');
        });


    </script>

@endsection