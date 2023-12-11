<?php

use App\Http\Controllers\Institue\Authentication\AuthenticationController;
use App\Http\Controllers\Institue\CourseManagement\CourseCategoriesController;
use App\Http\Controllers\Institue\CourseManagement\CourseControler;
use App\Http\Controllers\Institue\UserManagement\PermissionController;
use App\Http\Controllers\Institue\UserManagement\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('register',[AuthenticationController::class,'register']);
Route::post('login',[AuthenticationController::class,'login']);
Route::post('verifyOtp',[AuthenticationController::class,'verifyOtp']);
Route::post('generateOtp',[AuthenticationController::class,'generateOtp']);
Route::post('passwordResetVerifyotp',[AuthenticationController::class,'passwordResetVerifyotp']);
Route::post('resetPassword',[AuthenticationController::class,'resetPassword']);



Route::prefix('permission')->group(function () {
    Route::post('permission-create',[PermissionController::class,'createPermission']);
    Route::post('update-permission',[PermissionController::class,'updatePermission']);
    Route::post('delete-Permission',[PermissionController::class,'deletePermission']);
    Route::post('get-all-permission',[PermissionController::class,'getAllPermission']);
    Route::post('list-By-Id',[PermissionController::class,'listByIdPermission']);
});



Route::prefix('role')->group(function () {
    Route::post('create-role',[RoleController::class,'create']);
    Route::post('update-role',[RoleController::class,'update']);
    Route::post('status',[RoleController::class,'status']);
    Route::post('create-role-group',[RoleController::class,'createRoleGroup']);
    Route::post('update-role-group',[RoleController::class,'updateRoleGroup']);
    Route::post('delete-role-group',[RoleController::class,'deleteRoleGroup']);

});


Route::prefix('course-categories')->group(function () {
    Route::post('create',[CourseCategoriesController::class,'create']);
    Route::post('update',[CourseCategoriesController::class,'update']);

});    

Route::prefix('courses')->group(function(){
    Route::post('create',[CourseControler::class,'create']);
    Route::post('update',[CourseControler::class,'update']);
    Route::post('list-by-id',[CourseControler::class,'listById']);
    Route::post('show',[CourseControler::class,'show']);



});