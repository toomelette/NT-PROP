@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Requisition and Issue Slip</h1>
    </section>
@endsection
@section('content2')

    @php
        $employees = \App\Models\Employee::query()
        ->where(function ($query) {
            $query->where('locations', '=', 'VISAYAS')
                ->orWhere('locations', '=', 'LUZON/MINDANAO');
        })
        ->where('is_active', '=', 'ACTIVE')
        ->orderBy('fullname', 'asc')
        ->get();

       $employeesCollection = $employees->map(function ($data){
            return [
                'id' => $data->employee_no,
                'text' => $data->firstname.' '.$data->lastname.' - '.$data->employee_no,
                'employee_no' => $data->employee_no,
                'fullname' => $data->firstname.' '.$data->lastname,
                'position' => $data->position,
            ];
        })->toJson();
    @endphp

<section class="content col-md-12">
    <div role="document">
            <form id="add_form">

                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create RIS</h3>
{{--                           <button class="btn btn-primary btn-sm pull-right" id="saveBtn" type="button">--}}
{{--                        <i class="fa fa-check"></i> Save--}}
{{--                            </button>--}}
{{--                            <a type="button" style="margin-right: 3px" class="btn btn-danger btn-sm pull-right" id="backBtn" href="{{route('dashboard.ris.index')}}">Back</a>--}}
                        </div>

                        <div class="box-body">


                            {!! \App\Swep\ViewHelpers\__form2::select('resp_center',[
                               'label' => 'Responsibility Center',
                               'cols' => 3,
                               'id' => 'resp_center',
                               'options' => \App\Swep\Helpers\Arrays::groupedRespCodes(),
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                               'label' => 'RIS Date:',
                               'cols' => 3,
                               'type' => 'date',
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('sai',[
                                'label' => 'SAI Number:',
                                'cols' => 3,
                                'id' => 'sai_no',
                             ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('sai_date',[
                              'cols' => 3,
                              'label' => 'SAI Date:',
                              'type' => 'date',
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textarea('purpose',[
                              'cols' => 6,
                              'label' => 'Purpose',
                              'rows' => 1,
                              'id' => 'purpose',
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('iar_no',[
                                      'label' => 'IAR Reference No:',
                                      'cols' => 3,
                                      'id' => 'iar_no'
                            ]) !!}

                        </div>
                    </div>

                    <div class="box box-success">
                        <div class="box-body">
{{--                                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by',[--}}
{{--                                      'cols' => 3,--}}
{{--                                      'label' => 'Requested by: ',--}}
{{--                                    ]) !!}--}}


                                {!! \App\Swep\ViewHelpers\__form2::select('requested_by',[
                                        'label' => 'Accountable Officer:',
                                        'cols' => 3,
                                        'options' => [],
                                        'id' => 'requested_by',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by',[
                                      'cols' => 3,
                                      'label' => 'Approved by: ',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('issued_by',[
                                      'cols' => 3,
                                      'label' => 'Issued by: ',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('received_by',[
                                  'cols' => 3,
                                  'label' => 'Received by:',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('requested_by_designation',[
                                      'cols' => 3,
                                      'label' => 'Requested by (Designation): ',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('approved_by_designation',[
                                      'cols' => 3,
                                      'label' => 'Approved by (Designation): ',
                                    ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('issued_by_designation',[
                                  'cols' => 3,
                                  'label' => 'Issued by (Designation): ',
                                ]) !!}

                                {!! \App\Swep\ViewHelpers\__form2::textbox('received_by_designation',[
                                  'cols' => 3,
                                  'label' => 'Received by (Designation): ',
                                ]) !!}
                            </div>
                    </div>

                <div class="box box-success">
                        <div class="row">
                            <div class="col-md-12" style="min-height: 200px">
                                <button data-target="#ris_items_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=ris_items" style="margin-bottom: 5px; margin-top: 5px; margin-right: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                                    <table id="ris_items_table" class="table-bordered table table-condensed table-striped">
                                        <thead>
                                        <tr>
                                            <th style="width: 8%">Stock No.</th>
                                            <th style="width: 8%">Unit</th>
                                            <th style="width: 25%">Item</th>
                                            <th style="width: 25%">Description</th>
                                            <th style="width: 8%">Qty</th>
                                            <th style="width: 8%">Actual Qty</th>
                                            <th style="width: 25%">Remarks</th>
                                            <th style="width: 50px"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @include('dynamic_rows.ris_items')
                                        </tbody>
                                    </table>
                                <div class="pull-right">
                                    <button type="button" style="margin-right: 7px; margin-bottom: 7px" class="btn btn-primary" id="saveBtn">Save</button>
                                </div>
                            </div>
                        </div>
                </div>

            </form>
        </div>
    </div>
</section>
@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">

        var data = {!!$employeesCollection!!};
        $("#requested_by").select2({
            data : data,
        });


        $('#saveBtn').click(function(e) {
            e.preventDefault();
            let form = $('#add_form');
            let uri = '{{route("dashboard.ris.store")}}';
            loading_btn(form);

            $.ajax({
                url : uri,
                data: form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    console.log(res);
                    toast('success','RIS Successfully created.','Success!');
                    succeed(form,true,true);
                    Swal.fire({
                        title: 'Successfully created',
                        icon: 'success',
                        html:
                            'Click the print button below to print.',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText:
                            '<i class="fa fa-print"></i> Print',
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                        cancelButtonText:
                            'Dismiss',
                        cancelButtonAriaLabel: 'Thumbs down'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let link = "{{route('dashboard.ris.print','slug')}}";
                            link = link.replace('slug',res.slug);
                            window.open(link, '_blank');
                        }
                    })
                },
                error: function (res) {
                    toast('error',res.responseJSON.message,'Error!');
                }
            });
        });


        $(function(){
            $('#iar_no').keypress(function (event){
                if (event.keyCode === 13) {
                    let uri = '{{ route("dashboard.ris.findIAR", 'refNumber') }}';
                    uri = uri.replace('refNumber',$(this).val());
                    $.ajax({
                        url : uri,
                        type: 'GET',
                        headers: {
                            {!! __html::token_header() !!}
                        },
                        success: function (res) {
                            console.log(res);

                            $("#purpose").val(res.trans.purpose);

                            $('#ris_items_table tbody').remove();
                            let tableHtml = '<tbody>';
                            for(let i=0; i<res.transDetails.length; i++){
                                let stock = res.transDetails[i].stock_no;
                                stock = stock === null ? '' : stock;
                                 let rmks = res.transDetails[i].remarks == null ? '' : res.transDetails[i].remarks;
                                 let dscptn = res.transDetails[i].description == null ? '' : res.transDetails[i].description;
                                tableHtml += '<tr id='+res.transDetails[i].slug+'>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][stock_no]" name="items['+res.transDetails[i].slug+'][stock_no]" type="text" value="' + stock + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][unit]" name="items['+res.transDetails[i].slug+'][unit]" type="text" value="' + res.transDetails[i].unit + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][item]" name="items['+res.transDetails[i].slug+'][item]" type="text" value="' +  res.transDetails[i].item + '"></td>' +
                                    '<td><textarea class="input-sm" id="items['+res.transDetails[i].slug+'][description]" name="items['+res.transDetails[i].slug+'][description]" type="text">'+ dscptn +'</textarea></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][qty]" name="items['+res.transDetails[i].slug+'][qty]" type="text" value="' + res.transDetails[i].qty + '"></td>' +
                                    '<td><input class="form-control" id="items['+res.transDetails[i].slug+'][actual_qty]" name="items['+res.transDetails[i].slug+'][actual_qty]" type="text" value="' + res.transDetails[i].qty + '"></td>' +
                                    '<td><textarea class="input-sm" id="items['+res.transDetails[i].slug+'][remarks]" name="items['+res.transDetails[i].slug+'][remarks]" type="text" value="' + rmks + '"></textarea></td>' +
                                    '<td><button type=\'button\' class=\'btn btn-danger btn-sm delete-btn\' data-slug='+res.transDetails[i].slug+' onclick="deleteRow(this)"><i class=\'fa fa-times\'></i></button></td>' +
                                    '</tr>';
                            }
                            tableHtml += '</tbody>';
                            $('#ris_items_table').append(tableHtml).removeClass('hidden');

                        },
                        error: function (res) {
                            toast('error',res.responseJSON.message,'Error!');
                            console.log(res);
                        }
                    })
                }
            });
        });



        $(".select2_item").select2({
            ajax: {
                url: '{{route("dashboard.ajax.get","articles")}}',
                dataType: 'json',
                delay : 250,
            },
            placeholder: 'Select item',
        });

        $('.select2_item').on('select2:select', function (e) {
            let t = $(this);
            let parentTrId = t.parents('tr').attr('id');
            let data = e.params.data;

            $("#"+parentTrId+" [for='stockNo']").val(data.id);
            $("#"+parentTrId+" [for='uom']").val(data.populate.uom);
            $("#"+parentTrId+" [for='itemName']").val(data.text);
        });

    </script>
@endsection

