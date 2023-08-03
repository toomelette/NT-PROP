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
                    {!! \App\Swep\ViewHelpers\__form2::select('location',[
                    'id' => 'location',
                    'label' => 'Location:',
                    'cols' => 3,
                    'options' => \App\Swep\Helpers\Arrays::location(),
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
            $('#location').change(function() {
                var selectedValue = $(this).val();
                var href = "{{ route('dashboard.rpcppe.printInventoryCountForm', 'location') }}";
                href = href.replace('location', selectedValue);
                $('a.btn').attr('href', href);
            });
        });

    </script>
@endsection
