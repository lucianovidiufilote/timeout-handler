<?php
/**
 * Created by PhpStorm.
 * User: lucian
 * Date: 09.05.2018
 * Time: 16:07
 */

namespace LucianOvidiuFilote\TimeoutHandler;


use LucianOvidiuFilote\TimeoutHandler\Exceptions\TimeoutException;

/**
 * Helper class used to limit execution time and catch exceeding timeouts
 * Class TimeoutHelper
 * @package AppBundle\Helpers
 */
class TimeoutHelper
{
    /** @var float|null $start_time */
    private $startTime = null;

    /** @var float|null $timeout */
    private $timeout = null;

    /** @var float|null $exceptionTime */
    private $exceptionTime = null;

    public function __construct()
    {
        declare(ticks=1);
    }

    /**
     * On call, initialize start time, timeout time and the tick function
     * @param $timeout
     */
    public function start($timeout)
    {
        $this->startTime = microtime(true);
        $this->timeout = $timeout;
        register_tick_function(array($this, 'tick'), true);
    }

    /**
     * Unregister the tick function on end
     */
    public function end()
    {
        unregister_tick_function(array($this, 'tick'));
        $this->startTime = null;
        $this->timeout = null;
        $this->exceptionTime = null;
    }

    /**
     * On each tick, check that process the should be still running
     * @throws TimeoutException
     */
    public function tick()
    {
        $time = microtime(true);
        if (($time - $this->startTime) > $this->timeout) {
            $this->exceptionTime = $time;
            throw new TimeoutException("[TimeoutHelper] Timeout of " . $this->timeout . " seconds exceeded!");
        }
    }

    /**
     * @return float|null
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @return float|null
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @return float|null
     */
    public function getExceptionTime()
    {
        return $this->exceptionTime;
    }
}