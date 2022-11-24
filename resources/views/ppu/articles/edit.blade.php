@php
    $rand = \Illuminate\Support\Str::random();
@endphp
@extends('layouts.modal-content',['form_id' => 'edit_article_form_'.$rand , 'slug' => $article->id])

@section('modal-header')
    {{$article->article}}
@endsection

@section('modal-body')
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('article',[
            'label' => 'Article:',
            'cols' => 7,
        ],
        $article ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('type',[
            'label' => 'Article:',
            'cols' => 5,
            'options' => \App\Swep\Helpers\Arrays::inventoryTypes(),
        ],
        $article ?? null) !!}
    </div>
    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('unitPrice',[
            'label' => 'Unit Price:',
            'cols' => 4,
            'class' => 'text-right autonum_'.$rand,
        ],
        $article ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('uom',[
            'label' => 'Article:',
            'cols' => 3,
            'options' => \App\Swep\Helpers\Arrays::unitsOfMeasurement(),
        ],
        $article ?? null) !!}
        {!! \App\Swep\ViewHelpers\__form2::select('modeOfProc',[
            'label' => 'Article:',
            'cols' => 5,
            'options' => \App\Swep\Helpers\Arrays::modesOfProcurement(),
        ],
        $article ?? null) !!}
    </div>

    <div class="row">
        {!! \App\Swep\ViewHelpers\__form2::textbox('acctCode',[
            'label' => 'Acct. Code:',
            'cols' => 3,
            'class' => '',
        ],
        $article ?? null) !!}
    </div>

@endsection

@section('modal-footer')
<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Save</button>
@endsection

@section('scripts')
<script type="text/javascript">
    $(".autonum_{{$rand}}").each(function(){
        new AutoNumeric(this, autonum_settings);
    });

    $("#edit_article_form_{{$rand}}").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let uri = '{{route("dashboard.articles.update","slug")}}';
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
                articles_tbl.draw(false);
                toast('info','Item successfulyy updated.','Updated');
            },
            error: function (res) {
                errored(form,res);
            }
        })
    
    })

</script>
@endsection

