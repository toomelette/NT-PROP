@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Waste Materials Report</h1>
    </section>
@endsection
@section('content2')

<section class="content col-md-12">
        <div role="document">
            <form id="add_form">

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create Waste Materials Report</h3>
                        <button class="btn btn-primary btn-sm pull-right"  id="saveBtn" type="submit">
                            <i class="fa fa-check"></i> Save
                        </button>
                        <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.wmr.index')}}">Back</a>
                    </div>

                    <div class="box-body">

                        {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                           'label' => 'Date',
                           'cols' => 3,
                           'type' => 'date',
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('storage',[
                            'label' => 'Place of Storage:',
                            'cols' => 3,
                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('taken_from',[
                           'label' => 'Taken From:',
                           'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('taken_through',[
                          'label' => 'Taken Through:',
                          'cols' => 3,
                       ]) !!}


                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">

                        {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by',[
                          'label' => 'Certified By:',
                          'cols' => 3,
                       ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                           'label' => 'Approved By:',
                           'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('inspected_by',[
                         'label' => 'Inspected By:',
                         'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('witnessed_by',[
                          'label' => 'Witnessed By:',
                          'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by_designation',[
                           'label' => 'Designation:',
                           'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                           'label' => 'Designation',
                           'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('inspected_by_designation',[
                           'label' => 'Designation:',
                           'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('witnessed_by_designation',[
                           'label' => 'Designation',
                           'cols' => 3,
                        ]) !!}

                    </div>
                </div>

                <div class="box box-success">
                    <div class="row">
                        <div class="col-md-12" style="min-height: 200px">
                            <button data-target="#wmr_items_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=wmr_items" style="margin-bottom: 5px; margin-top: 5px; margin-right: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                            <table id="wmr_items_table" class="table-bordered table table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 8%">Qty</th>
                                    <th style="width: 8%">Unit</th>
                                    <th style="width: 30%">Item</th>
                                    <th style="width: 31%">Description</th>
                                    <th style="width: 15%">O.R. No.</th>
                                    <th style="width: 8%">Amount</th>
                                    <th style="width: 50px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @include('dynamic_rows.wmr_items')
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
            let uri = '{{route("dashboard.wmr.store")}}';
            loading_btn(form);

            $.ajax({
                url : uri,
                data: form.serialize(),
                type: 'POST',
                headers: {
                 {!! __html::token_header() !!}
                },
                success: function (res) {
                    console.log(res);
                    toast('success','IAR Successfully created.','Success!');
                    succeed(form,true,true);
                    Swal.fire({
                        title: 'Successfully created',
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
                    toast('error',res.responseJSON.message,'Error!');
                }
            });


        });

        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

    </script>
@endsection
