<?
$controller = new MainController();
['recommends' => $recommends, 'artists' => $artists, 'new_albums' => $new_albums] = $controller->index();

$new_album_section_title = UserHelper::checkLogin() ? "{$_SESSION['user']['nickname']}님이 추천한 아티스트의 신규 앨범" : "사용자들 추천 아티스트의 신규 앨범";
$artist_section_title = UserHelper::checkLogin() ? "{$_SESSION['user']['nickname']}님이 추천한 아티스트" : "사용자들 추천 아티스트";
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
<div class="main-container">
    <div class="new-album-section">
        <h2><?= $new_album_section_title ?></h2>
        <div class="new-album-container">
            <? if (isset($new_albums)): ?>
                <? foreach ($new_albums as $new_album): ?>
                    <a class="album-card" href="/search/albumDetail?id=<?= $new_album['flo_id'] ?>">
                        <img src="<?= $new_album['img_url'] . $_SERVER['IMG_RESIZE_PATH']($_SERVER['IMG_MEDIUM_SIZE']) ?>" />
                        <div class="album-card-content">
                            <h3 class="album-card-title"><?= $new_album['title'] ?></h3>
                            <? foreach ($new_album['artist'] as $artist): ?>
                                <p class="album-card-artist"><?= $artist['name'] ?></p>
                            <? endforeach; ?>
                        </div>
                    </a>
                <? endforeach; ?>
            <? else: ?>
                <p class="no-results">신규 앨범이 없습니다.</p>
            <? endif; ?>
        </div>
    </div>
    <div class="artist-section">
        <h2><?= $artist_section_title ?></h2>
        <div class="artist-container">
            <? if (isset($artists)): ?>
                <? foreach ($artists as $artist): ?>
                    <a class="artist-card" href="/search/artistDetail?id=<?= $artist['flo_id'] ?>">
                        <img src="<?= $artist['img_url'] . $_SERVER['IMG_RESIZE_PATH']($_SERVER['IMG_MEDIUM_SIZE']) ?>" />
                        <div class="artist-card-content">
                            <p class="artist-card-name"><?= $artist['name'] ?></p>
                            <div class="artist-card-recommend-count">
                                <svg viewBox="0 0 24 24">
                                    <g class="style-scope yt-icon">
                                        <path d="M18.77,11h-4.23l1.52-4.94C16.38,5.03,15.54,4,14.38,4c-0.58,0-1.14,0.24-1.52,0.65L7,11H3v10h4h1h9.43 c1.06,0,1.98-0.67,2.19-1.61l1.34-6C21.23,12.15,20.18,11,18.77,11z M7,20H4v-8h3V20z M19.98,13.17l-1.34,6 C18.54,19.65,18.03,20,17.43,20H8v-8.61l5.6-6.06C13.79,5.12,14.08,5,14.38,5c0.26,0,0.5,0.11,0.63,0.3 c0.07,0.1,0.15,0.26,0.09,0.47l-1.52,4.94L13.18,12h1.35h4.23c0.41,0,0.8,0.17,1.03,0.46C19.92,12.61,20.05,12.86,19.98,13.17z" class="style-scope yt-icon"></path>
                                    </g>
                                </svg>
                                <span>
                                    <?= $artist['recommend_cnt'] ?>
                                </span>
                            </div>
                        </div>
                    </a>
                <? endforeach; ?>
            <? else: ?>
                <p class="no-results">추천 아티스트가 없습니다.</p>
            <? endif; ?>
        </div>
    </div>
</div>

<script src="<?= $_SERVER['JS_PATH'] . 'lib/flickity.js' ?>"></script>
<script src="<?= $_SERVER['JS_PATH'] . 'main.js' ?>"></script>
<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>