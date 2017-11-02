<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/pushing-kiwi-client)
 * @package kiwi-suite/pushing-kiwi-client
 * @see https://github.com/kiwi-suite/pushing-kiwi-client
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

namespace KiwiSuiteTest\PushingKiwiClient;

use KiwiSuite\PushingKiwiClient\Exception\InvalidArgumentException;
use KiwiSuite\PushingKiwiClient\Message\AndroidMessage;
use KiwiSuite\PushingKiwiClient\Message\IosMessage;
use PHPUnit\Framework\TestCase;
use KiwiSuite\PushingKiwiClient\Notification;

class NotificationTest extends TestCase
{
    /**
     * @var IosMessage
     */
    private $iosMessage;

    /**
     * @var AndroidMessage
     */
    private $androidMessage;

    public function setUp()
    {
        $deviceIds = [];
        for ($i = 0; $i < 18756; $i++) {
            $deviceIds[] = \md5($i) . \md5($i);
        }
        $this->iosMessage = new IosMessage([
            'deviceIds' => $deviceIds,
        ]);

        $deviceIds = [];
        for ($i = 0; $i < 14456; $i++) {
            $deviceIds[] = \md5($i) . \md5($i);
        }
        $this->androidMessage = new AndroidMessage([
            'deviceIds' => $deviceIds,
        ]);
    }

    public function testGetMessages()
    {
        $notification = new Notification("test", [$this->iosMessage, $this->androidMessage]);

        $messages = $notification->getMessages();

        $this->assertSame(34, \count($messages));
        $this->assertSame($notification->getMessages(), $notification->jsonSerialize());
    }

    public function testInvalidMessageException()
    {
        $this->expectException(InvalidArgumentException::class);
        new Notification("test", ["test"]);
    }

    public function testCreateHttpRequest()
    {
        $notification = new Notification("test", [$this->iosMessage, $this->androidMessage]);

        $request = $notification->createHttpRequest();

        $this->assertSame("pushing.kiwi", $request->getUri()->getHost());
        $this->assertSame("https", $request->getUri()->getScheme());
        $this->assertSame("/api/v1/send/", $request->getUri()->getPath());
        $this->assertSame(\json_encode($notification->getMessages()), (string)$request->getBody());
        $this->assertSame(["application/json"], $request->getHeader("content-type"));
        $this->assertSame(["Bearer test"], $request->getHeader("authorization"));
    }
}
