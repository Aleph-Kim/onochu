<?
$controller = new MainController();
['recommends' => $recommends] = $controller->index();
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>
<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'main.css' ?>">

<div class="slide-container">
    <div class="music-slider">
        <? foreach ($recommends as $recommend): ?>
            <a class="music-slide" href="/recommends/detail?id=<?= $recommend['id'] ?>">
                <div class="music-card-wrap">
                    <div class="music-card-box">
                        <div class="music-card-body">
                            <span class="music-card-artist-img">
                                <img src="<?= $recommend['artist_img_url'] . $_SERVER['IMG_RESIZE_PATH']($_SERVER['IMG_MEDIUM_SIZE']) ?>">
                            </span>
                            <div class="music-card-bg" style="background-image: url(<?= $recommend['album_img_url'] . $_SERVER['IMG_RESIZE_PATH']($_SERVER['IMG_BIG_SIZE']) ?>);"></div>
                        </div>
                        <div class="music-card-footer">
                            <div>
                                <span class="music-card-title"><?= $recommend['song_title'] ?></span>
                                <span class="music-card-artist-name"><?= $recommend['artist_name'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <? endforeach; ?>
    </div>
</div>

<script src="<?= $_SERVER['JS_PATH'] . 'lib/flickity.js' ?>"></script>
<script src="<?= $_SERVER['JS_PATH'] . 'main.js' ?>"></script>
<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>