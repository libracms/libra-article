<?php

namespace LibraArticle\Form\Element;

use Zend\Form\Element\Textarea;
use Zend\Stdlib\ArrayUtils;

/**
 * @author duke
 */
class CKEditor extends Textarea
{
    /**
     * CKEditor config
     * @var array
     */
    protected $config = array();

    /**
     * Accepted options for CKEditor:
     * - config: an array used in the CKEditor.config
     *
     * @param array|\Traversable $options
     * @return Csrf
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['config'])) {
            $this->setConfig($options['config']);
        }

        return $this;
    }

    /**
     * Set config for Namespace CKEDITOR.config
     * @param array|\Traversable $config
     */
    public function setConfig($config)
    {
        if ($config instanceof \Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        } elseif (!is_array($config)) {
            throw new \InvalidArgumentException(
                'The options parameter must be an array or a Traversable'
            );
        }
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }
}
