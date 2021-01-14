<?php

use App\Models\AffiliatedAccountService;
use App\Models\Companies;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
Auth::routes();

Route::get('/', 'WelcomeController@index')->name('/');
Route::get('/login', 'WelcomeController@index')->name('login');
Route::get('/test/{url}', 'WelcomeController@test')->name('login');

Route::get('/inicio', 'WelcomeController@index')->name('home');
Route::get('/home', 'WelcomeController@index')->name('home');

Route::get('/acercade', function () {
    return view('aboutus');
})->name('aboutus');

Route::get('/enfoque_pedagogico', function () {
    return view('pedagogy');
})->name('pedagogy');

Route::get('/contactenos', function () {
    return view('contactus');
})->name('contactus');

Route::get('/guias_de_aprendizaje', function () {
    return view('sequences.search');
})->name('sequences.search');

Route::get('/guia_de_aprendizaje/{sequence_id}/{sequence_name}', 'CompanyController@show_company_sequences')->name('show_company_sequences');

Route::get('/implementos_de_laboratorio', function () {
    return view('elementsKits.search');
})->name('elementsKits.search');


Route::get('/dialog_template_detail_user', function () {
    return view('roles.admin.dialog_template_detail_user');
})->name('dialog_template_detail_user');


Route::get('/kit_de_laboratorio/{kit_id}/{kit_name}', 'KitController@showKitDetail')->name('elementsKits.getKit');
Route::get('/elemento_de_laboratorio/{element_id}/{element_name}', 'ElementController@showElementDetail')->name('elementsKits.getElement');

Route::get('/planes_de_acceso', function () {
    return view('ratingPlan.list');
})->name('ratingPlan.list');

Route::get('/plan_de_acceso/{rating_plan_id}/{rating_name}/{sequence_id?}', 'RatingPlanController@showRatingPlan')->name('ratingPlan.detailSequence');

Route::get('page500', function(){
    return view('page500',['companies'=>Companies::all()]);
})->name('page500');

Route::get('/terminos_condiciones', function () {
    return view('terms-conditions-social');
})->name('terms_conditions_social');

Route::get('/tutoriales', 'HelpPlatformController@index')->name('help_platform');

//routes login
Route::get('validate_registry_free_plan/{ratingPlanId}', 'Auth\RegisterController@validate_registry_free_plan')->name('validate_registry_free_plan');
Route::get('registro_afiliado/{error_email_social?}/{email?}', 'Auth\RegisterController@show_register')->name('registerForm');
Route::get('{empresa}/loginform', 'DataAffiliatedCompanyController@index')->middleware('company')->name('loginform');
Route::get('conexiones/loginform/admin', ['as' => 'loginformadmin', 'uses' => 'DataAffiliatedCompanyController@index_admin']);
Route::prefix('user')
    ->as('user.')
    ->group(function() {
        Route::namespace('Auth\Login')
            ->group(function() {
                Route::get('login/{empresa?}', 'AffiliatedCompanyController@showLoginForm')->name('showLogin');
                Route::post('login/{rol?}', 'AffiliatedCompanyController@login')->name('login');
                Route::post('logout', 'AffiliatedCompanyController@logout')->name('logout');
            });
        Route::get('home', 'Home\AfiliadoHomeController@index')->name('home');
        Route::get('redirectfacebook/{rol}/{action}', 'Auth\LoginController@redirectToProvider')->name('redirectfacebook');
        Route::get('callback', 'Auth\LoginController@handleProviderCallback')->name('callback');
        Route::get('redirectgmail/{rol}/{action}', 'Auth\LoginController@redirectToProviderGmail')->name('redirectgmail');
        Route::get('callbackgmail', 'Auth\LoginController@handleProviderCallbackGmail')->name('callbackgmail');
    });
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('callbackgmail', 'Auth\LoginController@handleProviderCallbackGmail')->name('callbackgmail');

