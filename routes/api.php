<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Add User
Route::post('/users/add', [UserController::class, 'addUser']);
//Get All User
Route::get('/users', [UserController::class, 'getAllUsers']);
//Get Specific User
Route::get('/users/{userId}', [UserController::class, 'getUser']);
//Update User
Route::put('/users/update/active', [UserController::class, 'updateUserStatus']);
//Get user pagination
Route::get('/users/{limit}/{page}', [UserController::class, 'getUsersWithPagination']);