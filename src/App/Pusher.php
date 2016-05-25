<?php

namespace App;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface
{
    protected $subscribedTopic = null;

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        echo __METHOD__."\n";
        $this->subscribedTopic = $topic;
    }

    public function broadcast($string)
    {
        if (!is_null($this->subscribedTopic)) {
            $this->subscribedTopic->broadcast($string);
        }
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo __METHOD__."\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo __METHOD__."\n";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        echo __METHOD__."\n";
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        echo __METHOD__."\n";
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo __METHOD__."\n";
    }
}