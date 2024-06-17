<?php

if (!function_exists('stream_console_log')) {
    /**
     * @param $message
     * @param string $type : alert, success, error, warning, info
     * @param string $event : Type of event such as "UserLogout", "EmailSent", etc
     * @return mixed
     */
    function stream_console_log($message, $type = 'info', $event = 'stream-console-log')
    {
        return app('LCL')->notify($message, $type, $event);
    }
}

if (!function_exists('getVisitorId')) {

    /**
     * getVisitorId
     *
     * @return md5
     */
    function getVisitorId(){
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $visitor_ip = getVisitorIp();
        
        return md5(php_uname('m') . $user_agent .$visitor_ip);
    }
}

if (!function_exists('getVisitorIp')) {

    /**
     * getVisitorIp
     *
     * @return string
     */
    function getVisitorIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return ''; // it will return empty string if Ip not found
    }
}