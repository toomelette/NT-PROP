@php
$rand = \Illuminate\Support\Str::random(10);
@endphp
@extends('layouts.modal-content',['form_id' => 'edit_pap_form_'.$rand, 'slug' => $pap->slug])

@section('modal-header')
    {{$pap->pap_code}} - Edit
@endsection

@section('modal-body')
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::select('year',[
            'label' => 'Year:',
            'cols' => 6,
            'options' => 'year',
        ],
        $pap ?? null
        ) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('budget_type',[
            'label' => 'Budget Type:',
            'cols' => 6,
            'options' => \App\Swep\Helpers\Arrays::fundSources(),
        ],
        $pap ?? null
        ) !!}

    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
            'label' => 'Responsibility Center:',
            'cols' => 12,
            'options' => \App\Swep\Helpers\PPUHelpers::respCentersArray(),
        ],
        $pap ?? null
        ) !!}
    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('pap_title',[
            'label' => 'PAP Title:*',
            'cols' => 12,
        ],
        $pap ?? null
        ) !!}

        {!! \App\Swep\ViewHelpers\__form2::textarea('pap_desc',[
            'label' => 'PAP Description:*',
            'cols' => 12,
        ],
        $pap ?? null
        ) !!}
    </div>

    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('ps',[
            'label' => 'PS:',
            'cols' => 4,
            'class' => 'text-right autonum_'.$rand,
        ],
        $pap ?? null
        ) !!}

        {!! \App\Swep\ViewHelpers\__form2::textbox('co',[
            'label' => 'Capital Outlay:',
            'cols' => 4,
            'class' => 'text-right autonum_'.$rand,
        ],
        $pap ?? null
        ) !!}

        {!! \App\Swep\ViewHelpers\__form2::textbox('mooe',[
            'label' => 'MOOE:',
            'cols' => 4,
            'class' => 'text-right autonum_'.$rand,
        ],
        $pap ?? null
        ) !!}
    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('pcent_share',[
            'label' => 'Percent Share:*',
            'cols' => 4,
            'type' => 'number',
            'step' => '0.01',
        ],
        $pap ?? null
        ) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('type',[
            'label' => 'Type:',
            'cols' => 4,
            'options' => \App\Swep\Helpers\Arrays::papTypes()
        ],
        $pap ?? null
        ) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('status',[
            'label' => 'Status:',
            'cols' => 4,
            'options' => \App\Swep\Helpers\Arrays::activeInactive()
        ],
        $pap ?? null
        ) !!}
    </div>

@endsection

@section('modal-footer')
 <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
@endsection

@section('scripts')
<script type="text/javascript">
    $(".autonum_{{$rand}}").each(function(){
        new AutoNumeric(this, autonum_settings);
    });
    
    $("#edit_pap_form_{{$rand}}").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let uri = '{{route("dashboard.pap.update","slug")}}';
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
                active = res.slug;
                pap_tbl.draw(false);
                toast('info','PAP successfully updated.','Updated');
            },
            error: function (res) {
                errored(form,res);
            }
        })
    
    })

</script>
@endsection

