@php
    $rand = \Illuminate\Support\Str::random(10);
@endphp
@extends('layouts.modal-content',['form_id'=>'edit_email_recipients_form_'.$rand,'slug' => $rc->rc_code])

@section('modal-header')
    {{$rc->desc}}
@endsection

@section('modal-body')
    Email Recipients <button class="btn btn-xs btn-success pull-right add_row_btn_{{$rand}} " type="button"><i class="fa fa-plus"></i> Add</button>
    <br><br>
    <table class="table table-bordered table-condensed" id="email_table_{{$rand}}">
        <thead>
            <tr>
                <th>Email Address</th>
                <th style="width: 50px">Action</th>
            </tr>
        </thead>
        <tbody>
            @if($emails->count() > 0)
                @foreach($emails as $email)
                    <tr>
                        <td>
                            {!! \App\Swep\ViewHelpers\__form2::textboxOnly('email[]',[

                            ],$email->email_address) !!}
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger remove_row_btn" tabindex="-1"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('email[]',[

                        ]) !!}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-danger remove_row_btn" tabindex="-1"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>


@endsection

@section('modal-footer')
    <button class="btn btn-sm pull-right btn-primary"><i class="fa fa-check"></i> Save</button>
@endsection

<table style="display: none">
    <tbody id="temp_tr_{{$rand}}">
    <tr >
        <td>
            {!! \App\Swep\ViewHelpers\__form2::textboxOnly('email[]',[

            ]) !!}
        </td>
        <td>
            <button class="btn btn-sm btn-danger remove_row_btn" tabindex="-1"><i class="fa fa-times"></i></button>
        </td>
    </tr>
    </tbody>

</table>


@section('scripts')
    <script type="text/javascript">
        $(".add_row_btn_{{$rand}}").click(function () {
            $("#email_table_{{$rand}} tbody").append($("#temp_tr_{{$rand}}").html());
        })

        $("#edit_email_recipients_form_{{$rand}}").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            let uri = '{{route("dashboard.email_recipients.update","slug")}}';
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
                    active  = res.slug;
                    email_recipients_tbl.draw(false);
                    toast('info','Email recipients successfully updated.','Updated');
                },
                error: function (res) {
                    if(res.status === 422){
                        toast('error','Please check the email addresses. Some fields might be empty or contains an invalid email.','Error');
                    }
                    errored(form,res);
                }
            })

        })
    </script>
@endsection

