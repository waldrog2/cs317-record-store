

<?php

    require __DIR__ . '/vendor/autoload.php';

use App\Controller\GridController;
use App\Controller\HomeController;
use App\Controller\TestController;
use App\Lib\App;
use App\Lib\NewRouter;
use App\Lib\Request;
use App\Lib\Response;

    NewRouter::add('/', function()
    {
        $res = new Response();
        $res->redirect('http://localhost:8044',true);
    },'GET');

    NewRouter::add('/album/([0-9]*)', "\App\Controller\AlbumController::getAlbumInfo",'GET');
    NewRouter::add('/search/?.*',"\App\Controller\SearchController::search",'GET');
    NewRouter::add('/gridpage?.*',"\App\Controller\GridController::getGridPage",'GET');
    NewRouter::add('/homepage',"\App\Controller\HomeController::getHomepage",'GET');
    NewRouter::add('/testsearch?.*',"\App\Controller\TestController::testSongSearch",'GET');
    NewRouter::add('/recommendation?.*',"\App\Controller\RecommendationController::recommend",'GET');
NewRouter::process_route('/api');

//    echo "Got HERE";
//    Router::get('/api', function() {
//        // echo "Hello";
//       $res = new Response();
//       $res->redirect("http://localhost:8044/",true);
//        exit;
//    });
//
//
//    Router::get('/api/album/([0-9]*)', function (Request $req, Response $res)
//    {
//       $res->toJSON([
//           'album_id' => ['id'=>$req->params[0]],
//           'status' => 'ok'
//       ]);
//    });
//
//    Router::get('/test/genre',function() {
//       echo "I Did a thing!";
//        TestController::getLastGenreID();
//    });
//
//    Router::get('/test/artist', function () {
//        TestController::getArtist();
//    });
//
//    Router::get('/test/songsearch([?A-Za-z+_=]*)',function(Request $request) {
////        echo "Running songsearch controller";
//        TestController::testSongSearch($request);
//    });
//
//    Router::post('/api/grid/([0-9]*', function() {GridController::getHomepageData(new Request());});

//    App::run();
?>