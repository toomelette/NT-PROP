@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Generate RPCPPE</h1>
    </section>
@endsection
@section('content2')
    <section class="content col-md-12">
        <div class="box box-solid">

            <form id="rpci_form">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9 hidden" style="height: 1019.938px;">
                        <iframe class="embed-responsive-item" src="" id="printIframe"></iframe>
                    </div>
                    {!! \App\Swep\ViewHelpers\__form2::select('fund_cluster',[
                    'id' => 'fund_cluster',
                    'label' => 'Fund Cluster:',
                    'cols' => 3,
                    'options' => \App\Swep\Helpers\Arrays::fundSources(),
                    ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('as_of',[
                            'id' => 'as_of',
                           'label' => 'As of:',
                           'cols' => 2,
                           'type' => 'date',
                        ]) !!}
                    <div class="clearfix"></div>
                    <div class="box-footer pull-left">
                        <a class="btn btn-primary btn-md" href="" target="_blank">
                            <i class="fa fa-print">Print</i>
                        </a>
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
        $(document).ready(function() {
            $('#as_of').val(new Date().toISOString().slice(0, 10));
            let href = "{{ route('dashboard.rpcppe.printRpcppe', ['fund_cluster', 'as_of']) }}";
            href = href.replace('fund_cluster', 'all');
            href = href.replace('as_of', $('#as_of').val());
            $('a.btn').attr('href', href);

            $('#fund_cluster, #as_of').change(function() {
                let href = "{{ route('dashboard.rpcppe.printRpcppe', ['fund_cluster', 'as_of']) }}";
                href = href.replace('fund_cluster', $('#fund_cluster').val() || "all");
                href = href.replace('as_of', $('#as_of').val());
                $('a.btn').attr('href', href);
            });
        });

    </script>
@endsection
