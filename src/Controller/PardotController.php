<?php

namespace CyberDuck\Pardot\Controller;

use CyberDuck\Pardot\Service\PardotApiService;
use SilverStripe\Control\Controller;
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

/**
 * Pardot Controller
 *
 * @package silverstripe-pardot
 * @license MIT License https://github.com/cyber-duck/silverstripe-pardot/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class PardotController extends Controller
{
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
        foreach (PardotApiService::getApi()->form()->query()->form as $form) {
            $forms->push(ArrayData::create([
                'ID' => $form->id,
                'Title' => sprintf('%s - %s', $form->campaign->name, $form->name),
                'EmbedCode' => $form->embedCode,
            ]));
        }
        return $forms->Sort('Title')->map();
    }

    private function getDynamicContent()
    {
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
        return $contents->Sort('Title')->map();
    }
}