//routes admin
Route::group(['middleware' =>['auth:afiliadoempresa']],function (){
    Route::get('conexiones/admin/get_users_contracted_products_view', 'AdminController@get_users_contracted_products_view')->middleware('role:admin')->name('get_users_contracted_products_view');
    Route::get('conexiones/admin/get_user_contracted_products_view/{affiliatedId?}', 'AdminController@get_user_contracted_products_view')->middleware('role:admin')->name('get_user_contracted_products_view');
    Route::get('conexiones/admin/get_users_contracted_products_dt/', 'AdminController@get_users_contracted_products_dt')->middleware('role:admin')->name('get_users_contracted_products_dt');
    Route::get('conexiones/admin/get_user_contracted_products_dt/{affiliatedId?}', 'AdminController@get_user_contracted_products_dt')->middleware('role:admin')->name('get_user_contracted_products_dt');
    Route::post('conexiones/admin/update_date_expiration_content_user', 'AdminController@update_date_expiration_content_user')->middleware('role:admin')->name('update_date_expiration_content_user');
    Route::get('conexiones/admin/plans_view', 'AdminController@plans_view')->middleware('role:admin')->name('plans_view');
    Route::get('conexiones/admin/get_plans_dt', 'RatingPlanController@get_plans_dt')->middleware('role:admin')->name('get_plans_dt');
    Route::get('conexiones/admin/management_kit_elements_view', 'AdminController@management_kit_elements_view')->middleware('role:admin')->name('management_kit_elements_view');
    Route::post('conexiones/admin/create_or_update_kit/{action?}', 'KitController@create_or_update_kit')->middleware('role:admin')->name('create_or_update_kit');
    Route::post('conexiones/admin/create_or_update_element/{action?}', 'ElementController@create_or_update_element')->middleware('role:admin')->name('create_or_update_element');
    Route::get('conexiones/admin/get_kit_element_dt', 'KitElementController@get_kit_element_dt')->middleware('role:admin')->name('get_kit_element_dt');
    Route::get('conexiones/admin/get_elements', 'ElementController@get_elements')->middleware('role:admin')->name('get_elements');
    Route::get('conexiones/admin/get_element/{id?}', 'ElementController@get_element')->middleware('role:admin')->name('get_element');
    Route::post('conexiones/admin/delete_elementorkit_in_moment', 'KitElementController@delete_elementorkit_in_moment')->middleware('role:admin')->name('delete_elementorkit_in_moment');
    Route::get('conexiones/admin/get_kit/{id?}', 'KitController@get_kit')->middleware('role:admin')->name('get_kit');
    Route::get('conexiones/admin/usuarios/', 'AdminController@show_all_users')->middleware('role:admin')->name('admin.all_users');
    Route::get('conexiones/admin/get_all_users/', 'AdminController@get_all_users')->middleware('role:admin')->name('admin.get_all_users');
    Route::get('conexiones/admin/get_user/{user_id}', 'AdminController@get_user')->middleware('role:admin')->name('admin.get_user');
    Route::get('conexiones/admin/get_user_shoppingCart/{idShoppingCart}', 'AdminController@get_user_shoppingCart')->middleware('role:admin')->name('admin.get_user_shoppingCart');
    Route::get('conexiones/admin/paginas', 'AdminController@management_pages')->middleware('role:admin')->name('management_pages');
    Route::get('conexiones/admin/get_pages', 'AdminController@get_pages')->middleware('role:admin')->name('admin.get_pages');
    Route::post('conexiones/admin/update_page', 'AdminController@update_page')->middleware('role:admin')->name('update_page');
    Route::get('conexiones/admin/transacciones', 'AdminController@show_all_transaction')->middleware('role:admin')->name('admin.show_all_transaction');
    Route::get('conexiones/admin/preguntas_frecuentes', 'AdminController@frequent_question_page')->middleware('role:admin')->name('frequent_question_page');
    Route::post('conexiones/admin/update_or_crate_question', 'AdminController@update_or_crate_question')->middleware('role:admin')->name('admin.update_or_crate_question');
    Route::post('conexiones/admin/delete_question', 'AdminController@delete_question')->middleware('role:admin')->name('admin.delete_question');
});


