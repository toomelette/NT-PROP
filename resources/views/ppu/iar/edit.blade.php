@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Inspection Acceptance Report</h1>
    </section>
@endsection

@section('content2')

    <section class="content col-md-12">

        <div role="document">
            <form id="edit_form">

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit IAR</h3>
                        <button class="btn btn-primary btn-sm pull-right" id="saveBtn" type="button">
                            <i class="fa fa-check"></i> Save
                        </button>
                        <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.iar.index')}}">Back</a>
                    </div>
                    <div class="box-body">
                        <input type="hidden" name="slug" id="slug" value="{{$iar->slug}}">


                        {!! \App\Swep\ViewHelpers\__form2::textbox('po_date',[
                           'label' => 'PO Date',
                           'cols' => 2,
                           'type' => 'date'
                        ],
                                        $iar ?? null
                                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('po_number',[
                            'label' => 'PO No:',
                            'cols' => 3,
                         ],
                                        $iar ?? null
                                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('invoice_date',[
                           'label' => 'Invoice Date',
                           'cols' => 2,
                           'type' => 'date',
                        ],
                                        $iar ?? null
                                      ) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('invoice_number',[
                          'label' => 'Invoice No:',
                          'cols' => 3,
                          'id' => 'invoice_number',
                       ],
                                        $iar ?? null
                                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('date_inspected',[
                         'label' => 'Date Inspected:',
                         'cols' => 2,
                         'id' => 'date_inspected',
                         'type' => 'date',
                      ],
                                        $iar ?? null
                                        ) !!}

                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">

                        {!! \App\Swep\ViewHelpers\__form2::textbox('supplier',[
                          'label' => 'Supplier:',
                          'cols' => 3,
                          'id' => 'supplier'
                       ],
                                        $iar ?? null
                                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                           'label' => 'Requisitioning Office/Department',
                           'cols' => 3,
                           'id' => 'resp_center',
                           'options' => \App\Swep\Helpers\Arrays::groupedRespCodes()
                        ],
                                        $iar ?? null
                                        ) !!}

{{--                        {!! \App\Swep\ViewHelpers\__form2::textbox('ref_no',[--}}
{{--                           'label' => 'PR/JR No:',--}}
{{--                           'cols' => 3,--}}
{{--                           'id' => 'ref_no'--}}
{{--                        ],--}}
{{--                                        $iar ?? null--}}
{{--                                        ) !!}--}}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                           'label' => 'Requested by:',
                           'cols' => 3,
                           'id' => 'requested_by'
                        ],
                                        $iar ?? null
                                        ) !!}

                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">

                        <div class="col-md-12" style="min-height: 200px">
                            <button data-target="#trans_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=iar_items" style="margin-bottom: 5px; margin-top: 5px; margin-right: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                            <table id="trans_table" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 5%">Stock No.</th>
                                    <th style="width: 10%">Unit</th>
                                    <th style="width: 25%">Item</th>
                                    <th style="width: 25%">Description</th>
                                    <th style="width: 8%">Qty</th>
                                    <th style="width: 8%">Unit Cost</th>
                                    <th style="width: 8%">Total Cost</th>
                                    {{--                                    <th>Prop. No.</th>--}}
                                    {{--                                    <th>Nature of Work</th>--}}
                                    <th style="width: 3%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($iar->transDetails))
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @foreach($iar->transDetails as $item)
                                        @php
                                            $grandTotal = $grandTotal + $item->total_cost;
                                        @endphp
                                        @include('dynamic_rows.iar_items',[
                                            'item' => $item,
                                        ])
                                    @endforeach
                                @else
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @include('dynamic_rows.iar_items')
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

@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">

    $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#edit_form');
            let uri = '{{route("dashboard.iar.update","slug")}}';
            uri = uri.replace('slug',$('#slug').val());
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
                    toast('success','Successfully Updated.','Success!');
                    $('#printIframe').attr('src',res.route);
                    succeed(form,true,true);
                    Swal.fire({
                        title: 'Successfully Updated',
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
                            let link = "{{route('dashboard.iar.print','slug')}}";
                            link = link.replace('slug',res.slug);
                            window.open(link, '_blank');
                        }
                    })
                },
                error: function(res) {
                    toast('error',res.responseJSON.message,'Error!');
                }
            });
        });


        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

    $(".select2_item").select2({
        ajax: {
            url: '{{route("dashboard.ajax.get","articles")}}',
            dataType: 'json',
            delay : 250,
        },
        placeholder: 'Select item',
    });

    $('.select2_item').on('select2:select', function (e) {
        let t = $(this);
        let parentTrId = t.parents('tr').attr('id');
        let data = e.params.data;

        $("#"+parentTrId+" [for='stockNo']").val(data.id);
        $("#"+parentTrId+" [for='uom']").val(data.populate.uom);
        $("#"+parentTrId+" [for='itemName']").val(data.text);
    });


    </script>
@endsection
