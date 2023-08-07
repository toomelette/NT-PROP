@php
    $rand = \Illuminate\Support\Str::random(10);
@endphp
@extends('layouts.modal-content',['form_id' => 'action_form_'.$rand, 'slug'=> $request->slug])

@section('modal-header')
    {{$request->request_no}} | {{$request->requested_by}} - Actions
@endsection

@section('modal-body')
    <div class="well well-sm">
        <div class="row">
            <div class="col-md-2">
                <dl style="margin-bottom: 5px">
                    <dt>Request No.:</dt>
                    <dd>{{$request->request_no}}</dd>
                </dl>
            </div>
            <div class="col-md-3">
                <dl style="margin-bottom: 5px">
                    <dt>Date & Time of Request:</dt>
                    <dd>{{\App\Swep\Helpers\Helper::dateFormat($request->created_at,'Y-m-d \a\t h:i A')}}</dd>
                </dl>
            </div>
            <div class="col-md-7">
                <dl style="margin-bottom: 5px">
                    <dt>Authorized Passengers:</dt>
                    <dd>
                        @if(!empty($request->passengers))
                            {{$request->passengers->implode('name','; ')}}
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="radio">
        <label style="padding-left: 0px !important;">
            <input type="radio" class="iCheck_{{$rand}}" name="action_made" id="approve_{{$rand}}" value="APPROVED" target="approve_container_{{$rand}}">
            <span class="text-strong text-success"><i class="fa fa-thumbs-up" style="font-size: 20px"></i> Approve and assign vehicle and driver</span>
        </label>
    </div>
    <div id="approve_container_{{$rand}}" class="cont_{{$rand}}">
        <fieldset disabled="disabled">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::select('vehicle_assigned',[
                    'cols' => 6,
                    'label' => 'Assign vehicle',
                    'options' => \App\Swep\Helpers\Arrays::vehicles(),
                ]) !!}
                {!! \App\Swep\ViewHelpers\__form2::select('driver_assigned',[
                    'cols' => 6,
                    'label' => 'Assign driver',
                    'options' => \App\Swep\Helpers\Arrays::drivers(),
                ]) !!}
            </div>
        </fieldset>
    </div>
    <div class="radio">
        <label style="padding-left: 0px !important;">
            <input type="radio" class="iCheck_{{$rand}}" name="action_made" id="disapprove_{{$rand}}" value="DISAPPROVED" target="disapprove_container_{{$rand}}">
            <span class="text-danger text-strong"><i class="fa fa-thumbs-down" style="font-size: 20px"></i> Disapprove and state the reason</span>
        </label>
    </div>
    <div id="disapprove_container_{{$rand}}" class="cont_{{$rand}}">
        <fieldset disabled="disabled">
            <div class="row">
                {!! \App\Swep\ViewHelpers\__form2::textbox('reason',[
                    'label' => 'Reason for disapproval:',
                    'cols' => 12,
                ]) !!}
            </div>
        </fieldset>
    </div>

@endsection

@section('modal-footer')
    <button class="btn btn-primary btn-sm" type="submit" disabled="disabled" id="submit_{{$rand}}"><i class="fa fa-check"></i> Save</button>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(".iCheck_{{$rand}}").iCheck(iCheckRadioOptions);
        $(".iCheck_{{$rand}}").on('ifChecked', function(event){
            $(".cont_{{$rand}}").each(function () {
                $(this).find('fieldset').attr('disabled',true);
            })

            $("#"+$(this).attr('target')).find('fieldset').removeAttr('disabled');
            $("#submit_{{$rand}}").removeAttr('disabled');
        });

        $("#action_form_{{$rand}}").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            let uri = '{{route("dashboard.request_vehicle.take_action","slug")}}';
            uri = uri.replace('slug',form.attr('data'));
            loading_btn(form);
            $.ajax({
                url : uri,
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,true);
                    active = res.slug;
                    request_tbl.draw(false);
                    toast('info','Action successfully made.','Updated');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        
        })

    </script>
@endsection

