<?php
// 한국 시간대 설정
date_default_timezone_set('Asia/Seoul');

// 프로젝트 루트 디렉토리 설정
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 1);

/**
 * 크론 작업을 위한 기본 Core 클래스
 */
class Cron
{
    private $cron_path;
    private $model_path;
    private $log_path;

    public function __construct()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/init/config.php";
        require_once $_SERVER['INIT_PATH'] . "loadEnv.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

        $this->cron_path = $_SERVER['INIT_PATH'] . "cron/";
        $this->model_path = $_SERVER['DOCUMENT_ROOT'] . "/app/models/";
        $this->log_path = "/var/log/apache2/";
    }

    /**
     * 크론 스크립트를 실행하는 메서드
     * 
     * @param string $script 실행할 스크립트 이름
     * @return void
     */
    public function run($script)
    {
        $scriptPath = $this->cron_path . "{$script}.php";

        if (!file_exists($scriptPath)) {
            echo "Error: Script {$script} not found." . PHP_EOL;
            $this->errorLog("Error: Script {$script} not found.");
            exit(1);
        }

        require_once $scriptPath;

        $className = ucfirst($script);
        $cronJob = new $className();
        $cronJob->execute();
    }

    /**
     * 모델 인스턴스를 가져오는 메서드
     * 
     * @param string $model 모델 이름
     * @return object 모델 인스턴스
     */
    protected function model($model)
    {
        require_once $this->model_path . "$model.php";
        return new $model();
    }

    /**
     * 일반 로그 기록 메서드
     * 
     * @param string $message 로그 메시지
     * @return void
     */
    protected function log($message)
    {
        $logMessage = date('Y-m-d H:i:s') . " - " . $message . PHP_EOL;
        file_put_contents($this->log_path . "cron.log", $logMessage, FILE_APPEND);
    }

    /**
     * 에러 로그 기록 메서드
     * 
     * @param string $message 에러 메시지
     * @param Exception $exception 예외 객체
     * @return void
     */
    protected function errorLog($message, $exception = null)
    {
        $logMessage = date('Y-m-d H:i:s') . ": " . $message;

        if ($exception) {
            $logMessage .= " in " . $exception->getFile() . ":" . $exception->getLine() . PHP_EOL;
            $logMessage .= "Stack trace:" . PHP_EOL;
            $logMessage .= $exception->getTraceAsString() . PHP_EOL;
            $logMessage .= $exception->getMessage() . PHP_EOL;
        } else {
            $logMessage .= PHP_EOL;
        }

        file_put_contents($this->log_path . "cron_error.log", $logMessage, FILE_APPEND);
    }
}

// 명령줄에서 직접 실행될 때
if (php_sapi_name() === 'cli') {
    $cron = new Cron();
    $cron->run($argv[1]);
}
