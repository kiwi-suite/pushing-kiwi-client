<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/pushing-kiwi-client)
 * @package kiwi-suite/pushing-kiwi-client
 * @see https://github.com/kiwi-suite/pushing-kiwi-client
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

namespace KiwiSuiteTest\PushingKiwiClient\Message;

use KiwiSuite\PushingKiwiClient\Message\AndroidMessage;
use PHPUnit\Framework\TestCase;

class AndroidMessageTest extends TestCase
{
    public function testAndroidMessage()
    {
        $deviceIds = [];
        for ($i = 0; $i < 30; $i++) {
            $deviceIds[] = \md5($i) . \md5($i);
        }

        $options = [
            'payload' => ['test' => 'test'],
            'deviceIds' => $deviceIds,
            'dontexist' => "efdfsdf",
        ];
        $androidMessage = new AndroidMessage($options);

        unset($options['dontexist']);

        $this->assertSame('android', $androidMessage->getType());
        $this->assertSame(['type' => $androidMessage->getType(), 'options' => $options], $androidMessage->jsonSerialize());
    }

    public function testMessageChunks()
    {
        $deviceIds = [];
        for ($i = 0; $i < 14789; $i++) {
            $deviceIds[] = \md5($i) . \md5($i);
        }

        $options = [
            'payload' => ['test' => 'test'],
            'deviceIds' => $deviceIds,
        ];

        $androidMessage = new AndroidMessage($options);

        $this->assertSame(15, \count($androidMessage->getMessageChunks()));
    }

    public function testDefaultValues()
    {
        $options = [
            'deviceIds' => "not an array",
        ];
        $androidMessage = new AndroidMessage($options);
        $this->assertSame(['type' => $androidMessage->getType(), 'options' => ['payload' => [], 'deviceIds' => []]], $androidMessage->jsonSerialize());


        $options = [
            'payload' => "string",
            'deviceIds' => ["12345", "a key" => "1234", "12345"],
        ];
        $androidMessage = new AndroidMessage($options);
        $this->assertSame(['type' => $androidMessage->getType(), 'options' => ['payload' => [], 'deviceIds' => ["12345", "1234"]]], $androidMessage->jsonSerialize());
    }
}
