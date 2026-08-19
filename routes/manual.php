<?php
use Illuminate\Support\Facades\Route;

Route::get('/manual', function () {
    return view('manual');
})->middleware(['auth']);
