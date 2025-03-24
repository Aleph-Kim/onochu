<?
$controller = new SearchController();

['artist_info' => $artist_info, 'albums_info' => $albums_info] = $controller->artistDetail();

?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>
<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'artistDetail.css' ?>">

<div class="main-content">
    <div class="profile">
        <img src="<?= $artist_info['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_BIG_SIZE']) ?>">
        <div>
            <div class="profile-name"><?= $artist_info['name'] ?></div>
            <div class="profile-info">
                <span><?= $artist_info['group_type'] ?></span>
                <span class="between-bar"></span>
                <span><?= $artist_info['genre'] ?></span>
            </div>
        </div>
    </div>

    <div class="albums-title">최근 앨범</div>
    <div class="albums">
        <? foreach ($albums_info as $album): ?>
            <div class="album">
                <div class="play-button">
                    <img src="<?= $album['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_MEDIUM_SIZE']) ?>">
                </div>
                <div class="album-info">
                    <div class="album-title"><?= $album['title'] ?></div>
                    <div class="album-artist"><?= $artist_info['name'] ?></div>
                    <div class="album-meta"><?= $album['type'] ?><br><?= $album['release_date'] ?></div>
                </div>
            </div>
        <? endforeach; ?>
    </div>
</div>

<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>