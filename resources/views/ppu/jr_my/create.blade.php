@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Create Job Request</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-solid">
            <form id="add_jr_form">
                <div class="box-header with-border">
                    <h3 class="box-title">Create JR</h3>
                    <button class="btn btn-primary btn-sm pull-right"  type="submit">
                        <i class="fa fa-check"></i> Save
                    </button>
                </div>
                <div class="box-body">
                    <div class="row">
                        {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                            'cols' => 5,
                            'label' => 'Department/Division/Section:',
                            'class' => 'resp_center_selector',
                            'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
                            'for' => 'select2_papCode',
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::select('pap_code',[
                            'cols' => 7,
                            'label' => 'PAP Code:',
                            'options' => [],
                            'class' => 'select2_papCode',
                        ]) !!}

                    </div>
                    <div class="row">
                        {!! \App\Swep\ViewHelpers\__form2::select('jr_type',[
                            'cols' => 5,
                            'label' => 'JR Type:',
                            'class' => 'jr_type_selector',
                            'options' => \App\Swep\Helpers\Arrays::jrType(),
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                            'cols' => 2,
                            'label' => 'JR Date:',
                            'type' => 'date',
                        ]) !!}
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button data-target="#pr_items_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=jr_items" style="margin-bottom: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                            <table id="pr_items_table" class="table-bordered table table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 8%">Property No.</th>
                                    <th style="width: 8%">Unit</th>
                                    <th style="width: 25%">Item</th>
                                    <th>Description</th>
                                    {{--<th style="width: 8%">Unit Cost</th>--}}
                                    <th style="width: 8%">Qty</th>
                                    {{--<th style="width: 8%">Total Cost</th>--}}
                                    <th style="width: 18%">Nature of Work</th>
                                    <th style="width: 50px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @include('dynamic_rows.jr_items')
                                </tbody>

                            </table>
                            {{--<button>Calculate</button>--}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textarea('purpose',[
                                  'cols' => 12,
                                  'label' => 'Purpose: ',
                                  'rows' => 4
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('abc',[
                                  'cols' => 12,
                                  'label' => 'ABC: ',
                                  'class' => 'text-right autonum',
                                  /*'readonly' => 'readonly',*/
                                ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by',[
                                  'cols' => 12,
                                  'label' => 'Certified by: ',
                                  'rows' => 4
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[
                                  'cols' => 12,
                                  'label' => 'Requested by: ',
                                  'rows' => 4
                                ]) !!}
                            </div>
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                                  'cols' => 12,
                                  'label' => 'Requested by (Designation): ',
                                  'rows' => 4
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                                  'cols' => 12,
                                  'label' => 'Approved by: ',
                                  'rows' => 4
                                ]) !!}
                            </div>
                            <div class="row">
                                {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                                  'cols' => 12,
                                  'label' => 'Approved by (Designation): ',
                                  'rows' => 4
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </section>


@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        $(".select2_papCode").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","pap_codes")}}',
                dataType: 'json',
                delay : 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            },
            placeholder: 'Type PAP Code/Title/Description',
        });
        $("#add_jr_form").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.my_jr.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,false);
                    $(".select2_papCode").select2("val", "");
                    $(".select2_papCode").trigger('change');
                    $(".remove_row_btn").each(function () {
                        $(this).click();
                    })
                    $(".add_button").click();
                    toast('success','Purchase request succesfully created','Success');
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })
    </script>
@endsection