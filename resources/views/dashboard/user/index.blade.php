@extends('layouts.admin-master')

@section('content')

  <section class="content-header">
    <h1>Manage Users</h1>
  </section>

  <section class="content">
    {{-- Table Grid --}}

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Users</h3>
        <div class="pull-right">
          <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add_user_employee_modal"><i class="fa fa-user"></i> New user from employee</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_user_modal"><i class="fa fa-plus"></i> New User</button>
          </div>

        </div>
      </div>

      <div class="panel">
        <div class="box-header with-border">
          <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#advanced_filters" aria-expanded="true" class="">
              <i class="fa fa-filter"></i>  Advanced Filters <i class=" fa  fa-angle-down"></i>
            </a>
          </h4>
        </div>
        <div id="advanced_filters" class="panel-collapse collapse" aria-expanded="true" style="">
          <div class="box-body">
            <div class="row">
              <div class="col-md-1 col-sm-2 col-lg-2">
                <label>Status:</label>
                <select name="status" aria-controls="scholars_table" class="form-control input-sm filter_status filters">
                  <option value="">All</option>
                  <option value="online">Online</option>
                  <option value="offline">Offline</option>
                </select>
              </div>
              <div class="col-md-1 col-sm-2 col-lg-2">
                <label>Account Status:</label>
                <select name="account" aria-controls="scholars_table" class="form-control input-sm filter_account filters">
                  <option value="">All</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>



      <div class="box-body">
        <div id="users_table_container" style="display: none">
          <table class="table table-bordered table-striped table-hover" id="users_table" style="width: 100% !important">
            <thead>
            <tr class="">
              <th class="th-20">Username</th>
              <th >Full Name</th>
              <th class="th-10">Status</th>
              <th class="th-10">Account</th>
              <th class="action">Action</th>
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
  </section>

@endsection






@section('modals')
  <div id="add_user_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <form id="add_user_form" autocomplete="off">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New User</h4>
          </div>
          <div class="modal-body">


            <div class="box-body">
              @csrf

              <div class="row">
                {!! __form::textbox(
                  '4 firstname', 'firstname', 'text', 'Firstname *', 'Firstname', '', 'firstname', '', ''
                ) !!}

                {!! __form::textbox(
                  '4 middlename', 'middlename', 'text', 'Middlename *', 'Middlename', '', 'middlename', '', ''
                ) !!}

                {!! __form::textbox(
                  '4 lastname', 'lastname', 'text', 'Lastname *', 'Lastname', '', 'lastname', '', ''
                ) !!}

              </div>
              <div class="row">
                {!! __form::textbox(
                  '6 email', 'email', 'email', 'Email *', 'Email', '', 'email', '', ''
                ) !!}

                {!! __form::textbox(
                  '6 position', 'position', 'text', 'Position *', 'Position', '', 'position', '', ''
                ) !!}
              </div>

              <div class="row">
                {!! __form::textbox(
                    '4 username', 'username', 'text', 'Username *', 'Username', '', 'username', '', ''
                ) !!}

                {!! __form::textbox_password_btn(
                    '4 password', 'password', 'Password *', 'Password', '', 'password', '', ''
                ) !!}

                {!! __form::textbox_password_btn(
                    '4 password_confirmation', 'password_confirmation', 'Confirm Password *', 'Confirm Password', '', 'password_confirmation', '', ''
                ) !!}

              </div>
              <div class="row">
                <div class="col-sm-12">
                  <h4>User Menu
                    <span class="pull-right ">
                      <small class="text-info">You can use CTRL & SHIFT keys for multiple selection. CTRL+A to select all.</small>
                    </span>
                  </h4>
                  <hr style="margin: 0 0 10px 0">
                </div>

                @foreach ($menus as $key => $sub)
                  <div class="col-md-4">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fa {{ $sub->icon }}"></i>
                        {{ $sub->name }}
                        <div class="pull-right">
                          <button class="btn btn-xs btn-default clear_btn" type="button">Clear</button>
                        </div>
                      </div>
                      <div class="panel-body" style="min-height: 180px">
                        <div class="row">
                          <div class="col-sm-12">
                            @if($sub->submenu->isEmpty())
                              <center>
                                <label>No submenu found for this Menu</label>
                              </center>

                            @else
                              <select multiple name="submenus[]" class="form-control select_multiple" size="6">
                                @foreach($sub->submenu as $key2 => $submenu)
                                  <option value="{{$submenu->submenu_id}}">
                                    {{ str_replace($sub->name,'', $submenu->name) }}
                                  </option>
                                @endforeach

                              </select>
                              <span class="help-block">No module selected</span>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
              {{-- USER MENU DYNAMIC TABLE GRID --}}
              <div class="col-md-12" style="padding-top:50px;">
                <div class="box box-solid">


                  <div class="box-body no-padding">



                  </div>

                </div>
              </div>

            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary add_user_btn"><i class="fa fa-save fa-fw"></i> Save</button>
          </div>
        </form>
      </div>

    </div>
  </div>

  <div class="modal fade" id="add_user_employee_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Make user from employee</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-12 employee_name ">
              <label for="employee_name">Name of employee:*</label>
              <input autocomplete="off" class="form-control " id="employee_name" name="employee_name" type="text" value="" placeholder="Name of employee"><ul class="typeahead dropdown-menu"></ul>
              <span class="help-block"><i class="fa fa-info-circle"></i> Employees with linked SWEP accounts to their employee number may not be included in the search. </span>
            </div>


          </div>

          <div id="new_user_from_employee_form_containter">

          </div>

        </div>

      </div>
    </div>
  </div>

  {!! __html::blank_modal('view_user_modal','lg') !!}

  {!! __html::blank_modal('edit_user_modal',80) !!}

  {!! __html::blank_modal('reset_password_modal','sm') !!}
