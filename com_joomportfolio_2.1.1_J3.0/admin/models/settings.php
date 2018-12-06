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

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Item Model
 */
class JoomPortfolioModelSettings extends JModelAdmin {

    protected $context = 'com_joomportfolio';

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Settings', $prefix = 'JoomPortfolioTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true) {
        $app = JFactory::getApplication();
        $mode = JoomPortfolioHelper::getMode();

        if (!file_exists(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'settings_' . $mode . '.xml')) {
            copy(JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'portfolio' . DIRECTORY_SEPARATOR . $mode . DIRECTORY_SEPARATOR . 'settings_' . $mode . '.xml', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'settings_' . $mode . '.xml');
        }

        $form = $this->loadForm('com_joomportfolio.settings', 'settings_' . $mode, array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function getItem($pk = NULL) {

        $db = JFactory::getDBO();
        $item = new JObject;
       
        $mode = JoomPortfolioHelper::getMode();
        $query = "SELECT s.params"
                . "\n FROM #__jp3_settings AS s"
                . "\n WHERE s.mode='". $mode."'"
        ;
        $db->setQuery($query);
        $params = $db->loadObject();
        $settings = json_decode($params->params);

        foreach ($settings as $key => $val) {
            $item->$key = $val;
        }

        return $item;
    }

    protected function loadFormData() {
        $data = $this->getItem();
        return $data;
    }

    public function saveItem() {
        $mode = JoomPortfolioHelper::getMode();
        $db = JFactory::getDBO();
        $input = JFactory::getApplication()->input;
        $data = $input->get('jform', array(), 'post', 'array');
        $data2=json_encode($data);
        $db->setQuery("UPDATE #__jp3_settings SET params='" . $data2 . "'  WHERE mode='" . $mode . "'");
        if (!$db->execute()) {
            return false;
        }
        return true;
    }

}

