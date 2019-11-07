<?php

namespace Helpers;

/**
 * Helper class used to limit execution time and catch exceeding timeouts
 * Class TimeoutHelper
 * @package AppBundle\Helpers
 */
class TimeoutHelper
{
    /** @var float $start_time */
    private $start_time;

    /** @var float $timeout */
    private $timeout;

    /**
     * On call, initialize start time, timeout time and the tick function
     * @param $timeout
     */
    public function start($timeout)
    {
        $this->start_time = microtime(true);
        $this->timeout = $timeout;
        register_tick_function(array($this, 'tick'), true);
    }

    /**
     * Unregister the tick function on end
     */
    public function end()
    {
        unregister_tick_function(array($this, 'tick'));
    }

    /**
     * On each tick, check that process the should be still running
     * @throws TimeoutException
     */
    public function tick()
    {
        if ((microtime(true) - $this->start_time) > $this->timeout) {
            throw new TimeoutException("Timeout of " . $this->timeout . " seconds exceeded!");
        }
    }
}

?>
