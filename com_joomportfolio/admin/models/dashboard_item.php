<?php
/**
* JoomPortfolio component for Joomla 3.0
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class JoomPortfolioModelDashboard_item extends JModelAdmin
{
	protected $context = 'com_joomportfolio';
       
    /**
     * Method override to check if you can edit an existing record.
     *
     * @param	array	$data	An array of input data.
     * @param	string	$key	The name of the key for the primary key.
     *
     * @return	boolean
     * @since	1.6
     */
    protected function allowEdit($data = array(), $key = 'id') {
        // Check specific edit permission then general edit permission.
        return JFactory::getUser()->authorise('core.edit', 'com_joomportfolio.dashboard_item.' . ((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
    }

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Dashboard_item', $prefix = 'JoomPortfolioTable', $config = array()) {
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
       
        $form = $this->loadForm($this->context . '.' . $this->getName(), $this->getName(), array('control' => 'jform', 'load_data' => false));
        $form->bind($this->getItem());

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function getItem($pk = null) {
        if (!isset($this->item)) {
            $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
            $table = $this->getTable();

            if ($pk > 0) {
                $return = $table->load($pk);

                if ($return === false && $table->getError()) {
                    $this->setError($table->getError());
                    return false;
                }
            }

            $properties = $table->getProperties(1);
            $this->item = new JObject($properties);
        }

        return $this->item;
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
            $app->setUserState('com_joomportfolio.default.dashboard_item.data', $data);
        }

        return true;
    }

    public function check($data)
    {
        if (count($data)) {

            if ($data['title'] == '' || $data['icon']=='' || $data['url']=='') {

                if ($data['title'] == '') {
                    throw new Exception(JText::_('COM_JOOMPORTFOLIO_ERROR_TITLE'), 404);
                }
                if ($data['icon']=='') {
                    throw new Exception(JText::_('COM_JOOMPORTFOLIO_DASHBOARD_ERROR_ICON'), 404);
                }

                if ($data['url']=='') {
                    throw new Exception(JText::_('COM_JOOMPORTFOLIO_DASHBOARD_ERROR_URL'), 404);
                }
                JFactory::getApplication()->redirect('index.php?option=com_joomportfolio&view=dashboard_item&layout=edit&id='.(int)$data['id']);
                return false;
            }

            return true;
        }

        JFactory::getApplication()->redirect('index.php?option=com_joomportfolio&view=dashboard_item&layout=edit&id='.(int)$data['id']);
        return false;
    }


}
