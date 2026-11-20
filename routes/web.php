<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RegistrationController::class, 'create'])->name('registrations.create');
Route::post('/register', [RegistrationController::class, 'store'])
    ->middleware('throttle:registration')
    ->name('registrations.store');
Route::get('/registration/submitted', [RegistrationController::class, 'submitted'])
    ->name('registrations.submitted');
Route::get('/registrations/{registration}/confirm', [RegistrationController::class, 'verify'])
    ->middleware('throttle:registration-confirmation')
    ->name('registrations.verify');
