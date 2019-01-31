<?php

if(!defined('SILVERSTRIPE_PARDOT_PATH')) define('SILVERSTRIPE_PARDOT_PATH', rtrim(basename(dirname(__FILE__))));

use CyberDuck\Pardot\Provider\PardotShortCodeProvider;
use SilverStripe\Forms\HtmlEditor\HtmlEditorConfig;
use SilverStripe\View\Parsers\ShortcodeParser;

ShortcodeParser::get('default')->register(
    'pardot_form', [PardotShortCodeProvider::class, 'PardotForm']
);
ShortcodeParser::get('default')->register(
    'pardot_dynamic_content', [PardotShortCodeProvider::class, 'PardotDynamicContent']
);

HtmlEditorConfig::get('cms')->enablePlugins([
    'pardot' => '/resources/vendor/cyber-duck/silverstripe-pardot/client/js/pardot.js'
]);
HtmlEditorConfig::get('cms')->addButtonsToLine(2, 'pardot');