@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Edit Property Acknowledgement Receipt</h1>
    </section>
@endsection
@section('content2')
    <section class="content">
        <div role="document">
            <form id="edit_form">
                <input class="hidden" type="text" id="slug" name="slug" value="{{$par->slug}}"/>
                <div class="box box-success">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                UPLOAD PAR PICTURE
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-left: 20px" id="saveBtn">Update</button>
                                    <a type="button" class="btn btn-danger pull-right" id="backBtn" href="{{route('dashboard.par.index')}}">Back to list</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        let active;
        $(document).ready(function () {
            $("#edit_form").submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let uri = '{{route("dashboard.par.update","slug")}}';
                uri = uri.replace('slug',$('#slug').val());
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
                            window.location.href = $("#backBtn").attr("href");
                        }, 3000);
                    },
                    error: function (res) {
                        errored(form,res);
                    }
                })
            });

            $(".select2_article").select2({
                ajax: {
                    url: '{{route("dashboard.ajax.get","articles")}}',
                    dataType: 'json',
                    delay : 250,
                },
                dropdownParent: $('#edit_form'),
                placeholder: 'Select item',
                language : {
                    "noResults": function(){

                        return "No item found.";
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });

            $('.select2_article').on('select2:select', function (e) {
                let data = e.params.data;
                console.log(data);
                $.each(data.populate,function (i, item) {
                    /*$("#select[name='"+i+"']").val(item).trigger('change');
                    $("#input[name='"+i+"']").val(item).trigger('change');*/
                })
            });
        })
    </script>
@endsection