Route::post('edit_image_perfil', 'TutorController@edit_image_perfil')->middleware('role:tutor')->name('edit_image_perfil');

Route::group(['middleware' =>['auth:afiliadoempresa', 'companyaffiliated', 'company'] ], function() {

    Route::get('{empresa}/teacher', 'TeacherController@index')->middleware('role:teacher')->name('teacher');
    Route::get('{empresa}/tutor', 'TutorController@index')->middleware('role:tutor')->name('tutor');
    Route::get('{empresa}/tutor/inscripciones', 'TutorController@showInscriptions')->middleware('role:tutor')->name('tutor.inscriptions');
    Route::get('{empresa}/tutor/productos', 'TutorController@showProducts')->middleware('role:tutor')->name('tutor.products');
    Route::get('{empresa}/tutor/historial_de_pagos', 'TutorController@showHistory')->middleware('role:tutor')->name('tutor.history');
    Route::get('{empresa}/tutor/carrito_de_compras', 'TutorController@showWishList')->middleware('role:tutor')->name('tutor.wishList');
    Route::get('{empresa}/student/', 'StudentController@index')->middleware('role:student')->name('student');
    Route::get('{empresa}/admin/', 'AdminController@index')->middleware('role:admin')->name('admin');
    Route::get('{empresa}/student/avatar', 'AvatarController@index')->middleware('role:student','company')->name('student.avatar');
    Route::post('{empresa}/student/update_avatar', 'AvatarController@update_avatar')->middleware('role:student')->name('update_avatar');
    Route::get('{empresa}/student/secuencias', 'StudentController@show_available_sequences')->middleware('role:student')->name('student.available_sequences');
    Route::get('{empresa}/student/secuencia/{sequence_id}/experiencia_cientifica/{account_service_id}', 'StudentController@show_available_experiences')->middleware('role:student')->name('student.available_experiences');

    Route::get('{empresa}/student/logros', 'StudentController@show_achievements')->middleware('role:student')->name('student.achievements');
    Route::get('{empresa}/student/logros_por_secuencia/{affiliated_account_service_id}/{sequence_id}', 'StudentController@show_achievements_sequence')->middleware('role:student')->name('student.achievements.sequence');
    Route::get('{empresa}/student/logros_por_momento/{affiliated_account_service_id}/{sequence_id}', 'StudentController@show_achievements_moment')->middleware('role:student')->name('student.achievements.moment');
    Route::get('{empresa}/student/logros_por_pregunta/{affiliated_account_service_id}/{sequence_id}', 'StudentController@show_achievements_question')->middleware('role:student')->name('student.achievements.question');
    Route::get('{empresa}/student/secuencia/{sequence_id}/situacion_generadora/{account_service_id}/{part_id?}', 'StudentController@show_sequences_section_1')->middleware('role:student')->name('student.sequences_section_1');
    Route::get('{empresa}/student/secuencia/{sequence_id}/Mapa_de_ruta/{account_service_id}/{part_id?}', 'StudentController@show_sequences_section_2')->middleware('role:student')->name('student.sequences_section_2');
    Route::get('{empresa}/student/secuencia/{sequence_id}/Guia_de_saberes/{account_service_id}/{part_id?}', 'StudentController@show_sequences_section_3')->middleware('role:student')->name('student.sequences_section_3');
    Route::get('{empresa}/student/secuencia/{sequence_id}/Punto_de_encuentro/{account_service_id}/{part_id?}', 'StudentController@show_sequences_section_4')->middleware('role:student')->name('student.sequences_section_4');
    Route::get('{empresa}/student/momento/{account_service_id}/{sequence_id}/{moment_id}/{order_moment_id}/{section_id?}/{part_id?}', 'StudentController@show_moment_section')->middleware('role:student')->name('student.show_moment_section');
    Route::get('{empresa}/tutor/registrar_estudiante', 'TutorController@showRegisterStudentForm')->middleware('role:tutor')->name('tutor.registerStudentForm');
    //servicio para consultar cursos asignados // cambiar por varibale de sesion company_id
    Route::get('{empresa}/get_available_sequences/{company_id}/{accountService_id?}', 'StudentController@get_available_sequences')->name('get_available_sequences');
    //servicio para actualizar contraseña
    Route::get('{empresa}/validate_password/{password}', 'TutorController@validate_password')->name('validate_password')->middleware('role:tutor');
    Route::post('{empresa}/update_password', 'TutorController@update_password')->name('update_password')->middleware('role:tutor');
    Route::post('{empresa}/edit_column_tutor', 'TutorController@edit_column_tutor')->name('edit_column_tutor')->middleware('role:tutor');

    Route::get('{empresa}/tutor/logros', 'TutorController@show_achievements')->middleware('role:tutor')->name('tutor.achievements');
    Route::get('{empresa}/tutor/logros_por_estudiante/{student_id}', 'TutorController@show_achievements_student')->middleware('role:tutor')->name('tutor.achievements.student');
    Route::get('{empresa}/tutor/logros_por_secuencia/{affiliated_account_service_id}/{sequence_id}/{student_id}', 'TutorController@show_achievements_sequence')->middleware('role:tutor')->name('tutor.achievements.sequence');
    Route::get('{empresa}/tutor/logros_por_momento/{affiliated_account_service_id}/{sequence_id}/{student_id}', 'TutorController@show_achievements_moment')->middleware('role:tutor')->name('tutor.achievements.moment');
    Route::get('{empresa}/tutor/logros_por_pregunta/{affiliated_account_service_id}/{sequence_id}/{student_id}', 'TutorController@show_achievements_question')->middleware('role:tutor')->name('tutor.achievements.question');
    
    

});
//servicios carrito de compras
Route::group([],function (){
        Route::get('carrito_de_compras', 'Shopping\ShoppingCartController@index')->name('shoppingCart');
        Route::get('formulario_de_envio', 'Shopping\ShippingFormController@index')->name('shippingForm');
        Route::get('registryWithPendingShoppingCart', function(){
            session(['redirect_to_shoppingcart'=>true]);
            return redirect()->route('registerForm');
        })->name('registryWithPendingShoppingCart');
        Route::get('get_shopping_cart/', 'Shopping\ShoppingCartController@get_shopping_cart')->name('get_shopping_cart');
        Route::get('payment_confirmation/', 'Payment\PaymentConfirmationController@payment_confirmation_test')->name('payment_confirmation');
        
        Route::get('get_preference_initPoint', 'Shopping\ShoppingCartController@get_preference_initPoint')->name('get_preference_initPoint');
        Route::get('get_preference_simulator', 'Shopping\ShoppingCartController@get_preference_simulator')->name('get_preference_simulator');
        Route::get('checkout', ['as' => 'checkout', 'uses' => 'Shopping\CheckoutController@index']);
        Route::post('create_shopping_cart', 'Shopping\ShoppingCartController@create')->name('create_shopping_cart');
        Route::post('delete_shopping_cart', 'Shopping\ShoppingCartController@delete')->name('delete_shopping_cart');
        Route::get('notification_gwpayment_callback', 'Shopping\NotifyCallbackController@notificacion_callback')->name('notification_gwpayment_callback')->middleware('auth:afiliadoempresa');
        Route::get('notification_gwpayment_failure_callback', 'Shopping\NotifyFailureCallbackController@notificacion_failure_callback')->name('notification_gwpayment_failure_callback')->middleware('auth:afiliadoempresa');
    }
);

