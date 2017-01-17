<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 2016/9/23
 * Time: 14:34
 */

namespace common\components\redis;
use yii\base\Component;

/**
 * Redis 是对 \Redis(https://github.com/ukko/phpredis-phpdoc) 的一个包装，以实现一个与 Yii2 编码风格一致的组件
 * Class RedisProxy
 *
 * @method mixed psetex($key, $ttl, $value)
 * @method mixed sScan($key, $iterator, $pattern = '', $count = 0)
 * @method mixed scan($iterator, $pattern = '', $count = 0)
 * @method mixed zScan($key, $iterator, $pattern = '', $count = 0)
 * @method mixed hScan($key, $iterator, $pattern = '', $count = 0)
 * @method mixed client($command, $arg = '')
 * @method mixed slowlog($command)
 * @method mixed close()
 * @method mixed setOption($name, $value)
 * @method mixed getOption($name)
 * @method mixed ping()
 * @method mixed get($key)
 * @method mixed set($key, $value, $timeout = 0)
 * @method mixed setex($key, $ttl, $value)
 * @method mixed setnx($key, $value)
 * @method mixed del($key1, $key2 = NULL, $key3 = NULL)
 * @method mixed delete($key1, $key2 = NULL, $key3 = NULL)
 * @method mixed multi()
 * @method mixed exec()
 * @method mixed discard()
 * @method mixed watch($key)
 * @method mixed unwatch()
 * @method mixed subscribe($channels, $callback)
 * @method mixed psubscribe($patterns, $callback)
 * @method mixed publish($channel, $message)
 * @method mixed exists($key)
 * @method mixed incr($key)
 * @method mixed incrByFloat($key, $increment)
 * @method mixed incrBy($key, $value)
 * @method mixed decr($key)
 * @method mixed decrBy($key, $value)
 * @method mixed getMultiple($keys)
 * @method mixed lPush($key, $value1, $value2 = NULL, $valueN = NULL)
 * @method mixed rPush($key, $value1, $value2 = NULL, $valueN = NULL)
 * @method mixed lPushx($key, $value)
 * @method mixed rPushx($key, $value)
 * @method mixed lPop($key)
 * @method mixed rPop($key)
 * @method mixed blPop($keys)
 * @method mixed brPop($keys)
 * @method mixed lLen($key)
 * @method mixed lSize($key)
 * @method mixed lIndex($key, $index)
 * @method mixed lGet($key, $index)
 * @method mixed lSet($key, $index, $value)
 * @method mixed lRange($key, $start, $end)
 * @method mixed lGetRange($key, $start, $end)
 * @method mixed lTrim($key, $start, $stop)
 * @method mixed listTrim($key, $start, $stop)
 * @method mixed lRem($key, $value, $count)
 * @method mixed lRemove($key, $value, $count)
 * @method mixed lInsert($key, $position, $pivot, $value)
 * @method mixed sAdd($key, $value1, $value2 = NULL, $valueN = NULL)
 * @method mixed sAddArray($key, $values)
 * @method mixed sRem($key, $member1, $member2 = NULL, $memberN = NULL)
 * @method mixed sRemove($key, $member1, $member2 = NULL, $memberN = NULL)
 * @method mixed sMove($srcKey, $dstKey, $member)
 * @method mixed sIsMember($key, $value)
 * @method mixed sContains($key, $value)
 * @method mixed sCard($key)
 * @method mixed sPop($key)
 * @method mixed sRandMember($key)
 * @method mixed sInter($key1, $key2, $keyN = NULL)
 * @method mixed sInterStore($dstKey, $key1, $key2, $keyN = NULL)
 * @method mixed sUnion($key1, $key2, $keyN = NULL)
 * @method mixed sUnionStore($dstKey, $key1, $key2, $keyN = NULL)
 * @method mixed sDiff($key1, $key2, $keyN = NULL)
 * @method mixed sDiffStore($dstKey, $key1, $key2, $keyN = NULL)
 * @method mixed sMembers($key)
 * @method mixed sGetMembers($key)
 * @method mixed getSet($key, $value)
 * @method mixed randomKey()
 * @method mixed select($dbindex)
 * @method mixed move($key, $dbindex)
 * @method mixed rename($srcKey, $dstKey)
 * @method mixed renameKey($srcKey, $dstKey)
 * @method mixed renameNx($srcKey, $dstKey)
 * @method mixed expire($key, $ttl)
 * @method mixed pExpire($key, $ttl)
 * @method mixed setTimeout($key, $ttl)
 * @method mixed expireAt($key, $timestamp)
 * @method mixed pExpireAt($key, $timestamp)
 * @method mixed keys($pattern)
 * @method mixed getKeys($pattern)
 * @method mixed dbSize()
 * @method mixed auth($password)
 * @method mixed bgrewriteaof()
 * @method mixed slaveof($host = '127.0.0.1', $port = 6379)
 * @method mixed object($string = '', $key = '')
 * @method mixed save()
 * @method mixed bgsave()
 * @method mixed lastSave()
 * @method mixed type($key)
 * @method mixed append($key, $value)
 * @method mixed getRange($key, $start, $end)
 * @method mixed substr($key, $start, $end)
 * @method mixed setRange($key, $offset, $value)
 * @method mixed strlen($key)
 * @method mixed getBit($key, $offset)
 * @method mixed setBit($key, $offset, $value)
 * @method mixed bitCount($key)
 * @method mixed bitOp($operation, $retKey, $keys)
 * @method mixed flushDB()
 * @method mixed flushAll()
 * @method mixed sort($key, $option = NULL)
 * @method mixed info($option = NULL)
 * @method mixed resetStat()
 * @method mixed ttl($key)
 * @method mixed pttl($key)
 * @method mixed persist($key)
 * @method mixed mset($array)
 * @method mixed mget($array)
 * @method mixed msetnx($array)
 * @method mixed rpoplpush($srcKey, $dstKey)
 * @method mixed brpoplpush($srcKey, $dstKey, $timeout)
 * @method mixed zAdd($key, $score1, $value1, $score2 = NULL, $value2 = NULL, $scoreN = NULL, $valueN = NULL)
 * @method mixed zRange($key, $start, $end, $withscores = NULL)
 * @method mixed zRem($key, $member1, $member2 = NULL, $memberN = NULL)
 * @method mixed zDelete($key, $member1, $member2 = NULL, $memberN = NULL)
 * @method mixed zRevRange($key, $start, $end, $withscore = NULL)
 * @method mixed zRangeByScore($key, $start, $end, $options = array())
 * @method mixed zRevRangeByScore($key, $start, $end, $options = array())
 * @method mixed zCount($key, $start, $end)
 * @method mixed zRemRangeByScore($key, $start, $end)
 * @method mixed zDeleteRangeByScore($key, $start, $end)
 * @method mixed zRemRangeByRank($key, $start, $end)
 * @method mixed zDeleteRangeByRank($key, $start, $end)
 * @method mixed zCard($key)
 * @method mixed zSize($key)
 * @method mixed zScore($key, $member)
 * @method mixed zRank($key, $member)
 * @method mixed zRevRank($key, $member)
 * @method mixed zIncrBy($key, $value, $member)
 * @method mixed zUnion($Output, $ZSetKeys, $Weights = NULL, $aggregateFunction = 'SUM')
 * @method mixed zInter($Output, $ZSetKeys, $Weights = NULL, $aggregateFunction = 'SUM')
 * @method mixed hSet($key, $hashKey, $value)
 * @method mixed hSetNx($key, $hashKey, $value)
 * @method mixed hGet($key, $hashKey)
 * @method mixed hLen($key)
 * @method mixed hDel($key, $hashKey1, $hashKey2 = NULL, $hashKeyN = NULL)
 * @method mixed hKeys($key)
 * @method mixed hVals($key)
 * @method mixed hGetAll($key)
 * @method mixed hExists($key, $hashKey)
 * @method mixed hIncrBy($key, $hashKey, $value)
 * @method mixed hIncrByFloat($key, $field, $increment)
 * @method mixed hMset($key, $hashKeys)
 * @method mixed hMGet($key, $hashKeys)
 * @method mixed config($operation, $key, $value)
 * @method mixed evaluate($script, $args = array(), $numKeys = 0, $upliu = 'before')
 * @method mixed evalSha($scriptSha, $args = array(), $numKeys = 0)
 * @method mixed evaluateSha($scriptSha, $args = array(), $numKeys = 0)
 * @method mixed script($command, $script)
 * @method mixed getLastError()
 * @method mixed clearLastError()
 * @method mixed _prefix($value)
 * @method mixed _unserialize($value)
 * @method mixed dump($key)
 * @method mixed restore($key, $ttl, $value)
 * @method mixed migrate($host, $port, $key, $db, $timeout)
 * @method mixed time()
 */
