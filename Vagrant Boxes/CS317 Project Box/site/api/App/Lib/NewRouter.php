<?php

namespace App\Lib;

class NewRouter
{
    private static $route_list = [];
    private static $path_not_found_handler = null;
    private static $method_not_allowed_handler = null;

    private static $found_path_match = false;
    private static $found_route_match = false;
    public static function add($route_expression, $callback, $method)
    {
        self::$route_list[] = [
            'route_expression' => $route_expression, // the regex expression representing the route
            'controller' => $callback, //this is a callable in the format 'Classname::method'
            'method' => $method // this is the HTTP method, only GET and POST are supported by this router.
        ];
    }

    public static function pathNotFoundHandler($callback)
    {
        self::$path_not_found_handler = $callback; // set the path not found handler
    }

    public static function methodNotAllowedHandler($callback)
    {
        self::$method_not_allowed_handler = $callback; // set the method not allowed handler
    }

    public static function process_route($base_path = '/')
    {
        $url = parse_url($_SERVER['REQUEST_URI']); // parses the url into an associative array of it's components (path, query, etc.)

        if (isset($url['path'])) //if there is a path, use it
        {
            $url_path = $url['path'];
        }
        else
        {
            $url_path = '/'; //we are accessing the base of the app, so set the path to /
        }

        $method = $_SERVER['REQUEST_METHOD']; // get the HTTP method

        self::$found_path_match = false; // set path found to false
        self::$found_route_match = false; // set route found to false
//    var_dump(self::$route_list);
        foreach(self::$route_list as $route) //process each registered route looking for a match
        {
            $temp_expression = rtrim($route['route_expression'],'/');
            $search_expression = "";
//            var_dump($base_path);
            if ($base_path != '' && $base_path != '/')
            {
                $search_expression = '#^(' . $base_path . ')' . $temp_expression . "$#";
//                var_dump($search_expression);
            }
            else
            {
                $search_expression = '#^' . $route['route_expression'] . '$#';
            }
            //preg_match has the odd property of
            //permitting almost any character to mark the start and end of regex, here # is used to avoid
            // matching with the / in the url.

//            var_dump($url_path);
//            var_dump($search_expression);

            if (preg_match($search_expression,$url_path,$matches)) //check if the route regex matches the URL
            {
                self::$found_path_match = true; //if so, a path is found. Now the method must be verified

                if (strtolower($method) == strtolower($route['method'])) //if the method matches
                {
                    array_shift($matches); //remove the first match as it is the entire string

                    if ($base_path != '' && $base_path != '/')
                    {
                        //if the base path (the path the URL is evaluated from) isn't the root, remove the base part
                        array_shift($matches);

                    }
                    self::$found_route_match = true; // a route match was found (method is supported)
                    if ($route['method'] === 'GET')
                    {
//                        var_dump($url["query"]);
                        if (array_key_exists("query",$url)) {
                            call_user_func_array($route['controller'], [$matches, $url["query"]]);
                        }
                        else
                        {
                            call_user_func($route['controller']);
                        }
                    }
                    else {
                        call_user_func_array($route['controller'], $matches); // call the route match
                    }
                    break;// stop processing additional routes

                }
            }
            else
            {
//                var_dump($matches);
            }

            if (!self::$found_route_match) //handle route not matching
            { $response = new Response();
                if (self::$found_path_match) //if we found the path, the method is not supported for the path.
                {
                    if (self::$method_not_allowed_handler) //if a handler is registered, call it
                    {
                        call_user_func_array(self::$method_not_allowed_handler, [$url_path]);
                    }
                    else // use a generic one by sending 405 to the browser
                    {
                        $response->send(405,"Method Not Allowed");
                    }
                }
                else //the path wasn't found
                {
//                    var_dump($url_path);
//                    var_dump($route['route_expression']);
                 if (self::$path_not_found_handler) //if a hadler is registered, call it
                 {
                    call_user_func_array(self::$path_not_found_handler, [$url_path]);
                 }
                 else //use a generic one (send 404 to the browser)
                 {
                     $response->send(404,'Not Found');
                 }
                }
            }
        }
    }


}