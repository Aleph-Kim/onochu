<?
$controller = new RecommendsController();
$song_info = $controller->index();
$_SESSION['song_info'] = $song_info;
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>

<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'recommends.css' ?>">

<form class="song-container" action="/recommends/post" method="post">
    <div class="artist-info">
        <img src="<?= $song_info['artist']['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_SMALL_SIZE']) ?>" alt="Artist Profile">
        <span><?= $song_info['artist']['name'] ?></span>
    </div>
    <div class="song-img">
        <img src="<?= $song_info['album']['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_MEDIUM_SIZE']) ?>">
    </div>
    <div class="song-info">
        <h2><?= $song_info['song']['title'] ?></h2>
        <p>
            <?= $song_info['album']['release_date'] ?>
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
        <button class="btn btn-cancel" onclick="confirmBack()">뒤로가기</button>
        <button class="btn btn-submit">추천</button>
    </div>
</form>

<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>