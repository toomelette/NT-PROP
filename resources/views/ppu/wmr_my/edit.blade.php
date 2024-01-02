@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Waste Materials Report</h1>
    </section>
@endsection

@section('content2')

    <section class="content col-md-12">
        <div role="document">
        <form id="edit_form">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title">Edit WMR</h4>
                <button class="btn btn-primary btn-sm pull-right"  type="submit">
                    <i class="fa fa-check"></i> Save
                </button>
                <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.wmr.myIndex')}}">Back</a>
                </div>

            <div class="box-body">

                    <input type="hidden" name="slug" id="slug" value="{{$wmr->slug}}">


                {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                       'label' => 'Date',
                       'cols' => 3,
                       'type' => 'date',
                    ], $wmr ?? null
                        ) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('storage',[
                    'label' => 'Place of Storage:',
                    'cols' => 3,
                    ],  $wmr ?? null
                        ) !!}
                {!! \App\Swep\ViewHelpers\__form2::textbox('taken_from',[
                   'label' => 'Taken From:',
                   'cols' => 3,
                    ],  $wmr ?? null
                        ) !!}

                {!! \App\Swep\ViewHelpers\__form2::textbox('taken_through',[
                  'label' => 'Taken Through:',
                  'cols' => 3,
                    ],  $wmr ?? null
                        ) !!}


                </div>
            </div>

                <div class="box box-success">
                    <div class="box-body">


                        {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by',[
                          'label' => 'Certified By:',
                          'cols' => 3,
                       ],  $wmr ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                           'label' => 'Approved By:',
                           'cols' => 3,
                        ],  $wmr ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('inspected_by',[
                         'label' => 'Inspected By:',
                         'cols' => 3,
                        ],  $wmr ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('witnessed_by',[
                          'label' => 'Witnessed By:',
                          'cols' => 3,
                        ],  $wmr ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by_designation',[
                           'label' => 'Designation:',
                           'cols' => 3,
                        ],  $wmr ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                           'label' => 'Designation',
                           'cols' => 3,
                        ],  $wmr ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('inspected_by_designation',[
                           'label' => 'Designation:',
                           'cols' => 3,
                        ],  $wmr ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('witnessed_by_designation',[
                           'label' => 'Designation',
                           'cols' => 3,
                        ],  $wmr ?? null
                        ) !!}


                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">

                        <div class="" id="tableContainer" style="margin-top: 50px">
                            <button data-target="#trans_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=wmr_items" style="margin-bottom: 5px; margin-top: 5px; margin-right: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                            <table class="table table-bordered table-striped table-hover" id="trans_table" style="width: 100% !important">
                                <thead>
                                <tr>
                                    <th style="width: 8%">Stock No.</th>
                                    <th style="width: 8%">Unit</th>
                                    <th style="width: 23%">Item</th>
                                    <th style="width: 23%">Description</th>
                                    <th style="width: 8%">Qty</th>
                                    <th style="width: 8%">O.R. No.</th>
                                    <th style="width: 10%">Amount</th>
                                    <th style="width: 50px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($wmr->wasteDetails))
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @foreach($wmr->wasteDetails as $item)
                                        @php
                                            $grandTotal = $grandTotal + $item->total_cost;
                                        @endphp
                                        @include('dynamic_rows.wmr_items',[
                                            'item' => $item,
                                        ])
                                    @endforeach
                                @else
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @include('dynamic_rows.wmr_items')
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


    $("#edit_form").submit(function(e) {
        e.preventDefault();
        let form = $(this);
        loading_btn(form);
        let uri = '{{route("dashboard.wmr.update","slug")}}';
        uri = uri.replace('slug',$('#slug').val());
        $.ajax({
            url : uri,

            data : form.serialize(),
            type: 'PATCH',
            headers: {
                {!! __html::token_header() !!}
            },
            success: function (res) {
                succeed(form,true,false);
                $(".remove_row_btn").each(function () {
                    $(this).click();
                })
                $(".add_button").click();
                toast('success','WMR successfully updated.','Success!');
                Swal.fire({
                    title: 'WMR Successfully updated',
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
                        let link = "{{route('dashboard.wmr.print','slug')}}";
                        link = link.replace('slug',res.slug);
                        window.open(link, '_blank');
                    }
                })
            },
            error: function (res) {
                errored(form,res);
                toast('error',res.responseJSON.message,'Error!');
            }
        })
    });

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

    function deleteRow(button) {
        const row = button.closest('tr');
        row.remove();
    }


    </script>
@endsection
