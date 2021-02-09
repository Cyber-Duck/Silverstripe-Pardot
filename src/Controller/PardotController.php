<?php

namespace CyberDuck\Pardot\Controller;

use CyberDuck\Pardot\Service\PardotApiService;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Flushable;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\View\ArrayData;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Environment;

/**
 * Pardot Controller
 *
 * @package silverstripe-pardot
 * @license MIT License https://github.com/cyber-duck/silverstripe-pardot/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class PardotController extends Controller implements Flushable
{
    protected static $FORMS_CACHE_KEY = 'pardot_cache_forms';
    protected static $DYNAMIC_CONTENTS_CACHE_KEY = 'pardot_dynamic_contents';
    protected static $CACHE_DURATION = ( 60 * 60 ) * 2; //2 hours

    private static $url_segment = 'pardot';

    private static $allowed_actions = [
        'PardotContentFormHTML',
        'PardotContentForm',
        'PardotDynamicContentForm'
    ];

    public function PardotContentFormHTML(): string
    {
        return DBField::create_field(
            'HTMLText',
            $this->PardotContentForm()->forAjaxTemplate().
            $this->PardotDynamicContentForm()->forAjaxTemplate()
        );
    }

    public function PardotContentForm()
    {
        $fields = FieldList::create(
            HeaderField::create('PardotFormHeading', 'Form Content', 3),
            DropdownField::create('PardotForm', 'Select')
                ->setEmptyString('- select form -')
                ->addExtraClass('form-group')
                ->setCustomValidationMessage('Please select a form')
                ->setSource($this->getForms()),
            NumericField::create('FormHeight', 'Height')
                ->addExtraClass('form-group'),
            NumericField::create('FormWidth', 'Width')
                ->addExtraClass('form-group'),
            TextField::create('FormCssClass', 'CSS Class')
                ->addExtraClass('form-group')
        );
        $actions = FieldList::create(
            FormAction::create('doFormContentSubmit', 'Insert')
                ->addExtraClass('btn btn-primary')
        );

        $required = [
            'PardotForm'
        ];
        $validator = RequiredFields::create($required);

        $form = Form::create($this, 'PardotContentForm', $fields, $actions, $validator);
        $form->setTemplate('cms-edit-form cms-panel-padded center');
        $form->setFormAction('/pardot/PardotContentForm');
        $form->setFormMethod('POST', true);
        $form->addExtraClass('ss-ui-pardot-form');

        return $form;
    }

    public function PardotDynamicContentForm()
    {
        $fields = FieldList::create(
            HeaderField::create('PardotDynamicContentHeading', 'Dynamic Content', 3),
            DropdownField::create('DynamicContent', 'Select')
                ->setEmptyString('- select content -')
                ->addExtraClass('form-group')
                ->setCustomValidationMessage('Please select a content type')
                ->setSource($this->getDynamicContent()),
            NumericField::create('DynamicContentHeight', 'Height')
                ->addExtraClass('form-group'),
            NumericField::create('DynamicContentWidth', 'Width')
                ->addExtraClass('form-group'),
            TextField::create('DynamicContentCssClass', 'CSS Class')
                ->addExtraClass('form-group')
        );
        $actions = FieldList::create(
            FormAction::create('doDynamicContentSubmit', 'Insert')
                ->addExtraClass('btn btn-primary')
        );

        $required = [
            'PardotDynamicContent'
        ];
        $validator = RequiredFields::create($required);

        $form = Form::create($this, 'PardotDynamicContentForm', $fields, $actions, $validator);
        $form->setTemplate('cms-edit-form cms-panel-padded center');
        $form->setFormAction('/pardot/PardotDynamicContentForm');
        $form->setFormMethod('POST', true);
        $form->addExtraClass('ss-ui-pardot-form');

        return $form;
    }

    private function getForms()
    {
        $forms = ArrayList::create();
        $cache = Injector::inst()->get(CacheInterface::class . '.cyberduckPardotCache');

        if ($cache->has(self::$FORMS_CACHE_KEY)) {
            return unserialize($cache->get(self::$FORMS_CACHE_KEY));
        }

        $queryForm = PardotApiService::getApi()->form()->query()->form;
        foreach ($queryForm as $form) {
            $forms->push(ArrayData::create([
                'ID' => $form->id,
                'Title' => sprintf('%s - %s', $form->campaign->name, $form->name),
                'EmbedCode' => $form->embedCode,
            ]));
        }
        $formList = $forms->Sort('Title')->map();
        $cache->set(self::$FORMS_CACHE_KEY, serialize($formList), static::getCacheDuration());

        return $formList;
    }

    private function getDynamicContent()
    {
        $cache = Injector::inst()->get(CacheInterface::class . '.cyberduckPardotCache');
        if ($cache->has(self::$DYNAMIC_CONTENTS_CACHE_KEY)) {
            return unserialize($cache->get(self::$DYNAMIC_CONTENTS_CACHE_KEY));
        }

        $data = PardotApiService::getApi()->dynamicContent()->query()->dynamicContent;
        $data = is_array($data) ? $data : [$data];

        $contents = ArrayList::create();
        foreach ((array) $data as $content) {
            $contents->push(ArrayData::create([
                'ID' => $content->id,
                'Title' => $content->name,
                'EmbedCode' => $content->embedCode,
            ]));
        }
        $contentList = $contents->Sort('Title')->map();
        $cache->set(self::$DYNAMIC_CONTENTS_CACHE_KEY, serialize($contentList), static::getCacheDuration());

        return $contentList;
    }

    protected static function getCacheDuration()
    {
        $duration = Environment::getEnv('PARDOT_CACHE_DURATION');

        return ((int)$duration > 0) ? (int)$duration: static::$CACHE_DURATION;
    }

    /**
     * Flush the cache when ?flush=1 is triggered
     */
    public static function flush()
    {
        Injector::inst()->get(CacheInterface::class . '.cyberduckPardotCache')->clear();
    }
}
