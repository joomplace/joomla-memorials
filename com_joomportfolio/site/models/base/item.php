<?php

/**
* JoomPortfolio component for Joomla 2.5
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class BaseItem extends JModelLegacy
{
	public function populateState()
	{
        $input =JFactory::getApplication()->input;
		$app = JFactory::getApplication('site');
		$params = $app->getParams();
		$this->setState('params', $params);

		$id = $input->get('id', null,'RAW');
        $id=str_replace (":" , "-", $id);
        if(!$id){
            $id=$this->getItemIdByItemId();
        }
		$this->setState('item.id', $id);
		
		$catid = $input->get('cid', null, 'get');
        $catid=str_replace (":" , "-", $catid);

		$this->setState('item.catid', $catid);

		$start = $input->get('start', $input->get('limitstart', 0));
		$this->setState('list.start', $start);

		$limit = $app->getUserStateFromRequest('com_joomportfolio.items.limit', 'limit');
		$this->setState('list.limit', $limit);
		
		parent::populateState();
	}

	public function getItem($id = false, $select = false)
	{

		if (!isset($this->item)) {
			$id = $id ? $id : $this->getState('item.id');

			if ($id) {
				$db = JFactory::getDBO();
				$query = $this->getItemQuery($id, $select);

				$db->setQuery($query);
				$this->item = $db->loadObject();
			} else {
				return false;
			}
		}
		return $this->item;
	}

	public function getCategory()
	{
        $input =JFactory::getApplication()->input;
		if (!isset($this->category)) {
			$catid = (int)$this->getState('item.catid', 0);
			
			if ($input->get('view') == 'category') {
				$catid = $input->get('id');
			}

			if (!$catid) {
				return false;
			}

			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			$query->select('cc.id, cc.title, cc.description, cc.parent_id, cc.level, cc.lft, cc.rgt, cc.params');
			$query->select('CASE WHEN CHAR_LENGTH(cc.alias) THEN cc.alias ELSE cc.id END as slug');
			$query->from('#__categories AS cp, #__categories AS cc');
			$query->where('cp.lft BETWEEN cc.lft AND cc.rgt');
			$query->where('cp.id='.$catid);

			$db->setQuery($query);
			$cats = $db->loadObjectList('id');

			if (!isset($cats[$catid])) {
				return false;
			}

			$this->category = new JObject($cats[$catid]);
			unset($cats[$catid]);
			$this->category->set('parents', $cats);
			$this->category->params = json_decode($this->category->params);
		}

		return $this->category;
	}

	public function getPagination()
	{
		if (empty($this->pagination)) {
			return null;
		}
		return $this->pagination;
	}

	public function validate($array, $fields = array())
	{
		if ($fields) {
			foreach ($fields as $field) {
				if (!isset($array[$field]) or !$array[$field]) {
					return false;
				}			
			}
			return true;
		} else {
			foreach ($array as $key) {
				if (!$key) {
					return false;
				}			
			}
			return true;
		}

		return false;
	}

	public function store($data)
	{
		$row = $this->getTable();

		if (!$row->bind($data)) {
			return false;
		}

		if (!$row->store()) {
			return false;
		}

		return $row->id;
	}

	public function save($data, $fields = array())
	{
		if (!$this->validate($data, $fields)) {
			return false;
		}

		$id = $this->store($data);
		if ($id === false) {
			return false;
		}

		return $id;
	}

	public function delete($id)
	{
		$row = $this->getTable($this->getState('item.table'));

		if (!$row->delete($id)) {
			return false;
		}
		
		return true;
	}

    public function getItemIdByItemId(){
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
        @$cur_cat=(int)$settings->id;
        $item=$this->getItemById($cur_cat);
        return  $item;
    }

    public function getItemById($id){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('c.alias');
        $query->from('#__jp3_items AS c');
        $query->where('c.id=' . $id);
        $db->setQuery($query);
        return $db->loadResult();
    }

}