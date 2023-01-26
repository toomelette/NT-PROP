@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Prepare AQ</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">
                    {{$trans->ref_book}}: {{$trans->ref_no}}

                </h3>
                <span class="pull-right">ABC: {{number_format($trans->abc,2)}} </span>
            </div>

            <div class="box-body">
                <button class="btn btn-xs btn-success pull-right" id="add_column_button" style="margin-bottom: 5px"><i class="fa fa-columns"></i> Add Column</button>
                <table class="table-bordered table-striped table-condensed" style="width: 100%; overflow-y: auto">
                    <thead id="items_head">
                    <tr>
                        <th>#</th>
                        <th>Qty Unit</th>
                        <th>Description of Articles</th>
                        <th style="width: 200px;">Supplier 1</th>
                    </tr>
                    </thead>
                    <tbody id="items_body">
                    @if(!empty($trans->transDetails))
                        @foreach($trans->transDetails as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->qty}} {{strtoupper($item->unit)}}</td>
                                <td>
                                    <b>{{$item->item}}</b>
                                    <br>
                                    <span style="white-space: pre-line" class="small">{{$item->description}}</span>
                                </td>
                                <td>
                                    {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items[][qty]',[
                                        'class' => 'input-sm autonumber text-right',
                                        'container_class' => 'items_qty',
                                        'step' => 'any',
                                    ],$item->qty ?? null) !!}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>

    </section>

    <div hidden id="populate">
        {!! \App\Swep\ViewHelpers\__form2::textboxOnly('items[][qty]',[
            'class' => 'input-sm autonumber text-right',
            'container_class' => 'items_qty',
            'step' => 'any',
        ],$item->qty ?? null) !!}
    </div>

@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        $("#add_column_button").click(function () {
            let rows = $('#items_body tr').length;
            let html = $("#populate").html();
            $("#items_head tr").each(function () {
                $(this).append('<td></td>');
            })
            $("#items_body tr").each(function () {
                $(this).append('<td>'+html+'</td>');
            })
        })
    </script>
@endsection