@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Requisition and Issue Slip</h1>
    </section>
@endsection

@section('content2')

    <section class="content col-md-12">
        <div role="document">
        <form id="edit_form">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title">Edit RIS</h4>
                <button class="btn btn-primary btn-sm pull-right"  type="submit">
                    <i class="fa fa-check"></i> Save
                </button>
                <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.ris.myIndex')}}">Back</a>
                </div>

            <div class="box-body">

                    <input type="hidden" name="slug" id="slug" value="{{$ris->slug}}">

{{--                    {!! \App\Swep\ViewHelpers\__form2::select('pap_code',[--}}
{{--                           'cols' => 6,--}}
{{--                           'label' => 'PAP Code:',--}}
{{--                           'options' => [],--}}
{{--                           'class' => 'select2_pap_code_'.$rand,--}}
{{--                           'select2_preSelected' => ($ris->pap->pap_code ?? null).' | '.($ris->pap->pap_title ?? null)--}}
{{--                       ],--}}
{{--                       $ris ?? null--}}
{{--                       ) !!}--}}

                    {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                       'label' => 'Department/Division:',
                       'cols' => 3,
                       'id' => 'resp_center',
                       'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
                    ],
                    $ris ?? null
                    ) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                         'label' => 'RIS Date:',
                         'cols' => 3,
                         'type' => 'date',
                      ],
                    $ris ?? null
                    ) !!}



                    {!! \App\Swep\ViewHelpers\__form2::textbox('sai',[
                        'label' => 'SAI Number:',
                        'cols' => 3,
                        'id' => 'sai_no',
                     ],
                    $ris ?? null
                    ) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('sai_date',[
                      'cols' => 3,
                      'label' => 'SAI Date:',
                      'type' => 'date',
                    ],
                    $ris ?? null
                    ) !!}

                {!! \App\Swep\ViewHelpers\__form2::textarea('purpose',[
                'cols' => 6,
                'label' => 'Purpose',
                'rows' => 1
              ],
              $ris ?? null
              ) !!}


                </div>
            </div>

                <div class="box box-success">
                    <div class="box-body">


                        {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                              'cols' => 3,
                              'label' => 'Requested by: ',
                            ],
                        $ris ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                              'cols' => 3,
                              'label' => 'Approved by: ',
                            ],
                        $ris ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('prepared_by',[
                              'cols' => 3,
                              'label' => 'Issued by: ',
                            ],
                        $ris ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by',[
                          'cols' => 3,
                          'label' => 'Received by:',
                        ],
                        $ris ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                              'cols' => 3,
                              'label' => 'Requested by (Designation): ',
                            ],
                        $ris ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                              'cols' => 3,
                              'label' => 'Approved by (Designation): ',
                            ],
                        $ris ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('prepared_by_position',[
                          'cols' => 3,
                          'label' => 'Issued by (Designation): ',
                        ],
                        $ris ?? null
                        ) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by_designation',[
                          'cols' => 3,
                          'label' => 'Received by (Designation): ',
                        ],
                        $ris ?? null
                        ) !!}


                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">

                        <div class="" id="tableContainer" style="margin-top: 50px">
                            <table class="table table-bordered table-striped table-hover" id="trans_table" style="width: 100% !important">
                                <thead>
                                <tr>
                                    <th style="width: 8%">Stock No.</th>
                                    <th style="width: 8%">Unit</th>
                                    <th style="width: 25%">Item</th>
                                    <th style="width: 25%">Description</th>
                                    <th style="width: 8%">Qty</th>
                                    <th style="width: 8%">Actual Qty</th>
                                    <th style="width: 25%">Remarks</th>
                                    <th style="width: 50px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($ris->transDetails))
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @foreach($ris->transDetails as $item)
                                        @php
                                            $grandTotal = $grandTotal + $item->actual_cost;
                                        @endphp
                                        @include('dynamic_rows.ris_items',[
                                            'item' => $item,
                                        ])
                                    @endforeach
                                @else
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @include('dynamic_rows.ris_items')
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
        let uri = '{{route("dashboard.ris.update","slug")}}';
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
                // $(".select2_papCode").select2("val", "");
                // $(".select2_papCode").trigger('change');
                $(".remove_row_btn").each(function () {
                    $(this).click();
                })
                $(".add_button").click();
                toast('success','RIS successfully updated.','Success!');
                Swal.fire({
                    title: 'RIS Successfully updated',
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
                        let link = "{{route('dashboard.ris.print','slug')}}";
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
        $("#"+parentTrId+" [for='itemName']").val(data.text);
        $("#"+parentTrId+" [for='uom']").val(data.populate.uom);
    });

    {{--$(".select2_pap_code_{{$rand}}").select2({--}}
    {{--    ajax: {--}}
    {{--        url: '{{route("dashboard.ajax.get","pap_codes")}}',--}}
    {{--        dataType: 'json',--}}
    {{--        delay : 250,--}}
    {{--    },--}}
    {{--    placeholder: 'Type PAP Code/Title/Description',--}}
    {{--});--}}

    function deleteRow(button) {
        const row = button.closest('tr');
        row.remove();
    }


    </script>
@endsection
