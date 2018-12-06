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

class BaseList extends JModelLegacy
{
	protected $context = 'com_forum';
	protected $cache = array();

	protected $items;
	protected $pagination;

	public function getItems()
	{
		$store = 'getItems';

		if (!empty($this->cache[$store])) {
			return $this->cache[$store];
		}

		$db = $this->_db;
		$query	= $this->getListQuery();

		$limit = $this->getState('limit');
		$start = $this->getState('limitstart');

		$db->setQuery($query, $start, $limit);
		$items = $db->loadObjectList($this->getState('array.key'));

		if (empty($items)) {
			$total = $this->getTotal();

			if ($start > $total) {
				$start = max(0, (int)(ceil($total / $limit) - 1) * $limit);
				$db->setQuery($query, $start, $limit);
				$items = $db->loadObjectList($this->getState('array.key'));
			}

			if ($items) {
				$this->setState('limitstart', $start);
			}
		}

		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	public function getPagination()
	{
		$store = 'getPagination';

		if (!empty($this->cache[$store])) {
			return $this->cache[$store];
		}

		jimport('joomla.html.pagination');
		$limit = $this->getState('limit');

		$page = new JPagination($this->getTotal(), $this->getStart(), $limit);
		$this->cache[$store] = $page;

		return $page;
	}

	public function getTotal()
	{
		$store = 'getTotal';

		if (!empty($this->cache[$store])) {
			return $this->cache[$store];
		}

		$db = JFactory::getDBO();


        $db = $this->_db;
        $query	= $this->getListQuery();

        $db->setQuery($query);
        $items = $db->loadObjectList($this->getState('array.key'));

		//$query = 'SELECT FOUND_ROWS()';
		//$db->setQuery($query);
		$total =count($items); //$db->loadResult();
		$this->cache[$store] = $total;
		return $total;
	}

	public function getStart()
	{
		$store = 'getStart';

		if (!empty($this->cache[$store])) {
			return $this->cache[$store];
		}

		$start = $this->getState('limitstart');

		$this->cache[$store] = $start;

		return $start;
	}

	protected function getStoreId($id = '')
	{
		$id	.= ':'.$this->getState('limitstart');
		$id	.= ':'.$this->getState('limit');
		$id	.= ':'.$this->getState('list.ordering');
		$id	.= ':'.$this->getState('list.direction');

		return md5($this->context.':'.$id);
	}
}