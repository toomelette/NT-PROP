@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1> Inventory Taking</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <div role="document">
            <form id="edit_form">

                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Take Inventory</h3>
                            <button class="btn btn-primary btn-sm pull-right" id="saveBtn" type="button">
                                <i class="fa fa-check"></i> Save
                            </button>
                            <button class="btn btn-secondary btn-sm pull-right" id="scanBtn" type="button" style="margin-right: 10px;">
                                <i class="fa fa-camera"></i> Scan
                            </button>
                             </div>

                        <div class="box-body">

                            {!! \App\Swep\ViewHelpers\__form2::textbox('propertyno',[
                               'label' => 'Reference Property No.:',
                               'cols' => 3,
                              'id' => 'propertyno'
                            ]) !!}

                        </div>


                        <div class="box-body">

                            {!! \App\Swep\ViewHelpers\__form2::select('location',[
                            'label' => 'Location:',
                            'cols' => 3,
                            'options' => \App\Swep\Helpers\Arrays::location(),
                            'id' => 'location'
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('article',[
                                'label' => 'Article',
                                'cols' => 3,
                                'id' => 'article'
                             ]) !!}


                            {!! \App\Swep\ViewHelpers\__form2::textbox('office',[
                               'label' => 'Office',
                               'cols' => 3,
                               'id' => 'office'
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::select('condition',[
                              'label' => 'Condition:',
                              'cols' => 3,
                              'options' => \App\Swep\Helpers\Arrays::condition(),
                              'id' => 'condition'
                            ]) !!}


                            {!! \App\Swep\ViewHelpers\__form2::textarea('description',[
                              'label' => 'Description',
                              'cols' => 3,
                              'id' => 'description'
                           ]) !!}

                        </div>
                    </div>

                </form>
            </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            $(document).ready(function() {
                $('input[name="propertyno"]').on('keyup', function(e) {
                    if ($(this).val() === '') {
                        toast('error', 'Reference Number cannot be empty', 'Invalid!');
                    } else {
                        if (e.keyCode === 13) {
                            e.preventDefault();
                            let uri = '{{ route("dashboard.par.findTransByPropNumber", "propertyno") }}';
                            uri = uri.replace('propertyno', $(this).val());
                            $.ajax({
                                url: uri,
                                type: 'GET',
                                headers: {
                                    {!! __html::token_header() !!}
                                },
                                success: function(res) {
                                    if(res.parinv) {
                                        $('select[name="location"]').val(res.parinv.location);
                                        $('input[name="article"]').val(res.parinv.article);
                                        $('textarea[name="description"]').val(res.parinv.description);
                                        $('select[name="condition"]').val(res.parinv.condition);
                                        $('input[name="office"]').val(res.parinv.office);
                                    } else {
                                        toast('error', 'No data found', 'Error!');
                                    }
                                },
                                error: function(res) {
                                    toast('error', res.responseJSON.message, 'Error!');
                                }
                            });
                        }
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('scanBtn').addEventListener('click', function() {
                    // Check if the browser supports media devices
                    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                        // Request the camera
                        navigator.mediaDevices.getUserMedia({ video: true })
                            .then(function(stream) {
                                // Create a video element to display the camera stream
                                const video = document.createElement('video');
                                video.srcObject = stream;
                                video.play();

                                // Style and position the video element as needed
                                video.style.position = 'fixed';
                                video.style.top = '50%';
                                video.style.left = '50%';
                                video.style.transform = 'translate(-50%, -50%)';
                                video.style.zIndex = '1000';
                                video.style.width = '80%';
                                video.style.maxWidth = '600px';
                                video.style.border = '2px solid black';
                                video.style.backgroundColor = 'black';

                                // Append the video element to the body (or another container)
                                document.body.appendChild(video);

                                // Create a close button to stop the video stream and remove the video element
                                const closeButton = document.createElement('button');
                                closeButton.textContent = 'Close';
                                closeButton.style.position = 'fixed';
                                closeButton.style.top = '10%';
                                closeButton.style.right = '10%';
                                closeButton.style.zIndex = '1001';
                                closeButton.style.padding = '10px';
                                closeButton.style.backgroundColor = '#f00';
                                closeButton.style.color = '#fff';
                                closeButton.style.border = 'none';
                                closeButton.style.borderRadius = '5px';
                                closeButton.style.cursor = 'pointer';

                                document.body.appendChild(closeButton);

                                // Event listener for the close button
                                closeButton.addEventListener('click', function() {
                                    stream.getTracks().forEach(track => track.stop());
                                    document.body.removeChild(video);
                                    document.body.removeChild(closeButton);
                                });
                            })
                            .catch(function(error) {
                                console.error("Error accessing the camera: ", error);
                                alert("Error accessing the camera: " + error.message);
                            });
                    } else {
                        alert("Your browser does not support camera access.");
                    }
                });
            });

            $("#saveBtn").click(function(e) {
                e.preventDefault();
                let form = $('#edit_form');
                let uri = '{{route("dashboard.par.inventoryTaking","propertyno")}}';
                uri = uri.replace('propertyno',$('#propertyno').val());
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
                        toast('info','PAR successfully updated.','Updated');
                        setTimeout(function() {
                        }, 3000);
                    },
                    error: function (res) {
                        errored(form,res);
                    }
                })
            });


        });


    </script>

@endsection