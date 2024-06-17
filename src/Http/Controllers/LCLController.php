<?php

namespace Saasscaleup\LCL\Http\Controllers;

use DateTime;
use Illuminate\Routing\Controller as BaseController;
use Saasscaleup\LCL\Models\StreamConsoleLog;
use Symfony\Component\HttpFoundation\StreamedResponse;
// use Illuminate\Http\Request;

class LCLController extends BaseController
{
    /**
     * Notifies LCL events.
     *
     * @param StreamConsoleLog $StreamConsoleLog
     * @return StreamedResponse
     * @throws \Exception
     */
    public function stream(StreamConsoleLog $StreamConsoleLog): StreamedResponse
    {

        $response = new StreamedResponse(function() use ($StreamConsoleLog){

            // if the connection has been closed by the client we better exit the loop
            if (connection_aborted()) {
                return;
            }

            $server_event_retry = config('lcl.server_event_retry');

            echo ':' . str_repeat(' ', 2048) . "\n"; // 2 kB padding for IE
            echo "retry: {$server_event_retry}\n";
            $client = getVisitorId();

            while($model = $StreamConsoleLog->where('client',$client)->where('delivered', false)->oldest()->first()) {

                // if the connection has been closed by the client we better exit the loop
                if (connection_aborted()) {
                    return;
                }

                $data = json_encode([
                    'message' => $model->message,
                    'type' => strtolower($model->type),
                    'time' => date('H:i:s', strtotime($model->created_at)),
                ]);

                echo 'id: ' . $model->id . "\n";
                echo 'event: ' . $model->event . "\n";
                echo 'data: ' . $data . "\n\n";

                ob_flush();
                flush();
               // sleep(config('lcl.interval'));
                sleep(1);
                $model->delivered = '1';
                $model->save();
            }

            echo ": heartbeat\n\n";

        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        return $response;

    }

}
