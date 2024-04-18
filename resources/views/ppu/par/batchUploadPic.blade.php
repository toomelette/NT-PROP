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
        <h1>Upload Picture for PPE Batch</h1>
    </section>
@endsection

@section('content2')
    <section class="content">
        <div role="document">
            <div class="box box-success">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('dashboard.par.batchUploadPic')}}" method="post" enctype="multipart/form-data" class="dropzone" id="my-great-dropzone">
                                <input type="text"  name="par_slug" hidden>
                                @csrf
                            </form>

                            <br>
                            <div class="post">
                                <div class="row margin-bottom">
                                    <div class="col-sm-12">
                                        <div class="row">



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
        let modalId = $('#image-gallery');
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
                toast('success',"Image Successfully uploaded.",'Success!');
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
