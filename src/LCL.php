<?php

namespace Saasscaleup\LCL;

use Saasscaleup\LCL\Models\StreamConsoleLog;

/**
 * LCL
 */
class LCL
{
    /**
     * @var StreamConsoleLog
     */
    protected $StreamConsoleLog;
    
    /**
     * __construct
     *
     * @param  StreamConsoleLog $StreamConsoleLog
     * @return void
     */
    public function __construct(StreamConsoleLog $StreamConsoleLog)
    {
        $this->StreamConsoleLog = $StreamConsoleLog;
    }

    /**
     * Notify LCL event.
     *
     * @param string $message : notification message
     * @param string $type : alert, success, error, warning, info, debug, critical, etc...
     * @param string $event : Type of event such as "EmailSent", "UserLoggedIn", etc
     * @return bool
     */
    public function notify(string $message, string $type = 'info', string $event = 'stream-console-log'): bool
    {
        return $this->StreamConsoleLog->saveEvent($message, $type, $event);
    }

}
