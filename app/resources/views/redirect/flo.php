<?
$flo_id = htmlspecialchars($_GET['id']);
$is_mobile = UserHelper::isMobile();

if ($is_mobile) {
    header('Location: ' . $_SERVER['FLO_APP_DETAIL_PATH']($flo_id));
} else {
    header('Location: ' . $_SERVER['FLO_DETAIL_PATH']($flo_id));
}
