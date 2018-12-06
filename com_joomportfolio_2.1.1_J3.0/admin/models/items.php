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
 * Items Model
 */
class JoomPortfolioModelItems extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'i.id', 
                'i.published', 
                'i.ordering', 
                'i.title', 
                'i.hits', 
                'c.title'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $this->setState('filter.search', $this->getUserStateFromRequest('com_joomportfolio.filter.search', 'filter_search'));
        $this->setState('filter.category_id', $this->getUserStateFromRequest('com_joomportfolio.filter.category_id', 'filter_category_id'));
        $published = $this->getUserStateFromRequest('com_joomportfolio.filter.published', 'filter_published', '');
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

        $query->select('i.*, c.title AS cat_name');
        $query->from('#__jp3_items AS i');
        //$query->select('(SELECT GROUP_CONCAT(c.cat_id SEPARATOR ",") AS cids FROM #__jp3_href AS c WHERE c.item_id=i.id) AS cids');
        $query->leftJoin('`#__categories` AS `c` ON `c`.id=`i`.`cat_id`');
        $query->where('i.mode="' . $mode . '"');
        // list orderring
        $query->order($db->escape($this->getState('list.ordering', 'i.title ')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

        //search filter
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->escape($search, true) . '%');
            $query->where('i.mode="' . $mode . '" AND i.title LIKE ' . $search . ' OR i.description_short LIKE ' . $search . ' OR i.description LIKE ' . $search);
        }

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('i.published = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(i.published = 0 OR i.published = 1)');
        }

        // Filter by cats
        $cat_id = $this->getState('filter.category_id');
        if (is_numeric($cat_id)) {
            $query->where('i.cat_id = ' . (int) $cat_id);
        }

        $query->group('i.id');

        $orderCol = $this->state->get('list.ordering', 'i.id');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getCategories() {
        $mode = JoomPortfolioHelper::getMode();
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('cc.id, cc.title');
        $query->from('#__categories AS cc');

        $query->where('cc.extension="com_'.$mode.'"');
        $query->where('cc.published=1');
        $query->where('cc.access=1');

        $db->setQuery($query);
        $items = $db->loadObjectList();

        return $items;
    }


    public function getImages($id)
    {

            $db = $this->getDBO();

            $query = $db->getQuery(true);
            $query->select('pii.*');
            $query->from('#__jp3_pictures AS pii');
            $query->where('pii.item_id=' . (int)$id);
            $query->order('is_default DESC');
            $query->order('ordering');
            $db->setQuery($query,0,1);
            $image = $db->loadObject();
        return $image;
    }

    public function getItem($id)
    {

        $db = $this->getDBO();

        $query = $db->getQuery(true);
        $query->select('i.*');
        $query->from('#__jp3_items AS i');
        $query->where('i.id=' . (int)$id);
               $db->setQuery($query);
        $item =  $db->loadObject();
        return $item;
    }

    public function getFields($id)
    {

        $mode = JoomPortfolioHelper::getMode();
        $db = JFactory::getDBO();

        $query = "SELECT f.*"
            . "\n FROM #__jp3_field AS f"
            . "\n WHERE mode='" . $mode . "'";
        $db->setQuery($query);
        $fields = $db->loadAssocList();
        for ($i = 0; $i < count($fields); $i++) {
            $fields[$i]['value'] = '';
        }

        //get all items this cat
        $query = "SELECT  value AS custom, field_id"
            . "\n FROM #__jp3_item_content"
            . "\n WHERE item_id=" . (int)$id;
        $db->setQuery($query);
        $ids = $db->loadAssocList();

        $custom = array();
        for ($i = 0; $i < count($ids); $i++) {
            $custom[$i]['custom'] = $ids[$i]['custom'];
            $custom[$i]['field_id'] = $ids[$i]['field_id'];
        }

        $field_ids = array();
        for ($i = 0; $i < count($fields); $i++) {
            $field_ids[$i] = intval($fields[$i]['id']);
        }
        for ($i = 0; $i < count($custom); $i++) {
            if (count($custom[$i]['custom'])) {
                if (is_array($custom[$i]['custom'])) {
                    foreach ($custom[$i]['custom'] as $key => $value) {
                        if (!in_array($key, $field_ids)) {
                            unset($custom[$i]['custom'][$key]);
                        }
                    }
                }
            }
        }
        $custom_f = array();
        if (!empty($custom)) {

            for ($i = 0; $i < count($custom); $i++) {
                for ($j = 0; $j < count($fields); $j++) {
                    if (count($custom[$i]['custom'])) {
                        $value = $custom[$i]['custom'];

                        if ((int)$fields[$j]['id'] == (int)$custom[$i]['field_id']) {

                            $custom_f[$j]['value'] = $value;
                            $custom_f[$j]['name'] = $fields[$j]['name'];
                            $custom_f[$j]['label'] = $fields[$j]['label'];
                            $custom_f[$j]['def'] = $fields[$j]['def'];
                            $custom_f[$j]['type'] = $fields[$j]['type'];
                            $custom_f[$j]['format'] = $fields[$j]['format'];

                        }

                    }
                }
            }
        }


        return $custom_f;
    }

}
