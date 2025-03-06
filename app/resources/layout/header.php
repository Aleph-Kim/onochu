<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8" />
    <title>Onochu - 오늘의 노래 추천</title>
    <meta name="viewport" content="width=device-width, initial-scale=1>
    <link rel="apple-touch-icon" sizes="57x57" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/apple-icon-57x57.png' ?>">
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
    <link rel="manifest" href="<?= $_SERVER['IMAGE_PATH'] . 'favicon/manifest.json' ?>">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'reset.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?= $_SERVER['CSS_PATH'] . 'layout.css' ?>">
</head>

<body>
    <!-- 헤더 영역 시작 -->
    <header class="header">
        <div class="header_inner">
            <!-- 로고 -->
            <a href="/" class="logo">
                <img src="<?= $_SERVER['IMAGE_PATH'] . 'logo.svg' ?>">
                Onochu
            </a>
            <!-- 검색 -->
            <form class="search" action="/search">
                <input type="text" name="q" placeholder="검색어를 입력하세요" value="<?= $_GET['q'] ?>" />
                <button class="btn-search">
                    <img src="<?= $_SERVER['IMAGE_PATH'] . 'icon/magnifier.svg' ?>">
                </button>
            </form>
            <!-- 유저 메뉴 -->
            <div class="user-menu">
                <button type="button" class="btn-login">로그인</button>
            </div>
        </div>
    </header>
    <!-- 헤더 영역 끝 -->

    <main>