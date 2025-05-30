<?
$controller = new SongController();
$song_info = $controller->detail();
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>
<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'songDetail.css' ?>">

<div class="container">
    <div class="song-header">
        <div class="song-header-info">
            <h1 class="song-title"><?= $song_info['song']['title'] ?></h1>
            <div>
                <? foreach ($song_info['artists'] as $artist): ?>
                    <a class="song-artist" href="/artist/detail?id=<?= $artist['flo_id'] ?>">
                        <?= $artist['name'] ?>
                    </a>
                <? endforeach; ?>
            </div>
            <a class="song-album" href="/album/detail?id=<?= $song_info['album']['flo_id'] ?>"><?= $song_info['album']['title'] ?></a>
        </div>
        <? if ($song_info['artists'][0]['img_url']) { ?>
            <div class="artist-profile">
                <a href="/artist/detail?id=<?= $song_info['artists'][0]['flo_id'] ?>">
                    <img src="<?= $song_info['artists'][0]['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_SMALL_SIZE']) ?>" alt="Artist Profile">
                </a>
            </div>
        <? } ?>
    </div>
    <div class="toggle-buttons">
        <button class="toggle-btn cover-btn active" onclick="toggleView('cover')">커버보기</button>
        <button class="toggle-btn lyrics-btn" onclick="toggleView('lyrics')">가사보기</button>
    </div>
    <div class="album-container">
        <img src="<?= $song_info['album']['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_BIG_SIZE']) ?>" alt="Album Cover" class="album-image">
        <? if ($song_info['song']['lyrics']) { ?>
            <div class="lyrics-overlay"><?= $song_info['song']['lyrics'] ?></div>
        <? } else { ?>
            <div class="lyrics-overlay none"><?= '가사가 제공되지 않는 곡입니다.' ?></div>
        <? } ?>
    </div>
    <div class="music-platforms">
        <a href="<?= $song_info['song']['url']['youtube'] ?>" target="_blank" class="platform-btn platform-youtube">YouTube Music</a>
        <a href="<?= $song_info['song']['url']['flo'] ?>" target="_blank" class="platform-btn platform-flo">FLO</a>
        <a href="<?= $song_info['song']['url']['spotify'] ?>" target="_blank" class="platform-btn platform-spotify">Spotify</a>
        <!-- <a href="<?= $song_info['song']['url']['genie'] ?>" target="_blank" class="platform-btn platform-genie">Genie</a> -->
    </div>
    <h3 class="section-title">곡 정보</h3>
    <div class="song-details">
        <div class="details-grid">
            <div class="details-item">
                <p class="details-label">발매일</p>
                <p class="details-value"><?= $song_info['album']['release_date'] ?? '발매일 미상' ?></p>
            </div>
            <div class="details-item">
                <p class="details-label">장르</p>
                <p class="details-value"><?= $song_info['song']['genre'] ?></p>
            </div>
            <? if ($song_info['song']['lyricist']) { ?>
                <div class="details-item">
                    <p class="details-label">작사</p>
                    <p class="details-value"><?= $song_info['song']['lyricist'] ?></p>
                </div>
            <? } ?>
            <div class="details-item">
                <p class="details-label">작곡</p>
                <p class="details-value"><?= $song_info['song']['composer'] ?: '작곡가 미상' ?></p>
            </div>
            <? if ($song_info['song']['arranger']) { ?>
                <div class="details-item">
                    <p class="details-label">편곡</p>
                    <p class="details-value"><?= $song_info['song']['arranger'] ?></p>
                </div>
            <? } ?>
        </div>
        <div class="buttons">
            <a class="btn-recommends" href="/recommends?id=<?= $song_info['song']['flo_id'] ?>">추천하기</a>
        </div>
    </div>
</div>

<script src="<?= $_SERVER['JS_PATH'] . 'songDetail.js' ?>"></script>
<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>