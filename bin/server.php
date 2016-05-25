<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$loop   = React\EventLoop\Factory::create();
$pusher = new App\Pusher;
$client = new Predis\Async\Client('tcp://127.0.0.1:6379', $loop);

$client->connect(function ($client) use ($pusher) {
    /** @var Predis\Async\Client $client */
    $client->pubSub('channel', function ($event, $pubsub) use ($pusher) {
        if ($event->kind == 'message') {
            $pusher->broadcast($event->payload);
            echo $event->payload."\n";
        }
    });
});

if (!$client->isConnected()) {
    die("Fatal error: TCP connection to redis-server is closed\n");
}

$webSock = new React\Socket\Server($loop);
$webSock->listen(8080, '0.0.0.0');
$webServer = new Ratchet\Server\IoServer(
    new Ratchet\Http\HttpServer(
        new Ratchet\WebSocket\WsServer(
            new Ratchet\Wamp\WampServer(
                $pusher
            )
        )
    ),
    $webSock
);

$loop->run();