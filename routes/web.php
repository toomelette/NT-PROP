<?php

/** Auth **/

use App\Swep\Helpers\Helper;
use Rats\Zkteco\Lib\ZKTeco;

Route::group(['as' => 'auth.'], function () {
	
	Route::get('/', 'Auth\LoginController@showLoginForm')->name('showLogin');
	Route::post('/', 'Auth\LoginController@login')->name('login');
	Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
	Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('/username_lookup','Auth\AccountRecoveryController@username_lookup')->name('username_lookup');
    Route::post('/reset_password','Auth\AccountRecoveryController@reset_password')->name('reset_password');
    Route::post('/verify_email','Auth\AccountRecoveryController@verify_email')->name('verify_email');
    Route::get('/reset_password _via_email','Auth\AccountRecoveryController@reset_password_via_email')->name('reset_password_via_email');
});


/** HOME **/
Route::get('dashboard/home', 'HomeController@index')->name('dashboard.home')->middleware(['check.user_status','verify.email']);


Route::get('/dashboard/plantilla/print','PlantillaController@print')->name('plantilla.print');

Route::group(['prefix'=>'dashboard', 'as' => 'dashboard.',
    'middleware' => ['check.user_status', 'last_activity','sidenav_mw','verify.email']
], function () {
    Route::post('dashboard/changePass','UserController@changePassword')->name('all.changePass');
    Route::post('/change_side_nav','SidenavController@change')->name('sidenav.change');
    Route::get('/profile', 'ProfileController@details')->name('profile.details');
    Route::patch('/profile/update_account_username/{slug}', 'ProfileController@updateAccountUsername')->name('profile.update_account_username');
    Route::patch('/profile/update_account_password/{slug}', 'ProfileController@updateAccountPassword')->name('profile.update_account_password');
    Route::patch('/profile/update_account_color/{slug}', 'ProfileController@updateAccountColor')->name('profile.update_account_color');
    Route::get('/profile/print_pds/{slug}/{page}', 'ProfileController@printPds')->name('profile.print_pds');
    Route::post('/profile/save_family_info','ProfileController@saveFamilyInfo')->name('profile.save_family_info');

    Route::get('/profile/service_record','ProfileController@serviceRecord')->name('profile.service_record');
    Route::post('/profile/service_record_store','ProfileController@serviceRecordStore')->name('profile.service_record_store');
    Route::put('/profile/service_record_update/{slug}','ProfileController@serviceRecordUpdate')->name('profile.service_record_update');
    Route::delete('/profile/service_record/destroy/{slug}','ProfileController@serviceRecordDestroy')->name('profile.service_record_destroy');

    Route::get('/profile/training','ProfileController@training')->name('profile.training');
    Route::post('/profile/training_store','ProfileController@trainingStore')->name('profile.training_store');
    Route::put('/profile/training_update/{slug}','ProfileController@trainingUpdate')->name('profile.training_update');
    Route::delete('/profile/training_destroy/{slug}','ProfileController@trainingDestroy')->name('profile.training_destroy');

    Route::get('/ajax/{for}','AjaxController@get')->name('ajax.get');
    Route::post('/ajax/{for}','AjaxController@post')->name('ajax.post');
    Route::post('/profile/educ_bg_store','ProfileController@educationalBackgroundStore')->name('profile.educ_bg_store');
    Route::post('/profile/eligibility_store','ProfileController@eligibilityStore')->name('profile.eligibility_store');
    Route::post('/profile/work_experience_store','ProfileController@workExperienceStore')->name('profile.work_experience_store');

    Route::post('/profile/select_theme','ProfileController@selectTheme')->name('profile.select_theme');

    Route::get('/view_edit_history','EditHistoryController@index')->name('view_edit_history');


    Route::get('/my_pr/{slug}/print','MyPrController@print')->name('my_pr.print');
    Route::resource('my_pr', 'MyPrController');

    Route::get('/my_jr/{slug}/print','MyJrController@print')->name('my_jr.print');
    Route::resource('my_jr', 'MyJrController');

    Route::get('/cancellation_request/create','CancellationRequestController@create')->name('cancellationRequest.create');
    Route::get('/cancellation_request/findTransactionByRefNumber/{refNumber}/{refBook}','CancellationRequestController@findTransactionByRefNumber')->name('cancellationRequest.ByRefNumber');
    Route::post('/cancellation_request/store','CancellationRequestController@store')->name('cancellationRequest.store');
    Route::get('/cancellation_request/print/{slug}','CancellationRequestController@print')->name('cancellationRequest.print');
    Route::get('/cancellation_request/myIndex','CancellationRequestController@myIndex')->name('cancellationRequest.myIndex');

    Route::get('/wmr/create','WMRController@create')->name('wmr.create');
    Route::get('/wmr/findTransByRefNumber/{refNumber}','WMRController@findTransByRefNumber')->name('wmr.findTransByRefNumber');
    Route::post('/wmr/store','WMRController@store')->name('wmr.store');
    Route::get('/wmr/{slug}/print','WMRController@print')->name('wmr.print');
    Route::get('/wmr/myIndex','WMRController@myIndex')->name('wmr.myIndex');
    Route::get('/wmr/{slug}/edit','WMRController@edit')->name('wmr.edit');
    Route::patch('/wmr/update/{slug}','WMRController@update')->name('wmr.update');
    Route::patch('/wmr/receiveWmr/{slug}','WMRController@receiveWmr')->name('wmr.receiveWmr');

    Route::get('/wmr/myIndex','MyWmrController@myIndex')->name('wmr.myIndex');

    Route::get('/ris/create','RISController@create')->name('ris.create');
    Route::get('/ris/findTransByRefNumber/{refNumber}','RISController@findTransByRefNumber')->name('ris.findTransByRefNumber');
    Route::post('/ris/store','RISController@store')->name('ris.store');
    Route::get('/ris/{slug}/print','RISController@print')->name('ris.print');
    Route::get('/ris/myIndex','RISController@myIndex')->name('ris.myIndex');
    Route::get('/ris/{slug}/edit','RISController@edit')->name('ris.edit');
    Route::patch('/ris/update/{slug}','RISController@update')->name('ris.update');
    Route::get('/ris/findIAR/{refNumber}','RISController@findIAR')->name('ris.findIAR');
    Route::patch('/ris/receiveRIS/{refNumber}','RISController@receiveRIS')->name('ris.receiveRIS');

    Route::get('/ris/myIndex','MyRISController@myIndex')->name('ris.myIndex');


    Route::resource('ris_my', 'MyRISController');

    Route::resource('ppmp_subaccounts', 'PPMPSubaccountsController');

    Route::resource('wmr_my', 'MyWmrController');

    Route::get('/my_jr/{slug}/print','MyJrController@print')->name('my_jr.print');

    Route::get('/request_vehicle/create','RequestForVehicleController@create')->name('request_vehicle.create');
    Route::post('/request_vehicle/store','RequestForVehicleController@store')->name('request_vehicle.store');
    Route::get('/request_vehicle/{slug}/print_own','RequestForVehicleController@printOwn')->name('request_vehicle.print_own');

    Route::get('/request_vehicle/my_requests','RequestForVehicleController@myRequests')->name('request_vehicle.my_requests');

    Route::get('/pr/monitoring/index','PRController@monitoringIndex')->name('pr.monitoringIndex');
    Route::get('/jr/monitoring/index','JRController@monitoringIndex')->name('jr.monitoringIndex');

    Route::post('/user/update_project_id/{employee_no}', 'UserController@UpdateProjectID')->name('user.update_project_id');

});

