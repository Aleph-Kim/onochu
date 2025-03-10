-- 기존 테이블 삭제
DROP TABLE IF EXISTS recommends;
DROP TABLE IF EXISTS songs;
DROP TABLE IF EXISTS albums;
DROP TABLE IF EXISTS artists;

-- 아티스트 정보를 저장하는 테이블
CREATE TABLE artists (
    artist_id INT AUTO_INCREMENT PRIMARY KEY COMMENT '아티스트 고유 ID',
    name VARCHAR(100) NOT NULL COMMENT '아티스트 이름',
    genre VARCHAR(50) COMMENT '장르 정보',
    photo_url VARCHAR(255) COMMENT '아티스트 사진 URL',
    flo_id int UNIQUE KEY COMMENT 'FLO music에서 사용하는 고유 ID',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시'
) COMMENT = '아티스트 정보를 저장하는 테이블';

-- 앨범 정보를 저장하는 테이블
CREATE TABLE albums (
    album_id INT AUTO_INCREMENT PRIMARY KEY COMMENT '앨범 고유 ID',
    artist_id INT NOT NULL COMMENT '연관 아티스트 ID (외래키)',
    title VARCHAR(100) NOT NULL COMMENT '앨범 제목',
    release_date DATE COMMENT '발매일',
    cover_photo_url VARCHAR(255) COMMENT '앨범 커버 사진 URL',
    flo_id int UNIQUE KEY COMMENT 'FLO music에서 사용하는 고유 ID',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    FOREIGN KEY (artist_id) REFERENCES artists(artist_id)
) COMMENT = '앨범 정보를 저장하는 테이블';

-- 노래 정보를 저장하는 테이블
CREATE TABLE songs (
    song_id INT AUTO_INCREMENT PRIMARY KEY COMMENT '노래 고유 ID',
    album_id INT NOT NULL COMMENT '연관 앨범 ID (외래키)',
    artist_id INT NOT NULL COMMENT '연관 아티스트 ID (외래키)',
    title VARCHAR(100) NOT NULL COMMENT '노래 제목',
    release_date DATE COMMENT '발매일',
    flo_id int UNIQUE KEY COMMENT 'FLO music에서 사용하는 고유 ID',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    FOREIGN KEY (album_id) REFERENCES albums(album_id),
    FOREIGN KEY (artist_id) REFERENCES artists(artist_id)
) COMMENT = '노래 정보를 저장하는 테이블';

-- 노래 추천 정보를 저장하는 테이블
CREATE TABLE recommends (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT '추천 정보 고유 ID',
    song_id INT NOT NULL COMMENT '원본 노래 ID (외래키)',
    recommended_song_id INT NOT NULL COMMENT '추천된 노래 ID (외래키)',
    score INT DEFAULT 1 COMMENT '추천 점수 (1 ~ 5)' CHECK (score BETWEEN 1 AND 5),
    comment TEXT COMMENT '추천 관련 추가 설명',
    flo_id int UNIQUE KEY COMMENT 'FLO music에서 사용하는 고유 ID',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성 일시',
    FOREIGN KEY (song_id) REFERENCES songs(song_id),
    FOREIGN KEY (recommended_song_id) REFERENCES songs(song_id)
) COMMENT = '노래 추천 정보를 저장하는 테이블';
