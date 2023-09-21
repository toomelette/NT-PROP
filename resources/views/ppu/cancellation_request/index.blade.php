@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Manage Cancellation Request</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Cancellation Request</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id="cr_table_container" style="display: none">
                            <table class="table table-bordered table-striped table-hover" id="cr_table" style="width: 100% !important">
                                <thead>
                                <tr class="">
                                    <th>Type</th>
                                    <th>Ref No.</th>
                                    <th>Ref Date</th>
                                    <th>ABC</th>
                                    <th>Requested By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="tbl_loader">
                            <center>
                                <img style="width: 100px" src="{{asset('images/loader.gif')}}">
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        var active;
        $(document).ready(function () {
            //-----DATATABLES-----//
            modal_loader = $("#modal_loader").parent('div').html();
            //Initialize DataTable

            cr_tbl = $("#cr_table").DataTable({
                "ajax" : '{{route("dashboard.cancellationRequest.index")}}',
                "columns": [
                    { "data": "ref_book" },
                    { "data": "ref_number" },
                    { "data": "ref_date" },
                    { "data": "total_amount" },
                    { "data": "requisitioner" },
                    { "data": "is_cancelled" },
                    { "data": "action" }
                ],
                "buttons": [
                    {!! __js::dt_buttons() !!}
                ],
                "responsive": false,
                'dom' : 'lBfrtip',
                "processing": true,
                "serverSide": true,
                "initComplete": function( settings, json ) {
                    style_datatable("#"+settings.sTableId);
                    $('#tbl_loader').fadeOut(function(){
                        $("#cr_table_container").fadeIn();
                        if(find != ''){
                            cr_tbl.search(find).draw();
                        }
                    });
                    //Need to press enter to search
                    $('#'+settings.sTableId+'_filter input').unbind();
                    $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                        if (e.keyCode == 13) {
                            cr_tbl.search(this.value).draw();
                        }
                    });
                },

                "language":
                    {
                        "processing": "<center><img style='width: 70px' src='{{asset("images/loader.gif")}}'></center>",
                    },
                "drawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();
                    $('[data-toggle="modal"]').tooltip();
                    if(active != ''){
                        if(Array.isArray(active) == true){
                            $.each(active,function (i,item) {
                                $("#cr_table #"+item).addClass('success');
                            })
                        }
                        $("#cr_table #"+active).addClass('success');
                    }
                }
            });

            $("body").on('click','.cancel_btn',function () {
                let btn = $(this);
                let uri  = '{{route('dashboard.cancellationRequest.approve','slug')}}';
                uri = uri.replace('slug',btn.attr('data'));
                Swal.fire({
                    title: 'Cancel Transaction?',
                    confirmButtonColor: '#dd4b39',
                    showCancelButton: true,
                    cancelButtonText : 'Back',
                    confirmButtonText: '<i class="fa fa-check"></i> Yes',
                    showLoaderOnConfirm: true,
                    preConfirm: (text) => {
                        return $.ajax({
                            url : uri,
                            type: 'POST',
                            headers: {
                                {!! __html::token_header() !!}
                            },
                            success : function (res) {
                               console.log(res);
                                active = res.slug;
                                cr_tbl.draw();
                            }
                        })
                            .then(response => {
                                return  response;

                            })
                            .catch(error => {
                                console.log(error);
                                Swal.showValidationMessage(
                                    'Error : '+ error.responseJSON.message,
                                )
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        toast('success','Transaction was successfully marked as cancel.','Success!');

                    }
                })
            })
        })
    </script>
@endsection