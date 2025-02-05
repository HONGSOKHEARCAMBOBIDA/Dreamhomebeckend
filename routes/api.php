<?php


use App\Http\Controllers\CommunceController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\VillageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolehaspermissionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemtypeController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\WharehouseController;
use App\Http\Controllers\ChecktimeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProvinceController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:api');
Route::get('/province',[ProvinceController::class,'index']);
Route::get('/district/{provinceid}',[DistrictController::class,'index']);
Route::get('/communce/{districtid}',[CommunceController::class,'index']);
Route::get('/village/{communceid}',[VillageController::class,'index']);
Route::get('/getuser/{name?}',[AuthController::class,'index']);
Route::get('/getuserbyid/{id}',[AuthController::class,'getuserbyid']);
// Route role has permision
Route::post('/rolehaspermision', [RolehaspermissionController::class, 'store']);
Route::get('/rolehaspermision', [RolehaspermissionController::class, 'index']);
Route::put('/rolehaspermission/{role_id}/{permission_id}', [RolehaspermissionController::class, 'update']);
Route::delete('/rolehaspermission/{role_id}/{permission_id}', [RolehaspermissionController::class, 'delete']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/department', [DepartmentController::class, 'index']);
Route::get('/role/{departmentID}', [RoleController::class, 'getRoleByDepartment']);
//************************
Route::middleware(['auth:api', 'permission:add-users'])->group(function () {
    
});
Route::middleware(['auth:api', 'permission:view-users'])->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
});
Route::middleware(['auth:api', 'permission:edit-users'])->group(function () {
    Route::put('/update/{id?}', [AuthController::class, 'update']);
    Route::put('/changepassword',[AuthController::class,'changePassword']);
});
Route::middleware(['auth:api', 'permission:delete-users'])->group(function () {
    Route::delete('/deleteuser/{id}', [AuthController::class, 'delete'])->middleware('auth:api');
});
Route::middleware(['auth:api', 'permission:view-department'])->group(function () {
   // Route::get('/department', [DepartmentController::class, 'index']);
});
Route::middleware(['auth:api', 'permission:add-department'])->group(function () {
    Route::post('/department', [DepartmentController::class, 'store']);
});
Route::middleware(['auth:api', 'permission:edit-department'])->group(function () {
    Route::put('/department/{id}', [DepartmentController::class, 'update']);
});
Route::middleware(['auth:api', 'permission:delete-department'])->group(function () {
    Route::delete('/department/{id}', [DepartmentController::class, 'delete']);
});
Route::middleware(['auth:api', 'permission:view-roles'])->group(function () {
  //  Route::get('/role/{departmentID?}', [RoleController::class, 'getRoleByDepartment']);
   // Route::get('/role', [RoleController::class, 'index']);
});
Route::middleware(['auth:api', 'permission:add-roles'])->group(function () {
    Route::post('/role', [RoleController::class, 'store']);
});
Route::middleware(['auth:api', 'permission:edit-roles'])->group(function () {
    Route::put('/role/{id}', [RoleController::class, 'update']);
});
Route::middleware(['auth:api', 'permission:delete-roles'])->group(function () {
    Route::delete('/role/{id}', [RoleController::class, 'delete']);
});
Route::middleware(['auth:api', 'permission:view-category'])->group(function () {
    Route::get('/category', [CategoryController::class, 'index']);
});
Route::middleware(['auth:api', 'permission:add-category'])->group(function () {
    Route::post('/category', [CategoryController::class, 'store']);
});
Route::middleware(['auth:api', 'permission:edit-category'])->group(function () {
    Route::put('/category/{id}', [CategoryController::class, 'update']);
});
Route::middleware(['auth:api', 'permission:delete-category'])->group(function () {
    Route::delete('/category/{id}', [CategoryController::class, 'delete']);
});
Route::middleware(['auth:api', 'permission:view-itemtype'])->group(function () {
    Route::get('/getItemTypebycategory/{CategoryId?}', [ItemtypeController::class, 'getItemTypebycategory']);
});
Route::middleware(['auth:api', 'permission:add-itemtype'])->group(function () {
    Route::post('/itemtype', [ItemtypeController::class, 'store']);
});
Route::middleware(['auth:api', 'permission:edit-itemtype'])->group(function () {
    Route::put('/itemtype/{id}', [ItemtypeController::class, 'update']);
});
Route::middleware(['auth:api', 'permission:delete-itemtype'])->group(function () {
    Route::delete('/itemtype/{id}', [ItemtypeController::class, 'delete']);
});
Route::middleware(['auth:api', 'permission:view-mesurement'])->group(function () {
    Route::get('/Measurement', [MeasurementController::class, 'index']);
});
Route::middleware(['auth:api', 'permission:add-mesurement'])->group(function () {
    Route::post('/Measurement', [MeasurementController::class, 'store']);
});
Route::middleware(['auth:api', 'permission:edit-mesurement'])->group(function () {
    Route::put('/Measurement/{id}', [MeasurementController::class, 'update']);
});
Route::middleware(['auth:api', 'permission:delete-mesurement'])->group(function () {
    Route::delete('/Measurement/{id}', [MeasurementController::class, 'delete']);
});
Route::middleware(['auth:api', 'permission:view-item'])->group(function () {
    Route::get('/getItembyitemType/{id?}', [ItemController::class, 'getItembyitemType']);
});
Route::middleware(['auth:api', 'permission:add-item'])->group(function () {
    Route::post('/item', [ItemController::class, 'store']);
});
Route::middleware(['auth:api', 'permission:edit-item'])->group(function () {
    Route::put('/item/{id}', [ItemController::class, 'update']);
});
Route::middleware(['auth:api', 'permission:delete-item'])->group(function () {
    Route::delete('/item/{id}', [ItemController::class, 'delete']);
});
Route::middleware(['auth:api', 'permission:add-checktime'])->group(function () {
    Route::post('/checktime', [ChecktimeController::class, 'store']);
});
Route::middleware(['auth:api', 'permission:view-checktime'])->group(function () {
    Route::get('/checktime', [ChecktimeController::class, 'index']);
    Route::get('/getbydate/{date?}', [ChecktimeController::class, 'getbydate']);
});
Route::middleware(['auth:api', 'permission:edit-checktime'])->group(function () {
    Route::put('/checktime/{id}', [ChecktimeController::class, 'update']);
});
Route::middleware(['auth:api', 'permission:delete-checktime'])->group(function () {
    Route::delete('/checktime/{id}', [ChecktimeController::class, 'delete']);
});
Route::middleware(['auth:api', 'permission:view-warehouse'])->group(function () {
    Route::get('/warehouse', [WharehouseController::class, 'index']);
});
Route::middleware(['auth:api', 'permission:add-warehouse'])->group(function () {
    Route::post('/warehouse', [WharehouseController::class, 'store']);
});
Route::middleware(['auth:api', 'permission:edit-warehouse'])->group(function () {
    Route::put('/warehouse/{id}', [WharehouseController::class, 'update']);
});
Route::middleware(['auth:api', 'permission:delete-warehouse'])->group(function () {
    Route::put('/deletewarehouse/{id}', [WharehouseController::class, 'delete']);
});
Route::middleware(['auth:api', 'permission:view-transation'])->group(function () {
    Route::get('/transation/{warehouseID?}', [TransactionController::class, 'sum']);
    Route::get('/brokenitem/{warehouseID?}', [TransactionController::class, 'brokenitem']);
});
Route::middleware(['auth:api', 'permission:add-transation'])->group(function () {
    Route::post('/transation', [TransactionController::class, 'store']);
});
Route::middleware(['auth:api', 'permission:delete-transation'])->group(function () {
    Route::delete('/transation/{id}', [TransactionController::class, 'delete']);
});
