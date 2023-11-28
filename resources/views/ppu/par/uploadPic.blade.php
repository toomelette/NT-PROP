@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Upload Picture for PPE -> {{$par->propertyno}}</h1>
        <h3>{{$par->article}}</h3>
        <h6>{{$par->description}}</h6>
    </section>
@endsection

@section('content2')
    <section class="content">
        <div role="document">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{route('dashboard.par.savePict')}}" method="post" enctype="multipart/form-data" class="dropzone" id="my-great-dropzone">
                                    <input type="text" value="{{$par->slug}}" name="par_slug" id="par_slug" hidden>
                                    @csrf
                                </form>

                                <br>
                                <div class="post">
                                    <div class="row margin-bottom">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                @php
                                                    $slug = $par->slug;
                                                    $directory = 'C:/external1/swep_ppu_storage/PPU/PAR/'.$slug;

                                                    // Check if the directory exists
                                                    if(File::exists($directory)) {
                                                        $files = File::allFiles($directory);
                                                    } else {
                                                        $files = [];
                                                    }
                                                @endphp

                                                @if(count($files) > 0)
                                                    <ul>
                                                        @foreach($files as $file)
                                                            <li>{{ $file->getFilename() }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="col-md-12">
                                                        <p>No files found in the directory.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        Dropzone.options.myGreatDropzone = { // camelized version of the `id`
            acceptedFiles: "image/*",
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            success: function (file, response) {
                console.log("Logs:", response);
                toast('success',"Image Successfully uploaded.",'Success!');
                /*Swal.fire({
                    title: 'Success!',
                    text: 'Image Successfully uploaded. Thank you.',
                    icon: 'success'
                });*/
            },
            error: function (res) {
                console.error("Error:", res);
                toast('error',"Error uploading file.",'Error!');
            }
        };
    </script>
@endsection
