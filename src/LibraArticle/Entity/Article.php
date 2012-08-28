<?php

namespace LibraArticle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Libra\Entity\AbstractEntityParams;
use DateTime;

/**
 * Description of Article
 *
 * @author duke
 * @ORM\Entity(repositoryClass="LibraArticle\Repository\ArticleRepository")
 * @ORM\Table(name="article",
 *      uniqueConstraints = {
 *          @ORM\UniqueConstraint(name="alias",columns={"locale", "alias"}),
 *          @ORM\UniqueConstraint(name="uid",columns={"uid", "locale"})
 *      },
 *      indexes = {
 *          @ORM\Index(name="state", columns={"state"}),
 *          @ORM\Index(name="locale", columns={"locale"})
 *      }
 * )
 */
class Article extends AbstractEntityParams
{
    const STATE_UNPUBLISHED = 'unpublished';
    const STATE_PUBLISHED   = 'published';
    const STATE_TRASHED     = 'trashed';

    protected $states = array(
        self::STATE_UNPUBLISHED,
        self::STATE_PUBLISHED,
        self::STATE_TRASHED,
    );

    /**
     * @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer")
     * @var int
     */
    protected $id;
    /**
     * @ORM\Column(length=10)
     * @var string
     */
    protected $locale;
    /**
     * @ORM\Column
     * @var string
     */
    protected $headline;
    /**
     * @ORM\Column
     * @var string
     */
    protected $alias;
    /**
     * Unique identifier
     * @ORM\Column(length=64)
     * @var string
     */
    protected $uid;
    /**
     * @ORM\Column(type="datetime")
     * @var \Zend\Date\Date
     */
    protected $created;
    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $createdBy;
    /**
     * @ORM\Column(type="datetime")
     * @var \Zend\Date\Date
     */
    protected $modified;
    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $modifiedBy;
    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $ordering;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $state;
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $content;

    /**
     * Set id.
     *
     * @param int $id the value to be set
     * @return Article
     */
    public function setId($articleId)
    {
        $this->id = (int) $articleId;
        return $this;
    }

    /**
     * Get id.
     *
     * @return int id
     */
    public function getId()
    {
        return $this->id;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setHeadline($headline)
    {
        $this->headline = $headline;
        return $this;
    }

    public function getHeadline()
    {
        return $this->headline;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $alias;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setCreated($datetime)
    {
        if ($datetime instanceof DateTime) {
        } else {
            $this->created = new DateTime($datetime);
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
    
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setModified($datetime)
    {
        if ($datetime instanceof DateTime) {
        } else {
            $this->modified = new DateTime($datetime);
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

    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
        return $this;
    }

    public function getModifiedBy($modifiedBy)
    {
        return $this->modifiedBy;
    }

    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;
        return $this;
    }

    public function getOrdering()
    {
        return $this->ordering;
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

    public function setState($state)
    {
        if (!in_array($state, $this->states)) {
            throw new \InvalidArgumentException(sprintf("Invalid state: %1"), $state);
        }
        $this->state = $state;
        return $this;
    }

    public function getState()
    {
        return $this->state;
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
