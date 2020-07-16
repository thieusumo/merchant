<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('dashboard');
});*/

Auth::routes();

Route::post('add-device-token', 'API\UserController@addDeviceToken')->name('addDeviceToken')->middleware('auth');

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/login', 'UserController@login')->name('login');
Route::post('/login', 'UserController@postLogin')->name('login');
Route::get('/reset-password', 'Auth\ResetPasswordController@index')->name('reset-password');
Route::get('/find-token/{token?}', 'Auth\ResetPasswordController@find')->name('find-token');
Route::post('/change-newpass','Auth\ResetPasswordController@resetPassword')->name('change-newpass');
Route::post('/reset-password', 'Auth\ResetPasswordController@create')->name('reset-password');
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::group(['middleware' => ['auth']], function () {
    //
    Route::get('/', function () {
        return view('dashboard');
    });
    // View::composer('*', function($view)
    // {
    //     $id=\App\Http\Controllers\Controller::getCurrentPlaceId();
    //     if(Auth::user()){
    //         $phone=Session::get('current_user_phone');
    //         $number=\App\Models\PosNotification::where('notification_place_id',$id)->where('notification_readed',0)->where('notification_user_phone','not like',$phone)->count('id');
    //         $posnotice=\App\Models\PosNotification::where('notification_place_id',$id)->where('notification_readed',0)->where('notification_user_phone','not like',$phone)->orderBy('id',"desc")->get();
    //         $view->with('posnotice',$posnotice);
    //         $view->with('number',$number);
    //         $view->with('phone',$phone);
    //     }
    //     $view->with('place_id',$id);
    // });
    
    //Upload Image
    Route::match(['get', 'post'], 'ajax-image-upload', 'ImageController@ajaxImage');
    Route::delete('ajax-remove-image/{filename}', 'ImageController@deleteImage');

    Route::get('/change-profile', 'UserController@changeProfile')->name('change-profile');
    Route::post('/change-profile', 'UserController@postChangeProfile')->name('change-profile');

    Route::get('/change-password', 'UserController@changePassword')->name('change-password');
    Route::post('/change-password', 'UserController@postChangePassword')->name('change-password');

    Route::get('/logout', 'UserController@logout');

    Route::get('/test', 'HomeController@test');
    //change session place id
    Route::post('/change-placeid', 'HomeController@changePlace')->name('postchangeplaceid');

    Route::group(['prefix'=>'dashboard'],function(){
        Route::get('/','HomeController@dashboard');
    });

    Route::group(['prefix'=>'statistic'],function(){
        Route::get('/','HomeController@dashboard');
    });

    
    Route::group(['prefix'=>'users'],function(){
        Route::get('/', 'UserController@listUser')->middleware('permission:read-users');
        Route::get('/roles','UserController@listRole')->middleware(['permission:read-roles']);
        Route::get('/permissions','UserController@permissions')->middleware(['permission:read-permission-setting']);      
        Route::get('/edit/{id?}','UserController@editUser')->where(['id' => '[0-9]+'])->name('edit-user')->middleware('permission:update-users');
        Route::get('/delete','UserController@deleteUser')->where(['id' => '[0-9]+'])->name('delete-user')->middleware('permission:delete-users');
        Route::get('get-user','UserController@getUserDatatable')->name('get-users')->middleware('permission:read-users');
        Route::get('change-status','UserController@changeStatus')->name('change-enable-user')->middleware('permission:update-users');
        Route::post('/edit','UserController@saveUser')->where(['id' => '[0-9]+'])->name('save-user');
        Route::get('get-role','UserController@getRoleDatatable')->name('get-roles')->middleware('permission:read-roles');
        Route::post('save-role','UserController@saveRole')->name('save-roles');
        Route::get('delete-role','UserController@deleteRole')->name('delete-role')->middleware('permission:delete-roles');
        Route::post('change-permission','UserController@changePermission')->name('change-permission');
    });

    Route::group(['prefix'=>'salefinances', 'namespace'=>'SaleFinance'],function(){
        Route::get('/schedule','ScheduleController@index')->name('schedule-index')->middleware('permission:read-schedule');
        Route::get('/get-schedule','ScheduleController@getSchedule')->name('get-schedule')->middleware('permission:read-schedule');
        Route::get('/get-detail-schedule','ScheduleController@getDetailSchedule')->name('get-detail-schedule')->middleware('permission:read-schedule');
        Route::get('/get-resource','ScheduleController@getResource')->name('get-resource')->middleware('permission:read-schedule');

        Route::get('get-schedule-by-month','ScheduleController@getScheduleByMonth')->name('get-schedule-by-month')->middleware('permission:read-schedule');
        Route::get('get-services-by-bookingid','ScheduleController@getServicesByBookingid')->name('get-services-by-bookingid')->middleware('permission:read-schedule');
        Route::get('get-list-booking','ScheduleController@getListBooking')->name('getListBooking')->middleware('permission:read-schedule');
        Route::get('/booking','TicketController@index')->name('booking-list')->middleware('permission:read-tickets');
        Route::get('/update-ticket-status','TicketController@updateTicketStatus')->name('update-ticket-status')->middleware('permission:update-tickets');
        Route::get('/booking/{id}','TicketController@view')->where(['id' => '[0-9]+'])->name('booking-view')->middleware('permission:read-tickets');
        Route::get('/payment/{id?}','PaymentController@checkout')->where(['id' => '[0-9]+'])->name('payment-checkout')->middleware('permission:read-payment');   
        Route::get('/order-history','OrderController@index')->middleware('permission:read-order-history');
        Route::get('/order-history/{id}','OrderController@view')->where(['id' => '[0-9]+'])->name('order-history-detail')->middleware('permission:update-order-history');
        Route::get('/expenses','ExpenseController@index')->name('expenses')->middleware('permission:read-expense');  
        Route::get('/get-expenses','ExpenseController@getDatatable')->name('get-expenses')->middleware('permission:read-expense');  
        Route::get('/get-booking-form-payment','PaymentController@getBookingFromPayment')->where(['id' => '[0-9]+'])->name('get-booking-form-payment')->middleware('permission:read-payment');  
        Route::get('/delete-expense','ExpenseController@delete')->name('delete-expense')->middleware('permission:delete-expense'); 
        Route::post('/save-expenses','ExpenseController@saveAdd')->name('save-expenses');  
        Route::get('/expense/{id?}','ExpenseController@edit')->name('expense')->where(['id' => '[0-9]+'])->middleware('permission:update-expense');
        Route::get('/booking-delete','TicketController@delete')->name('booking-delete')->middleware('permission:delete-tickets');
        Route::get('/booking-cofirm','TicketController@bookingConfirm')->name('booking-confirm')->middleware('permission:read-schedule');
        Route::get('/booking-clone/{id?}','TicketController@bookingClone')->where(['id' => '[0-9]+'])->name('booking-clone')->middleware('permission:read-schedule');
        Route::get('/working-cofirm','TicketController@workingConfirm')->name('working-confirm')->middleware('permission:read-schedule');


        Route::get('/update-amount','ExpenseController@updateAmount')->name('update-amount')->middleware('permission:update-expense');
        Route::get('/expense-template/{id?}','ExpenseController@expenseTemplate')->name('expense-template')->where(['id' => '[0-9]+'])->middleware('permission:update-expense');
        Route::get('/expense-template/delete','ExpenseController@deleteExpenseTemplate')->name('delete-expense-template')->middleware('permission:delete-expense');
        Route::get('/expense-template/add','ExpenseController@addExpenseTemplate')->name('add-expense-template')->middleware('permission:create-expense');
        Route::get('/check-data','ExpenseController@checkData')->name('check-data-null');
        Route::get('/insert-expense','ExpenseController@insertExpense')->name('insert-expense');
        Route::get('/change-style','ExpenseController@changeStyle')->name('change-style')->middleware('permission:update-expense');
        Route::get('/change-bill','ExpenseController@changeBill')->name('change-bill')->middleware('permission:update-expense');
        Route::get('/get-expenses-aver','ExpenseController@getExpensesAver')->name('get-expenses-aver');
        Route::get('/expenses-copy-data','ExpenseController@expensesCopy')->name('expenses-copy-data')->middleware('permission:update-expense');
        Route::get('/add-new-pe','ExpenseController@addNewPe')->name('add-new-pe')->middleware('permission:update-expense');

        Route::get('/get-booking-place', 'TicketController@getBookingListByPlace')->name('get-booking-place');
        Route::get('/get-order-history', 'OrderController@getOrderHistory')->name('get-order-history');
        Route::get('get-cateservices', 'PaymentController@getCateServices')->name('get-cateservices');
        Route::get('get-services-payment', 'PaymentController@getServices')->name('get-services-payment');
        Route::get('get-staffs-payment', 'PaymentController@getStaffs')->name('get-staffs-payment');
        Route::post('set-session-payment', 'PaymentController@putSessionPayment')->name('set-session-payment');

        Route::post('clear-session-payment', 'PaymentController@clearSessionPayment')->name('clear-session-payment');
        Route::post('save-customer-payment', 'PaymentController@saveCustomerInPayment')->name('save-customer-payment');
        Route::get('get-promotion-payment', 'PaymentController@getPromotion')->name('get-promotion-payment');
        Route::get('get-booking-list-payment', 'PaymentController@getBookingList')->name('get-booking-list-payment');
        Route::get('edit-booking/{id?}', 'TicketController@editBooking')->where(['id' => '[0-9]+'])->name('edit-booking');
        Route::get('choose-service', 'TicketController@chooseService')->name('choose-service');
        Route::get('delete-booking-session', 'TicketController@deleteBookingSession')->name('delete-booking-session');
        Route::get('get-booking-first', 'TicketController@getBooking')->name('get-booking-first');
        Route::get('get-service-booking', 'TicketController@getServiceBooking')->name('get-service-booking');
        Route::get('add-worker-session', 'TicketController@addWorkerBooking')->name('add-worker-session');
        Route::get('check-customer', 'TicketController@checkCustomer')->name('check-customer');
        Route::post('send-booking', 'TicketController@sendBooking')->name('send-booking');
        Route::get('booking-form-schedule/{worker_id?}/{date_selected?}', 'TicketController@bookingFromSchedule')->where(['worker_id' => '[0-9]+'])->name('booking-form-schedule');
        
        Route::get('get-point-from-coupon', 'PaymentController@getPointFromCoupon')->name('get-point-from-coupon');
        Route::get('get-point-from-payment', 'PaymentController@getPointFromPayment')->name('get-point-from-payment');
        Route::get('get-giftcard-code', 'PaymentController@getGiftcardCode')->name('get-giftcard-code');
        Route::get('get-customer-info-payment', 'PaymentController@getCustomerInfoPayment')->name('get_customer_info_payment');
        Route::post('buy-giftcard-payment', 'PaymentController@buyGifcard')->name('buy-giftcard-payment');
        Route::post('get-product-payment', 'PaymentController@getProduct')->name('get-product-payment');
        Route::get('convert-use-point-to-amount', 'PaymentController@convertUserPointToAmount')->name('convert-use-point-to-amount');

        //delete ticket
        Route::post('check-pass-for-delete-ticket', 'PaymentController@checkPassForDeleteTicket')->name('check-pass-for-delete-ticket');
        //SAVE TICKET TO DATABASE
         Route::post('save-ticket-to-database', 'PaymentController@saveTicketToDatabase')->name('save-ticket-to-database');
         Route::get('get-point-giftcard', 'PaymentController@getPointGiftcard')->name('get-point-giftcard');
         Route::post('get-giftcode-customer', 'PaymentController@getGiftcodeCustomer')->name('get-giftcode-customer');
         Route::get('get-customer-for-edit-ticket', 'PaymentController@getCustomerForEditTicket')->name('get-customer-for-edit-ticket');
         Route::get('buy-giftcard', 'PaymentController@buyGiftcardOutSide')->name('buy-giftcard');
         Route::get('check-correct-ticket', 'PaymentController@checkCorrectTicket')->name('check-correct-ticket');
         Route::get('get-correct-ticket-today', 'PaymentController@checkCorrectTicketToday')->name('get-correct-ticket-today');
         Route::get('add-turn-with-cateservice', 'PaymentController@addTurnWithService')->name('add-turn-with-cateservice');
         Route::get('check-ticket','PaymentController@checkTicket')->name('check-ticket');
         Route::get('get-ticket-today','PaymentController@getTicketToday')->name('get-ticket-today');
         Route::get('save-ticket-combine','PaymentController@saveTicketCombine')->name('save-ticket-combine');
         Route::get('split-ticket-with-staff','PaymentController@splitTicketWithStaff')->name('split-ticket-with-staff');
         Route::get('split-ticket','PaymentController@splitTicket')->name('split-ticket');
         Route::get('void-ticket','PaymentController@voidTicket')->name('void-ticket');
         Route::get('update-ticket-payment','PaymentController@updateTicketPayment')->name('update-ticket-payment');
         Route::get('get-membership-point','PaymentController@getMembershipPoint')->name('get-membership-point');
         Route::get('buy-membership','PaymentController@buyMembership')->name('buy-membership');
         Route::get('get-list-service-membership','PaymentController@getListServiceMembership')->name('get-list-service-membership');


    });
    
    Route::group(['prefix'=>'clients', 'namespace'=>'DataSetup'],function(){
        Route::get('/client/{id?}','CustomerController@edit')->where(['id' => '[0-9]+'])->name('client')->middleware('permission:update-clients');
        Route::get('/client/info/{id?}','CustomerController@view')->where(['id' => '[0-9]+'])->name('client-info')->middleware('permission:read-clients');
        Route::get('get-booking','CustomerController@getBookingListByCustomer')->name('get-booking');
        Route::get('get-orders','CustomerController@getOrderListByCustomer')->name('get-orders');

        Route::get('/','CustomerController@index')->name('listClients')->middleware('permission:read-clients');

        Route::get('/groups','CustomerController@groups')->name('groups')->middleware('permission:read-client-group');
        Route::post('save-group','CustomerController@saveGroup')->name('save-group');
        Route::get('get-groups','CustomerController@getGroupsDatatable')->name('get-groups')->middleware('permission:read-client-group');
        Route::get('get-customers','CustomerController@getCustomerDatatable')->name('get-customers')->middleware('permission:read-clients');
        Route::get('get-customers-payment','CustomerController@getCustomerDatatablePayment')->name('get-customers-payment');
        
        Route::post('save-customer','CustomerController@saveCustomer')->name('save-customer');
        Route::get('delete-customer','CustomerController@deleteCustomer')->name('delete-customer')->middleware('permission:delete-clients');     
        Route::get('get-list-customertag','CustomerController@getListCustomerTag')->name('get-list-customertag');
        Route::get('delete-group','CustomerController@deleteGroup')->name('delete-group')->middleware('permission:delete-client-group');
        Route::get('/import','CustomerController@import')->name('import');
        Route::post('/import-clients','CustomerController@importClients')->name('import-clients');
        Route::get('/export-clients','CustomerController@exportClients')->name('export-clients');    
    });
    
    Route::group(['prefix'=>'management', 'namespace'=>'DataSetup'],function(){  
        Route::get('/clients','CustomerController@index')->name('clients')->middleware('permission:read-clients');
        Route::get('/client/{id?}','CustomerController@edit')->where(['id' => '[0-9]+'])->name('client')->middleware('permission:update-clients');
        Route::get('/client/info/{id?}','CustomerController@view')->where(['id' => '[0-9]+'])->name('client-info');



        
        Route::post('/save-staff','StaffController@saveWorker')->name('save-staff');
        Route::get('/staffs','StaffController@index')->name('list-staff')->middleware('permission:read-rent-stations');
        Route::get('/staff/{id?}','StaffController@edit')->where(['id' => '[0-9]+'])->name('staff')->middleware('permission:update-rent-stations');
        Route::get('/taxforms','TaxFormController@index')->where(['id' => '[0-9]+'])->middleware('permission:read-tax-forms');
        Route::get('/get-worker-taxform','TaxFormController@getWorkerTaxform')->name('get-worker-taxform');
        Route::get('/tax-form-1099/{id}','TaxFormController@taxForm1099')->name('tax-form-1099');
        Route::get('/tax-form-w2/{id}','TaxFormController@taxFormW2')->name('tax-form-w2');
        Route::get('/tax-form-1096','TaxFormController@taxForm1096')->name('tax-form-1096');
        Route::get('/time-sheet/{id}','TaxFormController@timeSheet')->name('time-sheet');
        Route::get('all-time-sheet','TaxFormController@allTimeSheet')->name('all-time-sheet');

        Route::get('/services','ServiceController@index')->middleware('permission:read-services-products');
        Route::get('/service/{id?}','ServiceController@setup')->where(['id' => '[0-9]+'])->name('data-setup');
        Route::get('get-workers','StaffController@getWorker')->name('get-workers');
        Route::get('change-staff-status','StaffController@changeStatus')->name('change-staff-status');
        Route::get('delete-staff','StaffController@deleteWorker')->name('delete-staff');
        Route::get('staff/statements','StaffController@getStaffStatement')->name('changeInOutOff');
        Route::get('get-services','ServiceController@getService')->name('get-services-management');
        Route::get('/staff-attendances','StaffController@getStaffAttendances')->name('getStaffAttendances');
        Route::get('change-checkin-status', 'StaffController@changeCheckinStatus')->name('change-checkin-status');


        Route::get('get-combo','ServiceController@getCombo')->name('get-combo');
        Route::get('get-drink','ServiceController@getDrink')->name('get-drink');
        Route::get('get-product','ServiceController@getProduct')->name('get-product');
        Route::get('delete-combo','ServiceController@deleteCombo')->name('delete-combo');
        Route::get('delete-drink','ServiceController@deleteDrink')->name('delete-drink');
        Route::get('delete-product','ServiceController@deleteProduct')->name('delete-product');
        Route::get('save-cate-service','ServiceController@saveCateService')->name('save-cateservice');
        Route::get('delete-cate-service','ServiceController@deleteCateService')->name('delete-cate-service');
        Route::post('add-product','ServiceController@addProduct')->name('add-product');
        Route::get('get-combo-detail','ServiceController@getComboDetail')->name('get-combo-detail');
        Route::post('save-combo','ServiceController@saveCombo')->name('save-combo');
        Route::get('delete-combo-item','ServiceController@deleteComboItem')->name('delete-combo-item');
        Route::get('save-drink','ServiceController@saveDrink')->name('save-drink');
        Route::get('delete-service-mana','ServiceController@deleteService')->name('delete-service-mana');
        Route::get('save-service-mana','ServiceController@saveService')->name('save-service-mana');
        Route::get('/import','ServiceController@import')->where(['id' => '[0-9]+'])->name('import-service-mana');
        Route::post('/import-services','ServiceController@importServices')->name('post-import-services-mana');
        Route::get('export','ServiceController@exportService')->name('export-service-mana');   

        Route::get('loyalty','LoyaltyController@index')->name('loyalty');   
        Route::post('loyalty','LoyaltyController@postLoyalty')->name('postLoyalty');

        Route::get('/turn-tracker', 'TurnTrackerController@index')->name('turnTracker');
        Route::get('/get-list-turn-tracker', 'TurnTrackerController@getListTurnTracker')->name('getListTurnTracker');
        Route::post('/save-list-turn-tracker', 'TurnTrackerController@saveListTurnTracker')->name('saveListTurnTracker');
        Route::post('/delete-list-turn-tracker', 'TurnTrackerController@delete')->name('deleteTurnTracker');
        
        Route::get('/option-turn-tracker', 'TurnTrackerController@getOptionTurnTracker')->name('getOptionTurnTracker');
        Route::post('/option-turn-tracker', 'TurnTrackerController@postOptionTurnTracker')->name('postOptionTurnTracker');

        Route::get('membership','MembershipController@index')->name('membership');   
        Route::post('membership','MembershipController@post');
        Route::get('get-service-by-cateservice-id','MembershipController@getServiceByCateServiceId')->name('getServiceByCateServiceId');   
        Route::get('get-membership-detail-by-membership-id','MembershipController@getMembershipDetailByMembershipId')->name('getMembershipDetailByMembershipId');   
        // Route::post('delete-membership-detail','MembershipController@delete')->name('deleteMembershipDetail');   
        Route::post('save-membership-detail','MembershipController@save')->name('saveMembershipDetail');   
    });

    Route::group(['prefix'=>'marketing', 'namespace'=>'Marketing'],function(){
        Route::get('/reviews','ReviewController@listReviews')->name('list_reviews')->middleware('permission:read-reviews');
        Route::get('/showtable','ReviewController@return_table_ajax')->name("showtable");
        Route::get('/buysms','ReviewController@buysms')->name('view_buy_sms');
        Route::post('/post-authorization','ReviewController@post_authorization_sms_pakage')->name('post_authorization_sms_pakage');

        //ajax reviews 
        Route::get('/reviews/ajax_yelp','ReviewController@ajax_yelp')->name('ajax_yelp');
        Route::get('/reviews/ajax_facebook','ReviewController@ajax_facebook')->name('ajax_facebook');
        Route::get('/reviews/ajax_google','ReviewController@ajax_google')->name('ajax_google');
        Route::get('reviews/ajax_allreviews', 'ReviewController@ajax_allreviews')->name('ajax_allreviews');
        Route::get('reviews/ajax_website', 'ReviewController@ajax_website')->name('ajax_website');
        Route::get('reviews/ajax_bad_review', 'ReviewController@ajax_bad_review')->name('ajax_bad_review');
        Route::post('reviews/ajax_filter_form','ReviewController@ajax_filter_form')->name('ajax_filter_form');
        Route::get('reviews/check-review-website','ReviewController@checkReviewWebsite')->name('checkReviewWebsite');

        Route::get('reviews/check-review-website','ReviewController@checkReviewWebsite')->name('checkReviewWebsite');

        Route::get('/badreviews','ReviewController@badReviews')->name('badReviews');
        Route::get('/sms','SMSController@index')->name('smsAccountSummary'); 
        //load content template && group receivers
        Route::get('/sms-setting', 'SMSController@smsSetting')->name('smsSetting');        
        Route::get('/sms-setting/ajax_DataTableSmsSetting', 'SMSController@ajax_DataTableSmsSetting')->name('ajax_DataTableSmsSetting');
        Route::get('/sms-setting/ajax_getCustomerByStringCustomerId', 'SMSController@ajax_getCustomerByStringCustomerId')->name('ajax_getCustomerByStringCustomerId');

        Route::get('/sms/bps','SMSController@bookingPayment')->name('smsBookingPayment');
        //post
        Route::post('/sms/bps','SMSController@post_bookingPayment')->name('post_smsBookingPayment');
        // ajax load content template
        Route::get('/sms/bps/ajax','SMSController@ajax_bookingPayment')->name('ajax_smsBookingPayment');
        //        
        Route::get('/sms/tpl','SMSController@listTemplate')->name('smsTemplate');
        Route::get('/sms/tpladd','SMSController@addContentTemplate')->name('addSmsTemplate');
        // // post add sms content template
        Route::post('/sms/ajax-sms-setting','SMSController@post_addOrEditContentTemplate')->name('post_addOrEditContentTemplate');
        //dataTables
        Route::get('/sms/tpl/datatable/','SMSController@DataTables_content_template')->name('content_template');
        //--
        Route::get('/sms/tpladd/datatable/coupon_code','SMSController@loadDataTables_coupon_code')->name('coupon_code');
        Route::get('/sms/tpladd/datatable/promotion_link','SMSController@loadDataTables_promotion_link')->name('promotion_link');
        //
        Route::get('/sms/tpledit/{id}','SMSController@editContentTemplate')->name('editSmsTemplate')->where(['id' => '[0-9]+']);
        // post edit sms content template
        Route::post('/sms/tpledit/{id}','SMSController@post_editContentTemplate');

        Route::get('/sms/ajax-get-sms-setting', 'SMSController@get_SmsContentTemplate')->name('get_SmsContentTemplate');
        // CREATE SEND SMS EVENT
        Route::get('/sms/sendsms','SMSController@sendSMS')->name('sendSMS');
        Route::post('/sms/sendsms','SMSController@post_sendSMS')->name('post_sendSMS');
        //
        Route::get('/sms/sendemail','SMSController@sendEmail')->name('sendEmail');
        Route::get('/sms/mgmt','SMSController@listSMS')->name('smsManagement');
        // load DataTable SMS Management
        Route::get('/sms/mgmt/datatable','SMSController@Datatable_SMS_Management')->name('smsManagement_DataTables');
        Route::get('/sms/greceiver','SMSController@listGroupReceiver')->name('smsGroupReceiver');
        //dataTables /sms/greceiver
        Route::get('/sms/greceiver/datatable','SMSController@Datatables_group_receivers')->name('group_receivers');
        //
        //dataTables /sms/greceiver_detail
        Route::get('/sms/greceiver_detail/datatable','SMSController@Datatables_group_receivers_detail')->name('group_receivers_detail');
        //
        Route::get('/sms/greceiveradd','SMSController@addGroupReceiver')->name('addSmsGroupReceiver');
        // 
        Route::post('/sms/greceiveradd','SMSController@post_addGroupReceiver');
        // dataTables /sms/greceiveradd
        Route::get('/sms/greceiveradd/datatable','SMSController@Datatables_group_receivers_add')->name('group_receivers_add');
        //--
        Route::get('/sms/greceiver/ajax/delete','SMSController@delete_GroupReceiver')->name('delete.sms.greceiver');
        Route::get('/sms/greceiveradd/download_templatefile','SMSController@download_templatefile')->name('download_templatefile');
        //
        Route::get('/sms/greceiveredit/{id}','SMSController@editGroupReceiver')->name('editSmsGroupReceiver');
        Route::get('/sms/view/{id?}','SMSController@viewSMS')->name('viewSMS')->where(['id' => '[0-9]+'])->where(['id' => '[0-9]+']);
        Route::get('/sms/changestatus','SMSController@changeStatus')->name('changeSMSEventStatus');
        Route::get('/sms/delete','SMSController@deleteEvent')->name('delete-event');
        Route::get('/sms/event','SMSController@getEvent')->name('get-event');
        Route::get('/sms/event-detail','SMSController@eventDetail')->name('event-detail');
        Route::get('/sms/calculate-sms','SMSController@calculateSms')->name('calculate-sms');
        Route::get('/sms/get-recivers','SMSController@getReciever')->name('get-recivers');
        Route::get('/sms/get-event-type','SMSController@getEventType')->name('get-event-type');
        
        
        Route::get('/tracking','TrackingHistoryController@index');    
        Route::get('/contenttemplates','ContentTemplateController@index')->name('contenttemplates'); 
        Route::get('/getcontenttemplates','ContentTemplateController@getContent')->name('getcontenttemplates');
        Route::get('/deletecontenttemplate','ContentTemplateController@deleteContent')->name('deletecontenttemplate'); 
        Route::post('/savecontenttemplate','ContentTemplateController@save')->name('save-contenttemplate');      
        Route::get('/contenttemplate/{id?}','ContentTemplateController@edit')->where(['id' => '[0-9]+'])->name('contentedit');      

        Route::get('/coupons','CouponController@index')->name('coupons')->middleware('permission:read-coupons');
        Route::get('/coupon/add','CouponController@add')->name('addCoupon')->middleware('permission:create-coupons');
        Route::get('/coupon/auto-add','CouponController@autoSetup_Add')->name('autoAddCoupon');      
        Route::post('/coupon/save','CouponController@save')->name('saveCoupon');
        Route::get('/coupon/getdatatables','CouponController@getDataTables')->name("getCouponDataTables")->middleware('permission:read-coupons');
        Route::get('/coupon/get-templates','CouponController@getTemplates')->name("getCouponTemplates");  
        Route::get('/coupon/get-coupon-auto-templates','CouponController@getCouponAutoTemplates')->name("getCouponAutoTemplates");  
        Route::get('/coupon/get-services-by-list-id','CouponController@getServicesByListId')->name("getServicesByListId");  
        Route::post('/coupon/delete','CouponController@delete')->name('deleteCoupon')->middleware('permission:delete-coupons');
        Route::get('/promotions','PromotionController@index')->middleware('permission:read-promotions');
        Route::get('/get-content-template-booking','CouponController@getContentTemplateBooking')->name('get-content-template-booking');
        Route::post('/send-sms-coupon','CouponController@sendSmsCoupon')->name('send-sms-coupon');
        Route::get('/send-sms-coupon/{id?}','CouponController@getSendSmsCoupon')->where(['id' => '[0-9]+'])->name('get-send-sms-coupon');    

        Route::get('/promotion/add','PromotionController@add')->name('addPromotion')->middleware('permission:create-promotions');
        Route::get('/promotion/auto-add','PromotionController@autoSetup_Add')->name('autoAddPromotion');
 
        Route::post('/promotion/change-status','PromotionController@changeStatus')->name('changePromotionStatus')->middleware('permission:update-promotions'); 
        Route::post('/promotion/change-popup-website','PromotionController@ajax_changePopupWebsite')->name('changePopupWebsite'); 
        Route::get('/promotion/get-templates','PromotionController@getTemplates')->name("getPromotionTemplates");          
        Route::get('/promotion/get-promotion-auto-templates','PromotionController@getPromotionAutoTemplates')->name("getPromotionAutoTemplates");          
        Route::post('/promotion/save','PromotionController@save')->name('savePromotion');  
        Route::post('/promotion/delete','PromotionController@delete')->name('deletePromotion');  
        Route::get('/promotion/getdatatables','PromotionController@getDataTables')->name("getPromotionDataTables")->middleware('permission:read-promotions'); 
        Route::get('/promotion/{id?}','PromotionController@view')->where(['id' => '[0-9]+'])->middleware('permission:update-promotions');

        Route::post('/giftcard/save','GiftCardController@save')->name("save-gift-card");
        

        Route::get('/giftcards','GiftCardController@index')->name('giftcards.index')->middleware('permission:read-gift-cards');    
         //load datatable
        Route::get('/giftcards/loadData','GiftCardController@loadData')->name("loadDatatables.giftcard")->middleware('permission:read-gift-cards');   
        //delete column
        Route::get('/giftcards/deleteColumn', 'GiftCardController@deleteColumn')->name('deleteColumn.giftcard');

        Route::get('/giftcard/add','GiftCardController@add')->middleware('permission:create-gift-cards');
        Route::get('/giftcard/detail/{code}','GiftCardController@view')->name("viewGiftCard");


    });


    Route::group(['prefix'=>'webbuilder', 'namespace'=>'WebBuilder'],function(){     

        Route::get('/cateservices','CateServiceController@index')->name('cateservices')->middleware('permission:read-service-categories');
        Route::get('get-cateservice','CateServiceController@getCateServices')->name('get-cateservice')->middleware('permission:read-service-categories'); 
        Route::get('/cateservice/{id?}','CateServiceController@edit')->where(['id' => '[0-9]+'])->name('cateservice')->middleware('permission:update-service-categories');
        Route::post('cateservice/{id?}','CateServiceController@saveCateService');       
        Route::get('delete_cateservice','CateServiceController@deleteCateService')->name('delete-cateservice')->middleware('permission:delete-service-categories');
  
        Route::get('/services','ServiceController@index')->name('service-index')->middleware('permission:read-services');
        Route::get('get-services','ServiceController@getServiceDatatable')->name('get-services')->middleware('permission:read-services');
        Route::get('service/delete','ServiceController@deleteService')->name('delete-service')->middleware('permission:delete-services');
        Route::get('/service/{id?}','ServiceController@edit')->where(['id' => '[0-9]+'])->name('edit-service')->middleware('permission:update-services');
        Route::post('/service/save-service','ServiceController@saveService')->name('save-service');
        Route::post('change-service-status','ServiceController@changeStatus')->name('change-service-status');
        Route::post('change-online-booking','ServiceController@changeOnlineBooking')->name('change-online-booking');

        Route::get('/service/import','ServiceController@import')->where(['id' => '[0-9]+'])->name('import-service');
        Route::post('/import-services','ServiceController@importServices')->name('post-import-services');
        Route::get('export','ServiceController@exportService')->name('export-service');
        Route::get('/menus/import','MenuController@get_importMenus')->name('import-menus');
        Route::post('/menus/import','MenuController@post_importMenus')->name('post_importMenus');
        Route::get('/menus/export','MenuController@exportMenus')->name('export-menus');

        Route::post('upload-image-service','ServiceController@uploadImageService')->name('upload-image-service');
        Route::post('upload-multi-images-service','ServiceController@uploadMultiImages')->name('upload-multi-images-service');
        Route::get('remove-image-service','ServiceController@removeMultiImage')->name('remove-image-service');


        Route::get('/menus','MenuController@index')->name('menus')->middleware('permission:read-menus');
        Route::get('get-menu','MenuController@getMenu')->name('get-menu')->middleware('permission:read-menus');
        Route::get('/menu/{id?}','MenuController@edit')->where(['id' => '[0-9]+'])->name('menu')->middleware('permission:update-menus');
        Route::post('menu/{id?}','MenuController@saveMenu')->name('save-menu');
        Route::get('delete-menu','MenuController@deleteMenu')->name('delete-menu')->middleware('permission:delete-menus');
        Route::get('change-menu-status','MenuController@changeStatus')->name('change-menu-status')->middleware('permission:update-menus');
        Route::post('upload-multi-images','MenuController@uploadMultiImages')->name('upload-multi-images');
        Route::get('remove-image-menu','MenuController@removeMenu')->name('remove-image-menu');


        Route::get('/banners','BannerController@index')->name('banners')->middleware('permission:read-banners');
        Route::get('get-banner','BannerController@getBanner')->name('get-banner')->middleware('permission:read-banners');  
        Route::get('/banner/{id?}','BannerController@edit')->where(['id' => '[0-9]+'])->name('banner')->middleware('permission:update-banners');
        Route::post('banner/{id?}','BannerController@postBanner')->name('save-banner');
        Route::get('delete-banner','BannerController@destroyBanner')->name('delete-banner')->middleware('permission:delete-banners');
        Route::get('change-banner-status','BannerController@changeBannerStatus')->name('change-banner-status')->middleware('permission:update-banners');


        // Route::get('/themes','ThemeController@index');
        // Route::post('/themes/img','ThemeController@getImgLink')->name('get.img.theme');            
        // Route::get('/theme/payment/{id?}','ThemeController@payment')->where(['id' => '[0-9]+']);
        
        Route::get('/contacts','ContactController@index')->name('contacts');
        Route::get('/social-network','SocialNetworkController@index')->name('socialNetworkWebsite');
        Route::get('/get-contacts','ContactController@getContact')->name('get-contacts');
        Route::get('/delete-contact','ContactController@deleteContact')->name('delete-contact');
        Route::post('/save-social','SocialNetworkController@saveSocial')->name('save-social');

        Route::get('web-seo', 'WebSeoController@index')->name('webSeo');
        Route::post('web-seo', 'WebSeoController@save');

        Route::get('website-properties','WebSitePropertyController@index');
        Route::post('website-properties/save','WebSitePropertyController@save')->name('saveWebsiteProperty');
        Route::get('website-properties/datatable','WebSitePropertyController@datatable')->name('wpDatatable');
        Route::get('website-properties/get','WebSitePropertyController@getWebsitePropertyByVariable')->name('getWebsitePropertyByVariable');
        Route::get('website-properties/delete','WebSitePropertyController@deleteWebsiteProperty')->name('deleteWebsiteProperty');
        Route::get('website-properties/export', 'WebSitePropertyController@export')->name('exportWebsiteProperties');
        Route::get('website-properties/import', 'WebSitePropertyController@getImport')->name('importWebsiteProperties');
        Route::post('website-properties/import', 'WebSitePropertyController@postImport');
        Route::get('website-properties/template', 'WebSitePropertyController@template')->name('templateWebsiteProperties');
    });
    
    Route::group(['prefix'=>'setting'],function(){ 
        Route::get('/bstore', 'SettingController@configBusinessStore')->name("configBusinessStore");        
        Route::get('/marketing', 'SettingController@configMarketing')->name("configMarketing"); 
        //       
        Route::get('/system', 'SettingController@configSystem')->name("configSystem"); 
        Route::post('/system/postServerSetting','SettingController@postServerSetting')->name("postServerSetting");
        Route::post('/system/postAuthorize.netPayment','SettingController@postAuthorize')->name("postAuthorize");
        Route::post('/system/postSocialNetworkAccount','SettingController@postSocialNetworkAccount')->name("postSocialNetworkAccount");
        //
        Route::post('/save-bstore', 'SettingController@saveBusinessStore')->name("saveBusinessStore");  

        //send test email
        Route::get('/send_email_test', 'SettingController@sendEmailTest')->name('send_email_test');    
    });    
     Route::group(['prefix'=>'report', 'namespace'=>'Report'],function(){         
        Route::get('/finance', 'FinanceController@index')->name("reportFinance");        
        Route::get('/finance/getdata', 'FinanceController@loadReport')->name("loadReportFinance");        
        Route::get('/client', 'ClientController@index')->name("reportClient");         
        Route::post('/client', 'ClientController@loadReport')->name("loadReportClient");         
        Route::get('/client/ticket-his/{id?}', 'ClientController@getTicketHistory')->where(['id' => '[0-9]+'])->name("getClientTicketHistory");         
        Route::get('/staff', 'StaffController@index')->name("reportStaff");
        Route::get('/staff/getdata', 'StaffController@loadReport')->name("loadReportStaff");
        Route::get('/test-report', 'FinanceController@testReport')->name("test-report");
        Route::get('/get-yearly', 'FinanceController@getYearly')->name("get-yearly");
        Route::get('/get-promotion-coupon', 'FinanceController@getPromotionCoupon')->name("get-promotion-coupon");
        Route::get('/get-client', 'ClientController@getClient')->name("get-client-report");
        Route::get('/get-detail-order', 'ClientController@getDetailOrder')->name("get-detail-order");
        Route::get('/get-giftcard-report', 'ClientController@getGiftcard')->name("get-giftcard-report");
        Route::get('/get-staff-report', 'StaffController@getStaff')->name("get-staff-report");
        Route::get('/sms', 'SmsController@index')->name("reportSMS");
        Route::get('/sms/getdata', 'SmsController@loadReport')->name("loadReportSMS");
        Route::get('/giftcard', 'GiftCardController@index')->name("reportGiftCard");
        Route::get('/giftcard/getdata', 'GiftCardController@loadReport')->name("loadReportGiftCard");
        Route::get('/coupon', 'CouponController@index')->name("reportCoupon");
        Route::get('/coupon/getdata', 'CouponController@loadReport')->name("loadReportCoupon");
        Route::get('/sms/get-event-type-report','SmsController@getEventType')->name('get-event-type-report');
        Route::get('/sms/get-data-event','SmsController@getDataEvent')->name('get-data-event');
        Route::get('/sms/calculate-sms-report','SmsController@calculateSms')->name('calculate-sms-report');
        Route::get('/sms/event-detail-report','SmsController@eventDetail')->name('event-detail-report');
    }); 

     //notification
    //  Route::post('/editCheckNotification', 'NotificationController@editCheckNotification')->name("CheckNotification");
    //  Route::group(['prefix'=>'notification'],function(){ 
    //      Route::get('/', 'NotificationController@notification')->name("showNotification");
    //      Route::get('/all', 'NotificationController@allNotification')->name("allNotification");
    //      Route::get('/getNotification', 'NotificationController@getNotification')->name("getNotification");
    //     Route::get('/get-number', 'NotificationController@getNumber')->name("get-number");
    // });



     //notification
     Route::group(['prefix'=>'notification'],function(){
         Route::get('/', 'NotificationController@notification')->name("notification");
         Route::get('post-notification', 'NotificationController@postNotification')->name("postNotification");
         Route::get('/edit-read-notification', 'NotificationController@editReadNotification')->name("editReadNotification");
         Route::get('/all', 'NotificationController@allNotification')->name("allNotification");
         Route::get('/getNotification', 'NotificationController@getNotification')->name("getNotification");
        // Route::get('/get-number', 'NotificationController@getNumber')->name("get-number");
    });
    
    Route::group(['prefix'=>'dashboard'],function(){ 
         Route::get('/get-today-appt', 'HomeController@getTodayAppointments')->name("getTodayAppointments");
         Route::get('/get-chart-appt', 'HomeController@getUpcomingAppointmentChart')->name("getUpcomingAppointmentChart");         
    });

///code by tri
     Route::post('/giftcard/done','AuthorizeController@chargeCreditCard')->name("saveGiftCard");
///end


     //TEST CREATE MULTI PLACE
     Route::get('test/create-multi-place','UserController@testCreateMultiPlace');
     Route::post('test/create-multi-place','UserController@postTestCreateMultiPlace')->name('post-create-multi-place');

     Route::get('get-notification', 'NotificationController@get5Notification')->name('get5Notification');
     Route::post('read-notification', 'NotificationController@read')->name('readNotification');
});
