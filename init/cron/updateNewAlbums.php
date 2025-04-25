<?php

/**
 * 추천을 1번 이상 받은 아티스트의 새 앨범을 new_albums 테이블에 저장하는 크론 스크립트
 * 
 * 실행 주기: 매일 12시, 18시
 */
class UpdateNewAlbums extends Cron
{
    protected $recommends_model;
    protected $new_albums_model;
    protected $artists_model;
    protected $new_album_artists_model;
    protected $flo_api;

    /**
     * 초기화 메서드
     */
    public function __construct()
    {
        parent::__construct();
        $this->log("UpdateNewAlbums 크론 실행");

        // 모델 초기화
        $this->recommends_model = $this->model('Recommends');
        $this->new_albums_model = $this->model('NewAlbums');
        $this->artists_model = $this->model('Artists');
        $this->new_album_artists_model = $this->model('NewAlbumArtists');

        $this->flo_api = new FloApiHelper();
    }

    /**
     * 실제 크론 작업 수행
     */
    public function execute()
    {
        try {
            // 추천을 받은 아티스트 목록 가져오기
            $artists = $this->artists_model->getRecommendArtists();
            $this->log("추천 아티스트 수: " . count($artists));

            $new_albums = $this->getRecommendedArtistsNewAlbums($artists);
            $this->log("새 앨범 수: " . count($new_albums));

            $this->saveNewAlbums($new_albums);
            $this->log("UpdateNewAlbums 크론 완료");
        } catch (Exception $e) {
            $this->errorLog("UpdateNewAlbums 크론 작업 중 오류 발생", $e);
            exit(1);
        }
    }

    /**
     * 새 앨범 데이터 조회
     * 
     * @return array 앨범 정보와 아티스트 id를 포함한 배열
     */
    private function getNewAlbumData()
    {
        try {
            $kpop_data = $this->flo_api->getNewKpopAlbum();
            $pop_data = $this->flo_api->getNewPopAlbum();

            $this->log("K-POP 새 앨범 수: " . count($kpop_data['albums_info']));
            $this->log("POP 새 앨범 수: " . count($pop_data['albums_info']));

            return [
                'albums_info' => $kpop_data['albums_info'] + $pop_data['albums_info'],
                'artists_flo_id' => $kpop_data['artists_flo_id'] + $pop_data['artists_flo_id']
            ];
        } catch (Exception $e) {
            $this->errorLog("새 앨범 데이터 조회 중 오류 발생", $e);
            exit(1);
        }
    }

    /**
     * 추천 받은 적 있는 아티스트의 새 앨범 정보 조회
     * 
     * @param array $artists 사용자 관심 아티스트 또는 인기 아티스트 정보
     * @return array 새 앨범 정보 배열
     */
    private function getRecommendedArtistsNewAlbums($artists)
    {
        try {
            $new_albums = [];
            $new_album_flo_ids = [];
            $new_album_data = $this->getNewAlbumData();

            // 사용자 관심 아티스트 id 추출
            $artists_flo_id = array_column($artists, 'flo_id');

            // 새 앨범을 발매한 아티스트와 사용자 관심 아티스트의 교집합 id 추출
            $matching_artist_ids = array_intersect(array_keys($new_album_data['artists_flo_id']), $artists_flo_id);
            $this->log("매칭된 아티스트 수: " . count($matching_artist_ids));

            // 교집합 아티스트 id를 키로 사용하여 새 앨범 정보를 추가
            foreach ($matching_artist_ids as $artist_id) {
                $new_album = $new_album_data['albums_info'][$new_album_data['artists_flo_id'][$artist_id]];
                if (!in_array($new_album['flo_id'], $new_album_flo_ids)) {
                    $new_albums[] = $new_album;
                    $new_album_flo_ids[] = $new_album['flo_id'];
                }
            }

            return $new_albums;
        } catch (Exception $e) {
            $this->errorLog("추천 아티스트 새 앨범 조회 중 오류 발생", $e);
            exit(1);
        }
    }

    /**
     * 새 앨범을 데이터베이스에 저장하는 함수
     */
    private function saveNewAlbums($albums)
    {
        try {
            $saved_count = 0;
            foreach ($albums as $album) {
                if ($this->new_albums_model->getByFloId($album['flo_id'])) {
                    continue;
                }
                
                // new_albums 테이블에 저장
                $albumData = [
                    'album_title' => $album['title'],
                    'album_img_url' => $album['img_url'],
                    'flo_id' => $album['flo_id'],
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $albumId = $this->new_albums_model->insert($albumData);
                $saved_count++;

                // new_album_artists 테이블에 저장
                foreach ($album['artist'] as $artist) {
                    $artistData = [
                        'new_album_id' => $albumId,
                        'artist_name' => $artist['name'],
                        'flo_id' => $artist['flo_id']
                    ];

                    $this->new_album_artists_model->insert($artistData);
                }
            }
            $this->log("저장된 새 앨범 수: " . $saved_count);
        } catch (Exception $e) {
            $this->errorLog("새 앨범 저장 중 오류 발생", $e);
            exit(1);
        }
    }
}
