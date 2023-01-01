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
    return view('home');
});

Route::get('/data/rank', function () {
    // transporse table
    // https://stackoverflow.com/questions/6297591/how-to-invert-transpose-the-rows-and-columns-of-an-html-table
    $response = Http::post('http://127.0.0.1:5000/data/rank', [
        'data' => [  
            [ "a1", ['a','b','c'],   ['a','b','c','k','l']    ,'1993',['p1','p2']                                              ]
            , [ "a2", ['c','d','e'],   ['a','c','d','e','m','n'],'1993',['p1','p3']                                              ]
            , [ "a3", ['f','g','h'],   ['c','d','f','g','h','o'],'1993',['p2','p4','p5']                                         ]
            , [ "a4", ['i','j'],       ['c','d','p','q']        ,'1994',['p3','p6']      ,['a1','a2']                            ]
            , [ "a5", ['dj','dk'],     ['a','dj','dk','m','r']  ,'1994',['p1','p7']      ,['a1','a2','a3']                       ]
            , [ "a6", ['d','ac','ad'], ['d','ac','ad','s','t']  ,'1994',['p8','p9']      ,['a1','a3']                            ]
            ]
    ]);
    // return json_decode($response);
    return view('rank', ['rank' => json_decode($response)]);

});
Route::get('/data/graph', function () {
    $response = Http::post('http://127.0.0.1:5000/data/graph', [
        'data' => [  
            [ "a1", ['a','b','c'],   ['a','b','c','k','l']    ,'1993',['p1','p2']                                              ]
            , [ "a2", ['c','d','e'],   ['a','c','d','e','m','n'],'1993',['p1','p3']                                              ]
            , [ "a3", ['f','g','h'],   ['c','d','f','g','h','o'],'1993',['p2','p4','p5']                                         ]
            , [ "a4", ['i','j'],       ['c','d','p','q']        ,'1994',['p3','p6']      ,['a1','a2']                            ]
            , [ "a5", ['dj','dk'],     ['a','dj','dk','m','r']  ,'1994',['p1','p7']      ,['a1','a2','a3']                       ]
            , [ "a6", ['d','ac','ad'], ['d','ac','ad','s','t']  ,'1994',['p8','p9']      ,['a1','a3']                            ]
            ]
    ]);
    return view('graph', ['src' => "data:image/png;base64, $response"]);
});



Route::get('/dashboard', function () {
    $agent = new Agent();
    return view('dashboard', ['agent' => $agent]);
})->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';
