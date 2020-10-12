<?php
/**
 * Bootstrap for xhgui test suite.
 */

use XHGui\Saver\SaverInterface;
use XHGui\ServiceContainer;

require dirname(__DIR__) . '/src/bootstrap.php';

$di = ServiceContainer::instance();

// Use a test database.
$config = $di['config'];
$config['db.db'] = 'test_' . $config['db.db'];
$di['config'] = $config;

// Clean up globals.
unset($di, $config);

/**
 * Load a fixture into the database.
 */
function loadFixture(SaverInterface $saver, $file)
{
    $data = json_decode(file_get_contents($file), true);
    foreach ($data as $record) {
        if (isset($record['meta']['request_time'])) {
            $time = strtotime($record['meta']['request_time']);
            $record['meta']['request_time'] = new MongoDate($time);
        }
        if (isset($record['_id'])) {
            $record['_id'] = new MongoId($record['_id']);
        }
        $saver->save($record, $record['_id'] ?? null);
    }
}
