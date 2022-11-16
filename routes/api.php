<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EnterpriseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CropFieldController;
use App\Http\Controllers\Api\HeardController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\FlockController;
use App\Http\Controllers\Api\FarmCalenderController;







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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);


Route::group(['middleware' => ['auth:api']], function () {

    ////////////// Phase 1 /////////////////

    Route::post('/add-enerprise', [EnterpriseController::class, 'create']);
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);

    Route::post('/add-cropfield', [CropFieldController::class, 'create']);

    Route::post('/add-heard', [HeardController::class, 'create']);
    Route::post('/add-animal', [AnimalController::class, 'create']);
    Route::post('/add-flock', [FlockController::class, 'create']);

    Route::get('/animal-list', [AnimalController::class, 'animalList']);

    Route::get('/heard-list', [HeardController::class, 'heardList']);

    Route::get('/flock-list', [FlockController::class, 'flockList']);
    Route::get('/cropfield-list', [CropFieldController::class, 'cropfieldList']);
    Route::get('/enterprise-list', [EnterpriseController::class, 'enterpriseList']);


    ////////////// Phase 2 /////////////////


    Route::post('/invite-team', [AuthController::class, 'inviteFriend']);
    Route::get('/my-team', [AuthController::class, 'myTeam']);

    Route::post('/create-job', [FarmCalenderController::class, 'createJob']);
    Route::post('/update-job-status', [FarmCalenderController::class, 'completeJob']);
    Route::get('/my-jobs', [FarmCalenderController::class, 'myJobs']);
    Route::get('/all-farm-jobs', [FarmCalenderController::class, 'AllJobs']);
    Route::get('/jobs-assigned-to-me', [FarmCalenderController::class, 'assignedJobs']);
    Route::post('/job-review', [FarmCalenderController::class, 'jobReview']);
});