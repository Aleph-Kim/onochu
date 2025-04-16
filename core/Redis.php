<?

use Predis\Client;

class Redis
{
    private $redis;

    public function __construct()
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host'   => getenv("REDIS_HOST"),
            'port'   => getenv("REDIS_PORT"),
            'username' => getenv("REDIS_USERNAME"),
            'password' => getenv("REDIS_PASSWORD"),
        ]);
    }

    public function getRedis()
    {
        return $this->redis;
    }

    public function set($key, $value, $ttl = 86400)
    {
        try {
            // 배열일 경우 JSON으로 변환
            $storeValue = is_array($value) ? json_encode($value) : $value;
            $result = $this->redis->set($key, $storeValue);
            if ($ttl) {
                $this->redis->expire($key, $this->resolveTtl($ttl)); // 만료 시간 설정
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception("SET 오류: " . $e->getMessage());
        }
    }

    public function get($key)
    {
        try {
            $value = $this->redis->get($key);
            // JSON 형식일 경우 배열로 디코딩
            $decoded = json_decode($value, true);
            return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : $value;
        } catch (Exception $e) {
            throw new Exception("GET 오류: " . $e->getMessage());
        }
    }

    public function delete($key)
    {
        try {
            return $this->redis->del($key);
        } catch (Exception $e) {
            throw new Exception("DELETE 오류: " . $e->getMessage());
        }
    }

    public function close()
    {
        $this->redis->disconnect();
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * 현재 시간에서 다음 시 정각까지 남은 초 계산
     *
     * @return int 다음 정각까지 남은 초 수
     */
    private function calculateTtlToNextHour(): int
    {
        $now = new DateTime();
        // 다음 정각 시간 계산
        $nextHour = (clone $now)->setTime($now->format('H') + 1, 0, 0);
        // 남은 초 계산
        $ttl = $nextHour->getTimestamp() - $now->getTimestamp();
        return $ttl;
    }

    /**
     * TTL 값을 해석하여 초 단위로 반환
     *
     * @param mixed $ttl TTL 값
     * @return int 초 단위 TTL
     */
    private function resolveTtl($ttl)
    {
        return $ttl === "next-hour" ? $this->calculateTtlToNextHour() : $ttl;
    }
}
