<?php

use App\Http\Controllers\Student\Authentication\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::post('studentRegister',[AuthenticationController::class,'studentRegister']);
Route::post('login',[AuthenticationController::class,'login']);
Route::post('verifyOtp',[AuthenticationController::class,'verifyOtp']);
Route::post('generateOtp',[AuthenticationController::class,'generateOtp']);
Route::post('passwordResetVerifyotp',[AuthenticationController::class,'passwordResetVerifyotp']);
Route::post('resetPassword',[AuthenticationController::class,'resetPassword']);


