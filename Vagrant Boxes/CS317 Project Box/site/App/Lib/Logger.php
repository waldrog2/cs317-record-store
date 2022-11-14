<?php
    namespace App\Lib;

    use DateTimeZone;
    use Monolog\ErrorHandler;
    use Monolog\Handler\StreamHandler;

    class Logger extends \Monolog\Logger {
        private static $registered_loggers = [];

        public function __construct($key = "app", $config = null)
        {
            parent::__construct($key);

            if (empty($config)) {
                $LOG_PATH = Config::get_config_option('LOG_PATH','__DIR__'.'/../../logs');
                $config = [
                    'logFile' => "${LOG_PATH}/{$key}.log",
                    'logLevel' => \Monolog\Logger::DEBUG
                ];
            }

            $this->pushHandler(new StreamHandler($config['logFile'],$config['logLevel']));
        }

        public static function getLogger($key = "app",$config = null) {
            if (empty(self::$registered_loggers[$key])) {
                self::$registered_loggers[$key] = new Logger($key,$config);
            }

            return self::$registered_loggers[$key];
        }

        public static function enableSystemLogs()
        {
            $LOG_PATH = Config::get_config_option('LOG_PATH','__DIR__'.'/../../logs');
            self::$registered_loggers['error'] = new Logger('errors');
            self::$registered_loggers['error']->pushHandler(new StreamHandler("{$LOG_PATH}/errors.log"));
            ErrorHandler::register(self::$registered_loggers['error']);

            $data = [
                $_SERVER,
                $_REQUEST,
                trim(file_get_contents("php://input"))
            ];

            self::$registered_loggers['request'] = new Logger('request');
            self::$registered_loggers['request']->pushHandler(new StreamHandler("{$LOG_PATH}/request.log"));
            self::$registered_loggers['request']->info("REQUEST",$data);
        }
    }


