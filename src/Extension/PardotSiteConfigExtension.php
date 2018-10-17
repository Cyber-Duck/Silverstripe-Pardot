<?php

namespace CyberDuck\Pardot\Extension;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

/**
 * SiteConfig Pardot data extension 
 *
 * @package silverstripe-pardot
 * @license MIT License https://github.com/cyber-duck/silverstripe-pardot/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class PardotSiteConfigExtension extends DataExtension
{
    /**
     * SiteConfig fields
     *
     * @var array
     */
    private static $db = [
        'PardotEmail'    => 'Varchar(512)',
        'PardotPassword' => 'Varchar(512)',
        'PardotUserKey'  => 'Varchar(512)'
    ];

    /**
     * Updates the SiteConfig form UI
     *
     * @param FieldList $fields
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.Pardot', HeaderField::create('PardotHeader', 'Pardot API Configuration'));
        $fields->addFieldToTab('Root.Pardot', TextField::create('PardotEmail', 'Email'));
        $fields->addFieldToTab('Root.Pardot', TextField::create('PardotPassword', 'Password'));
        $fields->addFieldToTab('Root.Pardot', TextField::create('PardotUserKey', 'User Key'));
        
        return $fields;
    }
}