<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/pushing-kiwi-client)
 * @package kiwi-suite/pushing-kiwi-client
 * @see https://github.com/kiwi-suite/pushing-kiwi-client
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

namespace KiwiSuite\PushingKiwiClient\Message;

interface MessageInterface extends \JsonSerializable
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return array
     */
    public function getMessageChunks();
}
