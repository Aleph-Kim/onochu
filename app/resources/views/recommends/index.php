<?
$controller = new RecommendsController();
$song_info = $controller->index();
$_SESSION['song_info'] = $song_info;
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>

<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'recommends.css' ?>">

<div class="container">
    <form class="recommends-form" action="/recommends/post" method="post">
        <div class="artist-info">
            <img src="<?= $song_info['artists'][0]['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_SMALL_SIZE']) ?>" onclick="window.location.href = '/artist/detail?id=<?= $song_info['artists'][0]['flo_id'] ?>'">
            <span>
                <? foreach ($song_info['artists'] as $artist): ?>
                    <span class="artist-name" onclick="window.location.href = '/artist/detail?id=<?= $artist['flo_id'] ?>'"><?= $artist['name'] ?></span>
                <? endforeach; ?>
            </span>
        </div>
        <div class="song-img">
            <img src="<?= $song_info['album']['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_MEDIUM_SIZE']) ?>">
        </div>
        <div class="song-info">
            <h2><?= $song_info['song']['title'] ?></h2>
            <p>
                <?= $song_info['album']['release_date'] ?? '발매일 미상' ?>
                <span class="between-bar"></span>
                <?= $song_info['song']['genre'] ?>
                <span class="between-bar"></span>
                <?= $song_info['song']['play_time'] ?>
            </p>
        </div>
        <div class="recommends-rating">
            <input type="radio" id="star5" name="score" value="5">
            <label for="star5">★</label>
            <input type="radio" id="star4" name="score" value="4">
            <label for="star4">★</label>
            <input type="radio" id="star3" name="score" value="3" checked>
            <label for="star3">★</label>
            <input type="radio" id="star2" name="score" value="2">
            <label for="star2">★</label>
            <input type="radio" id="star1" name="score" value="1">
            <label for="star1">★</label>
        </div>
        <textarea name="comment" class="recommends-comment" placeholder="코멘트를 남겨주세요!"></textarea>
        <div class="buttons">
            <button type="button" class="btn btn-cancel" onclick="confirmBack()">뒤로가기</button>
            <button type="submit" class="btn btn-submit">추천</button>
        </div>
    </form>
</div>
<script src="<?= $_SERVER['JS_PATH'] . 'recommends.js' ?>"></script>
<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>