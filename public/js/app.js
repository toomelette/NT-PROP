// LOADER
$('#loader')
    .hide() 
    .ajaxStart(function() {
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    })
;


// SELECT2 Caller
$('.select2').select2();


// SELECT2 Multiple
$('select[multiple]').select2({
    closeOnSelect: false,
});

$("body").on("click",".remove_row_btn",function () {
    $(this).parents('tr').remove();
})

$("body").on("click",".add_button",function () {
    let btn = $(this);
    $.ajax({
        url : btn.attr('uri'),
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    success: function (res) {
        $(btn.attr('data-target')+' tbody').append(res);
    },
    error: function (res) {
    }
})
});

function toast(type,message,heading = null) {
    $.toast({
        text: message, // Text that is to be shown in the toast
        heading: heading, // Optional heading to be shown on the toast
        icon: type, // Type of toast icon
        showHideTransition: 'slide', // fade, slide or plain
        allowToastClose: true, // Boolean value true or false
        hideAfter: 5000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
        stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
        position: 'bottom-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
        textAlign: 'left',  // Text alignment i.e. left, right or center
        loader: false,  // Whether to show loader or not. True by default
        loaderBg: '#9EC600',  // Background color of the toast loader
    });
}
var autonum_settings = {
    currencySymbol : ' ',
    decimalCharacter : '.',
    digitGroupSeparator : ',',
    watchExternalChanges: true
};

var autonum_settings3D = {
    currencySymbol : ' ',
    decimalCharacter : '.',
    digitGroupSeparator : ',',
    watchExternalChanges: true,
    decimalPlaces: 3,
};

function autonum_init(){
    $(".autonum").each(function(){
        new AutoNumeric(this, autonum_settings);
    });
}
function autonum_init_modal_new(btn){
    setTimeout(function () {
        $(btn.attr('data-target')+" .autonum").each(function(){
            new AutoNumeric(this, autonum_settings);
        });
    },1000);
}

// Filter Form Submit Rule
$(document).ready(function($){
    autonum_init();
   $("#filter_form").submit(function() {
        $(this).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
        return true;
    });
    $("form").find( ":input" ).prop( "disabled", false );
    $(".tree_active").parents('.treeview').addClass('menu-open');
    $(".tree_active").parents('li').addClass('active');
    $(".tree_active").parents('.treeview-menu').slideDown();
});



// Price Format
// $(".priceformat").priceFormat({
//     prefix: "",
//     thousandsSeparator: ",",
//     clearOnEmpty: true,
//     allowNegative: true
// });



// Input to Uppercase
$(document).on('blur', "input[data-transform=uppercase]", function () {
    $(this).val(function (_, val) {
        return val.toUpperCase();
    });
});


// iCheck for checkbox and radio inputs
$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
  checkboxClass: 'icheckbox_minimal-blue',
  radioClass   : 'iradio_minimal-blue'
});


// Date Picker
$('.datepicker').each(function(){
    $(this).datepicker({
        autoclose: true,
        dateFormat: "mm/dd/yy",
        orientation: "bottom"
    });
});



// Time Picker
$('.timepicker').timepicker({
  showInputs: false,
  minuteStep: 1,
  showMeridian: true,
});



// Table Rule
$(document).on('change', 'select[id="action"]', function () {
  var element = $(this).children('option:selected');
  if(element.data('type') == '1' ){ 
    location = element.data('url');
  }
});


// Delete row in Dynamic Table
$(document).on("click","#delete_row" ,function(e) {
    $(this).closest('tr').remove();
});



// PJAX Form Caller
$(document).on('submit', 'form[data-pjax]', function(event) {
    $.pjax.submit(event, '#pjax-container');
});



// PJAX Link Caller
$(document).pjax('a[data-pjax]', '#pjax-container');




// PJAX INITIALIZATIONS
$(document).on('ready pjax:success', function() {
    

    // Filter Form Submit Rule
    $(document).ready(function($){
       $("#filter_form").submit(function() {
            $(this).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
            return true;
        });
        $("form").find( ":input" ).prop( "disabled", false );
    });


    // Price Format
    $(".priceformat").priceFormat({
        prefix: "",
        thousandsSeparator: ",",
        clearOnEmpty: true,
        allowNegative: true
    });


    // Text to Upper Case
    $(document).on('blur', "input[data-transform=uppercase]", function () {
        $(this).val(function (_, val) {
            return val.toUpperCase();
        });
    });


    // Select2
    $('.select2').select2();


    // Datepicker
    $('.datepicker').each(function(){
        $(this).datepicker({
            autoclose: true,
            dateFormat: "mm/dd/yy",
            orientation: "bottom"
        });
    });

});

function style_datatable(target_table){
    //Search Bar Styling
    $(target_table+'_filter input').css("width","300px");
    $(target_table+"_filter input").attr("placeholder","Press enter to search");
    $(target_table+"_wrapper .dt-buttons").addClass('col-md-4');
    $(target_table+"_wrapper .dataTables_length ").addClass('col-md-3');
    $(".buttons-html5").each(function(index, el) {
        $(this).addClass('btn-sm');
    });
}

function loading_btn(target_form){
    form_id = $(target_form[0]).attr('id');
    button = $("#"+form_id+" button[type='submit']");
    button_html = button.html();
    icon = button.children('i');
    old_icon_class = icon.attr('class');
    icon.attr('old-class',old_icon_class);
    icon.removeClass();
    icon.addClass('fa fa-spinner fa-spin');
    button.attr("disabled","disabled");
}

