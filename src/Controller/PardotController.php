<?php

namespace CyberDuck\Pardot\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;

class PardotController extends Controller
{
    private static $url_segment = 'pardot';

    private static $allowed_actions = [
        'PardotContentForm',
        'PardotContentFormHTML'
    ];

    public function PardotContentForm()
    {
        $fields = FieldList::create(
            HeaderField::create('PardotForms', 'Form Content', 3),
            DropdownField::create('Form', 'Select')->addExtraClass('form-group'),
            NumericField::create('FormHeight','Height')->addExtraClass('form-group'),
            NumericField::create('FormWidth','Width')->addExtraClass('form-group'),
            TextField::create('FormCssClass','CSS Class')->addExtraClass('form-group'),
            HeaderField::create('PardotDynamicContent', 'Dynamic Content', 3),
            DropdownField::create('DynamicContent', 'Select')->addExtraClass('form-group'),
            NumericField::create('DynamicContentHeight','Height')->addExtraClass('form-group'),
            NumericField::create('DynamicContentWidth','Width')->addExtraClass('form-group'),
            TextField::create('DynamicContentCssClass','CSS Class')->addExtraClass('form-group')
        );
        $actions = FieldList::create( 
            FormAction::create('doFormSubmit', 'Submit')->addExtraClass('btn btn-primary')
        );

        $required = [];
        $validator = new RequiredFields($required);

        $form = Form::create($this, 'PardotContentForm', $fields, $actions, $validator);
        $form->setTemplate('cms-edit-form cms-panel-padded center');

        return $form;
    }

    public function PardotContentFormHTML(): string
    {
        return $this->PardotContentForm()->forAjaxTemplate();
    }
}