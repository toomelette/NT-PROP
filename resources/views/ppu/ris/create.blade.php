@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Requisition and Issue Slip</h1>
{{--            <div class="pull-right">--}}
{{--                <button type="button" style="margin-right: 3px" class="btn btn-primary btn-sm" id="saveBtn">Save</button>--}}
{{--                <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.ris.index')}}">Back</a>--}}
{{--            </div>--}}
    </section>
@endsection
@section('content2')

<section class="content col-md-12">
    <div role="document">
            <form id="add_form">

                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create RIS</h3>
                            <button class="btn btn-primary btn-sm pull-right"  id="saveBtn" type="submit">
                                <i class="fa fa-check"></i> Save
                            </button>
                            <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.ris.index')}}">Back</a>
                        </div>

                        <div class="box-body">

{{--                            {!! \App\Swep\ViewHelpers\__form2::select('pap_code',[--}}
{{--                               'cols' => 6,--}}
{{--                               'label' => 'PAP Code:',--}}
{{--                               'options' => [],--}}
{{--                               'class' => 'select2_papCode',--}}
{{--                           ]) !!}--}}

                            {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                               'label' => 'Responsibility Center',
                               'cols' => 3,
                               'id' => 'resp_center',
                               'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                               'label' => 'RIS Date:',
                               'cols' => 3,
                               'type' => 'date',
                            ]) !!}



                            {!! \App\Swep\ViewHelpers\__form2::textbox('sai',[
                                'label' => 'SAI Number:',
                                'cols' => 3,
                                'id' => 'sai_no',
                             ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('sai_date',[
                              'cols' => 3,
                              'label' => 'SAI Date:',
                              'type' => 'date',
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textarea('purpose',[
                              'cols' => 6,
                              'label' => 'Purpose',
                              'rows' => 1
                            ]) !!}
                        </div>
                    </div>

                    <div class="box box-success">
                        <div class="box-body">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                                      'cols' => 3,
                                      'label' => 'Requested by: ',
                                    ]) !!}


                                {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                                      'cols' => 3,
                                      'label' => 'Approved by: ',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('issued_by',[
                                      'cols' => 3,
                                      'label' => 'Issued by: ',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('received_by',[
                                  'cols' => 3,
                                  'label' => 'Received by:',
                                ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                                      'cols' => 3,
                                      'label' => 'Requested by (Designation): ',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                                      'cols' => 3,
                                      'label' => 'Approved by (Designation): ',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('issued_by_designation',[
                                  'cols' => 3,
                                  'label' => 'Issued by (Designation): ',
                                ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('received_by_designation',[
                                  'cols' => 3,
                                  'label' => 'Received by (Designation): ',
                                ]) !!}
                            </div>
                    </div>
                <div class="box box-success">
                        <div class="row">
                            <div class="col-md-12" style="min-height: 200px">
                                <button data-target="#ris_items_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=ris_items" style="margin-bottom: 5px; margin-top: 5px; margin-right: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                                    <table id="ris_items_table" class="table-bordered table table-condensed table-striped">
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
                                        @include('dynamic_rows.ris_items')
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                </div>

            </form>
        </div>
    </div>
</section>
@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">


        $("#add_form").submit(function(e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.ris.store")}}',
                data : form.serialize(),
                type: 'POST',
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
                    toast('success','RIS successfully added.','Success!');
                    Swal.fire({
                        title: 'RIS Successfully created',
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
            $("#"+parentTrId+" [for='uom']").val(data.populate.uom);
            $("#"+parentTrId+" [for='itemName']").val(data.text);
        });


    </script>
@endsection

