<?

use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class CacheHelper
{
    private static ?AdapterInterface $cache = null;

    /**
     * 캐시 어댑터 초기화 (최초 호출 시 설정)
     * @param AdapterInterface|null $adapter 커스텀 어댑터, 기본값은 ArrayAdapter
     */
    public static function init(?AdapterInterface $adapter = null): void
    {
        if (self::$cache === null) {
            self::$cache = $adapter ?? new ArrayAdapter();
        }
    }

    /**
     * 캐시 어댑터 반환
     * @return AdapterInterface
     */
    private static function getCache(): AdapterInterface
    {
        if (self::$cache === null) {
            self::init(); // 어댑터가 없으면 기본 초기화
        }
        return self::$cache;
    }

    /**
     * 캐시에 데이터 생성(Create)
     * @param string $key 캐시 키
     * @param mixed $value 저장할 값
     * @param int|null $ttl 캐시 만료 시간(초 단위), 기본값: 24시간
     * @return bool 성공 여부
     */
    public static function create(string $key, $value, ?int $ttl = 86400): bool
    {
        $item = self::getCache()->getItem($key);
        $item->set($value);
        if ($ttl !== null) {
            $item->expiresAfter($ttl);
        }
        return self::getCache()->save($item);
    }

    /**
     * 캐시에서 데이터 조회(Read)
     * @param string $key 캐시 키
     * @param mixed $default 키가 없을 때 반환할 기본값
     * @return mixed 캐시된 값 또는 기본값
     */
    public static function read(string $key, $default = null)
    {
        $item = self::getCache()->getItem($key);
        return $item->isHit() ? $item->get() : $default;
    }

    /**
     * 캐시 데이터 업데이트(Update)
     * @param string $key 캐시 키
     * @param mixed $value 업데이트할 값
     * @param int|null $ttl 캐시 만료 시간(초 단위), null이면 기존 유지
     * @return bool 성공 여부
     */
    public static function update(string $key, $value, ?int $ttl = null): bool
    {
        if (!self::getCache()->hasItem($key)) {
            return false; // 키가 없으면 실패
        }
        $item = self::getCache()->getItem($key);
        $item->set($value);
        if ($ttl !== null) {
            $item->expiresAfter($ttl);
        }
        return self::getCache()->save($item);
    }

    /**
     * 캐시 데이터 삭제(Delete)
     * @param string $key 캐시 키
     * @return bool 성공 여부
     */
    public static function delete(string $key): bool
    {
        return self::getCache()->deleteItem($key);
    }

    /**
     * 키 존재 여부 확인
     * @param string $key 캐시 키
     * @return bool 키가 존재하면 true
     */
    public static function exists(string $key): bool
    {
        return self::getCache()->hasItem($key);
    }

    /**
     * 모든 캐시 데이터 삭제
     * @return bool 성공 여부
     */
    public static function clear(): bool
    {
        return self::getCache()->clear();
    }
}