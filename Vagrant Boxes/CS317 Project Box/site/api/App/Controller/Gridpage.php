<?php

    namespace App\Controller;

    use App\Lib\Request;

    class Gridpage {

        public static function getGridPage(Request $request)
        {
            $parameters = $request->getBody();
            $target_page = $parameters['page_number'];
            $rows = $parameters['rows'];
            $cols = $parameters['cols'];


        }
    }
