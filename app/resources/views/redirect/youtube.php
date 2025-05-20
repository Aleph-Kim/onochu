<?
$q = htmlspecialchars($_GET['q']);
$is_mobile = UserHelper::isMobile();
$is_android = UserHelper::isAndroid();

if (!$is_mobile) {
    ScriptHelper::go($_SERVER['YOUTUBE_MUSIC_SEARCH_PATH']($q));
}

if ($is_android) {
    ScriptHelper::go($_SERVER['YOUTUBE_MUSIC_ANDROID_APP_PATH']($q));
} else {
    ScriptHelper::go($_SERVER['YOUTUBE_MUSIC_IOS_APP_PATH']($q));
}
