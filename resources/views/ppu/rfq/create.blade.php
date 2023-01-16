@php($rand = \Illuminate\Support\Str::random())
@extends('layouts.modal-content',['form_id' => 'create_rfq_form_'.$rand , 'slug' => $trans->slug])

@section('modal-header')
    {{$trans->ref_no}}
@endsection

@section('modal-body')
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_deadline',[
            'label' => 'Deadline:',
            'cols' => 12,
            'type' => 'date',
        ]) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_s_name',[
            'label' => 'Signatory Name:',
            'cols' => 12,
        ],
        \App\Swep\Helpers\Helper::getSetting('rfq_name')->string_value ?? null
        ) !!}
        {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_s_position',[
            'label' => 'Signatory Name:',
            'cols' => 12,
        ],
        \App\Swep\Helpers\Helper::getSetting('rfq_position')->string_value ?? null
        ) !!}
    </div>

@endsection

@section('modal-footer')
    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Create</button>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("#create_rfq_form_{{$rand}}").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.rfq.store")}}',
                data : form.serialize()+'&trans={{$trans->slug}}',
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,true);
                    toast('success','RFQ Successfully created.','Success!');
                    Swal.fire({
                        title: 'RFQ Successfully Created',
                        icon: 'success',
                        html:
                            'Click the print button below to print RFQ.<br>You may also view RFQs on RFQ Tab of this page or by navigating to PRs and JRs.',
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
                            let link = "{{route('dashboard.rfq.print','slug')}}";
                            link = link.replace('slug','EK0puEhxHpm4LNv5');
                            window.open(link, '_blank');
                        }
                    })
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })
    </script>
@endsection