@endsection
@section('scripts')
  <script type="text/javascript">
    function dt_draw(){
      users_table.draw(false);
    }

    function filter_dt(){
      is_online = $(".filter_status").val();
      is_active = $(".filter_account").val();
      users_table.ajax.url("{{ route('dashboard.user.index') }}"+"?is_online="+is_online+"&is_active="+is_active).load();

      $(".filters").each(function(index, el) {
        if($(this).val() != ''){
          $(this).parent("div").addClass('has-success');
          $(this).siblings('label').addClass('text-green');
        }else{
          $(this).parent("div").removeClass('has-success');
          $(this).siblings('label').removeClass('text-green');
        }
      });
    }
  </script>
  <script type="text/javascript">

    modal_loader = $("#modal_loader").parent('div').html();

    active = '';
    slug = "";
    //-----DATATABLES-----//
    //Initialize DataTable
    $('#users_table')
            .on('preXhr.dt', function ( e, settings, data ) {

            } )


    users_table = $("#users_table").DataTable({
      'dom' : 'lBfrtip',
      "processing": true,
      "serverSide": true,
      "ajax" : '{{ route("dashboard.user.index") }}',
      "columns": [
        { "data": "username" },
        { "data": "fullname" },
        { "data": "is_online" },
        { "data": "account_status" },
        { "data": "action" }
      ],
      "buttons": [
        {!! __js::dt_buttons() !!}
      ],
      "columnDefs":[
        {
          "targets" : 0,
          "orderable" : true,
          "class" : 'w-10p'
        },
        {
          "targets" : [3],
          "orderable" : true,
          "class" : 'w-6p'
        },
        {
          "targets" : 2,
          "class" : 'w-6p'
        },
        {
          "targets" : 4,
          "orderable" : false,
          "class" : 'action4'
        },
        {
          "targets" : 1,
          "orderable" : false,
        },
      ],
      'order': [[2,'desc']],
      "responsive": false,
      "initComplete": function( settings, json ) {
        $('#tbl_loader').fadeOut(function(){
          $("#users_table_container").fadeIn();
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
          $("#users_table #"+active).addClass('success');
        }
      }
    })

    style_datatable("#users_table");

    //Need to press enter to search
    $('#users_table_filter input').unbind();
    $('#users_table_filter input').bind('keyup', function (e) {
      if (e.keyCode == 13) {
        users_table.search(this.value).draw();
      }
    });

    $(".filters").change(function(event) {
      filter_dt();
    });

    //Show/Hide Password


    //Submit add user form
    $("#add_user_form").submit(function(e){
      e.preventDefault();
      form = $(this);
      uri = "{{ route('dashboard.user.store') }}";
      loading_btn(form);
      $.ajax({
        url: uri,
        data: $(this).serialize(),
        type: 'POST',
        dataType: 'json',
        success: function(response) {
          users_table.draw(false);
          active = response.slug;
          succeed(form,true,false);
        },
        error: function (response) {
          errored(form,response);
        }
      })
    })

    //Upon changing the multiple select
    $("body").on("change", ".select_multiple", function(e){
      selected = $(":selected",this).length;
      all = $(this).children('option').length;

      if(selected == 0){
        $(this).siblings('.help-block').html('No module selected');
      }else{
        was_were = 'were';
        module_s = 'modules';
        if(selected <= 1){
          was_were = 'was';
        }
        if(all <= 1){
          module_s = 'module';
        }
        $(this).siblings('.help-block').html( selected + ' out of ' + all +' '+module_s+' '+was_were+' selected.');
      }

      percentage = selected/all*100;
      console.log(percentage);
      panel_body = $(this).parents('.panel-body');

      panel_body.find('.progress-bar').css('width',percentage+'%');
    })

    //Clearing selection of modules
    $("body").on('click', '.clear_btn', function() {
      select_element = $(this).parent('div').parent('div').siblings('.panel-body').find('.select_multiple');

      select_element.children('option').prop("selected",false);
      select_element.change();
    });

    //Show user button
    $("body").on('click', '.view_user_btn', function() {
      id = $(this).attr('data');
      $("#view_user_modal .modal-content").html(modal_loader);
      uri  =" {{ route('dashboard.user.show','slug') }}";
      uri = uri.replace('slug',id);
      $.ajax({
        url: uri,
        type: 'GET',
        success: function (response) {
          console.log(response);
          $("#view_user_modal #modal_loader").fadeOut(function() {
            $("#view_user_modal .modal-content").html(response);
          });
        },
        error: function (response) {
          console.log(response);
        }
      })

    });

    //Edit user button
    $("body").on('click', '.edit_user_btn', function() {
      id = $(this).attr('data');
      $("#edit_user_modal .modal-content").html(modal_loader);
      uri = " {{route('dashboard.user.edit', 'slug') }}";
      uri = uri.replace("slug",id);
      slug = id;

      $.ajax({
        url: uri,
        type: 'GET',
        success: function(response){
          $("#edit_user_modal #modal_loader").fadeOut(function() {
            $("#edit_user_modal .modal-content").html(response);

            $(".select_multiple").each(function(index, el) {
              $(this).change();
            });
          });
        },
        error: function(response){
          console.log(response);
        }
      })

    });

    //Activate and Deactivate


    $("body").on("submit",'#edit_user_form', function(e){
      e.preventDefault();
      id = $(this).attr('data');
      uri = " {{ route('dashboard.user.update', 'slug') }} ";
      uri = uri.replace("slug",id);
      form = $(this);
      loading_btn(form);

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        url : uri,
        type: 'PUT',
        dataType: 'json',
        data : $(this).serialize(),
        success: function(response){
          active = response.slug;
          users_table.draw(false);
          $("#edit_user_modal").modal('hide');
          notify("Changes were saved successfully.", "success");
          succeed(form, true,true);
        },
        error: function(response){
          console.log(response);
          errored(form,response);
        }
      })
    })

    $("body").on("click",".reset_password_btn", function(){
      slug = $(this).attr('data');
      fullname = $(this).attr('fullname');
      Swal.fire({
        title: 'Are you sure you want to reset the password?',
        html: "Account: "+fullname+"</br> The password will be changed to the user's birthday in <b>MMDDYY</b> format",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, reset it!'
      }).then((result) => {
        if (result.isConfirmed) {
          url = "{{route('dashboard.user.reset_password','slug')}}"
          url = url.replace("slug",slug);
          $.ajax({
              url : url,
              type: 'GET',
              headers: {
                  {!! __html::token_header() !!}
              },
              success: function (res) {
                 console.log(res);
                 active = res.slug;
                 users_table.draw(false);
                Swal.fire(
                        'Reset Successful!',
                        'The password of '+fullname+" has been reset successfully.",
                        'success'
                )
              },
              error: function (res) {
                  console.log(res);
                  notify(res.responseJSON.message,'danger');
              }
          })

        }
      })
    })

    $("body").on("submit", "#reset_password_form", function(e){
      e.preventDefault();
      id = $(this).attr("data");
      uri = " {{ route('dashboard.user.reset_password_post', 'slug') }} ";
      uri = uri.replace("slug",id);
      wait_button("#reset_password_form");
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      })
      $.ajax({
        url: uri,
        data: $(this).serialize(),
        type: 'PATCH',
        dataType: 'json',
        success: function(response){

          console.log(response);
          $("#reset_password_form .has-error").each(function(){
            $(this).removeClass("has-error");
            $(this).children("span").remove();
          });
          if(response.result == -1){
            $("#reset_password_form ."+response.target).addClass('has-error');
            $("#reset_password_form ."+response.target).append('<span class="help-block">'+response.message+'</span>');
          }
          if(response.result == 0){
            notify(response.message,'warning');
          }
          if(response.result == 1){
            notify("Account successfully updated.",'success');
            $("#reset_password_modal").modal("hide");
            active = response.slug;
            users_table.draw(false);
          }
        },
        error: function(response){
          console.log(response);
          errored("#reset_password_form","save",response);
        }
      })
    })

    $("body").on("change", ".change_pass_chk", function(){
      prop = $(this).prop("checked");
      if(prop == true){
        $(".password_container input").each(function(index, el) {
          $(this).removeAttr('disabled');
        });
        $(".password_container .password input").attr("name","password");
        $(".password_container .password_confirmation input").attr("name","password_confirmation");
      }else{
        $(".password_container input").each(function(index, el) {
          $(this).attr('disabled','disabled');
          $(this).removeAttr('name');
        });
      }
    });

    {{--$("body").on("click", ".delete_user_btn", function(){--}}
    {{--  id = $(this).attr('data');--}}
    {{--  confirm("{{ route('dashboard.user.destroy', 'slug') }}",id);--}}
    {{--})--}}


    $("body").on("click",".ac_dc", function () {
      var first_name = $(this).attr("user");
      var slug = $(this).attr('data');
      if($(this).attr("status") == "active"){
        Swal.fire({
          title: "Deactivate "+first_name+"'s account?",
          showCancelButton: true,
          confirmButtonText: 'Deactivate',
          icon : 'question',
        }).then((result) => {
          if (result.isConfirmed) {
            var uri = "{{route('dashboard.user.deactivate','slug')}}";
            var uri = uri.replace('slug',slug);
            $.ajax({
              url : uri,
              type : 'POST',
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              success: function (response) {
                Swal.fire('Account deactivation success!', '', 'success');
                active = response.slug;
                users_table.draw(false);
              },
              error: function (response) {
                console.log(response);
              }
            });
          }
        })
      }else{
        Swal.fire({
          title: "Activate "+first_name+"'s account?",
          showCancelButton: true,
          confirmButtonText: 'Activate',
          icon : 'question',
        }).then((result) => {
          if (result.isConfirmed) {
            var uri = "{{route('dashboard.user.activate','slug')}}";
            var uri = uri.replace('slug',slug);
            $.ajax({
              url : uri,
              type : 'POST',
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              success: function (response) {
                Swal.fire('Account activation success!', '', 'success');
                active = response.slug;
                users_table.draw(false);
              },
              error: function (response) {
                console.log(response);
              }
            });
          }
        })
      }
    })

    $("body").on('click','.activity_properties_btn',function () {
      var id = $(this).attr('data');
      $.ajax({
        url : '',
        type: 'GET',
        success: function (response) {
          console.log(response);
          Swal.fire({
            title: 'Properties',
            // icon: 'info',
            html: response,
            showCloseButton: true,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonText:
                    '<i class="fa fa-check"></i> Done!',
            confirmButtonAriaLabel: 'Done!',
            cancelButtonText:
                    '<i class="fa fa-thumbs-down"></i>',
            cancelButtonAriaLabel: 'Thumbs down'
          })
        },
        error: function (response) {
          console.log(response);
        }
      });
    })

    $("#employee_name").typeahead({
      ajax : "{{ route('dashboard.user.index') }}?typeahead=true",
        onSelect:function (result) {
          $("#add_user_employee_modal input[name='employee_slug']").val(result.value);
          $.ajax({
              url : "{{ route('dashboard.user.index') }}?afterTypeahead=true",
              data : {id:result.value},
              type: 'GET',
              headers: {
                  {!! __html::token_header() !!}
              },
              success: function (res) {
                $("#new_user_from_employee_form_containter").html(res);
                $("#new_user_from_employee_form_containter").slideDown(function () {
                  setTimeout(function () {
                    $("#new_user_from_employee_form #username").focus();
                  },100)
                },1000);


              },
              error: function (res) {
                  console.log(res);
              }
          })
        },
        lookup: function (i) {
          console.log(i);
        }
    });
  </script>


@endsection