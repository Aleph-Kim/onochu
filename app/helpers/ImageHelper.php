<?

/**
 * 이미지 업로드 및 관련 작업을 처리하는 헬퍼 클래스
 */
class ImageHelper
{
    /**
     * cURL을 통해 외부 서버에 요청을 보내는 범용 함수
     * 
     * @param string $url 요청 대상 URL
     * @param string $method HTTP 메서드
     * @param array $post_fields 필드 데이터 (없으면 null)
     * @param array $headers HTTP 헤더 배열
     * @return boolean 성공 시 true, 실패 시 false
     */
    private static function sendCurlRequest($url, $method, $post_fields = null, $headers = [])
    {
        $ch = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ];

        switch (strtoupper($method)) {
            case 'POST':
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = $post_fields;
                break;
            default:
                error_log("지원되지 않는 HTTP 메서드: $method");
                return null;
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 201 || $http_code == 200) {
            return true;
        }

        error_log("$method 요청 실패: HTTP 상태 코드 $http_code, 응답: " . $response);
        return false;
    }

    /**
     * 이미지를 업로드하고 URL을 반환
     * 
     * @param string $image_url 원본 이미지 URL
     * @param string $image_type 이미지 타입(앨범, 아티스트)
     * @return string|null 업로드된 이미지 URL 또는 실패 시 null
     */
    public static function uploadImage($image_url, $image_type = "album")
    {
        $max_file_size = 10 * 1024 * 1024; // 파일 크기 10MB 제한

        // 이미지 다운로드
        $image_content = file_get_contents($image_url);
        if ($image_content === false) {
            error_log("이미지 다운로드 실패: $image_url");
            return null;
        }

        // 파일 크기 체크
        if (strlen($image_content) > $max_file_size) {
            error_log("이미지 크기가 10MB를 초과했습니다: " . strlen($image_content) . " bytes");
            return null;
        }

        // 파일명 생성
        $img_id = uniqid();
        $file_name = "{$image_type}-{$img_id}.jpg";

        // POST 필드 설정
        $post_fields = [
            'file' => new CURLFile(
                'data://application/octet-stream;base64,' . base64_encode($image_content),
                'image/jpeg',
                $file_name
            ),
            'filename' => $file_name
        ];

        // 헤더 설정
        $headers = [
            'X-Username: ' . getenv('IMG_SERVER_USERNAME'),
            'X-Secret: ' . getenv('IMG_SERVER_SECRET'),
            'Content-Type: multipart/form-data'
        ];

        // cURL로 업로드
        if (self::sendCurlRequest($_SERVER['IMG_SERVER_PATH'], 'POST', $post_fields, $headers)) {
            return $_SERVER['IMG_SERVER_PATH'] . '/' . getenv('IMG_SERVER_USERNAME') . "/{$file_name}";
        }

        return null;
    }
}
