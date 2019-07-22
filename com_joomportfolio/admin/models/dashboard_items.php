<?php
/**
 * JoomPortfolio component for Joomla 3.0
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

class JoomPortfolioModelDashboard_items extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array('id', 'title', 'url', 'icon', 'published');
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState();
    }

    protected function getListQuery()
    {
        $mode = JoomPortfolioHelper::getMode();
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('`id`,`title`,`url`,`icon`,`published`');
        $query->from('`#__jp3_dashboard_items`');
        $query->where('mode="' . $mode . '"');
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->Escape($search, true) . '%');
            $query->where('title LIKE ' . $search);
        }
        $orderCol = $this->state->get('list.ordering', 'title');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        return $query;
    }

    function delete($cid)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__jp3_dashboard_items');
        $query->where('id IN (' . implode(',', $cid) . ')');
        $db->setQuery($query);
        try {
            $db->execute();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    public function publish($cid, $value = 1)
    {
        $database = JFactory::getDBO();
        $task = JFactory::getApplication()->input->getCmd('task');
        $state = ($task == 'publish') ? 1 : 0;

        if (!is_array($cid) || count($cid) < 1) {
            $action = ($task == 'publish') ? 'publish' : 'unpublish';
            echo "<script> alert('" . JText::_('COM_JOOMPORTFOLIO_SELECT_AN_ITEM_TO') . " $action'); window.history.go(-1);</script>\n";
            exit();
        }

        $cids = implode(',', $cid);
        $query = $database->getQuery(true);
        $query->update('#__jp3_dashboard_items');
        $query->set('published =' . (int)$state);
        $query->where('id IN (' . $cids . ')');
        $database->setQuery($query);
        if (!$database->execute()) {
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
            exit();
        }

        return true;
    }
}
