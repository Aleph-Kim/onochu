<?
$controller = new MainController();
$musics = $controller->index();
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>
<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'main.css' ?>">

<div class="slide-container">
    <div class="music-slider">
        <? foreach ($musics as $music): ?>
            <div class="music-slide">
                <div class="music-card-wrap">
                    <div class="music-card-box">
                        <div class="music-card-body">
                            <span class="music-card-artist-img">
                                <img src="<?= $music['artist_img'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_MEDIUM_SIZE']) ?>">
                            </span>
                            <div class="music-card-bg" style="background-image: url(<?= $music['bg_img'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_BIG_SIZE']) ?>);"></div>
                        </div>
                        <div class="music-card-footer">
                            <div>
                                <span class="music-card-title"><?= $music['music_name'] ?></span>
                                <span class="music-card-artist-name"><?= $music['artist_name'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </div>
</div>

<script src="<?= $_SERVER['JS_PATH'] . 'flickity.js' ?>"></script>
<script src="<?= $_SERVER['JS_PATH'] . 'main.js' ?>"></script>
<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>