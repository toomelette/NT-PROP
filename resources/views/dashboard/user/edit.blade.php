@php($rand = \Illuminate\Support\Str::random())
@extends('layouts.modal-content',['form_id'=>'edit_user_form_'.$rand, 'slug'=> $user->slug,])


@section('modal-header')
    Edit
@endsection

@section('modal-body')
    <div class="nav-tabs">
        <div class="row">
            <div class="col-md-2">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-pills nav-stacked">

                            @foreach($by_category as $category => $menus)
                                <li role="presentation" class="{{$loop->first ? 'active' : ''}}"><a href="#tab_{{($category == null) ? 'NoCategory' : $category}}_{{$rand}}" data-toggle="tab" aria-expanded="false">{{($category == null) ? 'No Category' : \App\Swep\ViewHelpers\__html::sidenav_labeler($category)}}</a></li>
                            @endforeach


                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <div class="tab-content">

                    @foreach($by_category as $category => $menus)
                        <div class="tab-pane {{($loop->first ? 'active' : '')}}" id="tab_{{($category == null) ? 'NoCategory' : $category}}_{{$rand}}">
                            <h4>{{($category == null) ? 'No Category' : \App\Swep\ViewHelpers\__html::sidenav_labeler($category)}}</h4>
                            <div class="row">
                                @foreach($menus as $menu)
                                    <div class="col-md-3">
                                        @if($menu->route == 'dashboard.home')
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <i class="fa {{$menu->icon}}"></i>
                                                    {{$menu->name}}
                                                </div>
                                                <div class="panel-body" style="min-height: 210px">
                                                    <div class="row">
                                                        {!!
                                                            __form::select_static2('12', 'dash_type', 'Dashboard type',$user->dash,
                                                            [
                                                                'HRU Dashboard' => 'hru',
                                                                'RECORDS Dashboard' => 'records',
                                                            ]
                                                            , '', '', '', '')
                                                           !!}

                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <i class="fa {{$menu->icon}}"></i>
                                                    {{$menu->name}}
                                                    <div class="pull-right">
                                                        <button class="btn btn-xs btn-default clear_btn" type="button">Clear</button>
                                                    </div>
                                                </div>
                                                <div class="panel-body" style="min-height: 180px">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <select multiple="" name="submenus[{{$menu->menu_id}}][]" class="form-control select_multiple" size="6">
                                                                @if($menu->submenu->count() > 0)
                                                                    @foreach($menu->submenu as $submenu)
                                                                        <option value="{{$submenu->submenu_id}}" @if(isset($user_submenus_arr[$submenu->submenu_id])) selected @endif>
                                                                            {{$submenu->name}}
                                                                        </option>
                                                                    @endforeach

                                                                @endif
                                                            </select>
                                                            <span class="help-block" style="font-size: 12px; font-family: 'Product Sans Light'">No module selected</span>
                                                        </div>
                                                    </div>
                                                    <div class="progress xs">
                                                        <div class="progress-bar bg-green" style="width: 0%;" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                @endforeach
                            </div>

                        </div>
                    @endforeach


                </div>
            </div>
        </div>


    </div>



    {{--<div class="row">--}}
    {{--    <div class="col-md-3">--}}
    {{--        <div class="row">--}}
    {{--            {!! __form::textbox(--}}
    {{--              '12 firstname', 'firstname', 'text', 'Firstname *', 'Firstname', $user->firstname, '', '', ''--}}
    {{--            ) !!}--}}

    {{--            {!! __form::textbox(--}}
    {{--                  '12 middlename', 'middlename', 'text', 'Middlename *', 'Middlename', $user->middlename, '', '', ''--}}
    {{--                ) !!}--}}

    {{--            {!! __form::textbox(--}}
    {{--              '12 lastname', 'lastname', 'text', 'Lastname *', 'Lastname', $user->lastname, '', '', ''--}}
    {{--            ) !!}--}}



    {{--            {!! __form::textbox(--}}
    {{--                  '12 email', 'email', 'email', 'Email *', 'Email', $user->email, '', '', ''--}}
    {{--                ) !!}--}}

    {{--            {!! __form::textbox(--}}
    {{--              '12 position', 'position', 'text', 'Position *', 'Position', $user->position, '', '', ''--}}
    {{--            ) !!}--}}


    {{--        </div>--}}

    {{--    <div class="col-md-9">--}}

    {{--        @php($count = 0)--}}
    {{--        @foreach($by_category as $category => $menus)--}}
    {{--            <div class="box box-sm box-default box-solid">--}}
    {{--                <div class="box-header with-border">--}}
    {{--                    <p class="box-title-sm no-margin"> {{($category == null) ? 'No Category' : $category}}</p>--}}
    {{--                    <div class="box-tools pull-right">--}}
    {{--                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
    {{--                        </button>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="box-body" style="">--}}
    {{--                    <div class="row">--}}
    {{--                            @foreach($menus as $menu)--}}
    {{--                            <div class="col-md-3">--}}
    {{--                                @if($menu->route == 'dashboard.home')--}}
    {{--                                    <div class="panel panel-default">--}}
    {{--                                        <div class="panel-heading">--}}
    {{--                                            <i class="fa {{$menu->icon}}"></i>--}}
    {{--                                            {{$menu->name}}--}}
    {{--                                        </div>--}}
    {{--                                        <div class="panel-body" style="min-height: 210px">--}}
    {{--                                            <div class="row">--}}
    {{--                                                {!!--}}
    {{--                                                    __form::select_static2('12', 'dash_type', 'Dashboard type',$user->dash,--}}
    {{--                                                    [--}}
    {{--                                                        'HRU Dashboard' => 'hru',--}}
    {{--                                                        'RECORDS Dashboard' => 'records',--}}
    {{--                                                    ]--}}
    {{--                                                    , '', '', '', '')--}}
    {{--                                                   !!}--}}

    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                @else--}}
    {{--                                    <div class="panel panel-default">--}}
    {{--                                        <div class="panel-heading">--}}
    {{--                                            <i class="fa {{$menu->icon}}"></i>--}}
    {{--                                            {{$menu->name}}--}}
    {{--                                            <div class="pull-right">--}}
    {{--                                                <button class="btn btn-xs btn-default clear_btn" type="button">Clear</button>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="panel-body" style="min-height: 180px">--}}
    {{--                                            <div class="row">--}}
    {{--                                                <div class="col-sm-12">--}}
    {{--                                                    <select multiple="" name="submenus[{{$menu->menu_id}}][]" class="form-control select_multiple" size="6">--}}
    {{--                                                        @if($menu->submenu->count() > 0)--}}
    {{--                                                            @foreach($menu->submenu as $submenu)--}}
    {{--                                                                <option value="{{$submenu->submenu_id}}" @if(isset($user_submenus_arr[$submenu->submenu_id])) selected @endif>--}}
    {{--                                                                    {{$submenu->name}}--}}
    {{--                                                                </option>--}}
    {{--                                                            @endforeach--}}

    {{--                                                        @endif--}}
    {{--                                                    </select>--}}
    {{--                                                    <span class="help-block" style="font-size: 12px; font-family: 'Product Sans Light'">No module selected</span>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="progress xs">--}}
    {{--                                                <div class="progress-bar bg-green" style="width: 0%;" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                @endif--}}

    {{--                            </div>--}}

    {{--                            @endforeach--}}

    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        @endforeach--}}


    {{--        @php($count = 0)--}}




    {{--    </div>--}}
    {{--</div>--}}
@endsection


@section('modal-footer')
    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button>
@endsection

@section('scripts')
    <script>
        $("#edit_user_form_{{$rand}}").submit(function (e) {
            e.preventDefault();
            uri = "{{route('dashboard.user.update', 'slug')}}";
            uri = uri.replace('slug',"{{$user->slug}}");
            let form = $(this);
            $.ajax({
                url : uri,
                data : form.serialize(),
                type : 'PATCH',
                success : function (response) {
                    active = response.slug;
                    users_table.draw(false);
                    notify("Changes were saved successfully.", "success");
                    succeed(form, true,true);
                },
                error: function (response) {
                    console.log(response);
                    errored(form,res);
                }
            })
        })
    </script>
@endsection
