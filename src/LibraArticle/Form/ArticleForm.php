<?php

/**
 * eJoom.com
 *
 * This source file is subject to the new BSD license.
 */

namespace LibraArticle\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use LibraLocale\Module as LocaleModule;
use LibraModuleManager\Module as ModuleManager;


/**
 * Description of ArticleForm
 *
 * @author duke
 */
class ArticleForm extends Form
{
    public function __construct($name = 'articleForm')
    {
        parent::__construct($name);
        $this->setAttribute('method', 'POST');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        if (ModuleManager::isModulePresent('LibraLocale')) {
            $locales = array_combine(
                array_keys(LocaleModule::getLocales()),
                array_keys(LocaleModule::getLocales())
            );
            $locales = array_merge(array('' => 'All'), $locales);
            $locale = new Element\Select('locale', array(
                //'twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL,
                'label' => 'Locale: *',
                'value_options' => $locales,
                //'column-size' => 'sm-2',
                //'label_attributes' => array('class' => 'col-sm-2'),
            ));
            $locale->setAttribute('class', 'span2 form-control');
            $this->add($locale);
        }
        $this->add(array(
            'name' => 'heading',
            'options' => array(
                'label' => 'Heading: *',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12 form-control',
            ),
        ));
        $this->add(array(
            'name' => 'alias',
            'options' => array(
                'label' => 'Alias: *',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12 form-control',
            ),
        ));
        $this->add(array(
            'name' => 'headTitle',
            'options' => array(
                'label' => 'Head title:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12 form-control',
            ),
        ));
        $this->add(array(
            'name' => 'metaKeywords',
            'options' => array(
                'label' => 'Meta Keywords:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12 form-control',
            ),
        ));
        $this->add(array(
            'name' => 'metaDescription',
            'options' => array(
                'label' => 'Meta Description:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12 form-control',
            ),
        ));
        $this->add(array(
            'type' => 'LibraArticle\Form\Element\CKEditor',
            'name' => 'content',
            'options' => array(
                'label' => 'Content:',
                // Config for ckedit
                'config' => array(
                    // Like new heght or any other
                    //'height' => 500,
                ),
            ),
            'attributes' => array(
                'rows' => 8,
                'class' => 'span12 form-control',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'options' => array(
                'label' => 'Save',
            ),
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Save',
                'formmethod' => 'POST',
                'class' => 'btn btn-primary',
            ),
        ));

        $csrf = new Element\Csrf('csrf');
        $this->add($csrf);
    }
}
