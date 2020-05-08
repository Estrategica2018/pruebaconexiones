<?php

use App\Models\Companies;

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
/*
Route::get('/', function () {
    $company = Companies::where('nick_name', 'conexiones')->first();
    return view('auth.login.afiliadoEmpresa',['company' => $company ]);
})->name('error_login_social');
*/
Route::get('/', function () {
    return view('welcome');
})->name('/');

Route::get('/inicio', function () {
    return view('welcome');
})->name('home');

Route::get('/acercade', function () {
    return view('aboutus');
})->name('aboutus');

Route::get('/contactenos', function () {
    return view('contactus');
})->name('contactus');

Route::get('/guias_de_aprendizaje', function () {
    return view('sequences.search');
})->name('sequences.search');

Route::get('/guia_de_aprendizaje/{sequence_id}/{sequence_name}', function () {
    return view('sequences.get');
})->name('sequences.get');

Route::get('/implementos_de_laboratorio', function () {
    return view('elementsKits.search');
})->name('elementsKits.search');

Route::get('/kit_de_laboratorio/{kit_id}/{kit_name}', function () {
    return view('elementsKits.getKit');
})->name('elementsKits.getKit');

Route::get('/elemento_de_laboratorio/{element_id}/{element_name}', function () {
    return view('elementsKits.getElement');
})->name('elementsKits.getElement');

Route::get('/planes_de_acceso', function () {
    return view('ratingPlan.list');
})->name('ratingPlan.list');

Route::get('/plan_de_acceso/{rating_plan_id}/{rating_name}', function () {
    return view('ratingPlan.detail');
})->name('ratingPlan.detailSequence');

Route::get('validate_registry_free_plan/{ratingPlanId}', 'Auth\RegisterController@validate_registry_free_plan')->name('validate_registry_free_plan');
Route::get('registro_afiliado/{error_email_social?}/{email?}', 'Auth\RegisterController@show_register')->name('registerForm');
Route::get('{empresa}/loginform', 'DataAffiliatedCompanyController@index')->middleware('company')->name('loginform');
Route::get('conexiones/loginform/admin', ['as' => 'loginformadmin', 'uses' => 'DataAffiliatedCompanyController@index_admin']);


Route::get('conexiones/admin/get_users_contracted_products_view', 'AdminController@get_users_contracted_products_view')->middleware('role:admin')->name('get_users_contracted_products_view');
Route::get('conexiones/admin/get_user_contracted_products_view/{affiliatedId?}', 'AdminController@get_user_contracted_products_view')->middleware('role:admin')->name('get_user_contracted_products_view');
Route::get('conexiones/admin/get_users_contracted_products_dt/', 'AdminController@get_users_contracted_products_dt')->middleware('role:admin')->name('get_users_contracted_products_dt');
Route::get('conexiones/admin/get_user_contracted_products_dt/{affiliatedId?}', 'AdminController@get_user_contracted_products_dt')->middleware('role:admin')->name('get_user_contracted_products_dt');
Route::post('conexiones/admin/update_date_expiration_content_user', 'AdminController@update_date_expiration_content_user')->middleware('role:admin')->name('update_date_expiration_content_user');
Route::get('conexiones/admin/plans_view', 'AdminController@plans_view')->middleware('role:admin')->name('plans_view');
Route::get('conexiones/admin/get_plans_dt', 'RatingPlanController@get_plans_dt')->middleware('role:admin')->name('get_plans_dt');





Route::prefix('user')
    ->as('user.')
    ->group(function() {
        Route::namespace('Auth\Login')
            ->group(function() {
                Route::get('login/{empresa?}', 'AffiliatedCompanyController@showLoginForm')->name('login');
                Route::post('login/{rol?}', 'AffiliatedCompanyController@login')->name('login');
                Route::post('logout', 'AffiliatedCompanyController@logout')->name('logout');
            });
        Route::get('home', 'Home\AfiliadoHomeController@index')->name('home');

        Route::get('redirectfacebook/{rol}/{action}', 'Auth\LoginController@redirectToProvider')->name('redirectfacebook');
        Route::get('callback', 'Auth\LoginController@handleProviderCallback')->name('callback');
        Route::get('redirectgmail/{rol}/{action}', 'Auth\LoginController@redirectToProviderGmail')->name('redirectgmail');
        Route::get('callbackgmail', 'Auth\LoginController@handleProviderCallbackGmail')->name('callbackgmail');
    });

