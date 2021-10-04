<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\TicketController;

Route::prefix('workspace')->group(function (){
    Route::get('/',[WorkController::class,'sort']);
    Route::post('/create',[WorkController::class,'store']);
    Route::put('/update/{id}',[WorkController::class,'update']);
    Route::delete('/delete/{id}',[WorkController::class,'delete']);
});

Route::prefix('label')->group(function (){
    Route::get('/',[LabelController::class,'sort']);
    Route::post('/create',[LabelController::class,'store']);
    Route::put('/update/{id}',[LabelController::class,'update']);
    Route::delete('/delete/{id}',[LabelController::class,'delete']);
});

Route::prefix('ticket')->group(function (){
    Route::get('/',[TicketController::class,'sort']);
    Route::post('/create',[TicketController::class,'store']);
    Route::put('/update/{id}',[TicketController::class,'update']);
    Route::delete('/delete/{id}',[TicketController::class,'delete']);
});