class RedisProxy extends Component
{
    /**
     * @var string the hostname or ip address to use for connecting to the redis server. Defaults to 'localhost'.
     * If [[unixSocket]] is specified, hostname and port will be ignored.
     */
    public $hostname = 'localhost';
    /**
     * @var integer the port to use for connecting to the redis server. Default port is 6379.
     * If [[unixSocket]] is specified, hostname and port will be ignored.
     */
    public $port = 6379;
    /**
     * @var string the unix socket path (e.g. `/var/run/redis/redis.sock`) to use for connecting to the redis server.
     * This can be used instead of [[hostname]] and [[port]] to connect to the server using a unix socket.
     * If a unix socket path is specified, [[hostname]] and [[port]] will be ignored.
     * @since 2.0.1
     */
    public $unixSocket;
    /**
     * @var string the password for establishing DB connection. Defaults to null meaning no AUTH command is send.
     * See http://redis.io/commands/auth
     */
    public $password;
    /**
     * @var integer the redis database to use. This is an integer value starting from 0. Defaults to 0.
     */
    public $database = 0;
    /**
     * @var float timeout to use for connection to redis.
     */
    public $connectionTimeout = 0.0;
    /**
     * @var float timeout to use for redis socket when reading and writing data.
     */
    public $dataTimeout = 0.0;

