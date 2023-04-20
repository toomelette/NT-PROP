@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('layouts.modal-content',['form_id' => 'edit_form' , 'slug' => $ana->slug])

@section('modal-header')
    {{$ana->title}}
@endsection

@section('modal-body')
    <div class="row">
        <input class="hidden" type="text" id="slug" name="slug" value=""/>
        <div class="" style="">
            {!! \App\Swep\ViewHelpers\__form2::textbox('award_date',[
                    'label' => 'Date:',
                    'cols' => 4,
                    'type' => 'date',
                    'required' => 'required'
                 ],
                $ana ?? null) !!}
            {!! \App\Swep\ViewHelpers\__form2::select('ref_book', [
                                'label' => 'Reference Type:',
                                'cols' => 2,
                                'options' => [
                                    'PR' => 'PR',
                                    'JR' => 'JR'
                                ],
                                'readonly' => 'readonly',
                            ],
                            $ana ?? null) !!}
            {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                                    'label' => 'Reference Number:',
                                    'cols' => 4,
                                    'readonly' => 'readonly',
                                ],
                        $ana ?? null) !!}
        </div>
        <div class="clearfix"></div>
        <div id="details">
            {!! \App\Swep\ViewHelpers\__form2::textbox('title',[
                                    'label' => 'Title:',
                                    'cols' => 4,
                                ],
                                $ana ?? null) !!}

            {!! \App\Swep\ViewHelpers\__form2::textbox('category',[
                                    'label' => 'Category:',
                                    'cols' => 4,
                                ],
                                $ana ?? null) !!}

            {!! \App\Swep\ViewHelpers\__form2::textbox('approved_budget',[
                                 'label' => 'ABC:',
                                 'cols' => 2,
                                 'readonly' => 'readonly',
                             ],
                            $ana ?? null) !!}

            {!! \App\Swep\ViewHelpers\__form2::textbox('registry_number',[
                                 'label' => 'Registry Number:',
                                 'cols' => 2,
                             ],
                            $ana ?? null) !!}

                {!! \App\Swep\ViewHelpers\__form2::select('awardee',[
                'cols' => 3,
                'label' => 'Supplier:',
                'options' => \App\Swep\Helpers\Arrays::suppliers(),
            ],
            $ana ?? null) !!}

            {{--<div class="form-group col-md-3 supplier">
                <label for="awardee">Supplier: </label>
                {!! Form::select('awardee', $suppliers, $ana ?? null, ['class' => 'form-control']) !!}
            </div>--}}
            {!! \App\Swep\ViewHelpers\__form2::textbox('contract_amount',[
                                     'label' => 'Contract Amount:',
                                     'cols' => 3,
                                 ],
                            $ana ?? null) !!}
            <div class="clearfix"></div>
            {!! \App\Swep\ViewHelpers\__form2::textbox('contact_person',[
                                 'label' => 'Contact Person:',
                                 'cols' => 2,
                             ],
                            $ana ?? null) !!}
            {!! \App\Swep\ViewHelpers\__form2::textbox('contact_person_address',[
                                 'label' => 'Address:',
                                 'cols' => 2,
                             ],
                            $ana ?? null) !!}
            {!! \App\Swep\ViewHelpers\__form2::textbox('phone_number_1',[
                                 'label' => 'Phone Number 1:',
                                 'cols' => 2,
                             ],
                            $ana ?? null) !!}
            {!! \App\Swep\ViewHelpers\__form2::textbox('phone_number_2',[
                                 'label' => 'Phone Number 2:',
                                 'cols' => 2,
                             ],
                            $ana ?? null) !!}
            {!! \App\Swep\ViewHelpers\__form2::textbox('fax_number',[
                                 'label' => 'Fax Number:',
                                 'cols' => 2,
                             ],
                            $ana ?? null) !!}
            {!! \App\Swep\ViewHelpers\__form2::textbox('corporate_title',[
                                 'label' => 'Corporate Title:',
                                 'cols' => 2,
                             ],
                            $ana ?? null) !!}
            <div class="clearfix"></div>
            <br>
            {!! \App\Swep\ViewHelpers\__form2::textbox('remarks',[
                                 'label' => 'Remarks:',
                                 'cols' => 6,
                             ],
                            $ana ?? null) !!}
            {!! \App\Swep\ViewHelpers\__form2::textbox('reason_for_award',[
                                 'label' => 'Reason for Award:',
                                 'cols' => 6,
                             ],
                            $ana ?? null) !!}

            <div class="col-md-6 no-padding" style="margin-top: 50px">
                {!! \App\Swep\ViewHelpers\__form2::textbox('organization_name',[
                                 'label' => 'Organization Name:',
                                 'cols' => 12,
                             ],
                            $ana ?? null) !!}
                {!! \App\Swep\ViewHelpers\__form2::textbox('contact_name',[
                                     'label' => 'Contact Name:',
                                     'cols' => 12,
                                 ],
                            $ana ?? null) !!}
            </div>

            <div class="col-md-6 no-padding" style="border-left: 1px solid black; margin-top: 50px">
                {!! \App\Swep\ViewHelpers\__form2::textbox('signatory',[
                                 'label' => 'Signatory:',
                                 'cols' => 12,
                             ],
                            $ana ?? null) !!}
                {!! \App\Swep\ViewHelpers\__form2::textbox('designation',[
                                     'label' => 'Designation:',
                                     'cols' => 12,
                                 ],
                            $ana ?? null) !!}
            </div>
        </div>
    </div>

@endsection
@section('modal-footer')
    <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        let active;

        $("#saveBtn").click(function(e) {
            e.preventDefault();
            let form = $('#edit_form');
            let uri = '{{route("dashboard.awardNoticeAbstract.update","slug")}}';
            uri = uri.replace('slug',form.attr('data'));
            loading_btn(form);
            $.ajax({
                url : uri,
                data : form.serialize(),
                type: 'PATCH',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,true);
                    active = res.id;
                    ana_tbl.draw(false);
                    toast('info','ANA successfully updated.','Updated');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        });
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

