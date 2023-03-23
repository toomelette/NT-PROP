@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>Request Cancellation</h1>
    </section>
@endsection
@section('content2')

    <section class="content col-md-6">
        <div class="box box-solid">
            <form id="rc_form">
                <div class="box-header with-border">

                </div>

                <div class="box-body">
                    {!! \App\Swep\ViewHelpers\__form2::textbox('ref_number',[
                                            'label' => 'Reference Number:',
                                            'cols' => 3,
                                        ]) !!}

                    {!! \App\Swep\ViewHelpers\__form2::textbox('reason',[
                                            'label' => 'Reason:',
                                            'cols' => 9,
                                        ]) !!}
                    <div class="box-footer pull-right">
                        <button type="submit" class="btn btn-primary">Save</button>
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

    </script>
@endsection