    /**
     * @var bool 是否使用持久化（pconnect）链接 redis server
     */
    public $usePersistence = false;

    /**
     * @var \Redis
     */
    protected $_redis;

    protected $_isConnected = false;

    public function connect()
    {
        if (!$this->_isConnected) {
            if ($this->unixSocket) {
                $this->_isConnected = $this->usePersistence ?
                    $this->_redis->pconnect($this->unixSocket)
                    :
                    $this->_redis->connect($this->unixSocket);
            } else {
                $this->_isConnected = $this->usePersistence ?
                    $this->_redis->pconnect($this->hostname, $this->port, $this->connectionTimeout)
                    :
                    $this->_redis->connect($this->hostname, $this->port, $this->connectionTimeout);
            }
            if ($this->_isConnected) {
                if ($this->password) {
                    $this->auth($this->password);
                }
                $this->select($this->database);
            }
        }
        return $this->_isConnected;
    }

    public function open()
    {
        return $this->connect();
    }

    public function popen()
    {
        return $this->pconnect();
    }

    public function pconnect()
    {
        throw new \LogicException('To use persistence connect, you should set usePersistence property to true');
    }

    public function init()
    {
        $this->_redis = new \Redis();
    }

    public function __call($name, $params)
    {
        if (method_exists($this->_redis, $name)) {
            return call_user_func_array([$this->_redis, $name], $params);
        } else {
            return parent::__call($name, $params);
        }
    }
}
