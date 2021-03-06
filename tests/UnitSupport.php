<?php
/**
 * This file is part of GitterApi package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gitter\Tests;

use Gitter\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class UnitSupport
 * @package Gitter\Tests
 * @mixin \PHPUnit_Framework_TestCase
 */
trait UnitSupport
{
    /**
     * @return string
     */
    public function debugRoomId(): string
    {
        return $_ENV['debug_room_id'] ?? $_SERVER['debug_room_id'] ?? '';
    }

    /**
     * @return string
     */
    public function debugHookId(): string
    {
        return $_ENV['debug_hook_id'] ?? $_SERVER['debug_hook_id'] ?? '';
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function userId(): string
    {
        return $this->client()->authId();
    }

    /**
     * @return Client
     * @throws \InvalidArgumentException
     */
    public function client()
    {
        $logger = new Logger('phpunit');
        $logger->pushHandler(new StreamHandler(STDOUT, Logger::DEBUG));

        $client = new Client($this->token(), $logger);


        if (0 === stripos(PHP_OS, 'WIN')) { // Windows SSL bugfix
            $client->viaHttp()->setOptions(['verify' => false]);
        }

        return $client;
    }

    /**
     * @return string
     */
    public function token(): string
    {
        return $_ENV['token'] ?? $_SERVER['token'] ?? '';
    }
}
