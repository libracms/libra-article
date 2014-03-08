<?php

/*
 * eJoom.com
 * This source file is subject to the new BSD license.
 */

namespace LibraArticle\Form;

use Zend\InputFilter\InputFilter;
use LibraModuleManager\Module as LibraModuleManagerModule;

/**
 * Description of ArticleFilter
 *
 * @author duke
 */
class ArticleFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'id',
            'required'   => true,
            'filters'    => array(
                array(
                    'name' => 'Int',
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'Int',
                    'options' => array('min' => 0, 'inclusive' => true)
                ),
            ),
        ));
        if (LibraModuleManagerModule::isModulePresent('LibraLocale')) {
            $this->add(array(
                'name'        => 'locale',
                'required'    => true,
                'allow_empty' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    ),
                ),
            ));
        }
        $this->add(array(
            'name'       => 'heading',
            'required'   => true,
            'filters'    => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        $this->add(array(
            'name'       => 'alias',
            'required'   => true,
            'filters'    => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        $this->add(array(
            'name'       => 'headTitle',
            'required'   => false,
            'filters'    => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        $this->add(array(
            'name'       => 'metaKeywords',
            'required'   => false,
        ));
        $this->add(array(
            'name'       => 'metaDescription',
            'required'   => false,
        ));
        $this->add(array(
            'name'       => 'content',
            'required'   => false,
            'filters'    => array(
                array(
                    'name' => 'Libra\Filter\StripCarriageReturn',
                ),
            ),
        ));
    }
}
