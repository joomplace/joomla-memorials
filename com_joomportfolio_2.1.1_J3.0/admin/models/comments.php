<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * Comments Model
 */
class JoomPortfolioModelComments extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'i.id', 
                'i.published', 
                'i.item_id',
                'i.date',
                'u.name',
                'i.title'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $this->setState('filter.search', $this->getUserStateFromRequest('com_joomportfolio.filter.search', 'filter_search'));
        $this->setState('filter.category_id', $this->getUserStateFromRequest('com_joomportfolio.filter.category_id', 'filter_category_id'));
        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);
        parent::populateState();
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return	string	An SQL query
     */
    protected function getListQuery() {
        $mode =JoomPortfolioHelper::getMode();/* JFactory::getApplication()->input->cookie->get('name');*/
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('i.*, u.name AS user_name');
        $query->from('#__jp3_comments AS i');
        $query->innerJoin('`#__users` AS `u` ON `u`.id=`i`.`user_id`');
        $query->where('i.mode="' . $mode . '"');
        // list orderring
        $query->order($db->escape($this->getState('list.ordering', 'i.title ')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

        //search filter
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->escape($search, true) . '%');
            $query->where('i.mode="' . $mode . '" AND i.title LIKE ' . $search );
        }

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('i.published = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(i.published = 0 OR i.published = 1)');
        }

        $query->group('i.id');

        $orderCol = $this->state->get('list.ordering', 'i.id');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

}
