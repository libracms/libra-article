<?php

namespace LibraArticle\Entity;

use Serializable;

/**
 * Description of Article
 *
 * @author duke
 */
class Article implements Serializable
{
    protected $articleId;
    protected $title;
    protected $alias;
    protected $params;
    protected $content;
    protected $created;
    protected $modified;

    public function __call($name, $args)
    {
        if (substr($name, 0, 3) == 'set') {
            $name = lcfirst(substr($name, 3));
            $this->$name = $args[0];
            return $this;
        } else if (substr($name, 0, 3) == 'get') {
                $name = lcfirst(substr($name, 3));
                return $this->$name;
        } else {
                return null;
        }
    }

    /**
     * Set id.
     *
     * @param int $id the value to be set
     * @return Article
     */
    public function setArticleId($articleId)
    {
        $this->articleId = (int) $articleId;
        return $this;
    }

    /**
     * Get id.
     *
     * @return int id
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    public function setParams($params)
    {
        if ($params === '' || $params === null) $params = new Parameters;
        if ($params instanceof Prameters) {
            $this->params = $params;
        } else if (is_object($params)) {
            $this->params = new Parameters((array)$params);
        } else if (is_array($params)) {
            $this->params = new Parameters($params);
        } else {
            $this->params = unserialize($params);
            if ($this->params === false) {
                trigger_error('Can\'t unserialize params');
            }
        }
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($name, $default = null)
    {
        if (!isset($this->params->$name)) {
            return $default;
        }
        return $this->params->$name;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setCreated($datetime)
    {
        if ($datetime instanceof Date) {
        } else {
            $this->created = new Date($datetime);
            //$this->created =  new Date($created . ' UTC');
            //$this->setTimezone(date_default_timezone_get());
        }
        return $this;
    }

    public function getCreated()
    {
        return $this->created->setTimezone(date_default_timezone_get());
    }

    public function getCreatedUtc()
    {
        return $this->created->setTimezone('UTC');
    }

    public function setModified($datetime)
    {
        if ($datetime instanceof Date) {
        } else {
            $this->modified = new Date($datetime);
        }
        return $this;
    }

    public function getModified()
    {
        return $this->modified->setTimezone(date_default_timezone_get());
    }

    public function getModifiedUtc()
    {
        return $this->modified->setTimezone('UTC');
    }

    public function toArray()
    {
        $params = $this->params->toArray();
        return array_merge(get_object_vars($this), array('params' => $params));
    }

    public function serialize()
    {
        return serialize($this->toArray());
    }

    public function unserialize($serialized)
    {
        $this->populate(unserialize($serialized));
    }

}
