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

function getData(){
    $articles = DB::table('article')
                ->select('no', 'keywords', 'abstracts', 'year', 'authors', 'citing_new')
                ->get();

    $data = json_decode($articles, true);

    $result = [];

    $flag=0;
    foreach ($data as $row) {
        $flag++;
        // if($flag==44||$flag==44||$flag==48||$flag==49||$flag==50)continue;
        if($flag<=0) continue;
        // if($flag==38)echo $row['authors'];
        if($flag>60) break;
        $keywords = preg_split('/\s*[,;\/]\s*/', $row['keywords']);
        $authors = preg_split('/\s*[,;\/]\s*/', $row['authors']);

       
        foreach ($authors as $key => $author) {
            if (strlen($author) <= 3) {
                unset($authors[$key]);
            }
        }  
        sort($authors, SORT_NUMERIC);
          
        $citingNew = preg_split('/\s*[,;\/]\s*/', $row['citing_new']);
        foreach ($citingNew as $key => $citing) {
            if (strlen($citing) <= 1) {
                unset($citingNew[$key]);
            }
        }
        sort($citingNew, SORT_NUMERIC);

        $abstracts = $keywords;

        if(strlen($row['citing_new'])==1){
            $result[] = [$row['no'], $keywords, $abstracts,(string) $row['year'],$authors];
        }         
        else{
            $result[] = [$row['no'], $keywords, $abstracts,(string) $row['year'],$authors,  $citingNew];
        }    
    }
    return $result;
}


Route::get('/data/rank', function () {
    $result = getData();
    // transporse table
    // https://stackoverflow.com/questions/6297591/how-to-invert-transpose-the-rows-and-columns-of-an-html-table
    $response = Http::timeout(999999)->post('http://127.0.0.1:5000/data/rank', [
        'data' => 
            $result
            // [  
            //     [ "a1", ['a','b','c'],   ['a','b','c','k','l']    ,'1993',['p1','p2']                                              ]
            //     , [ "a2", ['c','d','e'],   ['a','c','d','e','m','n'],'1993',['p1','p3']                                              ]
            //     , [ "a3", ['f','g','h'],   ['c','d','f','g','h','o'],'1993',['p2','p4','p5']                                         ]
            //     , [ "a4", ['i','j'],       ['c','d','p','q']        ,'1994',['p3','p6']      ,['a1','a2']                            ]
            //     , [ "a5", ['dj','dk'],     ['a','dj','dk','m','r']  ,'1994',['p1','p7']      ,['a1','a2','a3']                       ]
            //     , [ "a6", ['d','ac','ad'], ['d','ac','ad','s','t']  ,'1994',['p8','p9']      ,['a1','a3']                            ]
            // ]
    ]);
    // return $response;
    // return json_decode($response);
    $authors = $response[0];
    $ranks =  $response[1][1];

    // Combine the authors and ranks into an array of arrays
    $author_ranks = array();
    for ($i = 0; $i < count($authors); $i++) {
        $author_ranks[] = array($authors[$i], $ranks[$i]);
    }

    // Sort the author-rank pairs based on the rank (ascending order)
    usort($author_ranks, function($a, $b) {
        return $a[1] - $b[1];
    });

    return view('rank',  ['authors'=> $response[0],'ranktable' => $response[1][0],'rank' => $response[1][1],'author_ranks' => $author_ranks]);

});

Route::get('/data/graph', function () {
    $result = getData();

    $response = Http::post('http://127.0.0.1:5000/data/graph', [
        'data' => 
        $result
        // [  
        //     [ "a1", ['a','b','c'],   ['a','b','c','k','l']    ,'1993',['p1','p2']                                              ]
        //     , [ "a2", ['c','d','e'],   ['a','c','d','e','m','n'],'1993',['p1','p3']                                              ]
        //     , [ "a3", ['f','g','h'],   ['c','d','f','g','h','o'],'1993',['p2','p4','p5']                                         ]
        //     , [ "a4", ['i','j'],       ['c','d','p','q']        ,'1994',['p3','p6']      ,['a1','a2']                            ]
        //     , [ "a5", ['dj','dk'],     ['a','dj','dk','m','r']  ,'1994',['p1','p7']      ,['a1','a2','a3']                       ]
        //     , [ "a6", ['d','ac','ad'], ['d','ac','ad','s','t']  ,'1994',['p8','p9']      ,['a1','a3']                            ]
        // ]
    ]);
    return view('graph', ['src' => "data:image/png;base64, $response"]);
});



Route::get('/dashboard', function () {
    $agent = new Agent();
    return view('dashboard', ['agent' => $agent]);
})->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';
