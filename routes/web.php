<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;


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
    return view('flash');
});

Route::get('/data/rank', function () {
    // transporse table
    // https://stackoverflow.com/questions/6297591/how-to-invert-transpose-the-rows-and-columns-of-an-html-table
    $response = Http::post('http://127.0.0.1:5000/data/rank', [
        'name' => 'Steve',
        'role' => 'Network Administrator',
    ]);
    return $response;
});
Route::get('/data/graph', function () {
    $response = Http::post('http://127.0.0.1:5000/data/graph', [
        'name' => 'Steve',
        'role' => 'Network Administrator',
    ]);
    return view('flash', ['src' => "data:image/png;base64, $response"]);
});


Route::get('/dashboard', function () {
    $agent = new Agent();
    return view('dashboard', ['agent' => $agent]);
})->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';
