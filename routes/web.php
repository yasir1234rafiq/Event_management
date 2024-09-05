<?php

use
    Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isOrganizer;
use App\Http\Middleware\isAttendee;
use App\Http\Controllers\BookingController;

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

// Public Routes

Route::get('/', [AdminController::class, 'index'])->name('login');
Route::post('admin-login', [AdminController::class, 'adminLogin'])->name('login.custom');
Route::get('registration', [AdminController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [AdminController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [AdminController::class, 'signOut'])->name('signout');
Route::middleware('auth')->group(function () {
Route::get('home', [EventController::class, 'event'])->name('home');
    Route::post('booking', [EventController::class, 'bookEvent'])->name('book-event');
Route::post('event-attendies', [EventController::class, 'eventAttendies'])->name('event-attendies');
Route::get('dashboard', [AdminController::class, 'dashboard']);
Route::resource('events', EventController::class);

});
// Routes accessible by Admin only
Route::middleware([isAdmin::class])->group(function () {


    Route::get('/manage-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/send-reminder/{id}', [BookingController::class, 'sendReminder'])->name('bookings.sendReminder');
    Route::post('/bookings/{id}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::get('/bookings/search', [BookingController::class, 'search'])->name('bookings.search');
    Route::get('admin_dasboard', [AdminController::class, 'admindashboard'])->name('admin.dashboard');
});

// Routes accessible by Organizers only
Route::middleware([isOrganizer::class])->group(function () {

    // Add additional organizer-only routes here
});


// Routes accessible by Attendees only
Route::middleware([isAttendee::class])->group(function () {


    // Add additional attendee-only routes here
});
