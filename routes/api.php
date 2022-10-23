<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CourseController;
Route::post("register", [UserController::class, "register"]);
Route::post("login", [UserController::class, "login"]);
Route::group(["middleware" => ["auth:api"]], function(){
    Route::get("profile", [UserController::class, "profile"]);
    Route::get("logout", [UserController::class, "logout"]);
    // Cursos
    Route::post('createCourse', [CourseController::class, "create"]);
    Route::post('createCourses', [CourseController::class, "createMassive"]);
    Route::get('showCourses', [CourseController::class, "store"]);
    Route::delete('deleteLogicCourse', [CourseController::class, "destroyLogic"]);
    Route::delete('deletePhysicalCourse', [CourseController::class, "destroyPhysical"]);
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
