<?php

use App\Http\Controllers\WebsiteController;

Route::prefix('/')->group(function() {
    Route::get('/', [WebsiteController::class, 'index'])->name('website.home');
    Route::get('donativo', [WebsiteController::class, 'donativo'])->name('website.donativo');
    Route::get('quem-somos', [WebsiteController::class, 'quemSomos'])->name('website.quem-somos');
    Route::get('contactos', [WebsiteController::class, 'contactos'])->name('website.contactos');
});

Route::prefix('beneficiarios')->name('beneficiaries.')->group(function () {
    Route::get('/', [\App\Http\Controllers\BeneficiaryPortalController::class, 'index'])->name('index');
    Route::get('registar', [\App\Http\Controllers\BeneficiaryPortalController::class, 'showRegister'])->name('register');
    Route::post('registar', [\App\Http\Controllers\BeneficiaryPortalController::class, 'register'])->name('register.store');
    Route::get('login', [\App\Http\Controllers\BeneficiaryPortalController::class, 'showLogin'])->name('login');
    Route::post('login', [\App\Http\Controllers\BeneficiaryPortalController::class, 'login'])->name('login.store');
    Route::get('password/forgot', [\App\Http\Controllers\BeneficiaryPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [\App\Http\Controllers\BeneficiaryPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [\App\Http\Controllers\BeneficiaryPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [\App\Http\Controllers\BeneficiaryPasswordResetController::class, 'reset'])->name('password.update');
    Route::middleware('auth:beneficiary')->group(function () {
        Route::get('area', [\App\Http\Controllers\BeneficiaryPortalController::class, 'area'])->name('area');
        Route::post('area', [\App\Http\Controllers\BeneficiaryPortalController::class, 'update'])->name('area.update');
        Route::post('logout', [\App\Http\Controllers\BeneficiaryPortalController::class, 'logout'])->name('logout');
    });
});

Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Entrie
    Route::delete('entries/destroy', 'EntrieController@massDestroy')->name('entries.massDestroy');
    Route::resource('entries', 'EntrieController');

    // Countries
    Route::delete('countries/destroy', 'CountriesController@massDestroy')->name('countries.massDestroy');
    Route::resource('countries', 'CountriesController');

    // Prizes
    Route::delete('prizes/destroy', 'PrizesController@massDestroy')->name('prizes.massDestroy');
    Route::resource('prizes', 'PrizesController');

    // Winner
    Route::delete('winners/destroy', 'WinnerController@massDestroy')->name('winners.massDestroy');
    Route::resource('winners', 'WinnerController');

    // Payment
    Route::delete('payments/destroy', 'PaymentController@massDestroy')->name('payments.massDestroy');
    Route::resource('payments', 'PaymentController');

    // Pedidos EuPago (read-only)
    Route::resource('pedidos', 'PedidosController')->only(['index', 'show']);

    // Content Category
    Route::delete('content-categories/destroy', 'ContentCategoryController@massDestroy')->name('content-categories.massDestroy');
    Route::resource('content-categories', 'ContentCategoryController');

    // Content Tag
    Route::delete('content-tags/destroy', 'ContentTagController@massDestroy')->name('content-tags.massDestroy');
    Route::resource('content-tags', 'ContentTagController');

    // Content Page
    Route::delete('content-pages/destroy', 'ContentPageController@massDestroy')->name('content-pages.massDestroy');
    Route::post('content-pages/media', 'ContentPageController@storeMedia')->name('content-pages.storeMedia');
    Route::post('content-pages/ckmedia', 'ContentPageController@storeCKEditorImages')->name('content-pages.storeCKEditorImages');
    Route::resource('content-pages', 'ContentPageController');

    // Beneficiary Categories
    Route::delete('beneficiary-categories/destroy', 'BeneficiaryCategoryController@massDestroy')->name('beneficiary-categories.massDestroy');
    Route::post('beneficiary-categories/media', 'BeneficiaryCategoryController@storeMedia')->name('beneficiary-categories.storeMedia');
    Route::resource('beneficiary-categories', 'BeneficiaryCategoryController');

    // Beneficiaries
    Route::delete('beneficiaries/destroy', 'BeneficiaryController@massDestroy')->name('beneficiaries.massDestroy');
    Route::post('beneficiaries/media', 'BeneficiaryController@storeMedia')->name('beneficiaries.storeMedia');
    Route::post('beneficiaries/ckmedia', 'BeneficiaryController@storeCKEditorImages')->name('beneficiaries.storeCKEditorImages');
    Route::resource('beneficiaries', 'BeneficiaryController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
