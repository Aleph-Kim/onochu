<?
// $controller = new MypageController();
// $mypage_info = $controller->index();
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>

<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'mypage.css' ?>">

<div class="container">
    <div class="profile-header">
        <div class="profile-background" style="background-image: url('https://img.aleph.kr/files/onochu_dev/album-6823f78a68d6b.jpg?size=1000x1000');">
        </div>
        <div class="profile-info">
            <p class="profile-name">김채민</p>
            <p class="profile-stats">추천한 노래 123개</p>
        </div>
    </div>
    <div class="mypage-content">
        <div class="stats-content">
            <div class="stats-grid">
                <!-- 좋아하는 아티스트 섹션 -->
                <div class="stats-column">
                    <h2 class="section-title">좋아하는 아티스트</h2>
                    <div class="artist-list">
                        <a class="artist-card" href="/artist/detail?id=12345">
                            <img src="https://readdy.ai/api/search-image?query=portrait%20of%20a%20Korean%20female%20singer%20with%20long%20black%20hair%2C%20professional%20studio%20lighting%2C%20white%20background%2C%20high%20fashion&width=64&height=64&seq=133&orientation=squarish" alt="아티스트" class="artist-image">
                            <div class="artist-info">
                                <h3 class="artist-name">아이유</h3>
                                <p class="artist-followers">추천한 노래 123개</p>
                            </div>
                        </a>
                        <a class="artist-card" href="/artist/detail?id=12345">
                            <img src="https://readdy.ai/api/search-image?query=portrait%20of%20a%20Korean%20female%20singer%20with%20long%20black%20hair%2C%20professional%20studio%20lighting%2C%20white%20background%2C%20high%20fashion&width=64&height=64&seq=133&orientation=squarish" alt="아티스트" class="artist-image">
                            <div class="artist-info">
                                <h3 class="artist-name">아이유</h3>
                                <p class="artist-followers">추천한 노래 123개</p>
                            </div>
                        </a>
                        <a class="artist-card" href="/artist/detail?id=12345">
                            <img src="https://readdy.ai/api/search-image?query=portrait%20of%20a%20Korean%20female%20singer%20with%20long%20black%20hair%2C%20professional%20studio%20lighting%2C%20white%20background%2C%20high%20fashion&width=64&height=64&seq=133&orientation=squarish" alt="아티스트" class="artist-image">
                            <div class="artist-info">
                                <h3 class="artist-name">아이유</h3>
                                <p class="artist-followers">추천한 노래 123개</p>
                            </div>
                        </a>
                        <a class="artist-card" href="/artist/detail?id=12345">
                            <img src="https://readdy.ai/api/search-image?query=portrait%20of%20a%20Korean%20female%20singer%20with%20long%20black%20hair%2C%20professional%20studio%20lighting%2C%20white%20background%2C%20high%20fashion&width=64&height=64&seq=133&orientation=squarish" alt="아티스트" class="artist-image">
                            <div class="artist-info">
                                <h3 class="artist-name">아이유</h3>
                                <p class="artist-followers">추천한 노래 123개</p>
                            </div>
                        </a>
                        <a class="artist-card" href="/artist/detail?id=12345">
                            <img src="https://readdy.ai/api/search-image?query=portrait%20of%20a%20Korean%20female%20singer%20with%20long%20black%20hair%2C%20professional%20studio%20lighting%2C%20white%20background%2C%20high%20fashion&width=64&height=64&seq=133&orientation=squarish" alt="아티스트" class="artist-image">
                            <div class="artist-info">
                                <h3 class="artist-name">아이유</h3>
                                <p class="artist-followers">추천한 노래 123개</p>
                            </div>
                        </a>
                        <a class="artist-card" href="/artist/detail?id=12345">
                            <img src="https://readdy.ai/api/search-image?query=portrait%20of%20a%20Korean%20female%20singer%20with%20long%20black%20hair%2C%20professional%20studio%20lighting%2C%20white%20background%2C%20high%20fashion&width=64&height=64&seq=133&orientation=squarish" alt="아티스트" class="artist-image">
                            <div class="artist-info">
                                <h3 class="artist-name">아이유</h3>
                                <p class="artist-followers">추천한 노래 123개</p>
                            </div>
                        </a>
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
                <a class="song-card" href="/recommends/detail?id=12345">
                    <img src="https://readdy.ai/api/search-image?query=artistic%20album%20cover%20with%20dreamy%20watercolor%20effects%2C%20soft%20lighting%2C%20ethereal%20mood&width=80&height=80&seq=141&orientation=squarish" alt="앨범커버" class="album-cover">
                    <div class="song-info">
                        <h3 class="song-title">밤편지</h3>
                        <p class="song-artist">아이유</p>
                        <p class="recommend-date">2025.05.16</p>
                    </div>
                    <div>
                        <button class="btn btn-submit">
                            프로필 설정
                        </button>
                    </div>
                </a>
                <a class="song-card" href="/recommends/detail?id=12345">
                    <img src="https://readdy.ai/api/search-image?query=artistic%20album%20cover%20with%20dreamy%20watercolor%20effects%2C%20soft%20lighting%2C%20ethereal%20mood&width=80&height=80&seq=141&orientation=squarish" alt="앨범커버" class="album-cover">
                    <div class="song-info">
                        <h3 class="song-title">밤편지</h3>
                        <p class="song-artist">아이유</p>
                        <p class="recommend-date">2025.05.16</p>
                    </div>
                    <div>
                        <button class="btn btn-submit">
                            프로필 설정
                        </button>
                    </div>
                </a>
                <a class="song-card" href="/recommends/detail?id=12345">
                    <img src="https://readdy.ai/api/search-image?query=artistic%20album%20cover%20with%20dreamy%20watercolor%20effects%2C%20soft%20lighting%2C%20ethereal%20mood&width=80&height=80&seq=141&orientation=squarish" alt="앨범커버" class="album-cover">
                    <div class="song-info">
                        <h3 class="song-title">밤편지</h3>
                        <p class="song-artist">아이유</p>
                        <p class="recommend-date">2025.05.16</p>
                    </div>
                    <div>
                        <button class="btn btn-submit">
                            프로필 설정
                        </button>
                    </div>
                </a>
                <a class="song-card" href="/recommends/detail?id=12345">
                    <img src="https://readdy.ai/api/search-image?query=artistic%20album%20cover%20with%20dreamy%20watercolor%20effects%2C%20soft%20lighting%2C%20ethereal%20mood&width=80&height=80&seq=141&orientation=squarish" alt="앨범커버" class="album-cover">
                    <div class="song-info">
                        <h3 class="song-title">밤편지</h3>
                        <p class="song-artist">아이유</p>
                        <p class="recommend-date">2025.05.16</p>
                    </div>
                    <div>
                        <button class="btn btn-submit">
                            프로필 설정
                        </button>
                    </div>
                </a>
                <a class="song-card" href="/recommends/detail?id=12345">
                    <img src="https://readdy.ai/api/search-image?query=artistic%20album%20cover%20with%20dreamy%20watercolor%20effects%2C%20soft%20lighting%2C%20ethereal%20mood&width=80&height=80&seq=141&orientation=squarish" alt="앨범커버" class="album-cover">
                    <div class="song-info">
                        <h3 class="song-title">밤편지</h3>
                        <p class="song-artist">아이유</p>
                        <p class="recommend-date">2025.05.15</p>
                    </div>
                    <div>
                        <button class="btn btn-submit">
                            프로필 설정
                        </button>
                    </div>
                </a>
            </div>
        </div>
    </div>
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
</div>

<script src="<?= $_SERVER['JS_PATH'] . 'lib/echarts.min.js' ?>"></script>
<script src="<?= $_SERVER['JS_PATH'] . 'mypage.js' ?>"></script>

<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>