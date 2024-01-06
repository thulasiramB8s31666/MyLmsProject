<?php

use App\Http\Controllers\Staff\Authentication\AuthenticationController;
use App\Http\Controllers\Staff\StaffManagement\StaffController;
use Illuminate\Support\Facades\Route;

Route::post('staffRegister',[AuthenticationController::class,'staffRegister']);
Route::post('login',[AuthenticationController::class,'login']);
Route::post('verifyOtp',[AuthenticationController::class,'verifyOtp']);
Route::post('generateOtp',[AuthenticationController::class,'generateOtp']);
Route::post('passwordResetVerifyotp',[AuthenticationController::class,'passwordResetVerifyotp']);
Route::post('resetPassword',[AuthenticationController::class,'resetPassword']);



Route::prefix('staff')->group(function(){
    Route::post('update',[StaffController::class,'update']);
});