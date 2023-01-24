@php($rand = \Illuminate\Support\Str::random())
@extends('layouts.modal-content')

@section('modal-header')
    {{$trans->ref_no}}
@endsection

@section('modal-body')
    <div class="row">
        <div class="col-md-9">
            <div class="bs-example">
                <div class="embed-responsive embed-responsive-16by9" style="height: 1019.938px;">
                    <iframe id="print_f_frame" class="embed-responsive-item" src="{{route('dashboard.my_'.strtolower($trans->ref_book).'.print',$trans->slug)}}?noPrint=true"></iframe>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <form id="create_rfq_form_{{$rand}}" data="{{$trans->slug}}">

                <fieldset {{(!empty($trans->rfq)) ? 'disabled':''}}>
                    <p class="page-header-sm text-info" style="border-bottom: 1px solid #cedbe1">
                        Create RFQ
                    </p>
                    <p class="text-danger">{{(!empty($trans->rfq)) ? 'Exisiting RFQ was found.':''}}</p>
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
                    <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-check"></i> Create</button>
                </fieldset>
            </form>

        </div>
    </div>

@endsection

@section('modal-footer')
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                            link = link.replace('slug',res.slug);
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

