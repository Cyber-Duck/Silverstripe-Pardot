<?php

namespace CyberDuck\Pardot\Service;

use CyberDuck\Pardot\PardotApi;
use SilverStripe\Core\Environment;

/**
 * Pardot Controller
 *
 * @package silverstripe-pardot
 * @license MIT License https://github.com/cyber-duck/silverstripe-pardot/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
final class PardotApiService
{
    /**
     * API connection instance
     *
     * @var PardotApi
     */
    private static $api;

    /**
     * Singleton constructor
     *
     * @return void
     */
    public static function instance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new PardotApiProvider();
        }
        return $instance;
    }

    /**
     * Returns the API instance
     *
     * @return PardotApi
     */
    public static function getApi(): PardotApi
    {
        if(!self::$api) self::setApi();
        return self::$api;
    }

    /**
     * Sets the API instance
     *
     * @return void
     */
    private static function setApi()
    {
        self::$api = new PardotApi(
            Environment::getEnv('PARDOT_EMAIL'),
            Environment::getEnv('PARDOT_PASSWORD'),
            Environment::getEnv('PARDOT_USER_KEY'),
            Environment::getEnv('PARDOT_API_VERSION')
        );
        self::$api->getAuthenticator()->doAuthentication();
    }
    
    private function __construct() {}

    private function __clone() {}

    private function __sleep() {}

    private function __wakeup() {}
}