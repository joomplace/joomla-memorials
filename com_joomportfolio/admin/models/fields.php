<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class JoomPortfolioModelFields extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'name', 'type', 'def', 'req', 'catview'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $this->setState('filter.search', $this->getUserStateFromRequest('com_joomportfolio.filter.search', 'filter_search'));

        parent::populateState($ordering = null, $direction = null);
    }

    /* public function getItems() {
      $xml = JoomPortfolioHelper::loadData();

      $search = $this->getState('filter.search');
      if (!empty($search)) {
      $fields = $xml->xpath('//field[contains(@name, "' . $search . '") or contains(@label, "' . $search . '") or contains(@default, "' . $search . '")]');
      } else {
      $fields = $xml->xpath('//field');
      }

      if (!$fields) {
      return false;
      }

      $items = array();

      foreach ($fields as $field) {
      $item = new JObject();
      $item->set('name', (string) $field['name']);
      $item->set('label', (string) $field['label']);
      $item->set('type', (string) $field['type']);
      $item->set('default', (string) $field['default']);
      $item->set('required', (int) $field['required']);
      $item->set('catview', (int) $field['catview']);
      $items[] = $item;
      }

      usort($items, array('JoomPortfolioModelFields', 'sortItems'));

      return $items;
      } */

    protected function sortItems($item1, $item2) {
        $sortBy = $this->getState('list.ordering', 'name');
        $sortOrder = strtoupper($this->getState('list.direction', 'asc'));

        if ($item1->$sortBy == $item2->$sortBy) {
            return 0;
        }

        $value = strcmp($item1->$sortBy, $item2->$sortBy);

        if ($sortOrder == 'DESC') {
            $value = -$value;
        }

        return $value;
    }

    protected function getListQuery()
    {
        $mode =JoomPortfolioHelper::getMode(); /*JFactory::getApplication()->input->cookie->get('name');*/

        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__jp3_field ');
        $query->where('mode="' . $mode . '"');

        $search = trim($this->getState('filter.search'));
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('id = ' . (int) substr($search, 3));
            } else {
                $search = $this->_db->quote('%' . $search . '%', true);
                $query->where('(mode="' . $mode . '" AND name LIKE ' . $search . ' OR label  LIKE ' . $search . ')');
            }
        }

        $orderCol = $this->state->get('list.ordering', 'id');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

}
