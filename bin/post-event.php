<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$redis = new Predis\Client(array(
    'host' => '127.0.0.1',
    'port' => 6379,
));

for($i=0; $i <= 100; $i+=20) {
    sleep(1);
    $redis->publish('channel', $i);
}
