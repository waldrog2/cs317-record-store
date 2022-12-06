

<?php

    require __DIR__ . '/vendor/autoload.php';

use App\Lib\NewRouter;
use App\Lib\Response;

    NewRouter::add('/', function()
    {
        $res = new Response();
        $res->redirect('http://localhost:8044',true);
    },'GET');

    NewRouter::add('/album?.*', "\App\Controller\AlbumController::getAlbumInfo",'GET');
    NewRouter::add('/search?.*',"\App\Controller\SearchController::search",'GET');
    NewRouter::add('/gridpage',"\App\Controller\GridController::getHomepageGrid",'GET');
    NewRouter::add('/genregrid?.*',"\App\Controller\GridController::getGenreGrid",'GET');
    NewRouter::add('/homepage',"\App\Controller\HomeController::getHomepage",'GET');

NewRouter::process_route('/api');
?>