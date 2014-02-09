<?php

namespace LibraArticle\Entity;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Libra\Entity\AbstractEntityParams;

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
     * all locales by default
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $locale;
    /**
     * @ORM\Column
     * @var string
     */
    protected $heading;
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
     * @ORM\Column(type="integer")
     * @var int the latest revision
     */
    protected $revision;
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


    public function __construct()
    {
        //parent::__construct();
        $this->locale = '';
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

    public function setHeading($headline)
    {
        $this->heading = $headline;
        return $this;
    }

    public function getHeading()
    {
        return $this->heading;
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
            $this->created = $datetime;
        } else {
            $this->created = new DateTime($datetime);
            //$this->created =  new Date($created . ' UTC');
            //$this->setTimezone(date_default_timezone_get());
        }
        return $this;
    }

    public function getCreated()
    {
        return $this->created->setTimezone(new DateTimeZone(date_default_timezone_get()));
    }

    public function getCreatedUtc()
    {
        return $this->created->setTimezone(new DateTimeZone('UTC'));
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
            $this->modified = $datetime;
        } else {
            $this->modified = new DateTime($datetime);
        }
        return $this;
    }

    public function getModified()
    {
        return $this->modified->setTimezone(new DateTimeZone(date_default_timezone_get()));
    }

    /** @return \DateTime */
    public function getModifiedUtc()
    {
        return $this->modified->setTimezone(new DateTimeZone('UTC'));
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

    /**
     * Return brief part of content until <!---READ MORE--->
     * @return string html
     */
    public function getBriefContent()
    {
        $content = $this->content;
        if (empty($content)) return $content;

        $divider = '<!---READ'; // <!---READ MORE--->
        $brief = strstr($content, $divider, true);
        if ($brief === false) {
            $brief = substr($content, 0, 250);

            /** tidies the brief part */
            if (class_exists('tidy')) {
                $tidy = new \tidy();
                $brief = $tidy->repairString($brief, array(
                    'indent'         => true,
                    'indent-spaces'  => 4,
                    'wrap'           => 120,
                    'show-body-only' => true,
                ), 'utf8');
            }
        }
        return $brief;
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

    public function setRevision($revision)
    {
        $this->revision = $revision;
        return $this;
    }

    /**
     *
     * @return int latest revision
     */
    public function getRevision()
    {
        return $this->revision;
    }

    public function toArray()
    {
        return get_object_vars($this);
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
