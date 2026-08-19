<?php
use Illuminate\Support\Facades\Route;
Route::get('/download-manual', function() {
    return response()->download(base_path('HMS_Manual.docx'));
});
