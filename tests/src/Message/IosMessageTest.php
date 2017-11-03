<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/pushing-kiwi-client)
 * @package kiwi-suite/pushing-kiwi-client
 * @see https://github.com/kiwi-suite/pushing-kiwi-client
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

namespace KiwiSuiteTest\PushingKiwiClient\Message;

use KiwiSuite\PushingKiwiClient\Message\IosMessage;
use PHPUnit\Framework\TestCase;

class IosMessageTest extends TestCase
{
    public function testIosMessage()
    {
        $deviceIds = [];
        for ($i = 0; $i < 30; $i++) {
            $deviceIds[] = \md5($i) . \md5($i);
        }

        $options = [
            'title' => "title",
            'body' => "body",
            'launchImage' => "launchImage",
            'badge' => 1,
            'sound' => "sound",
            'payload' => ['test' => 'test'],
            'priority' => 5,
            'expiration' => new \DateTime(),
            'deviceIds' => $deviceIds,
            'dontexist' => "efdfsdf",
        ];
        $iosMessage = new IosMessage($options);

        unset($options['dontexist']);
        $options['expiration'] = $options['expiration']->getTimestamp();

        $this->assertSame('ios', $iosMessage->getType());
        $this->assertSame(['type' => $iosMessage->getType(), 'options' => $options], $iosMessage->jsonSerialize());
    }

    public function testMessageChunks()
    {
        $deviceIds = [];
        for ($i = 0; $i < 14789; $i++) {
            $deviceIds[] = \md5($i) . \md5($i);
        }

        $options = [
            'deviceIds' => $deviceIds,
        ];

        $iosMessage = new IosMessage($options);

        $this->assertSame(15, \count($iosMessage->getMessageChunks()));
    }

    public function testDefaultValues()
    {
        $options = [
            'title' => null,
            'body' => null,
            'launchImage' => null,
            'badge' => null,
            'sound' => null,
            'payload' => null,
            'priority' => null,
            'expiration' => null,
            'deviceIds' => "not an array",
        ];
        $iosMessage = new IosMessage($options);
        $options['deviceIds'] = [];
        $this->assertSame(['type' => $iosMessage->getType(), 'options' => $options], $iosMessage->jsonSerialize());

        $options = [
            'title' => null,
            'body' => null,
            'launchImage' => null,
            'badge' => null,
            'sound' => null,
            'payload' => null,
            'priority' => null,
            'expiration' => null,
            'deviceIds' => ["12345", "a key" => "1234", "12345"],
        ];

        $iosMessage = new IosMessage($options);
        $options['deviceIds'] = ["12345", "1234"];
        $this->assertSame(['type' => $iosMessage->getType(), 'options' =>$options], $iosMessage->jsonSerialize());

        $options = [
            'title' => ["string"],
            'body' => ["string"],
            'launchImage' => ["string"],
            'badge' => "string",
            'sound' => [],
            'payload' => "string",
            'priority' => 4,
            'expiration' => [],
            'deviceIds' => [],
        ];
        $iosMessage = new IosMessage($options);
        $options = [
            'title' => null,
            'body' => null,
            'launchImage' => null,
            'badge' => null,
            'sound' => null,
            'payload' => null,
            'priority' => null,
            'expiration' => null,
            'deviceIds' => [],
        ];
        $this->assertSame(['type' => $iosMessage->getType(), 'options' =>$options], $iosMessage->jsonSerialize());
    }
}