Route::group(['middleware' =>['auth:afiliadoempresa', 'companyaffiliated', 'company'] ], function() {
    Route::get('/profile', function () {
        return 'esta loggeado';
    });
    Route::get('{empresa}/teacher', 'TeacherController@index')->middleware('role:teacher')->name('teacher');
    Route::get('{empresa}/tutor', 'TutorController@index')->middleware('role:tutor')->name('tutor');
    Route::get('{empresa}/tutor/inscripciones', 'TutorController@showInscriptions')->middleware('role:tutor')->name('tutor.inscriptions');
    Route::get('{empresa}/tutor/productos', 'TutorController@showProducts')->middleware('role:tutor')->name('tutor.products');
    Route::get('{empresa}/tutor/historial_de_pagos', 'TutorController@showHistory')->middleware('role:tutor')->name('tutor.history');																																  
    Route::get('{empresa}/student/', 'StudentController@index')->middleware('role:student')->name('student');
    Route::get('{empresa}/admin/', 'AdminController@index')->middleware('role:admin')->name('admin');
    Route::get('{empresa}/student/avatar', 'AvatarController@index')->middleware('role:student','company')->name('avatar');
    Route::post('{empresa}/student/update_avatar', 'AvatarController@update_avatar')->middleware('role:student')->name('update_avatar');
    Route::get('{empresa}/student/secuencias', 'StudentController@show_available_sequences')->middleware('role:student')->name('student.available_sequences');
    Route::get('{empresa}/student/secuencia/{sequence_id}/situacion_generadora/{account_service_id}', 'StudentController@show_sequences_section_1')->middleware('role:student')->name('student.sequences_section_1');
    Route::get('{empresa}/student/secuencia/{sequence_id}/Mapa_de_ruta/{account_service_id}', 'StudentController@show_sequences_section_2')->middleware('role:student')->name('student.sequences_section_2');
    Route::get('{empresa}/student/secuencia/{sequence_id}/Guia_de_saberes/{account_service_id}', 'StudentController@show_sequences_section_3')->middleware('role:student')->name('student.sequences_section_3');
    Route::get('{empresa}/student/secuencia/{sequence_id}/Punto_de_encuentro/{account_service_id}', 'StudentController@show_sequences_section_4')->middleware('role:student')->name('student.sequences_section_4');
                                                                                                                                                                                      
    
    Route::get('{empresa}/student/momento/{sequence_id}/{moment_id}/{section}/{account_service_id}/{order_moment_id}', 'StudentController@show_moment_section')->middleware('role:student')->name('student.show_moment_section');
    
    Route::get('{empresa}/tutor/registrar_estudiante', 'TutorController@showRegisterStudentForm')->middleware('role:tutor')->name('tutor.registerStudentForm');

//servicio para consultar cursos asignados // cambiar por varibale de sesion company_id
    Route::get('{empresa}/get_available_sequences/{company_id}', 'StudentController@get_available_sequences')->name('get_available_sequences');
//servicio para actualizar contraseña
    Route::get('{empresa}/validate_password/{password}', 'TutorController@validate_password')->name('validate_password')->middleware('role:tutor');
    Route::post('{empresa}/update_password', 'TutorController@update_password')->name('update_password')->middleware('role:tutor');
    Route::post('{empresa}/edit_column_tutor', 'TutorController@edit_column_tutor')->name('edit_column_tutor')->middleware('role:tutor');


});

//servcios carrito de comprar
Route::group([],function (){
        Route::get('carrito_de_compras', 'Shopping\ShoppingCartController@index')->name('shoppingCart');
        Route::get('formulario_de_envio', 'Shopping\ShippingFormController@index')->name('shippingForm');
        Route::get('registryWithPendingShoppingCart', function(){
            session(['redirect_to_shoppingcart'=>true]);
            return redirect()->route('registerForm');
        })->name('registryWithPendingShoppingCart');
        Route::get('get_shopping_cart/', 'Shopping\ShoppingCartController@get_shopping_cart')->name('get_shopping_cart');//->middleware('auth:afiliadoempresa');
        Route::get('checkout', ['as' => 'checkout', 'uses' => 'Shopping\CheckoutController@index']);
        Route::post('update_shopping_cart', 'Shopping\ShoppingCartController@update')->name('update_shopping_cart');//->middleware('auth:afiliadoempresa');
        Route::post('create_shopping_cart', 'Shopping\ShoppingCartController@create')->name('create_shopping_cart');//->middleware('auth:afiliadoempresa');
        Route::get('notification_gwpayment_callback', 'Shopping\NotifyCallbackController@notificacion_callback')->name('notification_gwpayment_callback');//->middleware('auth:afiliadoempresa');
        Route::get('notification_gwpayment_failure_callback', 'Shopping\NotifyFailureCallbackController@notificacion_failure_callback')->name('notification_gwpayment_failure_callback');//->middleware('auth:afiliadoempresa');
    }
);

