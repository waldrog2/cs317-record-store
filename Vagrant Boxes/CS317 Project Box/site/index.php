

<?php

    require __DIR__ . '/vendor/autoload.php';

use App\Controller\Gridpage;
use App\Controller\Homepage;
use App\Controller\TestController;
use App\Lib\App;
use App\Lib\Router;
use App\Lib\Request;
use App\Lib\Response;

    Router::get('/', function() {
        echo(file_get_contents(__DIR__."/homepage.html"));
    });


    Router::get('/api/album/([0-9]*)', function (Request $req, Response $res)
    {
        echo "JSON!";
       $res->toJSON([
           'album_id' => ['id'=>$req->params[0]],
           'status' => 'ok'
       ]);
    });

    Router::get('/test/genre',function() {
//        echo "I Did a thing!";
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