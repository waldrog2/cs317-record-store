

<?php

    require __DIR__ . '/vendor/autoload.php';

use App\Controller\Gridpage;
use App\Controller\Homepage;
use App\Controller\TestController;
use App\Lib\App;
use App\Lib\Router;
use App\Lib\Request;
use App\Lib\Response;

    Router::get('/api', function() {
        // echo "Hello";
       $res = new Response();
       $res->redirect("http://localhost:8044/",true);
        exit;
    });


    Router::get('/api/album/([0-9]*)', function (Request $req, Response $res)
    {
       $res->toJSON([
           'album_id' => ['id'=>$req->params[0]],
           'status' => 'ok'
       ]);
    });

    Router::get('/test/genre',function() {
       echo "I Did a thing!";
        TestController::getLastGenreID();
    });

    Router::get('/test/artist', function () {
        TestController::getArtist();
    });

    Router::get('/test/songsearch([?A-Za-z+_=]*)',function(Request $request) {
//        echo "Running songsearch controller";
        TestController::testSongSearch($request);
    });

    Router::post('/api/grid/([0-9]*', function() {Gridpage::getHomepageData(new Request());});

//    App::run();
?>