@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Create Notice of Award</h1>
    </section>
@endsection
@section('content2')
    <section class="content">
        <div role="document">
            <form id="add_form">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                            <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                        </div>
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('ref_no',[
                                        'label' => 'PR/JR No.:',
                                        'cols' => 3,
                                    ]) !!}
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('document_no',[
                                        'label' => 'MEMO No.:',
                                        'cols' => 3,
                                    ]) !!}
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                               'label' => 'date:',
                               'cols' => 3,
                               'type' => 'date',
                            ]) !!}
                            <div class="clearfix"></div>
                            <h3 class="text-center">NOTICE OF AWARD</h3>
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('supplier',[
                                        'label' => 'Supplier:',
                                        'cols' => 3,
                                    ]) !!}
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_address',[
                                        'label' => 'Address:',
                                        'cols' => 3,
                                    ]) !!}
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_representative',[
                                        'label' => 'Representative:',
                                        'cols' => 3,
                                    ]) !!}
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('supplier_representative_position',[
                                        'label' => 'Position:',
                                        'cols' => 3,
                                    ]) !!}
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textarea('project_name',[
                                'label' => 'Project Name:',
                                'rows' => 5, // Specify the number of rows you want to display
                                'cols' => 12,
                            ]) !!}
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textarea('contents',[
                                'label' => 'Content:',
                                'rows' => 10,
                                'cols' => 12,
                            ]) !!}
                            <div class="clearfix"></div>
                            {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                                        'label' => 'Approved By:',
                                        'cols' => 3,
                                    ], 'PABLO LUIS S. AZCONA') !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                                        'label' => 'Designation:',
                                        'cols' => 3,
                                    ], 'Administrator') !!}
                            <div class="col-md-12">
                                <div class="box-footer pull-right">
                                    <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#add_form');
            loading_btn(form);
            $.ajax({
                type: 'POST',
                url: '{{route("dashboard.noa.store")}}',
                data: form.serialize(),
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function(res) {
                    console.log(res);
                    $('#printIframe').attr('src',res.route);
                    form.find('input, select, textarea').val('');
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
                            let link = "{{route('dashboard.noa.print','slug')}}";
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
    </script>
@endsection