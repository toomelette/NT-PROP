@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Gate Pass</h1>
    </section>
@endsection

@section('content2')

<section class="content col-md-12">
    <div role="document">
        <form id="add_form">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Gate Pass</h3>
                    <button class="btn btn-primary btn-sm pull-right" id="saveBtn" type="button">
                        <i class="fa fa-check"></i> Save
                    </button>
                    <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.gp.index')}}">Back</a>
                </div>
                    <div class="box-body">

                        {!! \App\Swep\ViewHelpers\__form2::textbox('bearer',[
                           'label' => 'Bearer:',
                           'cols' => 3,
                           'id' => 'bearer'
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('originated_from',[
                            'label' => 'Originated from:',
                            'cols' => 3,
                            'id' => 'originated_from'
                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                           'label' => 'Date:',
                           'cols' => 3,
                           'type' => 'date',
                        ]) !!}

                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">

                        {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                          'label' => 'Approved By:',
                          'cols' => 3,
                          'id' => 'approved_by'
                       ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                       'label' => 'Position:',
                       'cols' => 3,
                       'id' => 'approved_by_designation'
                        ]) !!}


                        {!! \App\Swep\ViewHelpers\__form2::textbox('guard_on_duty',[
                           'label' => 'Guard on Duty:',
                           'cols' => 3,
                           'id' => 'guard_on_duty'
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('received_by',[
                           'label' => 'Received by:',
                           'cols' => 3,
                           'id' => 'received_by'
                        ]) !!}



                    </div>
                </div>

                <div class="box box-success">
                    <div class="row">
                        <div class="col-md-12" style="min-height: 200px">
                            <button data-target="#gp_items_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=gp_items" style="margin-bottom: 5px; margin-top: 5px; margin-right: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                            <table id="gp_items_table" class="table-bordered table table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 10%">Qty</th>
                                    <th style="width: 35%">Item</th>
                                    <th style="width: 35%">Description</th>
                                    <th style="width: 8%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @include('dynamic_rows.gp_items')
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
            let form = $('#add_form');
            let uri = '{{route("dashboard.gp.store")}}';
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
                    toast('success','Gate Pass successfully added.','Success!');
                    Swal.fire({
                        title: 'Gate Pass Successfully created',
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
                            let link = "{{route('dashboard.gp.print','slug')}}";
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


        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        {{--$(".select2_item").select2({--}}
        {{--    ajax: {--}}
        {{--        url: '{{route("dashboard.ajax.get","articles")}}',--}}
        {{--        dataType: 'json',--}}
        {{--        delay : 250,--}}
        {{--    },--}}
        {{--    placeholder: 'Select item',--}}
        {{--});--}}

        {{--$('.select2_item').on('select2:select', function (e) {--}}
        {{--    let t = $(this);--}}
        {{--    let parentTrId = t.parents('tr').attr('id');--}}
        {{--    let data = e.params.data;--}}
        {{--    $("#"+parentTrId+" [for='itemName']").val(data.text);--}}
        {{--});--}}

    </script>
@endsection

