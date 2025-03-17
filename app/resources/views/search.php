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
                    <div class="song-details">
                        <span class="album-img">
                            <img src="<?= $song['album']['img_url'] ?>" alt="">
                        </span>
                        <div class="song-info">
                            <div class="song-name"><?= $song['song']['title'] ?></div>
                            <div class="song-etc">
                                <span class="artist-name"><?= $song['artist']['name'] ?></span>
                                <span class="between-bar"></span>
                                <span class="album-name"><?= $song['album']['title'] ?></span>
                                <span class="between-bar"></span>
                                <span class="song-play-time"><?= $song['song']['play_time'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="icon-box">
                        <a href="/recommends?id=<?= $song['song']['flo_id'] ?>">
                            <svg viewBox="0 0 24 24">
                                <g>
                                    <path d="M18.77,11h-4.23l1.52-4.94C16.38,5.03,15.54,4,14.38,4c-0.58,0-1.14,0.24-1.52,0.65L7,11H3v10h4h1h9.43 c1.06,0,1.98-0.67,2.19-1.61l1.34-6C21.23,12.15,20.18,11,18.77,11z M7,20H4v-8h3V20z M19.98,13.17l-1.34,6 C18.54,19.65,18.03,20,17.43,20H8v-8.61l5.6-6.06C13.79,5.12,14.08,5,14.38,5c0.26,0,0.5,0.11,0.63,0.3 c0.07,0.1,0.15,0.26,0.09,0.47l-1.52,4.94L13.18,12h1.35h4.23c0.41,0,0.8,0.17,1.03,0.46C19.92,12.61,20.05,12.86,19.98,13.17z" class="style-scope yt-icon"></path>
                                </g>
                            </svg>
                        </a>
                        <a>
                            <svg viewBox="0 0 24 24">
                                <g>
                                    <path d="M16,6v2h-2v5c0,1.1-0.9,2-2,2s-2-0.9-2-2s0.9-2,2-2c0.37,0,0.7,0.11,1,0.28V6H16z M18,20H4V6H3v15h15V20z M21,3H6v15h15V3z M7,4h13v13H7V4z" class="style-scope yt-icon"></path>
                                </g>
                            </svg>
                        </a>
                        <a>
                            <svg viewBox="0 0 24 24">
                                <g>
                                    <path d="M22,10h-4v2v3.51C17.58,15.19,17.07,15,16.5,15c-1.38,0-2.5,1.12-2.5,2.5c0,1.38,1.12,2.5,2.5,2.5 c1.36,0,2.46-1.08,2.5-2.43V12h3V10z M3.06,19c0.38-3.11,2.61-6.1,7.94-6.1c0.62,0,1.19,0.05,1.73,0.13l0.84-0.84 c-0.58-0.13-1.19-0.23-1.85-0.26C13.58,11.59,15,9.96,15,8c0-2.21-1.79-4-4-4C8.79,4,7,5.79,7,8c0,1.96,1.42,3.59,3.28,3.93 C4.77,12.21,2,15.76,2,20h10.02L12,19H3.06z M8,8c0-1.65,1.35-3,3-3s3,1.35,3,3s-1.35,3-3,3S8,9.65,8,8z" class="style-scope yt-icon"></path>
                                </g>
                            </svg>
                        </a>
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

<script src="<?= $_SERVER['JS_PATH'] . 'search.js' ?>"></script>
<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>