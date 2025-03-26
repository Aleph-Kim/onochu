<?
$controller = new RecommendsController();
$recommend_info = $controller->detail();
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>

<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'recommendDetail.css' ?>">

<div class="container">
    <div class="artist-info">
        <img src="<?= $recommend_info['artists'][0]['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_SMALL_SIZE']) ?>" onclick="window.location.href = '/search/artistDetail?id=<?= $recommend_info['artists'][0]['flo_id'] ?>'">
        <span>
            <? foreach ($recommend_info['artists'] as $artist): ?>
                <span class="artist-name" onclick="window.location.href = '/search/artistDetail?id=<?= $artist['flo_id'] ?>'"><?= $artist['name'] ?></span>
            <? endforeach; ?>
        </span>
    </div>
    <div class="music-platforms">
        <a href="<?= $recommend_info['url']['youtube'] ?>" target="_blank" class="platform-btn platform-youtube">YouTube Music</a>
        <a href="<?= $recommend_info['url']['flo'] ?>" target="_blank" class="platform-btn platform-flo">FLO</a>
        <a href="<?= $recommend_info['url']['genie'] ?>" target="_blank" class="platform-btn platform-genie">Genie</a>
    </div>
    <div class="song-img">
        <img src="<?= $recommend_info['album_img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_MEDIUM_SIZE']) ?>">
    </div>
    <div class="song-info">
        <h2><?= $recommend_info['song_title'] ?></h2>
        <p>
            <?= $recommend_info['release_date'] ?>
            <span class="between-bar"></span>
            <?= $recommend_info['genre'] ?>
            <span class="between-bar"></span>
            <?= $recommend_info['play_time'] ?>
        </p>
    </div>
    <div class="recommend-info">
        <div class="recommends-rating">
            <input type="radio" id="star5" name="score" value="5" <?= $recommend_info['score'] == 5 ? 'checked' : '' ?> disabled>
            <label for="star5">★</label>
            <input type="radio" id="star4" name="score" value="4" <?= $recommend_info['score'] == 4 ? 'checked' : '' ?> disabled>
            <label for="star4">★</label>
            <input type="radio" id="star3" name="score" value="3" <?= $recommend_info['score'] == 3 ? 'checked' : '' ?> disabled>
            <label for="star3">★</label>
            <input type="radio" id="star2" name="score" value="2" <?= $recommend_info['score'] == 2 ? 'checked' : '' ?> disabled>
            <label for="star2">★</label>
            <input type="radio" id="star1" name="score" value="1" <?= $recommend_info['score'] == 1 ? 'checked' : '' ?> disabled>
            <label for="star1">★</label>
        </div>
        <textarea name="comment" class="recommends-comment" placeholder="작성된 코멘트가 없습니다." disabled><?= $recommend_info['comment'] ?></textarea>
        <div class="recommend-date">추천일: <?= date('Y년 m월 d일', strtotime($recommend_info['recommend_date'])) ?></div>
    </div>
</div>
<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>