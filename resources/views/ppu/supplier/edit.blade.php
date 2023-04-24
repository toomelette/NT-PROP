@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('layouts.modal-content',['form_id' => 'edit_supplier_form' , 'slug' => $supplier->slug])

@section('modal-header')
    {{$supplier->name}}
@endsection

@section('modal-body')
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('name',[
            'label' => 'Name:',
            'cols' => 3,
        ],
        $supplier ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('address',[
            'label' => 'Address:',
            'cols' => 3,
        ],
        $supplier ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('office_contact_number',[
            'label' => 'Office Tel/Phone Number:',
            'cols' => 3,
        ],
        $supplier ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('tin',[
            'label' => 'TIN:',
            'cols' => 3,
        ],
        $supplier ?? null) !!}

        {!! \App\Swep\ViewHelpers\__form2::textbox('contact_person',[
            'label' => 'Contact Person:',
            'cols' => 3,
        ],
        $supplier ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('contact_person_address',[
            'label' => 'Address:',
            'cols' => 3,
        ],
        $supplier ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('phone_number_1',[
            'label' => 'Primary Phone Number:',
            'cols' => 3,
        ],
        $supplier ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('phone_number_2',[
            'label' => 'Secondary Phone Number:',
            'cols' => 3,
        ],
        $supplier ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('fax_number',[
            'label' => 'Fax Number:',
            'cols' => 3,
        ],
        $supplier ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('designation',[
            'label' => 'Designation:',
            'cols' => 3,
        ],
        $supplier ?? null) !!}
    </div>

@endsection

@section('modal-footer')
<button type="button" class="btn btn-primary btn-sm" id="save_edit_btn"><i class="fa fa-check"></i> Save</button>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        let active;
        $("#save_edit_btn").click(function(e) {
            e.preventDefault();
            let form = $("#edit_supplier_form");
            let uri = '{{route("dashboard.supplier.update","slug")}}';
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
                    suppliers_tbl.draw(false);
                    toast('info','Supplier successfully updated.','Updated');
                },
                error: function (res) {
                    errored(form,res);
                }
            });
        });
    });
</script>
@endsection

