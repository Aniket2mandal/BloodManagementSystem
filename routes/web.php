<?php

use App\Http\Controllers\Backend\BloodbankController;
use App\Http\Controllers\Backend\BloodcampController;
use App\Http\Controllers\Backend\BloodController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DonorController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\DashboardFrontController;
use App\Http\Controllers\Frontend\DonorController as FrontendDonorController;
use App\Http\Controllers\Frontend\FrontBloodBankController;
use App\Http\Controllers\Frontend\FrontBloodController;
use App\Http\Controllers\Frontend\FrontendBloodCampController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;














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
Route::get('/', [DashboardFrontController::class, 'index'])->name('frontdashboard.index');
Route::get('/services', [DashboardFrontController::class, 'services'])->name('frontdashboard.services');
Route::get('/about', [AboutController::class, 'index'])->name('about.index');
// FRONTEND BLOOD
Route::get('blood/search', [FrontBloodController::class, 'index'])->name('frontblood.index');
Route::get('blood/search/data', [FrontBloodController::class, 'searchBlood'])->name('frontblood.search');
Route::get('blood/detail/{id}', [FrontBloodController::class, 'detail'])->name('frontblood.detail');

// FORNTEND BLOODBANK
Route::get('bloodbank/search', [FrontBloodBankController::class, 'index'])->name('frontbloodbank.index');
Route::get('bloodbank/search/data', [FrontBloodBankController::class, 'searchBlood'])->name('frontbloodbank.search');
Route::get('bloodbank/detail/{id}', [FrontBloodBankController::class, 'detail'])->name('frontbloodbank.detail');

// FRONTEND BLOOD CAMP
Route::get('bloodcamp/index/{id}', [FrontendBloodCampController::class, 'index'])->name('frontbloodcamp.index');

// FRONTEND DONOR
Route::post('/donor/login', [FrontendDonorController::class, 'login'])->name('donor.login');
Route::middleware('donor.auth')->group(function () {
  Route::get('/donor/dashboard', [FrontendDonorController::class, 'dashboard'])->name('frontend.donor.dashboard');
  Route::get('/donor/logout', [FrontendDonorController::class, 'logout'])->name('donor.logout');
  Route::post('/donor/uploadimage/', [FrontendDonorController::class, 'uploadImage'])->name('donor.upload-image');
  Route::post('/donor/changepassword/', [FrontendDonorController::class, 'changePassword'])->name('donor.change-password');
});


Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // USER
    Route::get('/user/home', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::post('/userstatus/{id}', [UserController::class, 'status'])->name('user.status');
    Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');


    // ROLE
    Route::get('/role/home', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/role/update/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::get('/role/delete/{id}', [RoleController::class, 'delete'])->name('role.delete');

    // PERMISSION
    Route::get('/permission/home', [PermissionController::class, 'index'])->name('permission.index');

    // BLOOD
    Route::get('/blood/home', [BloodController::class, 'index'])->name('blood.index');
    Route::get('/blood/create', [BloodController::class, 'create'])->name('blood.create');
    Route::post('/blood/store', [BloodController::class, 'store'])->name('blood.store');
    Route::get('/blood/edit/{id}', [BloodController::class, 'edit'])->name('blood.edit');
    Route::put('/blood/update/{id}', [BloodController::class, 'update'])->name('blood.update');
    Route::get('/blood/delete/{id}', [BloodController::class, 'delete'])->name('blood.delete');
    Route::post('/blood/status/{id}', [BloodController::class, 'status'])->name('blood.status');
    Route::post('/blood/addbloodquantity/', [BloodController::class, 'addBloodQuantity'])->name('blood.addbloodquantity');


      // BLOOD BANK
      Route::get('/bloodbank/home', [BloodbankController::class, 'index'])->name('bloodbank.index');
      Route::get('/bloodbank/create', [BloodbankController::class, 'create'])->name('bloodbank.create');
      Route::post('/bloodbank/store', [BloodbankController::class, 'store'])->name('bloodbank.store');
      Route::get('/bloodbank/edit/{slug}', [BloodbankController::class, 'edit'])->name('bloodbank.edit');
      Route::put('/bloodbank/update/{slug}', [BloodbankController::class, 'update'])->name('bloodbank.update');
      Route::get('/bloodbank/delete/{id}', [BloodbankController::class, 'delete'])->name('bloodbank.delete');
      Route::post('/bloodbank/status/{id}', [BloodbankController::class, 'status'])->name('bloodbank.status');


      // CAMPS

      Route::get('/bloodcamp/home', [BloodcampController::class, 'index'])->name('bloodcamp.index');
      Route::get('/bloodcamp/create', [BloodcampController::class, 'create'])->name('bloodcamp.create');
      Route::post('/bloodcamp/store', [BloodcampController::class, 'store'])->name('bloodcamp.store');
      Route::get('/bloodcamp/edit/{slug}', [BloodcampController::class, 'edit'])->name('bloodcamp.edit');
      Route::put('/bloodcamp/update/{slug}', [BloodcampController::class, 'update'])->name('bloodcamp.update');
      Route::get('/bloodcamp/delete/{id}', [BloodcampController::class, 'delete'])->name('bloodcamp.delete');
      Route::post('/bloodcamp/status/{id}', [BloodcampController::class, 'status'])->name('bloodcamp.status');


      //DONOR
      Route::get('/donor/home', [DonorController::class, 'index'])->name('donor.index');
      Route::get('/donor/create', [DonorController::class, 'create'])->name('donor.create');
      Route::post('/donor/store', [DonorController::class, 'store'])->name('donor.store');
      Route::get('/donor/edit/{id}', [DonorController::class, 'edit'])->name('donor.edit');
      Route::put('/donor/update/{id}', [DonorController::class, 'update'])->name('donor.update');
      Route::get('/donor/delete/{id}', [DonorController::class, 'delete'])->name('donor.delete');
      Route::post('/donor/status/{id}', [DonorController::class, 'status'])->name('donor.status');
      Route::get('/donor/search/data', [DonorController::class, 'search'])->name('donor.search');
      Route::post('/donor/addbloodbank/', [DonorController::class, 'addBloodBank'])->name('donor.addbloodbank');
     
    
    // Route::get('/home', [HomeController::class, 'index'])->name('home');
});
