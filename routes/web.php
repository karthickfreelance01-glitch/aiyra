<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/about-us', [FrontController::class, 'about'])->name('about');
Route::get('/services', [FrontController::class, 'services'])->name('services');
Route::get('/projects', [FrontController::class, 'projects'])->name('projects');
Route::get('/projects/{project:slug}', [FrontController::class, 'project'])->name('projects.show');
Route::get('/contact-us', [FrontController::class, 'contact'])->name('contact');
Route::post('/enquiry', [FrontController::class, 'enquiry'])->name('enquiry.store')->middleware('throttle:10,1');

/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit')->middleware('throttle:6,1');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::resource('projects', ProjectController::class)->except('show');
    Route::delete('projects/{project}/images/{image}', [ProjectController::class, 'destroyImage'])->name('projects.images.destroy');
    Route::post('projects/{project}/toggle', [ProjectController::class, 'toggle'])->name('projects.toggle');

    // Enquiries
    Route::get('enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('enquiries/export', [EnquiryController::class, 'export'])->name('enquiries.export');
    Route::get('enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
    Route::post('enquiries/{enquiry}/toggle-read', [EnquiryController::class, 'toggleRead'])->name('enquiries.toggle');
    Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');
    Route::post('enquiries/bulk', [EnquiryController::class, 'bulk'])->name('enquiries.bulk');

    // Page settings
    Route::get('settings/home', [SettingController::class, 'home'])->name('settings.home');
    Route::post('settings/home', [SettingController::class, 'updateHome'])->name('settings.home.update');
    Route::get('settings/about', [SettingController::class, 'about'])->name('settings.about');
    Route::post('settings/about', [SettingController::class, 'updateAbout'])->name('settings.about.update');
    Route::get('settings/footer', [SettingController::class, 'footer'])->name('settings.footer');
    Route::post('settings/footer', [SettingController::class, 'updateFooter'])->name('settings.footer.update');

    // Account
    Route::get('account', [AuthController::class, 'account'])->name('account');
    Route::post('account', [AuthController::class, 'updateAccount'])->name('account.update');
});
