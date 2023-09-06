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

    Route::resource('ppmp_subaccounts', 'PPMPSubaccountsController');

    Route::get('/my_jr/{slug}/print','MyJrController@print')->name('my_jr.print');

    Route::get('/request_vehicle/create','RequestForVehicleController@create')->name('request_vehicle.create');
    Route::post('/request_vehicle/store','RequestForVehicleController@store')->name('request_vehicle.store');
    Route::get('/request_vehicle/{slug}/print_own','RequestForVehicleController@printOwn')->name('request_vehicle.print_own');

    Route::get('/request_vehicle/my_requests','RequestForVehicleController@myRequests')->name('request_vehicle.my_requests');
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
    Route::get('/pr/{slug}/print','PRController@print')->name('pr.print');
    Route::get('/pr/monitoring/index','PRController@monitoringIndex')->name('pr.monitoringIndex');
    Route::resource('pr', 'PRController');

    Route::post('/jr/{slug}/cancel','JRController@cancel')->name('jr.cancel');
    Route::get('/jr/{slug}/print','JRController@print')->name('jr.print');
    Route::get('/jr/monitoring/index','JRController@monitoringIndex')->name('jr.monitoringIndex');
    Route::resource('jr', 'JRController');

    Route::get('/rfq/{slug}/print','RFQController@print')->name('rfq.print');
    Route::get('/rfq/findTransByRefNumber/{refNumber}/{refBook}/{action}/{id}','RFQController@findTransByRefNumber')->name('rfq.findTransByRefNumber');
    Route::resource('rfq', 'RFQController');

    Route::resource('articles','ArticlesController');

    Route::get('/aq/{slug}/print','AqController@print')->name('aq.print');
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

    Route::get('/po/{slug}/print','POController@print')->name('po.print');
    Route::get('/po/{slug}/edit','POController@edit')->name('po.edit');
    Route::get('/po/findTransByRefNumber/{refNumber}/{refBook}/{action}/{id}','POController@findTransByRefNumber')->name('po.findTransByRefNumber');
    Route::get('/po/findSupplier/{slug}','POController@findSupplier')->name('po.findSupplier');
    Route::patch('/po/update/{slug}','POController@update')->name('po.update');
    Route::resource('po', 'POController');

    Route::get('/jo/{slug}/print','JOController@print')->name('jo.print');
    Route::get('/jo/{slug}/edit','JOController@edit')->name('jo.edit');
    Route::get('/jo/findTransByRefNumber/{refNumber}/{refBook}/{action}/{id}','JOController@findTransByRefNumber')->name('jo.findTransByRefNumber');
    Route::get('/jo/findSupplier/{slug}','JOController@findSupplier')->name('jo.findSupplier');
    Route::patch('/jo/update/{slug}','JOController@update')->name('jo.update');
    Route::resource('jo', 'JOController');

    Route::get('/par/{slug}/print_property_tag','PARController@printPropertyTag')->name('par.print_property_tag');
    Route::get('/par/create','PARController@create')->name('par.create');
    Route::get('/par/getEmployee/{slug}','PARController@getEmployee')->name('par.getEmployee');
    Route::get('/par/getInventoryAccountCode/{slug}','PARController@getInventoryAccountCode')->name('par.getInventoryAccountCode');
    Route::get('/par/{slug}/print','PARController@print')->name('par.print');
    Route::get('/par/edit/{slug}','PARController@edit')->name('par.edit');
    Route::patch('/par/update/{slug}','PARController@update')->name('par.update');
    Route::get('/par/{fund_cluster}/printRpcppe','PARController@printRpcppe')->name('rpcppe.printRpcppe');
    Route::get('/par/generateRpcppeByCriteria','PARController@rpcppeByCriteria')->name('rpcppe.rpcppeByCriteria');
    Route::get('/par/generateRpcppe','PARController@generateRpcppe')->name('rpcppe.generateRpcppe');
    Route::get('/par/{location}/printInventoryCountForm','PARController@printInventoryCountForm')->name('rpcppe.printInventoryCountForm');
    Route::get('/par/generateInventoryCountFormByCriteria','PARController@generateInventoryCountFormByCriteria')->name('rpcppe.generateICF');
    Route::resource('par', 'PARController');



    Route::get('/iar/create','IARController@create')->name('iar.create');
    Route::get('/iar/findTransByRefNumber/{refNumber}','IARController@findTransByRefNumber')->name('iar.findTransByRefNumber');
    Route::post('/iar/store','IARController@store')->name('iar.store');
    Route::get('/po/{slug}/print','IARController@print')->name('iar.print');
    Route::get('/iar/index','IARController@index')->name('iar.index');
    Route::get('/iar/edit/{slug}','IARController@edit')->name('iar.edit');





    Route::resource('supplier', 'SupplierController');
    Route::resource('email_recipients',\App\Http\Controllers\EmailRecipientsController::class);

    Route::get('/request_vehicle/{slug}/print','RequestForVehicleController@print')->name('request_vehicle.print');
    Route::get('request_vehicle/{slug}/actions','RequestForVehicleController@actions')->name('request_vehicle.actions');
    Route::post('request_vehicle/{slug}/take_action','RequestForVehicleController@takeAction')->name('request_vehicle.take_action');
    Route::resource('request_vehicle', \App\Http\Controllers\RequestForVehicleController::class)->except([
        'create','store'
    ]);

    Route::get('/vehicles/schedule','VehiclesController@schedule')->name('vehicles.schedule');
    Route::resource('vehicles',\App\Http\Controllers\VehiclesController::class);
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



