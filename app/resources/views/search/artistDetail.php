<?
$controller = new SearchController();
?>

<? include $_SERVER['LAYOUT_PATH'] . "header.php"; ?>
<link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'artistDetail.css' ?>">

<div class="main-content">
    <div class="profile">
        <img src="https://cdn.music-flo.com/image/v2/album/010/887/05/04/405887010_6041b9ef_s.jpg?1614920177177/dims/resize/500x500/quality/90" alt="DINOKID">
        <div>
            <div class="profile-name">DINOKID</div>
            <div class="profile-info">
                <span>솔로</span>
                <span class="between-bar"></span>
                <span>신스팝, 인디 포크, 알앤비, 인디록</span>
            </div>
        </div>
    </div>

    <div class="albums-title">최근 앨범</div>
    <!-- 앨범 리스트 -->
    <div class="albums">
        <!-- 앨범 1 -->
        <div class="album">
            <div class="play-button">
                <img src="https://cdn.music-flo.com/image/v2/album/010/887/05/04/405887010_6041b9ef_s.jpg?1614920177177/dims/resize/500x500/quality/90" alt="ㅋㅋ">
            </div>
            <div class="album-info">
                <div class="album-title">ㅋㅋ</div>
                <div class="album-artist">DINOKID</div>
                <div class="album-meta">싱글<br>2022.09.28</div>
            </div>
        </div>

        <!-- 앨범 2 -->
        <div class="album">
            <div class="play-button">
                <img src="https://cdn.music-flo.com/image/v2/album/010/887/05/04/405887010_6041b9ef_s.jpg?1614920177177/dims/resize/500x500/quality/90" alt="Know">
            </div>
            <div class="album-info">
                <div class="album-title">Know</div>
                <div class="album-artist">DINOKID</div>
                <div class="album-meta">싱글<br>2022.02.23</div>
            </div>
        </div>

        <!-- 앨범 3 -->
        <div class="album">
            <div class="play-button">
                <img src="https://cdn.music-flo.com/image/v2/album/010/887/05/04/405887010_6041b9ef_s.jpg?1614920177177/dims/resize/500x500/quality/90" alt="ON & ON">
            </div>
            <div class="album-info">
                <div class="album-title">ON & ON</div>
                <div class="album-artist">DINOKID</div>
                <div class="album-meta">싱글<br>2021.10.21</div>
            </div>
        </div>

        <!-- 앨범 4 -->
        <div class="album">
            <div class="play-button">
                <img src="https://cdn.music-flo.com/image/v2/album/010/887/05/04/405887010_6041b9ef_s.jpg?1614920177177/dims/resize/500x500/quality/90" alt="STAY WITH ME">
            </div>
            <div class="album-info">
                <div class="album-title">STAY WITH ME</div>
                <div class="album-artist">DINOKID</div>
                <div class="album-meta">미니<br>2021.04.14</div>
            </div>
        </div>

        <!-- 앨범 5 -->
        <div class="album">
            <div class="play-button">
                <img src="https://cdn.music-flo.com/image/v2/album/010/887/05/04/405887010_6041b9ef_s.jpg?1614920177177/dims/resize/500x500/quality/90" alt="Without">
            </div>
            <div class="album-info">
                <div class="album-title">Without</div>
                <div class="album-artist">DINOKID</div>
                <div class="album-meta">싱글<br>2020.10.30</div>
            </div>
        </div>
    </div>
</div>

<? include $_SERVER['LAYOUT_PATH'] . "footer.php"; ?>