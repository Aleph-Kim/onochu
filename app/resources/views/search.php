<?
$controller = new SearchController();
$songs = $controller->index();
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>
<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'search.css' ?>">

<div class="search-container">
    <? if ($songs) { ?>
        <div class="search-text-box">
            <span class="search-text"><?= $_GET['q'] ?></span> 검색 결과
        </div>
        <div class="song-wrap">
            <? foreach ($songs as $song): ?>
                <div class="song-box">
                    <span class="album-img">
                        <img src="<?= $song['album']['img_url'] ?>" alt="">
                    </span>
                    <div class="song-info">
                        <div class="song-name"><?= $song['song']['name'] ?></div>
                        <div class="song-etc">
                            <span class="artist-name"><?= $song['artist']['name'] ?></span>
                            <span> • </span>
                            <span class="album-name"><?= $song['album']['title'] ?></span>
                            <span> • </span>
                            <span class="song-play-time"><?= $song['song']['play_time'] ?></span>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    <? } else { ?>
        <div class="not-found">
            <svg viewBox="0 0 24 24">
                <g>
                    <path d="M20.87,20.17l-5.59-5.59C16.35,13.35,17,11.75,17,10c0-3.87-3.13-7-7-7s-7,3.13-7,7s3.13,7,7,7c1.75,0,3.35-0.65,4.58-1.71 l5.59,5.59L20.87,20.17z M10,16c-3.31,0-6-2.69-6-6s2.69-6,6-6s6,2.69,6,6S13.31,16,10,16z"></path>
                </g>
            </svg>
            <div class="not-found-text"><span class="search-text"><?= $_GET['q'] ?></span>에 대한 검색결과가 없습니다.</div>
            <div class="another-text">다른 검색어를 사용해보세요.</div>
        </div>
    <? }  ?>
</div>


<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>