function errored(target_form, response){

    form_id = $(target_form[0]).attr('id');
    remove_loading_btn(target_form);


    if(response.status == 503 || response.status == 510){
        toast('error',response.responseJSON.message,'Error!');
    }else if(response.status == 422){
        unmark_required(target_form);
        mark_required(target_form,response);
    }else if(response.status == 413){
        toast('error','File too large.','Error!');
    }else{
        alert(response.responseJSON.message);
    }
}
function remove_loading_btn(target_form){
    form_id = $(target_form[0]).attr('id');
    button = $("#"+form_id+" button[type='submit']");
    button.removeAttr("disabled");

    icon = button.children('i');
    icon.removeClass();
    icon.addClass(icon.attr('old-class'));
}

function unmark_required(target_form){
    form_id = $(target_form[0]).attr('id');
    $("#"+form_id+" .has-error:not(.except)").each(function(){
        $(this).removeClass('has-error');
        $(this).children("span").last().remove();
    });
    $("#"+form_id+" .warning-message").each(function () {
        $(this).remove();
    })
}

function mark_required(target_form, response){
    form_id = $(target_form[0]).attr('id');
    $.each(response.responseJSON.errors, function(i, item){
        let replaced = i.replaceAll('.','_');

        if($("#"+form_id+" ."+replaced).hasClass('single') == true){
            $("#"+form_id+" ."+replaced).parent().append("<span class='warning-message small text-danger'> "+item+" </span>");
            $("#"+form_id+" ."+replaced).parent().addClass('has-error');
        }else{
            if($("#"+form_id+" ."+replaced).hasClass('minimal') == false){
                $("#"+form_id+" ."+replaced).append("<span class='warning-message small text-danger'> "+item+" </span>");
            }
        }

        $("#"+form_id+" ."+replaced).addClass('has-error');

    });
}


function wait_button(target_form){
    button = $(target_form+" button[type='submit']");
    button_html = button.html();

    button.html("<i class='fa fa-spinner fa-spin'> </i> Please wait");
    button.attr("disabled","disabled");
    Pace.restart();
}

function unwait_button(target_form , type){
    text = '';
    switch(type){
        case 'save' :
            text = "<i class='fa fa-save'> </i> Save";
            break;
        default:
            text = type;
            break;
    }
    button = $(target_form+" button[type='submit']");
    button.html(text);
    button.removeAttr('disabled');
}

function notify(message, type = 'success'){
    $.notify({
        // options
        message: message
    },{
        // settings
        type: type,
        z_index: 5000,
        delay: 3500,
        placement: {
            from: "top"
        },
        animate:{
            enter: "animate__animated animate__bounceIn",
            exit: "animated zoomOutRight"
        }
    });
}

function load_modal2(btn){
    $(btn.attr('data-target')+" .modal-content").html(modal_loader);
}


function populate_modal2(btn, response){
    target_modal = btn.attr('data-target');
    $(target_modal +" #modal_loader").fadeOut(function() {
        $(target_modal +" .modal-content").html(response);
        $('.datepicker').each(function(){
            $(this).datepicker({
                autoclose: true,
                dateFormat: "mm/dd/yy",
                orientation: "bottom"
            });
        });
        $("ol.sortable").sortable();
    });
}

function populate_modal2_error(response){
    if(response.status == 503){
        notify('Error: '+ response.responseJSON.message, 'danger');
    }
    alert(response.responseJSON.message);
}

function succeed(target_form, reset,modal){
    form_id = $(target_form[0]).attr('id');
    if(reset == true){
        $("#"+form_id).get(0).reset();
    }

    if(modal == true){
        $(target_form).parents('.modal').modal('hide');
    }
    unmark_required(target_form);
    remove_loading_btn(target_form);
}

function wait_this_button(btn) {
    btn.attr('disabled','disabled');
    prent = btn.children('i').parent();
    btn.attr('old-i',prent.html());
    btn.html('<i class="fa fa-spin fa-spinner"></i>');
}

function unwait_this_button(btn) {
    btn.removeAttr('disabled');
    prent = btn.children('i').parent();
    btn.html(btn.attr('old-i'));
}

function delete_data(slug,url){
    var btn = $("button[data='"+slug+"']");
    btn.parents('#'+slug).addClass('warning');
    url = url.replace('slug',slug);
    Swal.fire({
        title: 'Please confirm to delete permanently this data.',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-trash"></i> DELETE',
        confirmButtonColor: '#d73925',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url : url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
            success: function (res) {
                if(res == 1){
                    btn.parents('#'+slug).addClass('danger');
                    btn.parents('#'+slug).addClass('animate__animated animate__zoomOutLeft');
                    toast('warning','Data deleted successfully','Deleted');
                    setTimeout(function () {
                        btn.parents('#'+slug).parent('tbody').parent('table').DataTable().draw(false);
                    },500);
                }else{
                    btn.parents('#'+slug).removeClass('warning');
                    toast('danger','An error occurred while deleting data.','Error!');
                }

            },
            error: function (res) {
                toast('error',res.responseJSON.message,'Error '+res.status);
                console.log(res);
                btn.parents('#'+slug).removeClass('warning');
            }
        });
            // Swal.fire('Saved!', '', 'success')
        }else{
            btn.parents('#'+slug).removeClass('warning');
        }
    })
}
const formatToCurrency = amount => {
    return "" + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
};

function toastMessage(text){
    $.toast({
        text: text, // Text that is to be shown in the toast
        showHideTransition: 'fade', // fade, slide or plain
        allowToastClose: false, // Boolean value true or false
        hideAfter: 1000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
        stack: false, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
        position: 'bottom-center', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
        textAlign: 'center',  // Text alignment i.e. left, right or center
        loader: false,  // Whether to show loader or not. True by default
    });
}

function makeid(length) {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
    }
    return result;
}

const iCheckRadioOptions = {
    checkboxClass: 'icheckbox_square-green',
    radioClass: 'iradio_square-green',
    increaseArea: '5%' // optional
};

