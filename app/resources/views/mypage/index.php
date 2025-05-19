<?
$controller = new MypageController();
[
    'user_info' => $user_info,
    'artist_list' => $artist_list,
    'genre_list' => $genre_list,
    'song_list' => $song_list
] = $controller->index();

?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>

<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'mypage.css' ?>">

<div class="container">
    <a class="profile-header" href="/album/detail?id=<?= $user_info['profile_album_flo_id'] ?>">
        <div class="profile-background" style="background-image: url('<?= $user_info['profile_img_url'] ?>?size=1000x1000');"></div>
        <div class="profile-info">
            <? if ($genre_list) { ?>
                <p class="profile-genre"><?= array_keys($genre_list)[0] ?> 장르를 좋아하는</p>
            <? } ?>
            <p class="profile-name"><?= $user_info['nickname'] ?> 님</p>
            <p class="profile-stats">추천한 노래 <?= $user_info['recommend_count'] ?>개</p>
        </div>
    </a>
    <? if ($user_info['recommend_count'] > 0) { ?>
        <div class="mypage-content">
            <div class="stats-content">
                <div class="stats-grid">
                    <!-- 좋아하는 아티스트 섹션 -->
                    <div class="stats-column">
                        <h2 class="section-title">좋아하는 아티스트</h2>
                        <div class="artist-list">
                            <? foreach ($artist_list as $artist) { ?>
                                <a class="artist-card" href="/artist/detail?id=<?= $artist['flo_id'] ?>">
                                    <img src="<?= $artist['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_SMALL_SIZE']) ?>" alt="아티스트" class="artist-image">
                                    <div class="artist-info">
                                        <h3 class="artist-name"><?= $artist['name'] ?></h3>
                                        <p class="artist-followers">추천한 노래 <?= $artist['count'] ?>개</p>
                                    </div>
                                </a>
                            <? } ?>
                        </div>
                    </div>
                    <!-- 장르 차트 섹션 -->
                    <div class="stats-column">
                        <h2 class="section-title">좋아하는 장르</h2>
                        <div id="genreChart" class="chart-container"></div>
                    </div>
                </div>
            </div>
            <!-- 추천 노래 리스트 섹션 -->
            <div class="recommend-content">
                <h2 class="section-title">추천하는 노래</h2>
                <div class="songs-filter">
                    <div class="toggle-sort">
                        <span class="toggle-label latest active">최신순</span>
                        <label class="toggle-switch">
                            <input type="checkbox" id="sortToggle">
                            <span class="slider"></span>
                        </label>
                        <span class="toggle-label oldest">오래된순</span>
                    </div>
                    <div class="search-filter">
                        <input type="text" id="songSearch" placeholder="노래 검색">
                    </div>
                </div>
                <div class="songs-grid">
                    <? foreach ($song_list as $song) { ?>
                        <a class="song-card" href="/recommends/detail?id=<?= $song['recommend_id'] ?>">
                            <img src="<?= $song['album_img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_SMALL_SIZE']) ?>" alt="앨범커버" class="album-cover" loading="lazy">
                            <div class="song-info">
                                <h3 class="song-title"><?= $song['song_title'] ?></h3>
                                <p class="song-artist"><?= $song['artist_name'] ?></p>
                                <p class="recommend-date"><?= date('Y.m.d', strtotime($song['recommend_date'])) ?></p>
                            </div>
                            <!-- <div class="song-card-btn">
                                <button class="btn btn-submit">
                                    프로필 설정
                                </button>
                            </div> -->
                        </a>
                    <? } ?>
                </div>
            </div>
        </div>
    <? } else { ?>
        <!-- 스켈레톤 UI -->
        <div class="skeleton-content">
            <div class="stats-content">
                <div class="stats-grid">
                    <!-- 좋아하는 아티스트 섹션 -->
                    <div class="stats-column">
                        <div class="skeleton skeleton-section-title"></div>
                        <div class="artist-list">
                            <div class="skeleton skeleton-artist-card"></div>
                            <div class="skeleton skeleton-artist-card"></div>
                            <div class="skeleton skeleton-artist-card"></div>
                            <div class="skeleton skeleton-artist-card"></div>
                        </div>
                    </div>
                    <!-- 장르 차트 섹션 -->
                    <div class="stats-column">
                        <div class="skeleton skeleton-section-title"></div>
                        <div class="skeleton skeleton-chart"></div>
                    </div>
                </div>
            </div>
            <div class="skeleton-no-results">
                <p>활동이 없는 사용자입니다.</p>
            </div>
        </div>
    <? } ?>
</div>

<script src="<?= $_SERVER['JS_PATH'] . 'lib/echarts.min.js' ?>"></script>

<? if ($genre_list) { ?>
    <script src="<?= $_SERVER['JS_PATH'] . 'mypage.js' ?>"></script>
    <script>
        const genreList = <?= $genre_list ? json_encode($genre_list) : '[]' ?>;
    </script>
<? } ?>

<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>