Route::post('register_student', 'TutorController@register_student')->name('register_student');

Route::get('/conexiones/admin/fileupload', ['as' => 'fileupload', 'uses' => 'Admin\FileUploadController@index']);
Route::get('/conexiones/admin/fileuploadlogs', ['as' => 'fileuploadlogs', 'uses' => 'Admin\FileUploadLogsController@index']);
Route::post('/fileupload/action', ['as' => 'fileuploadAction', 'uses' => 'Admin\FileUploadController@store']);
Route::get('/conexiones/admin/sequences_list', 'Admin\EditCompanySequenceController@get_sequences_list')->name('admin.get_sequences_list');
Route::get('/conexiones/admin/sequences_get/{sequence_id}', 'Admin\EditCompanySequenceController@get_sequences_get')->name('admin.get_sequences_get');
Route::post('/conexiones/admin/get_folder_image', 'FolderImageController@getFiles')->name('get_folder_image');
Route::get('/conexiones/admin/get_affiliates', 'AdminController@affiliates')->name('get_affiliates')->middleware('role:admin');;
Route::get('/conexiones/admin/get_payments', 'AdminController@payments')->name('get_payments')->middleware('role:admin');;

Route::get('get_companies', 'CompanyController@get_companies')->name('get_companies');
Route::get('get_departments', 'DepartmentController@get_departments')->name('get_departments');
Route::get('get_cities', 'CityController@getCitiesList')->name('get_cities');
Route::get('get_countries', 'CountryController@getCountriesList')->name('get_countries');
Route::get('get_company_sequences/{company_id?}/{sequence_id?}', 'CompanyController@get_company_sequences')->name('get_company_sequences');

