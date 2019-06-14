<?php

namespace CyberDuck\Pardot\Provider;

use CyberDuck\Pardot\Service\PardotApiService;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Injector\Injector;

/**
 * Pardot Controller
 *
 * @package silverstripe-pardot
 * @license MIT License https://github.com/cyber-duck/silverstripe-pardot/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class PardotShortCodeProvider
{
    protected static $FORM_CACHE_KEY_PREFIX = "form_cache_key";
    protected static $DYNAMIC_CONTENT_CACHE_KEY_PREFIX = "dynamic_content_cache_key";
    protected static $CACHE_DURATION = ( 60 * 60 ) * 6; //6 hours

    /**
     * Renders a Pardot form
     *
     * @param array $arguments
     * @param string $content
     * @param object $parser
     * @param string $shortcode
     * @param array $extra
     * @return void
     */
    public static function PardotForm($arguments, $content, $parser, $shortcode, $extra = [])
    {
        $form = null;
        $cache = Injector::inst()->get(CacheInterface::class . '.cyberduckPardotCache');

        if ($cache->has(self::formCacheKey($arguments['id']))) {
            $form = unserialize($cache->get(self::formCacheKey($arguments['id'])));
        }

        if (! $form) {
            $form = PardotApiService::getApi()->form()->read($arguments['id']);
            $cache->set(self::formCacheKey($arguments['id']), serialize($content), self::$CACHE_DURATION);
        }

        $code = $form->embedCode;
        if(array_key_exists('class', $arguments)) {
            $code = str_replace(
                '></',
                sprintf(' class="%s"></', $arguments['class']),
                $code
            );
        }

        return $code;
    }

    /**
     * Renders Pardot dynamic content
     *
     * @param array $arguments
     * @param string $content
     * @param object $parser
     * @param string $shortcode
     * @param array $extra
     * @return void
     */
    public static function PardotDynamicContent($arguments, $content, $parser, $shortcode, $extra = [])
    {
        $content = null;
        $cache = Injector::inst()->get(CacheInterface::class . '.cyberduckPardotCache');

        if ($cache->has(self::dynamicContentCacheKey($arguments['id']))) {
            $content = unserialize($cache->get(self::dynamicContentCacheKey($arguments['id'])));
        }

        if (! $content) {
            $content = PardotApiService::getApi()->dynamicContent()->read($arguments['id']);
            $cache->set(self::dynamicContentCacheKey($arguments['id']), serialize($content), self::$CACHE_DURATION);
        }

        return $content->embedCode;
    }

    private static function formCacheKey($id)
    {
        return self::$FORM_CACHE_KEY_PREFIX . $id;
    }

    private static function dynamicContentCacheKey($id)
    {
        return self::$DYNAMIC_CONTENT_CACHE_KEY_PREFIX . $id;
    }
}