/** Dashboard **/
Route::group(['prefix'=> 'dashboard','as'=> 'dashboard.', 'middleware' => ['check.user_status', 'check.user_route', 'last_activity','verify.email']], function () {

	/** USER **/

	Route::post('/user/activate/{slug}', 'UserController@activate')->name('user.activate');
	Route::post('/user/deactivate/{slug}', 'UserController@deactivate')->name('user.deactivate');
	Route::get('/user/{slug}/reset_password', 'UserController@resetPassword')->name('user.reset_password');
	Route::patch('/user/reset_password/{slug}', 'UserController@resetPasswordPost')->name('user.reset_password_post');
	Route::get('/user/{slug}/sync_employee', 'UserController@syncEmployee')->name('user.sync_employee');
	Route::patch('/user/sync_employee/{slug}', 'UserController@syncEmployeePost')->name('user.sync_employee_post');
	Route::post('/user/unsync_employee/{slug}', 'UserController@unsyncEmployee')->name('user.unsync_employee');

	Route::resource('user', 'UserController');

	/** MENU **/
	Route::resource('menu', 'MenuController');

    /** MENU **/
    Route::get('/submenu/fetch','SubmenuController@fetch')->name('submenu.fetch');
	Route::resource('submenu','SubmenuController');


    /** PAP **/
    Route::resource('pap', 'PapController');

    /** PPMP Modal **/
    Route::resource('ppmp_modal', 'PPMPModalController');

    /** PAP  Parents**/
    Route::resource('pap_parent', 'PapParentController');

    Route::resource('ppmp', 'PPMPController');

    Route::resource('dtr', 'DTRController');

    /** DTR **/
    Route::resource('jo_employees','JOEmployeesController');

    /** Budget Proposal**/
    Route::resource('budget_proposal', 'BudgetProposalController');

    /** PPMP **/
    Route::resource('ppmp', 'PPMPController');
    /** PR **/
    Route::post('/pr/{slug}/cancel','PRController@cancel')->name('pr.cancel');
    Route::post('/pr/unlock/{slug}','PRController@unlock')->name('pr.unlock');
    Route::get('/pr/{slug}/print','PRController@print')->name('pr.print');
    Route::get('/pr/{slug}/edit_thru_admin','PRController@edit_thru_admin')->name('pr.edit_thru_admin');
    //Route::get('/pr/monitoring/index','PRController@monitoringIndex')->name('pr.monitoringIndex');
    Route::resource('pr', 'PRController');

    Route::post('/jr/{slug}/cancel','JRController@cancel')->name('jr.cancel');
    Route::post('/jr/unlock/{slug}','JRController@unlock')->name('jr.unlock');
    Route::get('/jr/{slug}/print','JRController@print')->name('jr.print');
    Route::get('/jr/{slug}/edit_thru_admin','JRController@edit_thru_admin')->name('jr.edit_thru_admin');
    //Route::get('/jr/monitoring/index','JRController@monitoringIndex')->name('jr.monitoringIndex');
    Route::resource('jr', 'JRController');

    Route::get('/rfq/{slug}/print','RFQController@print')->name('rfq.print');
    Route::get('/rfq/findTransByRefNumber/{refNumber}/{refBook}/{action}/{id}','RFQController@findTransByRefNumber')->name('rfq.findTransByRefNumber');
    Route::resource('rfq', 'RFQController');

    Route::resource('articles','ArticlesController');

    Route::get('/aq/{slug}/print','AqController@print')->name('aq.print');
    Route::get('/aq/createManual','AqController@createManual')->name('aq.createManual');
    Route::post('/aq/storeManual','AqController@storeManual')->name('aq.storeManual');
    Route::resource('aq','AqController')->except(['create','store']);
    Route::get('/aq/create/{slug}','AqController@create')->name('aq.create');
    Route::post('/aq/store/{slug}','AqController@store')->name('aq.store');
    Route::post('/aq/finalized/{slug}','AqController@finalized')->name('aq.finalized');
    Route::post('/aq/unlock/{slug}','AqController@unlock')->name('aq.unlock');

    Route::get('/cancellation_request/index','CancellationRequestController@index')->name('cancellationRequest.index');
    Route::post('/cancellation_request/approve/{slug}','CancellationRequestController@approve')->name('cancellationRequest.approve');

    //Route::resource('cancellationRequest', 'CancellationRequestController');

    Route::get('/award_notice_abstract/create','AwardNoticeAbstractController@create')->name('awardNoticeAbstract.create');
    Route::get('/award_notice_abstract/findTransactionByRefNumber/{refNumber}/{refBook}','AwardNoticeAbstractController@findTransactionByRefNumber')->name('awardNoticeAbstract.ByRefNumber');
    Route::get('/award_notice_abstract/findSupplier/{slug}','AwardNoticeAbstractController@findSupplier')->name('awardNoticeAbstract.BySupplier');
    Route::post('/award_notice_abstract/store','AwardNoticeAbstractController@store')->name('awardNoticeAbstract.store');
    Route::get('/award_notice_abstract/print/{slug}','AwardNoticeAbstractController@print')->name('awardNoticeAbstract.print');
    Route::get('/award_notice_abstract/index','AwardNoticeAbstractController@index')->name('awardNoticeAbstract.index');
    Route::get('/award_notice_abstract/edit/{slug}','AwardNoticeAbstractController@edit')->name('awardNoticeAbstract.edit');
    Route::patch('/award_notice_abstract/update/{slug}','AwardNoticeAbstractController@update')->name('awardNoticeAbstract.update');

    /*Route::get('/purchase_order/create','PurchaseOrderController@create')->name('purchaseOrder.create');
    Route::get('/purchase_order/findRefNumber/{refNumber}/{refBook}','PurchaseOrderController@findRefNumber')->name('purchaseOrder.findRefNumber');
    Route::post('/purchase_order/store','PurchaseOrderController@store')->name('purchaseOrder.store');
    Route::get('/purchase_order/print/{slug}','PurchaseOrderController@print')->name('purchaseOrder.print');*/

    Route::get('/po/{slug}/print1','POController@print1')->name('po.print1');
    Route::get('/po/{slug}/printManual','POController@printManual')->name('po.printManual');
    Route::get('/po/{slug}/edit','POController@edit')->name('po.edit');
    Route::get('/po/findTransByRefNumber/{refNumber}/{refBook}/{action}/{id}','POController@findTransByRefNumber')->name('po.findTransByRefNumber');
    Route::get('/po/findSupplier/{slug}','POController@findSupplier')->name('po.findSupplier');
    Route::patch('/po/update/{slug}','POController@update')->name('po.update');
    Route::get('/po/createpublicbidding','POController@createpublicbidding')->name('po.createpublicbidding');
    Route::post('/po/{slug}/cancel','POController@cancel')->name('po.cancel');
    Route::get('/po/createManual','POController@createManual')->name('po.createManual');
    Route::post('/po/storeManual','POController@storeManual')->name('po.storeManual');
    Route::resource('po', 'POController');

    Route::get('/jo/{slug}/print','JOController@print')->name('jo.print');
    Route::get('/jo/{slug}/edit','JOController@edit')->name('jo.edit');
    Route::get('/jo/findTransByRefNumber/{refNumber}/{refBook}/{action}/{id}','JOController@findTransByRefNumber')->name('jo.findTransByRefNumber');
    Route::get('/jo/findSupplier/{slug}','JOController@findSupplier')->name('jo.findSupplier');
    Route::patch('/jo/update/{slug}','JOController@update')->name('jo.update');
    Route::post('/jo/{slug}/cancel','JOController@cancel')->name('jo.cancel');
    Route::get('/jo/createpublicbidding','JOController@createpublicbidding')->name('jo.createpublicbidding');
    Route::resource('jo', 'JOController');

    Route::get('/par/{slug}/print_property_tag','PARController@printPropertyTag')->name('par.print_property_tag');
    Route::get('/par/create','PARController@create')->name('par.create');
    Route::get('/par/getEmployee/{slug}','PARController@getEmployee')->name('par.getEmployee');
    Route::get('/par/getInventoryAccountCode/{slug}','PARController@getInventoryAccountCode')->name('par.getInventoryAccountCode');
    Route::get('/par/{slug}/print','PARController@print')->name('par.print');
    Route::get('/par/edit/{slug}','PARController@edit')->name('par.edit');
    Route::patch('/par/update/{slug}','PARController@update')->name('par.update');
    Route::get('/par/printRpcppe','PARController@printRpcppe')->name('rpcppe.printRpcppe');
    Route::get('/par/printRpcppeExcel','PARController@printRpcppeExcel')->name('rpcppe.printRpcppeExcel');

    Route::get('/par/generateRpcppeByCriteria','PARController@rpcppeByCriteria')->name('rpcppe.rpcppeByCriteria');
    Route::get('/par/generateRpcppe','PARController@generateRpcppe')->name('rpcppe.generateRpcppe');
    Route::get('/par/{location}/printInventoryCountForm','PARController@printInventoryCountForm')->name('rpcppe.printInventoryCountForm');
    Route::get('/par/generateInventoryCountFormByCriteria','PARController@generateInventoryCountFormByCriteria')->name('rpcppe.generateICF');
    Route::get('/par/uploadPic/{slug}','PARController@uploadPic')->name('par.uploadPic');

    Route::get('/par/propCard/{slug}','PARController@propCard')->name('par.propCard');
    Route::post('/par/savePropCard/{slug}','PARController@savePropCard')->name('par.savePropCard');
    Route::get('/par/{slug}/printPropCard','PARController@printPropCard')->name('par.printPropCard');

    Route::post('/par/savePict','PARController@savePict')->name('par.savePict');

    Route::resource('par', 'PARController');

    Route::resource('ics', 'ICSController');

    Route::get('/iar/create','IARController@create')->name('iar.create');
    Route::get('/iar/findTransByRefNumber/{refNumber}','IARController@findTransByRefNumber')->name('iar.findTransByRefNumber');
    Route::post('/iar/store','IARController@store')->name('iar.store');
    Route::get('/iar/{slug}/print','IARController@print')->name('iar.print');
    Route::get('/iar/index','IARController@index')->name('iar.index');
    Route::get('/iar/{slug}/edit','IARController@edit')->name('iar.edit');
    Route::patch('/iar/update/{slug}','IARController@update')->name('iar.update');
    Route::patch('/iar/receiveIar/{slug}','IARController@receiveIar')->name('iar.receiveIar');

    Route::get('/trip_ticket/create','TripTicketController@create')->name('trip_ticket.create');
    Route::get('/trip_ticket/findTransByRefNumber/{refNumber}','TripTicketController@findTransByRefNumber')->name('trip_ticket.findTransByRefNumber');
    Route::get('/trip_ticket/findOdo/{vehicle}','TripTicketController@findOdo')->name('trip_ticket.findOdo');
    Route::post('/trip_ticket/store','TripTicketController@store')->name('trip_ticket.store');
    Route::get('/trip_ticket/{slug}/print','TripTicketController@print')->name('trip_ticket.print');
    Route::get('/trip_ticket/index','TripTicketController@index')->name('trip_ticket.index');
    Route::get('/trip_ticket/{slug}/edit','TripTicketController@edit')->name('trip_ticket.edit');
    Route::patch('/trip_ticket/update/{slug}','TripTicketController@update')->name('trip_ticket.update');

    Route::get('/trip_ticket/generateReport','TripTicketController@generateReport')->name('ttr.generateReport');
    Route::get('/trip_ticket/printReport','TripTicketController@printReport')->name('ttr.printReport');
    Route::get('/trip_ticket/generateReport2','TripTicketController@generateReport2')->name('ttr.generateReport2');
    Route::get('/trip_ticket/printReport2','TripTicketController@printReport2')->name('ttr.printReport2');
    Route::get('/trip_ticket/generateReport3','TripTicketController@generateReport3')->name('ttr.generateReport3');
    Route::get('/trip_ticket/printReport3','TripTicketController@printReport3')->name('ttr.printReport3');


    Route::get('/drivers/index','DriversController@index')->name('drivers.index');
    Route::get('/drivers/create','DriversController@create')->name('drivers.create');
    Route::get('/drivers/edit','DriversController@edit')->name('drivers.edit');

    Route::get('/ris/index','RISController@index')->name('ris.index');

    Route::get('/wmr/index','WMRController@index')->name('wmr.index');

    Route::get('/gp/create','GPController@create')->name('gp.create');
    Route::post('/gp/store','GPController@store')->name('gp.store');
    Route::get('/gp/{slug}/print','GPController@print')->name('gp.print');
    Route::get('/gp/index','GPController@index')->name('gp.index');
    Route::get('/gp/{slug}/edit','GPController@edit')->name('gp.edit');
    Route::patch('/gp/update/{slug}','GPController@update')->name('gp.update');
    Route::patch('/gp/receiveGp/{slug}','GPController@receiveGp')->name('gp.receiveGp');

    Route::get('/ics/create','ICSController@create')->name('ics.create');
    Route::get('/ics/findIAR/{refNumber}','ICSController@findIAR')->name('ics.findIAR');
    Route::post('/ics/store','ICSController@store')->name('ics.store');
    Route::get('/ics/{slug}/print','ICSController@print')->name('ics.print');
    Route::get('/ics/{slug}/edit','ICSController@edit')->name('ics.edit');
    Route::patch('/ics/update/{slug}','ICSController@update')->name('ics.update');
    Route::get('/ics/report','ICSController@report')->name('ics.report');
    Route::get('/ics/{slug}/print_ics_tag','ICSController@printIcsTag')->name('ics.print_ics_tag');
    Route::get('/ics/{employee}/printByEmployee','ICSController@printByEmployee')->name('ics.printByEmployee');
    Route::resource('ics', 'ICSController');

    Route::post('/noa/store','NOAController@store')->name('noa.store');
    Route::get('/noa/{slug}/print','NOAController@print')->name('noa.print');
    Route::get('/noa/{slug}/edit','NOAController@edit')->name('noa.edit');
    Route::patch('/noa/update/{slug}','NOAController@update')->name('noa.update');
    Route::resource('noa', 'NOAController');

    Route::post('/ntp/store','NTPController@store')->name('ntp.store');
    Route::get('/ntp/{slug}/print','NTPController@print')->name('ntp.print');
    Route::get('/ntp/{slug}/edit','NTPController@edit')->name('ntp.edit');
    Route::patch('/ntp/update/{slug}','NTPController@update')->name('ntp.update');
    Route::resource('ntp', 'NTPController');

    Route::resource('supplier', 'SupplierController');
    Route::resource('email_recipients',\App\Http\Controllers\EmailRecipientsController::class);

    Route::get('/request_vehicle/{slug}/print','RequestForVehicleController@print')->name('request_vehicle.print');
    Route::get('request_vehicle/{slug}/actions','RequestForVehicleController@actions')->name('request_vehicle.actions');
    Route::post('request_vehicle/{slug}/take_action','RequestForVehicleController@takeAction')->name('request_vehicle.take_action');


    Route::resource('request_vehicle', \App\Http\Controllers\RequestForVehicleController::class)->except([
        'create','store'
    ]);

    Route::get('/vehicles/schedule','VehiclesController@schedule')->name('vehicles.schedule');
    Route::get('/vehicles/index','VehiclesController@index')->name('vehicles.index');
    Route::get('/vehicles/create','VehiclesController@create')->name('vehicles.create');
    Route::post('/vehicles/store','VehiclesController@store')->name('vehicles.store');
    Route::get('/vehicles/{slug}/edit','VehiclesController@edit')->name('vehicles.edit');
    Route::patch('/vehicles/update/{slug}','VehiclesController@update')->name('vehicles.update');

});

Route::get('/verifyEmail',function (){
    if(\Illuminate\Support\Facades\Auth::user()->email != null){
        return redirect('/');
    }
   return view('ppu.verify_email.verify');
});

Route::post('/verifyEmail',function (\Illuminate\Http\Request $request){
    $request->validate([
        'email' => 'required|email',
    ]);
    $user = Auth::user();
    $user->email = $request->email;
    if($user->save()){
        return 1;
    }
    abort(503,'Error updating email.');
});

Route::get('test',function (){
    dd(\App\Swep\Helpers\Arrays::recipientsOfProcurementUpdates());
});

Route::get('/test',function (){
   dd(public_path());
});

Route::get('/mailtest',function (){
    if(Helper::getSetting('send_email_notification')->int_value == 1) {
        \App\Jobs\PRReceivedNotification::dispatch('gguance221@gmail.com', 'SUBJECT', 'BODY', [
            'geraldjesterguance02@gmail.com',
            'geraldjesterguance021@gmail.com',
        ]);
        return 'Email sent.';
    }else{
        dd('send_email_notification not allowed');
    }

});



