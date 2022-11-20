<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Jenssegers\Agent\Agent;


Route::get('/', function () {
    return view('home');
});

Route::get('/nib', function () {
    return view('nib');
});

Route::get('/cek', function () {
    return view('cek');
});

Route::get('/flash', function () {
    return view('flash');
});


Route::get('/dashboard', function () {
    $agent = new Agent();
    return view('dashboard', ['agent' => $agent]);
})->middleware(['auth'])->name('dashboard');

// Route::get('/email', function () {
//     return view('email.email');
// });

require __DIR__.'/auth.php';
