<?
$controller = new RecommendsController();
$song = $controller->index();
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>

<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'recommends.css' ?>">

<div class="song-container">
    <div class="artist-info">
        <img src="<?= $song['artist']['img_url'] ?>">
        <span><?= $song['artist']['name'] ?></span>
    </div>
    <div class="song-img">
        <img src="<?= $song['album']['img_url'] ?>">
    </div>
    <div class="song-info">
        <h2><?= $song['song']['name'] ?></h2>
        <p>
            <?= $song['album']['release_date'] ?>
            <span class="between-bar"></span>
            <?= $song['song']['genre'] ?>
            <span class="between-bar"></span>
            <?= $song['song']['play_time'] ?>
        </p>
    </div>
    <div class="recommends-rating">
        <input type="radio" id="star5" name="rating" value="5">
        <label for="star5">★</label>
        <input type="radio" id="star4" name="rating" value="4">
        <label for="star4">★</label>
        <input type="radio" id="star3" name="rating" value="3">
        <label for="star3">★</label>
        <input type="radio" id="star2" name="rating" value="2">
        <label for="star2">★</label>
        <input type="radio" id="star1" name="rating" value="1">
        <label for="star1">★</label>
    </div>
    <textarea name="comment" class="recommends-comment" placeholder="코멘트를 남겨주세요!"></textarea>
    <div class="buttons">
        <button class="btn btn-cancel" onclick="confirmBack()">뒤로가기</button>
        <button class="btn btn-submit">추천</button>
    </div>
</div>

<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>