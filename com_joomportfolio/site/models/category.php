<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomportfolio.php');

class JoomPortfolioModelCategory extends JModelList
{
    
    public function populateState($ordering = NULL, $direction = NULL)
    {
        $jinput = JFactory::getApplication()->input;
        $app = JFactory::getApplication('site');

        $params = $app->getParams();
        $this->setState('params', $params);

        $id = $jinput->get('id', 0, 'INT');
        $this->setState('id', $id);

        $letter = $jinput->get('letter', '');
        if(!$letter){
            $letter = $app->getUserStateFromRequest('letter', 'letter', '');
        }
        $this->setState('letter', $letter);

        $value = $jinput->get('limit', $app->get('list_limit', 0), 'uint');
        $this->setState('limit', $value);

        $value = $jinput->get('limitstart', 0, 'uint');
        $this->setState('limitstart', $value);

        parent::populateState();
    }

    public function getListQuery()
    {
        $mode = JoomPortfolioHelper::getModeByCatId();
        $jinput = JFactory::getApplication()->input;
        $alias = $jinput->get('id', '', 'HTML');
        if(!$alias) {
            $alias = $jinput->get('cid', '', 'HTML');
        }
        $alias=str_replace (":" , "-", $alias);

        if(!$alias){
            $alias=$this->getCatIdByItemId();
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        if (!$mode) {
            $mode = JoomPortfolioHelper::getModeByCatId();
        }
        $query->select('i.title AS item_title, i.alias AS item_alias, i.published AS item_published, pr.sum AS item_sum,pr.count AS item_count,
                  i.description_short AS item_description_short,i.hits AS item_hits, i.custom AS item_custom,
                  i.def_image AS item_def_img,i.id AS item_id, c.*');
        $query->select("CASE WHEN CHAR_LENGTH(i.alias) THEN i.alias ELSE i.id END as slug");
        $query->select("CASE WHEN CHAR_LENGTH(c.alias) THEN c.alias ELSE c.id END as cat_slug");

        $query->from('#__jp3_items AS i');
        $query->leftJoin('#__categories AS c ON c.id=i.cat_id');
        $query->join('LEFT', '#__jp3_rating AS pr ON pr.item_id=i.id');

        if (!$this->getState('letter')) {
            $this->setState('letter', $jinput->get('letter', ''));
        }

        $letter = $this->getState('letter');
        if ($letter) {
            $letter = $this->_db->quote($letter . '%', true);
            $query->where('i.title LIKE ' . $letter);
        }

        $query->where("i.mode='" . $mode . "' AND c.alias='" . $alias . "' AND i.published=1");

        $settings=JoomPortfolioHelper::getSetting($mode);

        if($mode='memorials' && isset($settings->item_hide_old_item) && !(int)$settings->item_hide_old_item){
             if(isset($settings->item_days)){
                if(!(int)$settings->item_days){
                    $days=30;
                }else{
                    $days=  abs((int)$settings->item_days);
                }

             }else{
                 $days=30;
             }
            $query->where('i.date > DATE_SUB(NOW(), INTERVAL '.$days.' day) ');
        }

        $query->order('i.title');

        if (!$this->getState('limit')) {
            $this->setState('limit', $jinput->get('limit', 0, 'INT'));
        }

        if (!$this->getState('limitstart')) {
            $this->setState('limitstart', $jinput->get('limitstart', 0, 'INT'));
        }

        //die($query->__toString());

        return $query;
    }

    public function getCategory($id)
    {
        $id=str_replace (":" , "-", $id);
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('c.*');
        $query->from('#__categories AS c');
        $query->where('c.alias="' . $id.'"');
        $db->setQuery($query);
        return $db->loadObject();
    }


    public function getChildren($id)
    {
        $mode = JoomPortfolioHelper::getVarMode();
        if (!$mode) {
            $mode = JoomPortfolioHelper::getModeByCatId();
        }

        if (!isset($this->children)) {

            $db = JFactory::getDBO();
            $query = 'SELECT cc.*,'.
            ' cc.alias as slug
          FROM #__categories AS cc WHERE cc.extension="com_' . $mode . '" AND cc.published=1 AND cc.parent_id=' . (int)$id;

            $db->setQuery($query);

            $this->children = $db->loadObjectList();
            $count_cat = count($this->children);
            for ($i = 0; $i < $count_cat; $i++) {
                $query = $db->getQuery(true);
                $query->select('COUNT(*) AS count_items');
                $query->from('#__jp3_items');
                $query->where('cat_id=' . (int)$this->children[$i]->id);
                $db->setQuery($query);
                $this->children[$i]->count_items = $db->loadResult();
            }
        }

        return $this->children;
    }

    public function getCatItems($cat_id, $total=false)
    {
        $limit = $this->getState('limit');
        $limitstart = $this->getState('limitstart');
        $jinput = JFactory::getApplication()->input;

        $mode = JoomPortfolioHelper::getVarMode();
        if (!$mode) {
            $mode = JoomPortfolioHelper::getModeByCatId();
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('i.title AS item_title, i.alias AS item_alias, i.published AS item_published, pr.sum AS item_sum,pr.count AS item_count,
                  i.description_short AS item_description_short,i.hits AS item_hits, i.custom AS item_custom,
                  i.def_image AS item_def_img,i.id AS item_id, c.*');
        $query->select("CASE WHEN CHAR_LENGTH(i.alias) THEN i.alias ELSE i.id END as slug");
        $query->select("CASE WHEN CHAR_LENGTH(c.alias) THEN c.alias ELSE c.id END as cat_slug");

        $query->from('#__jp3_items AS i');
        $query->leftJoin('#__categories AS c ON c.id=i.cat_id');
        $query->join('LEFT', '#__jp3_rating AS pr ON pr.item_id=i.id');

        $letter = $jinput->get('letter', '');
        if ($letter) {
            $letter = $this->_db->quote($letter . '%', true);
            $query->where('i.title LIKE ' . $letter);
        }

        $query->where("i.mode='" . $mode . "' AND i.cat_id=" . $cat_id . " AND i.published=1");

        if(!$total) {
            $query->setLimit($limit, $limitstart);
        }

        $query->order('i.title');

        $db->setQuery($query);
        $cat_items = $db->loadObjectList();

        return $cat_items;
    }

    public function changeMenu()
    {
        $jinput = JFactory::getApplication()->input;
        $itemid = $jinput->get('Itemid', 0, 'INT');

        $mode = JoomPortfolioHelper::getVarMode();
        if (!$mode) {
            $mode = JoomPortfolioHelper::getModeByCatId();
        }
        if (!$mode) {
            $mode = $jinput->get('mode', '');
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('link');
        $query->from('#__menu');
        $query->where('id=' . $itemid);
        $db->setQuery($query);
        $link = $db->loadResult();

        $query->clear();
        $query->select('id');
        $query->from('#__categories');
        $query->where('extension="com_' . $db->escape($mode) . '"');
        $query->where('published=1');
        $db->setQuery($query, 0, 1);
        $id = $db->loadResult();
        if ($link && $id) {
            $query->clear();
            $query->update('#__menu')
                ->set('`link`="index.php?option=com_joomportfolio&view=category&id=' . (int)$id . '&extension=' . $db->escape($mode) . '&mode=' . $db->escape($mode) . '"')
                ->where('`id`=' . $itemid . '');
            $db->setQuery($query);
            if (!$db->execute()) {
                return NULL;
            } else {
                $_REQUEST['id']=$id;
                return $id;
            }
        }
        return NULL;
    }

    public function getCatIdByItemId(){     //return Category Alias
        $jinput = JFactory::getApplication()->input;
        $itemid = $jinput->get('Itemid', 0, 'INT');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('params')
            ->from('`#__menu`')
            ->where('id=' . (int)$itemid);
        $db->setQuery($query);
        $params= $db->loadResult();

        $settings=json_decode($params);
        $cur_cat=(int)$settings->id;
        $category=$this->getCategoryById($cur_cat);
        return  $category;
    }

    public function getCategoryIdByItemId(){
        $jinput = JFactory::getApplication()->input;
        $itemid = $jinput->get('Itemid', 0, 'INT');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('params')
            ->from('`#__menu`')
            ->where('id=' . (int)$itemid);
        $db->setQuery($query);
        $params= $db->loadResult();

        $settings=json_decode($params);
        $cur_cat = !empty($settings->id) ? $settings->id : 0;

        return  $cur_cat;
    }

    public function getCategoryById($id){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('c.alias');
        $query->from('#__categories AS c');
        $query->where('c.id=' . $id);
        $db->setQuery($query);
        return $db->loadResult();
    }

    public function getTotal(){
        $store = 'getTotal';
        if (!empty($this->cache[$store])) {
            return $this->cache[$store];
        }
        $cat_id = $this->getCategoryIdByItemId();
        $items = $this->getCatItems($cat_id, true);
        $total = count($items);
        $this->cache[$store] = $total;
        return $total;
    }
}