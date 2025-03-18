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
                $this->redis->expire($key, $ttl); // 만료 시간 설정
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
}
