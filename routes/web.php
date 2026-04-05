<?php

use Illuminate\Support\Facades\Route;

Route::get('/buku', function () {
    return view('buku.halamanbuku');
});
Route::get('/', function () {
    return view('welcome');
}); 
Route::get('/tambahbuku', function () {
    return view('buku.tambahbuku');
});