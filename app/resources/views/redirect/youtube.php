<?
$q = htmlspecialchars($_GET['q']);
$is_mobile = UserHelper::isMobile();

if ($is_mobile) {
    header('Location: ' . $_SERVER['YOUTUBE_MUSIC_APP_PATH']($q));
} else {
    header('Location: ' . $_SERVER['YOUTUBE_MUSIC_SEARCH_PATH']($q));
}
?>
