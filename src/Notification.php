<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/pushing-kiwi-client)
 * @package kiwi-suite/pushing-kiwi-client
 * @see https://github.com/kiwi-suite/pushing-kiwi-client
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

namespace KiwiSuite\PushingKiwiClient;

use KiwiSuite\PushingKiwiClient\Exception\InvalidArgumentException;
use KiwiSuite\PushingKiwiClient\Message\MessageInterface;
use Zend\Diactoros\Request;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;

final class Notification implements \JsonSerializable
{
    /**
     * @var array
     */
    private $messages = [];

    /**
     * @var string
     */
    private $token;

    /**
     * Notification constructor.
     * @param $token
     * @param array $messages
     */
    public function __construct($token, array $messages)
    {
        $this->token = $token;

        foreach ($messages as $message) {
            if (!($message instanceof MessageInterface)) {
                throw new InvalidArgumentException(\sprintf("'%s' must implement '%s'", ((\is_object($message)) ? \get_class($message) : \gettype($message)), MessageInterface::class));
            }

            $this->messages = \array_merge($message->getMessageChunks(), $this->messages);
        }
    }

    /**
     * @return Request
     */
    public function createHttpRequest()
    {
        $request = (new Request())
            ->withUri(new Uri("https://pushing.kiwi/api/v1/send/"))
            ->withMethod('POST')
            ->withBody(new Stream("php://memory", "r+"))
            ->withAddedHeader('authorization', 'Bearer ' . $this->token)
            ->withAddedHeader('content-type', 'application/json');

        $request->getBody()->write(json_encode($this));

        return $request;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     *
     */
    public function jsonSerialize()
    {
        return $this->messages;
    }
}
