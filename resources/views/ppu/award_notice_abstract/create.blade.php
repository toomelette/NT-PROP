@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Award Notice Abstract</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <div class="box box-solid">

            <form id="ana_form">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                        <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                    </div>
                    <input class="hidden" type="text" id="slug" name="slug"/>
                    <div class="" style="margin-bottom: 100px">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                                'label' => 'Date:',
                                'cols' => 2,
                                'type' => 'date',
                                'required' => 'required'
                             ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::select('ref_book', [
                                            'label' => 'Reference Type:',
                                            'cols' => 2,
                                            'options' => [
                                                'PR' => 'PR',
                                                'JR' => 'JR'
                                            ]
                                        ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                                                'label' => 'Reference Number:',
                                                'cols' => 2,
                                            ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('registry_number',[
                                             'label' => 'Registry Number:',
                                             'cols' => 2,
                                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('category',[
                                                'label' => 'Category:',
                                                'cols' => 4,
                                            ]) !!}
                    </div>
                    <div class="form-group col-md-3 supplier">
                        <label for="supplier">Supplier: </label>
                        {!! Form::select('supplier', $suppliers, null, ['class' => 'form-control']) !!}
                    </div>
                    {!! \App\Swep\ViewHelpers\__form2::textbox('contract_amount',[
                                             'label' => 'Contract Amount:',
                                             'cols' => 3,
                                         ]) !!}
                    <div class="clearfix"></div>
                        {!! \App\Swep\ViewHelpers\__form2::textbox('contact_person',[
                                             'label' => 'Contact Person:',
                                             'cols' => 2,
                                         ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('contact_person_address',[
                                             'label' => 'Address:',
                                             'cols' => 2,
                                         ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('phone_number_1',[
                                             'label' => 'Phone Number 1:',
                                             'cols' => 2,
                                         ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('phone_number_2',[
                                             'label' => 'Phone Number 2:',
                                             'cols' => 2,
                                         ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('fax_number',[
                                             'label' => 'Fax Number:',
                                             'cols' => 2,
                                         ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('corporate_title',[
                                             'label' => 'Corporate Title:',
                                             'cols' => 2,
                                         ]) !!}
                        <div class="clearfix"></div>
                        <br>
                        <br>
                        {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
                                             'label' => 'Remarks:',
                                             'cols' => 6,
                                         ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('reason',[
                                             'label' => 'Reason for Award:',
                                             'cols' => 6,
                                         ]) !!}
                    <div class="box-footer pull-right">
                        <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
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
        $(document).ready(function() {

        });

        $('#saveBtn').click(function(e) {
            //let refBook = $('select[name="ref_book"]').val();
            if($('input[name="ref_number"]').val() === ''){
                toast('error','Reference Number cannot be empty','Invalid!');
            }
            else if ($('input[name="reason"]').val() === ''){
                toast('error','Reason cannot be empty','Invalid!');
            }
            else {
                e.preventDefault();
                let form = $('#ana_form');
                loading_btn(form);
                $.ajax({
                    type: 'POST',
                    url: '{{route("dashboard.awardNoticeAbstract.store")}}',
                    data: form.serialize(),
                    headers: {
                        {!! __html::token_header() !!}
                    },
                    success: function(res) {
                        console.log(res);
                        $('#printIframe').attr('src',res.route);
                        form.find('input, select, textarea').val('');
                        toast('success','Notice successfully created.','Success!');
                    },
                    error: function(res) {
                        // Display an alert with the error message
                        toast('error',res.responseJSON.message,'Error!');
                    }
                });
            }
        });
    </script>
@endsection