Route::get('get_company_groups/{company_id?}', 'CompanyController@get_company_groups')->name('get_company_groups');
Route::get('get_teachers_company/{company_id?}', 'CompanyController@get_teachers_company')->    name('get_teachers_company');

Route::get('get_students_tutor', 'TutorController@get_students_tutor')->name('get_students_tutor');
Route::get('get_products_tutor', 'TutorController@get_products_tutor')->name('get_products_tutor');
Route::get('get_history_tutor', 'TutorController@get_history_tutor')->name('get_history_tutor');


Route::get('list_files', 'BulkLoadController@list_files')->name('list_files');
Route::get('read_file', 'BulkLoadController@read_file')->name('read_file');
Route::get('import', ['as' => 'import', 'uses'=> 'Admin\UsersController@import']);
Route::get('error', ['as' => 'error', 'uses'=> 'Admin\UsersController@import']);

Route::get('{empresa}/password/sendlink/{rol}/{email?}', 'Auth\ForgotPasswordController@showLinkRequestForm')->middleware('company')->name('password.sendlink');
Route::post('{empresa}/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->middleware('company')->name('password.email');
Route::get('{empresa}/password/reset/{token}/{rol}/{email}', 'Auth\ForgotPasswordController@showResetForm')->middleware('company')->name('password.reset');
Route::post('{empresa}/password/reset/{rol}', 'Auth\ResetPasswordController@reset')->middleware('company')->name('password.update');

Route::post('/send_email_contactus', 'ContactusController@send_email_contactus')->name('send_email_contactus');

Route::get('get_kit_elements', 'KitElementController@get_kit_elements')->name('get_kit_elements');
Route::get('get_kit_element/kit/{kid_id}', 'KitElementController@get_kit')->name('get_kit_by_id');
Route::get('get_kit_element/element/{element_id}', 'KitElementController@get_element')->name('get_element_by_id');

//servcio planes
Route::get('get_rating_plans', 'RatingPlanController@get_rating_plans')->name('get_rating_plans');
Route::get('get_rating_plan/{rating_plan_id}', 'RatingPlanController@get_rating_plan_detail')->name('get_rating_plan');
Route::post('create_rating_plan', 'RatingPlanController@create')->name('create_rating_plan')->middleware('role:admin');
Route::post('edit_rating_plan', 'RatingPlanController@update')->name('edit_rating_plan')->middleware('role:admin');
Route::get('get_types_plans', 'RatingPlanController@get_types_plans')->name('get_types_plans')->middleware('role:admin');

//servicios secuencias
Route::get('/admin/get_sequence/{sequence_id}', 'SequencesController@get')->name('get_sequence');
Route::post('create_sequence', 'SequencesController@create')->name('create_sequence')->middleware('role:admin');
Route::post('update_sequence', 'SequencesController@update')->name('update_sequence')->middleware('role:admin');
Route::post('update_sequence_section', 'SequencesController@update_sequence_section')->name('update_sequence_section')->middleware('role:admin');
Route::get('get_all_sequences/{company_id?}', 'SequencesController@get_all_sequences')->name('get_all_sequences');

//servicios momentos
Route::post('update_moment', 'MomentController@update')->name('update_moment')->middleware('role:admin');
Route::post('update_moment_section', 'MomentController@update_moment_section')->name('update_moment_section')->middleware('role:admin');
//servicios momentos
Route::post('update_experience', 'ExperienceController@update')->name('update_experience');//delete
Route::post('update_experience_section', 'ExperienceController@update_experience_section')->name('update_experience_section');//delete
//servicio consulta linea de avance
Route::get('get_advance_line/{account_service_id}/{sequence_id}', 'AdvanceLineController@get')->name('get_advance_line')->middleware('role:student');
//servicio consultar usuario
Route::get('get_user/{user_id}', 'AffiliatedCompanyController@get_user')->name('get_user')->middleware('role:tutor');
//servicio editar usuario
Route::post('edit_user_student', 'AffiliatedCompanyController@edit_user_student')->name('edit_user_student')->middleware('role:tutor|student');
//servicio validar nombre de usuario
Route::get('validate_user_name/{user_name}', 'AffiliatedCompanyController@validate_user_name')->name('validate_user_name');
//servicios gestión de preguntas y respuestras
Route::group([],function (){
    Route::post('register_update_question', 'QuestionController@register_update_question')->name('register_update_question')->middleware('role:admin');
    Route::post('remove_questions_experiencie', 'QuestionController@remove_questions_experiencie')->name('remove_questions_experiencie')->middleware('role:admin');
    Route::post('remove_questions', 'QuestionController@remove_questions')->name('remove_questions')->middleware('role:admin');
    Route::get('get_questions/{sequence_id}/{moment_id}/{experience_id}/{accountServiceId}', 'QuestionController@get_questions')->name('get_questions');
    Route::post('register_update_answer', 'AnswerController@register_update_answer')->name('register_update_answer')->middleware('role:student');
    Route::get('get_answers', 'AnswerController@get_answers')->name('get_answers');

});
//servcios preguntas frecuentes
Route::get('get_frequent_questions', 'FrequentQuestionController@get_frequent_questions')->name('get_frequent_questions');
Route::get('get_available_experiences/{company_id}/{sequence_id}/{account_service_id}', 'StudentController@get_available_experiences')->middleware('role:student')->name('get_available_experiences');

Route::post('user-logout', 'Auth\LogoutController@close_session')->name('user.logout');

//Route::get('notification/plan_expiration', 'Notification\NotificationController@plan_expiration')->name('plan_expiration');
Route::get('user_mail_validation/', 'AffiliatedCompanyController@user_mail_validation')->name('user_mail_validation');
Route::get('confirm_mail/{user_id}', 'AffiliatedCompanyController@confirm_mail')->name('confirm_mail');

Route::get('corpoboyaca', 'corpo\CorpoboyacaController@show')->name('corpoboyaca_show');
Route::post('corpoboyaca/upload', 'corpo\CorpoboyacaController@upload')->name('corpoboyaca_upload');
Route::get('corpoboyaca/search/{code_in}/{doc_number}/{year}', 'corpo\CorpoboyacaController@search')->name('corpoboyaca_search');

/*

Route::get('registryWithPendingShoppingCart2', function(){
    $expiration_date = Carbon::now()->addDays(4)->format('Y-m-d');
    $users = AffiliatedAccountService::with('rating_plan','company_affiliated.retrive_afiliado_empresa','affiliated_content_account_service.sequence')
        ->where('end_date',$expiration_date)->each(function ($user) {
            dd($user);
        });
})->name('registryWithPendingShoppingCart2');

*/
