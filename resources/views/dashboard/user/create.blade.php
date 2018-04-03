@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>Create User</h1>
</section>

<section class="content">
            
    <div class="box">
        
      <div class="box-header with-border">
        <h3 class="box-title">Form</h3>
      </div>
      
      <form class="form-horizontal" method="POST" autocomplete="off" action="{{ route('dashboard.user.store') }}">

        <div class="box-body">

          @if(Session::has('USER_FORM_FAIL_USERNAME_EXIST'))
            {!! HtmlHelper::alert('danger', '<i class="icon fa fa-ban"></i> Alert!', Session::get('USER_FORM_FAIL_USERNAME_EXIST')) !!}
          @endif

          <div class="col-md-11">
                  
              @csrf    

              {!! FormHelper::textbox_inline(
                  'firstname', 'text', 'Firstname', 'Firstname', old('firstname'), $errors->has('firstname'), $errors->first('firstname')
              ) !!}

              {!! FormHelper::textbox_inline(
                  'middlename', 'text', 'Middlename', 'Middlename', old('middlename'), $errors->has('middlename'), $errors->first('middlename')
              ) !!}

              {!! FormHelper::textbox_inline(
                  'lastname', 'text', 'Lastname', 'Lastname', old('lastname'), $errors->has('lastname'), $errors->first('lastname')
              ) !!}

              {!! FormHelper::textbox_inline(
                  'email', 'email', 'Email', 'Email', old('email'), $errors->has('email'), $errors->first('email')
              ) !!}

              {!! FormHelper::textbox_inline(
                  'position', 'text', 'Position', 'Position / Plantilla', old('position'), $errors->has('position'), $errors->first('position')
              ) !!}

              {!! FormHelper::textbox_inline(
                  'username', 'text', 'Username', 'Username', old('username'), $errors->has('username') || Session::has('USER_CREATE_FAIL_USERNAME_EXIST'), $errors->first('username')
              ) !!}

              {!! FormHelper::password_inline(
                  'password', 'Password', 'Password', $errors->has('password'), $errors->first('password')
              ) !!}

              {!! FormHelper::password_inline(
                  'password_confirmation', 'Confirm Password', 'Confirm Password', '', ''
              ) !!}

          </div>


          <!-- USER MENU DYNAMIC TABLE GRID -->
          <div class="col-md-12" style="padding-top:50px;">
            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">User Menu</h3>
                <button id="add_row" type="button" class="btn btn-sm bg-green pull-right"><i class="fa fa-plus"></i></button>
              </div>
              
              <div class="box-body no-padding">
                
                <table class="table table-bordered">

                  <tr>
                    <th>Menus</th>
                    <th>Menu Modules</th>
                    <th style="width: 40px"></th>
                  </tr>

                  <tbody id="table_body">

                    @if(old('menu'))
                      
                      @foreach(old('menu') as $key => $value)

                        <tr>

                          <td style="width:450px;">
                            <select name="menu[]" id="menu" class="form-control select2" style="width: 90%;">
                              <option value="">Select</option>
                              @foreach($global_menus_all as $data) 
                                  <option value="{{ $data->menu_id }}" {!! old('menu.'.$key) == $data->menu_id ? 'selected' : ''!!}>{{ $data->name }}</option>
                              @endforeach
                            </select>
                            <br><small class="text-danger">{{ $errors->first('menu.'.$key) }}</small>
                          </td>

                          <td style="min-width:50px; min-width:50px; max-width:50px">
                            <select name="submenu[]" id="submenu" class="form-control select2" multiple="multiple" data-placeholder="Modules" style="width: 80%;">
                                <option value="">Select</option>
                                @foreach($global_submenus_all as $data)
                                    @if(old('submenu') && $data->menu_id == old('menu.'.$key))
                                        <option value="{{ $data->submenu_id }}" {!! in_array($data->submenu_id, old('submenu')) ? 'selected' : '' !!}>{{$data->name}}</option>
                                    @else
                                        <option value="{{ $data->submenu_id }}">{{$data->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                          </td>

                          <td>
                            <button id="delete_row" type="button" class="btn btn-sm bg-red"><i class="fa fa-times"></i></button>
                          </td>

                        </tr>

                      @endforeach

                    @else

                      <tr>

                        <td style="width:450px;">
                          <select name="menu[]" id="menu" class="form-control select2" style="width:90%;">
                            <option value="">Select</option>
                            @foreach($global_menus_all as $data) 
                              <option value="{{ $data->menu_id }}">{{ $data->name }}</option>
                            @endforeach
                          </select>
                        </td>

                        <td>
                          <select name="submenu[]" id="submenu" class="form-control select2" multiple="multiple" data-placeholder="Modules" style="width:80%;">
                              <option value="">Select</option>
                              @foreach($global_submenus_all as $data)
                                <option value="{{ $data->submenu_id }}">{{$data->name}}</option>
                              @endforeach
                          </select>
                        </td>

                        <td>
                            <button id="delete_row" type="button" class="btn btn-sm bg-red"><i class="fa fa-times"></i></button>
                        </td>

                      </tr>

                    @endif

                    </tbody>
                </table>
               
              </div>

            </div>
          </div>

        </div>

        <div class="box-footer">
          <button type="submit" class="btn btn-default">Save</button>
        </div>

      </form>

    </div>

</section>

@endsection


@section('modals')

  @include('modals.user.user_create')

@endsection 


@section('scripts')

  @include('scripts.user.user_create')
    
@endsection