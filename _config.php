<?php

use SilverStripe\Forms\HtmlEditor\HtmlEditorConfig;

$config = HtmlEditorConfig::get('cms');
$config->enablePlugins(array('pardot' => '/resources/vendor/cyber-duck/silverstripe-pardot/js/pardot.js'));
$config->addButtonsToLine(2, 'pardot');