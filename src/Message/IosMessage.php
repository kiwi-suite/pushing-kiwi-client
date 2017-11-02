<?php
/**
 * kiwi-suite/entity (https://github.com/kiwi-suite/pushing-kiwi-client)
 * @package kiwi-suite/pushing-kiwi-client
 * @see https://github.com/kiwi-suite/pushing-kiwi-client
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

namespace KiwiSuite\PushingKiwiClient\Message;

final class IosMessage implements MessageInterface
{
    /**
     * @var array
     */
    private $attributes = [
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

    /**
     * AppleMessage constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach (\array_keys($this->attributes) as $attrName) {
            if (!\array_key_exists($attrName, $attributes)) {
                continue;
            }

            $value = $attributes[$attrName];

            if (\in_array($attrName, ['title', 'body', 'launchImage', 'sound']) && !\is_string($attributes[$attrName])) {
                continue;
            } elseif (\in_array($attrName, ['payload']) && !\is_array($attributes[$attrName])) {
                continue;
            } elseif (\in_array($attrName, ['expiration'])) {
                if (!($value instanceof \DateTimeInterface)) {
                    continue;
                }
                $value = $value->getTimestamp();
            } elseif (\in_array($attrName, ['priority']) && (!\is_int($value) || !\in_array($value, [5, 10], true))) {
                continue;
            } elseif (\in_array($attrName, ['badge']) && !\is_int($value)) {
                continue;
            } elseif (\in_array($attrName, ['deviceIds'])) {
                if (!\is_array($value)) {
                    continue;
                }
                $value = \array_unique(\array_values($value));
            } else {
                continue;
            }

            $this->attributes[$attrName] = $value;
        }
    }

    public function getMessageChunks()
    {
        $result = [];
        $deviceChunks = \array_chunk($this->attributes['deviceIds'], 1000);

        foreach ($deviceChunks as $deviceChunk) {
            $attributes = $this->attributes;
            $attributes['deviceIds'] = $deviceChunk;
            $result[] = new IosMessage($attributes);
        }

        return $result;
    }

    public function jsonSerialize()
    {
        return [
            'type' => $this->getType(),
            'options' => $this->attributes,
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'ios';
    }
}
