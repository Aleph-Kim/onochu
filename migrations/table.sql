-- 기존 테이블 삭제
DROP TABLE IF EXISTS recommends;
DROP TABLE IF EXISTS songs;
DROP TABLE IF EXISTS albums;
DROP TABLE IF EXISTS artists;
DROP TABLE IF EXISTS users;
-- 유저 정보를 저장하는 테이블
CREATE TABLE `users` (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT '유저 고유 ID',
    username varchar(50) NOT NULL COMMENT '유저 이름',
    password varchar(255) NOT NULL COMMENT '유저 비밀번호',
    email varchar(100) NOT NULL COMMENT '유저 이메일',
    created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '업데이트 일시'
) COMMENT = '유저 정보를 저장하는 테이블';
-- 아티스트 정보를 저장하는 테이블
CREATE TABLE artists (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT '아티스트 고유 ID',
    name VARCHAR(100) NOT NULL COMMENT '아티스트 이름',
    img_url VARCHAR(255) COMMENT '아티스트 사진 URL',
    flo_id int UNIQUE KEY COMMENT 'FLO music에서 사용하는 고유 ID',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '업데이트 일시'
) COMMENT = '아티스트 정보를 저장하는 테이블';
-- 앨범 정보를 저장하는 테이블
CREATE TABLE albums (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT '앨범 고유 ID',
    artist_id INT NOT NULL COMMENT '연관 아티스트 ID',
    title VARCHAR(100) NOT NULL COMMENT '앨범 제목',
    release_date DATE COMMENT '발매일',
    img_url VARCHAR(255) COMMENT '앨범 커버 사진 URL',
    flo_id int UNIQUE KEY COMMENT 'FLO music에서 사용하는 고유 ID',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '업데이트 일시',
    FOREIGN KEY (artist_id) REFERENCES artists(id)
) COMMENT = '앨범 정보를 저장하는 테이블';
-- 노래 정보를 저장하는 테이블
CREATE TABLE songs (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT '노래 고유 ID',
    album_id INT NOT NULL COMMENT '연관 앨범 ID',
    artist_id INT NOT NULL COMMENT '연관 아티스트 ID',
    title VARCHAR(100) NOT NULL COMMENT '노래 제목',
    genre VARCHAR(50) COMMENT '장르 정보',
    lyrics TEXT COMMENT '노래 가사',
    composer VARCHAR(100) COMMENT '작곡가',
    lyricist VARCHAR(100) COMMENT '작사가',
    arranger VARCHAR(100) COMMENT '편곡가',
    flo_id int UNIQUE KEY COMMENT 'FLO music에서 사용하는 고유 ID',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '업데이트 일시',
    FOREIGN KEY (album_id) REFERENCES albums(id),
    FOREIGN KEY (artist_id) REFERENCES artists(id)
) COMMENT = '노래 정보를 저장하는 테이블';
-- 노래 추천 정보를 저장하는 테이블
CREATE TABLE recommends (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT '추천 정보 고유 ID',
    song_id INT NOT NULL COMMENT '추천 노래 ID',
    user_id INT NOT NULL COMMENT '추천 유저 ID',
    score INT DEFAULT 1 COMMENT '추천 점수 (1 ~ 5)' CHECK (
        score BETWEEN 1 AND 5
    ),
    comment TEXT COMMENT '추천 관련 추가 설명',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '업데이트 일시',
    FOREIGN KEY (song_id) REFERENCES songs(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
) COMMENT = '노래 추천 정보를 저장하는 테이블';