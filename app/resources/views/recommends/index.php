<?
$controller = new RecommendsController();
$songInfo = $controller->index();
$_SESSION['songInfo'] = $songInfo;
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>

<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'recommends.css' ?>">

<form class="song-container" action="/recommends/post" method="post">
    <div class="artist-info">
        <span><?= $songInfo['artist']['name'] ?></span>
    </div>
    <div class="song-img">
        <img src="<?= $songInfo['album']['img_url'] ?>">
    </div>
    <div class="song-info">
        <h2><?= $songInfo['song']['title'] ?></h2>
        <p>
            <?= $songInfo['album']['release_date'] ?>
            <span class="between-bar"></span>
            <?= $songInfo['song']['genre'] ?>
            <span class="between-bar"></span>
            <?= $songInfo['song']['play_time'] ?>
        </p>
    </div>
    <div class="recommends-rating">
        <input type="radio" id="star5" name="score" value="5">
        <label for="star5">★</label>
        <input type="radio" id="star4" name="score" value="4">
        <label for="star4">★</label>
        <input type="radio" id="star3" name="score" value="3">
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