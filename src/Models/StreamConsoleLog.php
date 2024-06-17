<?php

namespace Saasscaleup\LCL\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use DateTime;

class StreamConsoleLog extends Model
{
    protected $table = 'stream_console_logs';

    protected $fillable = [
        'user_id',
        'message',
        'event',
        'type',
        'delivered',
    ];

    /**
     * Saves SSE event in database table.
     *
     * @param $message
     * @param $type
     * @param $event
     * @return bool
     */
    public function saveEvent($message, $type, $event): bool
    {

        $this->deleteProcessed();

        $date               = new DateTime;
        $data['message']    = $message;
        $data['event']      = $event;
        $data['type']       = $type;
        $data['client']     = getVisitorId();
        $data['created_at'] = $date->format('Y-m-d H:i:s');
        $data['updated_at'] = $date->format('Y-m-d H:i:s');

        if (config('lcl.append_user_id') && auth()->check()) {
            $data['user_id'] = auth()->user()->getAuthIdentifier();
        }

        return self::query()->insert($data);
    }

    /**
     * Delete already processed events
     */
    public function deleteProcessed()
    {
        if (config('lcl.keep_events_logs')) {
            return false;
        }

        if (Cache::has('delete_stream_log') ){
            return false;
        }
        
        // Delete delivered messages
        self::query()->where('delivered', true)->delete();

        // delete old not delivered messages
        $date = new DateTime;
        $date = $date->modify('-30 minutes');
        self::query()->where('delivered', false)->where('created_at','<=',$date->format('Y-m-d H:i:s'))->delete();
        
        // set cache
        Cache::put('delete_stream_log',true,config('lcl.delete_log_interval'));
    }
    
}