Route::post('register_student', 'TutorController@register_student')->name('register_student');

Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('callbackgmail', 'Auth\LoginController@handleProviderCallbackGmail')->name('callbackgmail');

Route::get('testangular', 'HomeController@testangular')->name('testangular');


Route::get('/conexiones/admin/fileupload', ['as' => 'fileupload', 'uses' => 'Admin\FileUploadController@index']);
Route::get('/conexiones/admin/fileuploadlogs', ['as' => 'fileuploadlogs', 'uses' => 'Admin\FileUploadLogsController@index']);
Route::post('/fileupload/action', ['as' => 'fileuploadAction', 'uses' => 'Admin\FileUploadController@store']);
Route::get('/conexiones/admin/sequences_list', 'Admin\EditCompanySequenceController@get_sequences_list')->name('admin.get_sequences_list');
Route::get('/conexiones/admin/sequences_get/{sequence_id}', 'Admin\EditCompanySequenceController@get_sequences_get')->name('admin.get_sequences_get');
Route::post('/conexiones/admin/get_folder_image', 'FolderImageController@getFiles')->name('get_folder_image');

Route::get('get_companies', 'CompanyController@get_companies')->name('get_companies');
Route::get('get_departments', 'DepartmentController@get_departments')->name('get_departments');
Route::get('get_cities', 'CityController@getCitiesList')->name('get_cities');
Route::get('get_countries', 'CountryController@getCountriesList')->name('get_countries');
Route::get('get_company_sequences/{company_id?}', 'CompanyController@get_company_sequences')->name('get_company_sequences');

Route::get('get_company_groups/{company_id?}', 'CompanyController@get_company_groups')->name('get_company_groups');
Route::get('get_teachers_company/{company_id?}', 'CompanyController@get_teachers_company')->    name('get_teachers_company');

Route::get('get_students_tutor', 'TutorController@get_students_tutor')->name('get_students_tutor');
Route::get('get_products_tutor', 'TutorController@get_products_tutor')->name('get_products_tutor');
Route::get('get_history_tutor', 'TutorController@get_history_tutor')->name('get_history_tutor');


Route::get('list_files', 'BulkLoadController@list_files')->name('list_files');
Route::get('read_file', 'BulkLoadController@read_file')->name('read_file');
Route::get('import', ['as' => 'import', 'uses'=> 'Admin\UsersController@import']);
Route::get('error', ['as' => 'error', 'uses'=> 'Admin\UsersController@import']);

Route::get('{empresa}/password/sendlink/{rol}', 'Auth\ForgotPasswordController@showLinkRequestForm')->middleware('company')->name('password.sendlink');
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
Route::post('create_rating_plan', 'RatingPlanController@create')->name('create_rating_plan');

//servicios secuencias
Route::get('get_sequence/{sequence_id}', 'SequencesController@get')->name('get_sequence');
Route::post('create_sequence', 'SequencesController@create')->name('create_sequence');
Route::post('update_sequence', 'SequencesController@update')->name('update_sequence');
Route::post('update_sequence_section', 'SequencesController@update_sequence_section')->name('update_sequence_section');
//servicios momentos
Route::post('update_moment', 'MomentController@update')->name('update_moment');
Route::post('update_moment_section', 'MomentController@update_moment_section')->name('update_moment_section');
//servicios momentos
Route::post('update_experience', 'ExperienceController@update')->name('update_experience');
Route::post('update_experience_section', 'ExperienceController@update_experience_section')->name('update_experience_section');


Route::get('get_advance_line/{account_service_id}/{sequence_id}', 'AdvanceLineController@get')->name('get_advance_line');
//servicio consultar usuario
Route::get('get_user/{user_id}', 'AffiliatedCompanyController@get_user')->name('get_user');
//servicio editar usuario
Route::post('edit_user_student', 'AffiliatedCompanyController@edit_user_student')->name('edit_user_student');
//servicio validar nombre de usuario
Route::get('validate_user_name/{user_name}', 'AffiliatedCompanyController@validate_user_name')->name('validate_user_name');

Route::get('page500', function(){
    return view('page500',['companies'=>Companies::all()]);
})->name('page500');