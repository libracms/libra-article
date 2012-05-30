<?php

namespace LibraArticle\Mapper;

use Doctrine\ORM\EntityManager;

/**
 * Description of ArticleMapper
 *
 * @author duke
 */
class ArticleDoctrineMapper
{
    //protected $tableName = 'content';
    //protected $fields = array('id' => 'id');
    protected $primaryKey = 'article_id';

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getRowObjectPrototype()
    {
        return new ArticleRowGateway($this->getTableGateway(), 'article_id'); //@todo for future
    }

    public function prepareQuery()
    {
        $query = 'SELECT * FROM ' . $this->getTableGateway()->getTableName() . '';
    }

    public function fetchAll($offset = null, $limit = null)
    {
        /*$select = $this->getTableGateway()->getSqlSelect(true)
                ->columns(array('uid'))
                ->where('1 GROUP BY uid ORDER BY title');
        $resultSet = $this->getTableGateway()->select($select);*/
        $resultSet = $this->getTableGateway()->select('1 GROUP BY uid ORDER BY ordering');
        //$resultSet = $resultSet->toArray();

        //$this->getTableGateway()->getSqlSelect()->columns(array('*'));
        $articles = array();
        foreach ($resultSet as $row) {
            $resultSet2 = $this->getTableGateway()->select('uid = "' . $row['uid'] . '" ORDER BY locale');
            foreach ($resultSet2 as $row2) {
                $articles[$row['uid']][] = $row2;
            }
        }
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('articles' => $articles));
        return $articles;
    }

    public function persist(Article $article)
    {
        $data = new ArrayObject($article->toArray()); // or perhaps pass it by reference?
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('data' => $data, 'article' => $article));
        if ($article->getArticleId() > 0) {
            $this->getTableGateway()->update((array) $data, array('article_id' => $article->getArticleId()));
        } else {
            $this->getTableGateway()->insert((array) $data);
            $articleId = $this->getTableGateway()->getLastInsertId();
            $article->setArticleId($articleId);
        }
        return $article;
    }

    public function findByAlias($alias, $locale = '')
    {
        $query = "SELECT * FROM article WHERE alias = '$alias' AND (locale='$locale' OR locale = '') ORDER BY locale DESC";
        $statement = $this->getTableGateway()->getAdapter()->createStatement($query);
        $result = $statement->execute();
        $resultSet = new \Zend\Db\ResultSet\ResultSet();
        //$resultSet->setRowObjectPrototype($this->getRowObjectPrototype());
        $resultSet->setDataSource($result);
        $rowset = $resultSet;


        //$rowset = $this->getTableGateway()->select(array('alias' => $alias));
        $row = $rowset->current();
        $article = Article::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('article' => $article, 'row' => $row));
        return $article;
    }

    public function findByUidAndLocale($uid, $locale)
    {
        $rowset = $this->getTableGateway()->select("uid = '$uid' AND (locale = '$locale' OR locale = '') ORDER BY locale DESC");
        $row = $rowset->current();
        $article = Article::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('article' => $article, 'row' => $row));
        return $article;
    }

    public function findUid($alias)
    {
        $query = "SELECT uid FROM article WHERE alias = '$alias'";
        $statement = $this->getTableGateway()->getAdapter()->createStatement($query);
        $result = $statement->execute();
        $resultSet = new \Zend\Db\ResultSet\ResultSet();
        $resultSet->setDataSource($result);
        $row = $resultSet->current();
        return $row;
    }

    public function findById($id)
    {
        $rowset = $this->getTableGateway()->select(array('article_id' => $id));
        $rowset->setRowObjectPrototype($this->getRowObjectPrototype());
        $article = $rowset->current();
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('article' => $article));
        return $article;
    }

    public function createArticleFromForm($values, $userLocation)
    {
        $values = (array) $values;
        if ($values['article_id'] == 0) $values['article_id'] = null;
        if (empty($values['uid'])) $values['uid'] = uniqid();
        $values['params'] = new \Zend\Stdlib\Parameters();
        $values['params']->metaKeys = $values['metaKeys'];
        $values['params']->metaDesc = $values['metaDesc'];
        unset ($values['metaKeys']);
        unset ($values['metaDesc']);
        //$values['created']->setTimezone($userLocation); @todo for UTC time zone
        //$values['modified'] = new setTimezone($userLocation);
        $article = $this->getRowObjectPrototype()
                ->exchangeArray($values);
        return $article;
    }

    /**
     * Prepare post ids array to sql where form
     * @param array $ids
     * @return sting of where
     */
    protected function getWhereFromIds(array $ids)
    {
        /*
         * force convert data to int
         */
        array_walk($ids, function(&$item) {
            $item = intval($item);
        });
        $ids = implode(',', $ids);
        $where = "$this->primaryKey IN ($ids)";
        return $where;
    }


    public function delete($ids)
    {
        $this->tableGateway->delete($this->getWhereFromIds($ids));
    }

    public function changeStatus($ids, $state = 'published')
    {
        return $this->tableGateway->update(array('state' => $state), $this->getWhereFromIds($ids));
    }

    public function getAvailableLocales($uid)
    {
        //$uid   = $this->tableGateway->getAdapter()->escape($uid);
        $uids  = "SELECT locale FROM article WHERE uid = '$uid'";
        $where = "locale NOT IN ($uids)";
        //$localeMapper = new LocaleMapper(new \Zend\Db\TableGateway\TableGateway('locale', $this->tableGateway->getAdapter()));
        $localeTable = new \Zend\Db\TableGateway\TableGateway('locale', $this->tableGateway->getAdapter());
        $rows  = $localeTable->select('1 ORDER BY title');
        $locales = array();
        foreach ($rows as $row) {
            $locales[$row->locale] = $row->title;
        }
        return $locales;
    }

}

