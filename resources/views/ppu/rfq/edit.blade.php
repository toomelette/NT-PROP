@php($rand = \Illuminate\Support\Str::random())
@extends('layouts.modal-content')

@section('modal-header')

@endsection

@section('modal-body')

    <div class="row">
        <div class="col-md-9">
            <div class="bs-example">
                <div class="embed-responsive embed-responsive-16by9" style="height: 1019.938px;">
                    <iframe class="embed-responsive-item" src="{{route('dashboard.my_'.strtolower($trans->transaction->ref_book).'.print',$trans->transaction->slug)}}?noPrint=true"></iframe>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <form id="edit_rfq_form_{{$rand}}" data="{{$trans->slug}}">

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
                        ],
                        $trans ?? null
                        ) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_s_name',[
                            'label' => 'Signatory Name:',
                            'cols' => 12,
                        ],
                        $trans ?? null
                        ) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('rfq_s_position',[
                            'label' => 'Signatory Position:',
                            'cols' => 12,
                        ],
                        $trans ?? null
                        ) !!}
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-check"></i> Save</button>
                </fieldset>
            </form>

        </div>
    </div>
@endsection

@section('modal-footer')

@endsection

@section('scripts')
    <script type="text/javascript">
        $("#edit_rfq_form_{{$rand}}").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            let uri = '{{route("dashboard.rfq.update","slug")}}';
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
                     all_rqf_tbl_active = res.slug;
                    all_rqf_tbl.draw(false);
                    toast('info','RFQ successfully updated.','Updated');
                },
                error: function (res) {
                    errored(form,res);
                }
            })

        })
    </script>
@endsection

