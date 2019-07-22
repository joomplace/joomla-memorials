<?php

/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');
jimport('joomla.filter.output');

class JoomPortfolioModelTemplate extends JModelAdmin
{

    protected $context = 'com_joomportfolio';

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm($this->context . '.' . $this->getName(), $this->getName(), array('control' => 'jform', 'load_data' => false));
        $form->bind($this->getItem());

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getTable($type = 'Template', $prefix = 'JoomPortfolioTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function save($data)
    {
        $table = $this->getTable();

        if (!$table->bind($data)) {
            $this->setError($table->getError());
            return false;
        }
        $data['mode'] = JoomPortfolioHelper::getMode();
        // Bind the data.
        if (!$table->bind($data)) {
            $this->setError($table->getError());
            return false;
        }

        // Check the data.
        if (!$table->check()) {
            $this->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store()) {
            $this->setError($table->getError());
            return false;
        } else {
            $app = JFactory::getApplication();
            $app->setUserState('com_joomportfolio.default.template.data', $data);
        }

        return true;
    }


    public function getCatCustomFields(){
        $db = JFactory::getDBO();
        $mode = JoomPortfolioHelper::getMode();
        $query = $db->getQuery(true);
        $query->select('f.*');
        $query->from('#__jp3_field AS f');
        $query->where('mode="' .$mode.'"');
        $query->where('catview=1');
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    public function getItemCustomFields(){
        $db = JFactory::getDBO();
        $mode = JoomPortfolioHelper::getMode();
        $query = $db->getQuery(true);
        $query->select('f.*');
        $query->from('#__jp3_field AS f');
        $query->where('mode="' .$mode.'"');
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }


}
