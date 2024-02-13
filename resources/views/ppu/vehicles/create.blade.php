@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>VEHICLES</h1>
    </section>
@endsection
@section('content2')

<section class="content col-md-12">
        <div role="document">
            <form id="add_form">

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Vehicle</h3>
                        <button class="btn btn-primary btn-sm pull-right"  id="saveBtn" type="submit">
                            <i class="fa fa-check"></i> Save
                        </button>
                        <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.vehicles.index')}}">Back</a>
                    </div>

                    <div class="box-body">

                        {!! \App\Swep\ViewHelpers\__form2::textbox('year',[
                           'label' => 'Year:',
                           'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('make',[
                            'label' => 'Brand:',
                            'cols' => 3,
                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('model',[
                           'label' => 'Model:',
                           'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('plate_no',[
                          'label' => 'Plate Number:',
                          'cols' => 3,
                       ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('odometer',[
                        'label' => 'Current Odometer:',
                        'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::select('status',[
                               'label' => 'Condition:',
                               'cols' => 3,
                               'options' => \App\Swep\Helpers\Arrays::condition(),
                       ]) !!}


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
                    succeed(form,true,false);
                    $(".remove_row_btn").each(function () {
                        $(this).click();
                    })
                    $(".add_button").click();
                    toast('success','WMR Successfully created.','Success!');
                    Swal.fire({
                        title: 'WMR Successfully created',
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

