<?
$controller = new SearchController();
$song_info = $controller->songDetail();
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>
<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'songDetail.css' ?>">

<div class="container">
    <div class="main-content">
        <div class="song-header">
            <div>
                <h1 class="song-title"><?= $song_info['song']['title'] ?></h1>
                <p class="song-artist"><?= $song_info['artist']['name'] ?></p>
            </div>
            <? if ($song_info['artist']['img_url']) { ?>
                <div class="artist-profile">
                    <img src="<?= $song_info['artist']['img_url'] ?>" alt="Artist Profile">
                </div>
            <? } ?>
        </div>
        <div class="toggle-buttons">
            <button class="toggle-btn cover-btn active" onclick="toggleView('cover')">커버보기</button>
            <button class="toggle-btn lyrics-btn" onclick="toggleView('lyrics')">가사보기</button>
        </div>
        <div class="album-container" id="album-container">
            <img src="<?= $song_info['album']['img_url'] ?>" alt="Album Cover" class="album-image">
            <? if ($song_info['song']['lyrics']) { ?>
                <div class="lyrics-overlay"><?= $song_info['song']['lyrics'] ?></div>
            <? } else { ?>
                <div class="lyrics-overlay none"><?= '가사가 제공되지 않는 곡입니다.' ?></div>
            <? } ?>
        </div>
        <div class="music-platforms">
            <a href="<?= $song_info['song']['url']['youtube'] ?>" target="_blank" class="platform-btn platform-youtube">YouTube Music</a>
            <a href="<?= $song_info['song']['url']['flo'] ?>" target="_blank" class="platform-btn platform-flo">FLO</a>
            <a href="<?= $song_info['song']['url']['genie'] ?>" target="_blank" class="platform-btn platform-genie">Genie</a>
        </div>
        <h3 class="section-title">곡 정보</h3>
        <div class="song-details">
            <div class="details-grid">
                <div class="details-item">
                    <p class="details-label">발매일</p>
                    <p class="details-value"><?= $song_info['album']['release_date'] ?></p>
                </div>
                <div class="details-item">
                    <p class="details-label">장르</p>
                    <p class="details-value"><?= $song_info['song']['genre'] ?></p>
                </div>
                <div class="details-item">
                    <p class="details-label">작사</p>
                    <p class="details-value"><?= $song_info['song']['lyricist'] ?></p>
                </div>
                <div class="details-item">
                    <p class="details-label">작곡</p>
                    <p class="details-value"><?= $song_info['song']['composer'] ?></p>
                </div>
                <? if ($song_info['song']['arranger']) { ?>
                    <div class="details-item">
                        <p class="details-label">편곡</p>
                        <p class="details-value"><?= $song_info['song']['arranger'] ?></p>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
</div>

<script src="<?= $_SERVER['JS_PATH'] . 'songDetail.js' ?>"></script>
<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>