<?php

use Illuminate\Support\Facades\Route;
use Laramine\Utility\Controller\UtilityController;
use Laramine\Utility\VugiChugi;

Route::middleware(VugiChugi::gtc())->controller(UtilityController::class)->group(function(){
    Route::get(VugiChugi::acRouter(),'laramineStart')->name(VugiChugi::acRouter());
    Route::post(VugiChugi::acRouterSbm(),'laramineSubmit')->name(VugiChugi::acRouterSbm());
});
