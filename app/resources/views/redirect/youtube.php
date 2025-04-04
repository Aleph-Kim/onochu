<?
$q = htmlspecialchars($_GET['q']);
$is_mobile = UserHelper::isMobile();
$is_android = UserHelper::isAndroid();

if (!$is_mobile) {
    header('Location: ' . $_SERVER['YOUTUBE_MUSIC_SEARCH_PATH']($q));
    exit;
}

if ($is_android) {
    header('Location: ' . $_SERVER['YOUTUBE_MUSIC_ANDROID_APP_PATH']($q));
    exit;
} else {
    header('Location: ' . $_SERVER['YOUTUBE_MUSIC_IOS_APP_PATH']($q));
    exit;
}
