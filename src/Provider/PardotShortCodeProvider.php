<?php

namespace CyberDuck\Pardot\Provider;

use CyberDuck\Pardot\Service\PardotApiService;

/**
 * Pardot Controller
 *
 * @package silverstripe-pardot
 * @license MIT License https://github.com/cyber-duck/silverstripe-pardot/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class PardotShortCodeProvider
{
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
        $form = PardotApiService::getApi()->form()->read($arguments['id']);
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
        $content = PardotApiService::getApi()->dynamicContent()->read($arguments['id']);
        
        return $content->embedCode;
    }
}