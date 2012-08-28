<?php

/**
 * eJoom.com
 * 
 * This source file is subject to the new BSD license.
 */

namespace LibraArticle\Form;

use Zend\Form\Form;
use Zend\Form\Element;


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
        $this->setAttribute('method', 'PUT');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'headline',
            'options' => array(
                'label' => 'Headline: *',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'alias',
            'options' => array(
                'label' => 'Alias: *',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'metaKeys',
            'options' => array(
                'label' => 'Meta Keys:',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'metaDescription',
            'options' => array(
                'label' => 'Meta Description:',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'content',
            'options' => array(
                'label' => 'Content:',
            ),
            'attributes' => array(
                'type' => 'textarea',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'options' => array(
                'label' => 'Send',
            ),
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Send',
                'method' => 'POST',
            ),
        ));

        $csrf = new Element\Csrf('csrf');
        $this->add($csrf);
    }
}