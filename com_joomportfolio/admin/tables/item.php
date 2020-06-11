<?php

/**
* JoomPortfolio component for Joomla 3.x
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');
jimport('joomla.filter.output');

class JoomPortfolioTableItem extends JTable
{
	protected $catid = array();
	protected $rating = array();
	protected $images = array();

	function __construct(&$db)
	{
		parent::__construct('#__jp3_items', 'id', $db);
	}
	
	public function check()
	{
		$error = true;

		$xml = JoomPortfolioHelper::loadData();
		$fields = $xml->xpath('//field[@required!=0]');

		foreach ($fields as $field) {
			$name = (string)$field['name'];
			if (empty($this->custom[$name])) {
				$this->setError(JText::sprintf('COM_JOOMPORTFOLIO_WARNING_FIELD_REQUIRED', (string)$field['label']));
				$error = false;
			}
		}

		return $error;
	}

	public function load($pk = null, $reset = true) 
	{
		if (parent::load($pk, $reset)) {
			$db = $this->getDBO();

			$registry = new JRegistry;
			$registry->loadString($this->custom);
			$this->custom = $registry->toArray();

			$query = $db->getQuery(true);
			$query->select('c.cat_id');
			$query->from('#__jp3_href AS c');
			$query->where('c.item_id='.$this->id);
			$db->setQuery($query);
			$this->catid = $db->loadColumn();

			$query = $db->getQuery(true);
			$query->select('r.sum, r.count');
			$query->from('#__jp3_rating AS r');
			$query->where('r.item_id='.$this->id);
			$db->setQuery($query);
			$this->rating = $db->loadAssoc();

			$query = $db->getQuery(true);
			$query->select('i.*');
			$query->from('#__jp3_pictures AS i');
			$query->where('i.item_id='.$this->id);
			$db->setQuery($query);
			$this->images = $db->loadObjectList();
			return true;
		} else {
			return false;
		}
	}

	public function delete($pk = null) {
		$db = $this->getDBO();

		$db->setQuery('DELETE FROM `#__jp3_href` WHERE `item_id`='.$pk);
		$db->execute();

		$db->setQuery('DELETE FROM `#__jp3_pictures` WHERE `item_id`='.$pk);
		$db->execute();

		$db->setQuery('DELETE FROM `#__jp3_rating` WHERE `item_id`='.$pk);
		$db->execute();

		return parent::delete($pk);
	}
	
	public function store($updateNulls = false)
    {
        $input =JFactory::getApplication()->input;
        jimport( 'joomla.html.html' );
        if ((!$this->date) || (!preg_match('#[\d]{4}[-/][\d]{2}[-/][\d]{2}[ ][\d]{2}[:][\d]{2}[:][\d]{2}#', $this->date))) {
            $this->date =JHtml::date('now','Y-m-d H:i:s');

        }
		$isNew = false;
		if (!$this->id) {
			$isNew = true;
            $this->date =JHtml::date('now','Y-m-d H:i:s');
			$this->reorder();
		}

		if (is_array($this->custom)) {
			$this->custom = json_encode($this->custom);

		}


		if(empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);

		$jform = $input->getVar('jform');
		if (isset($jform['imagedefault']))
		{
			$this->def_image = htmlspecialchars($jform['imagedefault']);
		}
		if (!$this->ordering) $this->ordering = $this->getNextOrder();
		if (parent::store()) {
			if (!empty($this->catid)) {
				$db = $this->getDBO();
				$db->setQuery('DELETE FROM `#__jp3_href` WHERE `item_id`='.$this->id);
				$db->execute();

				if (!is_array($this->catid)) {
					$this->catid = array($this->catid);
				}

				foreach ($this->catid as $cid) {
					$query = $db->getQuery(true);
					$query->insert('#__jp3_href');
					$query->set('cat_id='.$cid);
					$query->set('item_id='.$this->id);
					$db->setQuery($query);
					$db->execute();
				}
			}


			if (!empty($this->images)) {
				$db = $this->getDBO();

				foreach ($this->images as $img) {
					if (is_object($img)) $img = get_object_vars($img);
					if (!empty($img['path'])) {
						$query = $db->getQuery(true);
						$query->insert('#__jp3_pictures');
						$query->set('item_id='.$this->id);
						$query->set('path='.$db->Quote($img['path']));
						if (isset($img['title'])) {
							$query->set('title='.$db->Quote($img['title']));
						}
						if (isset($img['copyright'])) {
							$query->set('copyright='.$db->Quote($img['copyright']));
						}
						$db->setQuery($query);
						$db->execute();
					}
				}
			}

			if (!empty($this->rating)) {
				$db = $this->getDBO();
				$query = $db->getQuery(true);
				if ($isNew) {
					$query->insert('#__jp3_rating');
					$query->set('item_id='.$this->id);
				} else {
					$query->update('#__jp3_rating');
					$query->where('item_id='.$this->id);
				}
				$query->set('sum='.$db->Quote($this->rating['sum']));
				$query->set('count='.$db->Quote($this->rating['count']));
				$query->set('lastip='.$db->Quote($_SERVER['REMOTE_ADDR']));
				$db->setQuery($query);
				$db->execute();
			}
			return true;
		} else {
			return false;
		}

	}
		
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_joomportfolio.item.'.(int)$this->$k;
	}

	protected function _getAssetTitle()
	{
		return $this->title;
	}

	protected function _getAssetParentId(Joomla\CMS\Table\Table $table = NULL, $id = NULL)
	{
		$asset = JTable::getInstance('Asset');
		$asset->loadByName('com_joomportfolio');
		return $asset->id;
	}
}
