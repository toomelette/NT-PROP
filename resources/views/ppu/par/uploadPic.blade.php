@extends('layouts.admin-master')
@section('styles')
    <style>
        .btn:focus, .btn:active, button:focus, button:active {
            outline: none !important;
            box-shadow: none !important;
        }

        #image-gallery .modal-footer{
            display: block;
        }

        .thumb{
            margin-top: 15px;
            margin-bottom: 15px;
        }
    </style>
@endsection
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
                            </div>

                            <div class="col-md-12">
                                @php
                                    $slug = $par->slug;
                                    $directory = '/external1/swep_ppu_storage/PPU/PAR/'.$slug;
                                    // Check if the directory exists
                                    if(File::exists($directory)) {
                                        $files = File::allFiles($directory);
                                    } else {
                                        $files = [];
                                    }
                                @endphp
                                <div class="row" style="margin-top: 20px">
                                    @if(count($files) > 0)

                                        @foreach($files as $file)
                                            @php
                                                $fileExtension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
                                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp']; // List of common image file extensions
                                            @endphp
                                            @if(!in_array(strtolower($fileExtension), $imageExtensions))
                                                <div class="col-md-3 thumb">
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <td style="width: 10%; vertical-align: top">
                                                                <button type="button" class="btn btn-sm btn-danger delete_user_btn" onclick="return deletePicture('{{ $slug }}', '{{ $file->getFilename() }}')" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <td style="width: 25%;"><img src="{{ asset('images/pdf-file.svg') }}" alt="" width="100%"></td>
                                                            <td style="text-align: top">
                                                                <a href="{{ asset("images/par/{$slug}/{$file->getFilename()}") }}" target="_blank">

                                                                    {{$file->getFilename()}}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            @else
                                                {{-- File extension is for an image --}}
                                                <div class="col-md-3 thumb">
                                                    <table style="width: 100%">
                                                        <tr>
                                                            <td style="width: 10%; vertical-align: top">
                                                                <button type="button" class="btn btn-sm btn-danger delete_user_btn" onclick="return deletePicture('{{ $slug }}', '{{ $file->getFilename() }}')" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <a class="thumbnail" href="javascript:void(0);" data-image-id="" data-toggle="modal" data-title=""
                                                                   data-image="{{ asset("images/par/{$slug}/{$file->getFilename()}") }}"
                                                                   data-target="#image-gallery">
                                                                    <img class="img-thumbnail"
                                                                         src="{{ asset("images/par/{$slug}/{$file->getFilename()}") }}"
                                                                         alt="{{$file->getFilename()}}" width="200">
                                                                </a>
                                                            </td>

                                                        </tr>
                                                    </table>


                                                </div>
                                            @endif
                                        @endforeach



                                    @else
                                        <div class="col-md-12">
                                            <p>No files found in the directory.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
{{--                                <div class="post">--}}
{{--                                    <div class="row margin-bottom">--}}
{{--                                        <div class="col-sm-12">--}}
{{--                                            <div class="row">--}}
{{--                                                @php--}}
{{--                                                    $slug = $par->slug;--}}
{{--                                                    $directory = '/external1/swep_ppu_storage/PPU/PAR/'.$slug;--}}
{{--                                                    // Check if the directory exists--}}
{{--                                                    if(File::exists($directory)) {--}}
{{--                                                        $files = File::allFiles($directory);--}}
{{--                                                    } else {--}}
{{--                                                        $files = [];--}}
{{--                                                    }--}}
{{--                                                @endphp--}}

{{--                                                @if(count($files) > 0)--}}
{{--                                                    <div class="row">--}}
{{--                                                        <div class="col-md-12">--}}
{{--                                                            <div class="row">--}}
{{--                                                                @foreach($files as $file)--}}
{{--                                                                    @php--}}
{{--                                                                        $fileExtension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);--}}
{{--                                                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp']; // List of common image file extensions--}}
{{--                                                                    @endphp--}}
{{--                                                                    @if(!in_array(strtolower($fileExtension), $imageExtensions))--}}
{{--                                                                        <div class="col-md-3 thumb">--}}
{{--                                                                            <button type="button" class="btn btn-sm btn-danger delete_user_btn" onclick="return deletePicture('{{ $slug }}', '{{ $file->getFilename() }}')" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">--}}
{{--                                                                                <i class="fa fa-trash"></i>--}}
{{--                                                                            </button>--}}
{{--                                                                            <a href="{{ asset("images/par/{$slug}/{$file->getFilename()}") }}" target="_blank">--}}
{{--                                                                                <img src="{{ asset('images/pdf-file.svg') }}" alt="" width="25%">--}}
{{--                                                                                {{$file->getFilename()}}--}}
{{--                                                                            </a>--}}
{{--                                                                        </div>--}}
{{--                                                                    @else--}}
{{--                                                                        --}}{{-- File extension is for an image --}}
{{--                                                                        <div class="col-md-3 thumb">--}}
{{--                                                                            <button type="button" class="btn btn-sm btn-danger delete_user_btn" onclick="return deletePicture('{{ $slug }}', '{{ $file->getFilename() }}')" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">--}}
{{--                                                                                <i class="fa fa-trash"></i>--}}
{{--                                                                            </button><a class="thumbnail" href="javascript:void(0);" data-image-id="" data-toggle="modal" data-title=""--}}
{{--                                                                                        data-image="{{ asset("images/par/{$slug}/{$file->getFilename()}") }}"--}}
{{--                                                                                        data-target="#image-gallery">--}}
{{--                                                                                <img class="img-thumbnail"--}}
{{--                                                                                     src="{{ asset("images/par/{$slug}/{$file->getFilename()}") }}"--}}
{{--                                                                                     alt="{{$file->getFilename()}}" width="200">--}}
{{--                                                                            </a>--}}
{{--                                                                        </div>--}}
{{--                                                                    @endif--}}
{{--                                                                @endforeach--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                    </div>--}}
{{--                                                @else--}}
{{--                                                    <div class="col-md-12">--}}
{{--                                                        <p>No files found in the directory.</p>--}}
{{--                                                    </div>--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                        </div>
                    </div>
                </div>
        </div>
    </section>

@endsection
@section('modals')
    <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="image-gallery-title"></h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="image-gallery-image" class="img-responsive col-md-12" src="" alt="" width="50">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary float-left" id="show-previous-image"><i class="fa fa-arrow-left"></i>
                    </button>

                    <button type="button" id="show-next-image" class="btn btn-secondary float-right"><i class="fa fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let modalId = $('#image-gallery');

        function deletePicture(parSlug, fileName) {
            if (confirm('Are you sure you want to delete this file?')) {
                $.ajax({
                    url: "{{ route('dashboard.par.deletePicture') }}",
                    type: 'POST',
                    data: {
                        par_slug: parSlug,
                        file_name: fileName,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Handle success response
                        toast('success',"File Successfully deleted.",'Success!');
                        // Reload the page or update UI as needed
                        setTimeout(function(){
                            location.reload();
                        }, 1000); // Reload after 1 second (1000 milliseconds)
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error("Error:", res);
                        toast('error',"Error:"+JSON.parse(res.xhr.responseText).message,'Error!',null,-1);
                    }
                });
            }
            return false; // Prevent the default link behavior
        }

        $(document).ready(function () {
                loadGallery(true, 'a.thumbnail');

                //This function disables buttons when needed
                function disableButtons(counter_max, counter_current) {
                    $('#show-previous-image, #show-next-image')
                        .show();
                    if (counter_max === counter_current) {
                        $('#show-next-image')
                            .hide();
                    } else if (counter_current === 1) {
                        $('#show-previous-image')
                            .hide();
                    }
                }

                /**
                 *
                 * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
                 * @param setClickAttr  Sets the attribute for the click handler.
                 */

                function loadGallery(setIDs, setClickAttr) {
                    let current_image,
                        selector,
                        counter = 0;

                    $('#show-next-image, #show-previous-image')
                        .click(function () {
                            if ($(this)
                                .attr('id') === 'show-previous-image') {
                                current_image--;
                            } else {
                                current_image++;
                            }

                            selector = $('[data-image-id="' + current_image + '"]');
                            updateGallery(selector);
                        });

                    function updateGallery(selector) {
                        let $sel = selector;
                        current_image = $sel.data('image-id');
                        $('#image-gallery-title')
                            .text($sel.data('title'));
                        $('#image-gallery-image')
                            .attr('src', $sel.data('image'));
                        disableButtons(counter, $sel.data('image-id'));
                    }

                    if (setIDs == true) {
                        $('[data-image-id]')
                            .each(function () {
                                counter++;
                                $(this)
                                    .attr('data-image-id', counter);
                            });
                    }
                    $(setClickAttr)
                        .on('click', function () {
                            updateGallery($(this));
                        });
                }
            });

        // build key actions
        $(document).keydown(function (e) {
                switch (e.which) {
                    case 37: // left
                        if ((modalId.data('bs.modal') || {})._isShown && $('#show-previous-image').is(":visible")) {
                            $('#show-previous-image')
                                .click();
                        }
                        break;

                    case 39: // right
                        if ((modalId.data('bs.modal') || {})._isShown && $('#show-next-image').is(":visible")) {
                            $('#show-next-image')
                                .click();
                        }
                        break;

                    default:
                        return; // exit this handler for other keys
                }
                e.preventDefault(); // prevent the default action (scroll / move caret)
            });


        Dropzone.options.myGreatDropzone = { // camelized version of the `id`
            acceptedFiles: ".jpg, .jpeg, .png, .pdf", // Add a comma between each file extension
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            success: function (file, response) {
                console.log("Logs:", response);
                toast('success',"File Successfully uploaded.",'Success!');
                /*Swal.fire({
                    title: 'Success!',
                    text: 'Image Successfully uploaded. Thank you.',
                    icon: 'success'
                });*/
            },
            error: function (res) {
                console.error("Error:", res);
                toast('error',"Error:"+JSON.parse(res.xhr.responseText).message,'Error!',null,-1);
            }
        };
    </script>
@endsection
