<?php

namespace XHGui\Test;

use MongoDate;
use MongoId;
use XHGui\Saver\SaverInterface;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Load a fixture into the database.
     */
    protected function loadFixture(SaverInterface $saver, $file)
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
}
