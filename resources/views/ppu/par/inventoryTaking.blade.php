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
                            <button class="btn btn-secondary btn-sm pull-right" id="scanBtn" data-toggle="modal" data-target="#scan_modal" type="button" style="margin-right: 10px;">
                                <i class="fa fa-camera"></i> Scan
                            </button>
                             </div>

                        <div class="box-body">
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('propertyno',[
                                   'label' => 'Reference Property No.:',
                                   'cols' => 3,
                                  'id' => 'propertyno'
                                ]) !!}
                            </div>
                            <div id="populate">

                            </div>
                        </div>
                    </div>

                </form>
            </div>
    </section>
@endsection

@section('modals')
    <div class="modal fade" id="scan_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Capture</h4>
                </div>
                <div class="modal-body">
                    <div id="reader"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {


            let config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                } ,
                rememberLastUsedCamera: false,
            };
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                config,
                /* verbose= */ false);

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);

            function onScanSuccess(decodedText, decodedResult) {
                // handle the scanned code as you like, for example:
                let property_no = JSON.parse(decodedText).property_no;
                getInvData(property_no);
                $("#propertyno").val(property_no);
            }

            function onScanFailure(error) {
                // handle scan failure, usually better to ignore and keep scanning.
                // for example:
                //console.warn(`Code scan error = ${error}`);
            }

            function getInvData(property_no){
                $.ajax({
                    url : '{{route("dashboard.par.inventoryTaking")}}?getInv=true&property_no='+property_no,
                    type: 'GET',
                    success: function (res){
                        $("#populate").html(res);

                        $("#scan_modal").modal('hide');
                        // html5QrcodeScanner.pause();
                    },
                    error: function (res){
                        console.log(res);
                    }
                })



            }
            $(document).ready(function() {
                $("#propertyno").change(function (){
                    let property_no = $(this).val();
                    getInvData(property_no);
                    $("#propertyno").val(property_no);
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