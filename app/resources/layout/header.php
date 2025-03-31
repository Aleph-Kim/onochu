<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8" />
    <title>Onochu - 오늘의 노래 추천</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0" />
    <link rel=" apple-touch-icon" sizes="57x57" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/apple-icon-57x57.png' ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/apple-icon-60x60.png' ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/apple-icon-72x72.png' ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/apple-icon-76x76.png' ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/apple-icon-114x114.png' ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/apple-icon-120x120.png' ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/apple-icon-144x144.png' ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/apple-icon-152x152.png' ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/apple-icon-180x180.png' ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/android-icon-192x192.png' ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/favicon-32x32.png' ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/favicon-96x96.png' ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/favicon-16x16.png' ?>">
    <link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'reset.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'layout.css' ?>">
</head>

<body>
    <!-- 헤더 영역 시작 -->
    <header class="header">
        <div class="header_inner">
            <!-- 로고 -->
            <a id="logo" href="/">
                <img src="<?= $_SERVER['IMAGE_PATH'] . 'logo.svg' ?>">
                Onochu
            </a>

            <div id="searchFormWrap">
                <button id="searchFormHideBtn" onclick="hiddenSearchForm()">
                    <svg viewBox="0 0 24 24">
                        <g mirror-in-rtl="">
                            <path d="M21,11v1H5.64l6.72,6.72l-0.71,0.71L3.72,11.5l7.92-7.92l0.71,0.71L5.64,11H21z" class="style-scope yt-icon"></path>
                        </g>
                    </svg>
                </button>

                <!-- 검색 -->
                <form id="searchForm" action="/search">
                    <input type="text" name="q" placeholder="검색어를 입력하세요" value="<?= $_GET['q'] ?>" required />
                    <button class="btn-search">
                        <svg viewBox="0 0 24 24">
                            <g>
                                <path d="M20.87,20.17l-5.59-5.59C16.35,13.35,17,11.75,17,10c0-3.87-3.13-7-7-7s-7,3.13-7,7s3.13,7,7,7c1.75,0,3.35-0.65,4.58-1.71 l5.59,5.59L20.87,20.17z M10,16c-3.31,0-6-2.69-6-6s2.69-6,6-6s6,2.69,6,6S13.31,16,10,16z"></path>
                            </g>
                        </svg>
                    </button>
                </form>
            </div>

            <div id="rightBtnBox">
                <button class="mobile-btn-search" onclick="showSearchForm()">
                    <svg viewBox="0 0 24 24">
                        <g>
                            <path d="M20.87,20.17l-5.59-5.59C16.35,13.35,17,11.75,17,10c0-3.87-3.13-7-7-7s-7,3.13-7,7s3.13,7,7,7c1.75,0,3.35-0.65,4.58-1.71 l5.59,5.59L20.87,20.17z M10,16c-3.31,0-6-2.69-6-6s2.69-6,6-6s6,2.69,6,6S13.31,16,10,16z"></path>
                        </g>
                    </svg>
                </button>

                <!-- 유저 메뉴 -->
                <div class="user-menu">
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="/auth/logout" type="button" class="btn">로그아웃</a>
                    <?php else: ?>
                        <a href="/login" type="button" class="btn">로그인</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <!-- 헤더 영역 끝 -->

    <main>