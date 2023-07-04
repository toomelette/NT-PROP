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
                    <div class="" style="">
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
                    </div>
                    <div class="clearfix"></div>
                    <div id="details" class="hidden">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('title',[
                                                'label' => 'Title:',
                                                'cols' => 4,
                                            ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('category',[
                                                'label' => 'Category:',
                                                'cols' => 4,
                                            ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('abc',[
                                             'label' => 'ABC:',
                                             'cols' => 2,
                                             'readonly' => 'readonly',
                                         ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('registry_number',[
                                             'label' => 'Registry Number:',
                                             'cols' => 2,
                                         ]) !!}

                        <div class="form-group col-md-3 supplier">
                            <label for="awardee">Supplier: </label>
                            {!! Form::select('awardee', $suppliers, null, ['class' => 'form-control']) !!}
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
                        {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
                                             'label' => 'Remarks:',
                                             'cols' => 6,
                                         ]) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('reason',[
                                             'label' => 'Reason for Award:',
                                             'cols' => 6,
                                         ]) !!}

                        <div class="col-md-6 no-padding" style="margin-top: 50px">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('organization_name',[
                                             'label' => 'Organization Name:',
                                             'cols' => 12,
                                         ], 'Sugar Reg. Admin. - Visayas') !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('contact_name',[
                                                 'label' => 'Contact Name:',
                                                 'cols' => 12,
                                             ], 'ATTY. GUILLERMO C. TEJIDA III') !!}
                        </div>

                        <div class="col-md-6 no-padding" style="border-left: 1px solid black; margin-top: 50px">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('signatory_name',[
                                             'label' => 'Signatory:',
                                             'cols' => 12,
                                         ], 'ATTY. GUILLERMO C. TEJIDA III') !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('signatory_title',[
                                                 'label' => 'Designation:',
                                                 'cols' => 12,
                                             ], 'Deputy Administrator II for Regulation') !!}
                        </div>
                        <div class="box-footer pull-right">
                            <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
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

        $('input[name="ref_number"]').unbind().bind('keyup', function(e) {
            if($('input[name="ref_number"]').val() === ''){
                toast('error','Reference Number cannot be empty','Invalid!');
            }
            else {
                let refBook = $('select[name="ref_book"]').val();
                if (e.keyCode === 13) {
                    let uri = '{{route("dashboard.awardNoticeAbstract.ByRefNumber", ["refNumber", "refBook"]) }}';
                    uri = uri.replace('refNumber',$(this).val());
                    uri = uri.replace('refBook',refBook);
                    $.ajax({
                        url : uri,
                        type: 'GET',
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success: function (res) {
                            $('#details').removeClass('hidden');
                            $('#slug').val(res.slug);
                            $('input[name="title"]').val(res.purpose);
                            $('input[name="abc"]').val(res.abc);
                            console.log(res);
                        },
                        error: function (res) {
                            toast('error',res.responseJSON.message,'Error!');
                            $('#details').addClass('hidden');
                            console.log(res);
                        }
                    })
                }
            }
        });

        $('select[name="awardee"]').change(function() {
            let uri = '{{route("dashboard.awardNoticeAbstract.BySupplier", ["slug"]) }}';
            uri = uri.replace('slug',$(this).val());
            $.ajax({
                url : uri,
                type: 'GET',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    $('input[name="contact_person"]').val(res.contact_person);
                    $('input[name="contact_person_address"]').val(res.contact_person_address);
                    $('input[name="phone_number_1"]').val(res.phone_number_1);
                    $('input[name="phone_number_2"]').val(res.phone_number_2);
                    $('input[name="fax_number"]').val(res.fax_number);
                    $('input[name="corporate_title"]').val(res.designation);
                    console.log(res);
                },
                error: function (res) {
                    toast('error',res.responseJSON.message,'Error!');
                    console.log(res);
                }
            })
        });
    </script>
@endsection
