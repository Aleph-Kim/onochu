<?
@session_start();

// 한국 시간대 설정
date_default_timezone_set('Asia/Seoul');

require_once '../vendor/autoload.php';

require_once '../init/config.php';

require_once $_SERVER['INIT_PATH'] . "loadEnv.php";

require_once $_SERVER['INIT_PATH'] . "loadView.php";