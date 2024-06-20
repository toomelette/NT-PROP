<div class="row">
    {!! \App\Swep\ViewHelpers\__form2::select('location',[
    'label' => 'Location:',
    'cols' => 3,
    'options' => \App\Swep\Helpers\Arrays::location(),
    'id' => 'location'
    ],$parInv ?? null) !!}

    {!! \App\Swep\ViewHelpers\__form2::textbox('article',[
        'label' => 'Article',
        'cols' => 3,
        'id' => 'article'
     ],$parInv ?? null) !!}


    {!! \App\Swep\ViewHelpers\__form2::textbox('office',[
       'label' => 'Office',
       'cols' => 3,
       'id' => 'office'
    ],$parInv ?? null) !!}

    {!! \App\Swep\ViewHelpers\__form2::select('condition',[
      'label' => 'Condition:',
      'cols' => 3,
      'options' => \App\Swep\Helpers\Arrays::condition(),
      'id' => 'condition'
    ],$parInv ?? null) !!}


    {!! \App\Swep\ViewHelpers\__form2::textarea('description',[
      'label' => 'Description',
      'cols' => 3,
      'id' => 'description'
    ],$parInv ?? null) !!}



</div>

<div class="row">
    <div class="col-md-12">
        <form action="{{route('dashboard.par.savePict')}}" method="post" enctype="multipart/form-data" class="dropzone" id="my-great-dropzone">
            <input type="text" value="{{$parInv->slug}}" name="par_slug" id="par_slug" hidden>
            @csrf
        </form>
    </div>
</div>

<script>
    $("#my-great-dropzone").dropzone({
        url: "{{route('dashboard.par.savePict')}}?par_slug={{$parInv->slug}}",
        method : 'post'
    });
</script>