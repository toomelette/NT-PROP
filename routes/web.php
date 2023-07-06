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
    Route::get('/reset_password_via_email','Auth\AccountRecoveryController@reset_password_via_email')->name('reset_password_via_email');
});


/** HOME **/
Route::get('dashboard/home', 'HomeController@index')->name('dashboard.home')->middleware('check.user_status');


Route::get('/dashboard/plantilla/print','PlantillaController@print')->name('plantilla.print');

Route::group(['prefix'=>'dashboard', 'as' => 'dashboard.',
    'middleware' => ['check.user_status', 'last_activity','sidenav_mw']
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
});

/** Dashboard **/
Route::group(['prefix'=> 'dashboard','as'=> 'dashboard.', 'middleware' => ['check.user_status', 'check.user_route', 'last_activity']], function () {

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
    Route::get('/po/findTransByRefNumber/{refNumber}/{refBook}/{action}/{id}','POController@findTransByRefNumber')->name('po.findTransByRefNumber');
    Route::get('/po/findSupplier/{slug}','POController@findSupplier')->name('po.findSupplier');
    Route::resource('po', 'POController');

    Route::get('/jo/{slug}/print','JOController@print')->name('jo.print');
    Route::get('/jo/findTransByRefNumber/{refNumber}/{refBook}/{action}/{id}','JOController@findTransByRefNumber')->name('jo.findTransByRefNumber');
    Route::get('/jo/findSupplier/{slug}','JOController@findSupplier')->name('jo.findSupplier');
    Route::resource('jo', 'JOController');

    Route::get('/par/{slug}/print','PARController@print')->name('par.print');
    Route::get('/par/edit/{slug}','PARController@edit')->name('par.edit');
    Route::patch('/par/update/{slug}','PARController@update')->name('par.update');
    Route::resource('par', 'PARController');

    Route::get('/rpci/{fund_cluster}/print','RPCIController@print')->name('rpci.print');
    Route::get('/rpci/generate','RPCIController@generate')->name('rpci.generate');
    Route::resource('rpci', 'RPCIController');

    Route::resource('supplier', 'SupplierController');
});

Route::get('test',function (){
    abort(500,'Sayang');
});

Route::get('/arrangePap',function (){
   $resps = \App\Models\PPURespCodes::query()
       ->where('division','=','')
       ->where('section','=','')
       ->get();
    foreach ($resps as $resp) {
        $resp->desc = $resp->department;
        $resp->save();
   }
});


