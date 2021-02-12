<?php

namespace CyberDuck\Pardot\Provider;

use CyberDuck\Pardot\Service\PardotApiService;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ArrayData;

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
    protected static $CACHE_DURATION = ( 60 * 60 ) * 2; //2 hours

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
            $cache->set(self::formCacheKey($arguments['id']), serialize($form), static::getCacheDuration());
        }

        $code = $form->embedCode;

        // Extract url from iframe
        preg_match('/(?<=src=").*?(?=[\"])/', $code, $matches);

        $codeHTML = DBHTMLText::create();
        $codeHTML->setValue($code);

        $data = [
            'Code' => $codeHTML,
            'FormURL' => $matches[0]
        ];

        if(array_key_exists('class', $arguments)) {
            $data['CSSClass'] = $arguments['class'];
        }

        return ArrayData::create($data)->renderWith('PardotForm')->forTemplate();
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
            $cache->set(self::dynamicContentCacheKey($arguments['id']), serialize($content), static::getCacheDuration());
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

    protected static function getCacheDuration()
    {
        $duration = Environment::getEnv('PARDOT_CACHE_DURATION');

        return ((int)$duration > 0) ? (int)$duration: static::$CACHE_DURATION;
    }
}
