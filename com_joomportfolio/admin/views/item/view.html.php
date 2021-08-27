<?php

/**
 * JoomPortfolio component for Joomla 3
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Item View
 */
class JoomPortfolioViewItem extends JViewLegacy
{

    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null)
    {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $submenu = 'item';
        JoomPortfolioHelper::showTitle($submenu);
        //JoomPortfolioHelper::getCSSJS();

        $this->leftmenu = JoomPortfolioHelper::getLeftMenu();
        $this->custom = $this->get('FieldsValue');
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $model = $this->getModel('Item');
        //$this->fields= $model->getItemFields();
        $this->fields = $model->getItemFields();
        //die(var_dump($this->fields));
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        JoomPortfolioHelper::addManagementSubmenu('items');
        $this->sidebar = JHtmlSidebar::render();

        $errors = $this->get('Errors');
        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors), 500);
        }

        // Set the toolbar
        $this->addToolBar();

        // Set the document
        $this->setDocument();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        $user = JFactory::getUser();
        $userId = $user->id;
        $isNew = $this->item->id == 0;
        $canDo = JoomPortfolioHelper::getAllActions($this->item->id);
        JToolBarHelper::title($isNew ? JText::_('COM_JOOMPORTFOLIO_ITEM_CREATING') : JText::_('COM_JOOMPORTFOLIO_ITEM_EDITING'), 'items');
        // Built the actions for new and existing records.
        if ($isNew) {
            // For new records, check the create permission.
            if ($canDo->get('core.create')) {
                JToolBarHelper::apply('item.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('item.save', 'JTOOLBAR_SAVE');
                JToolBarHelper::custom('item.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            }
            JToolBarHelper::cancel('item.cancel', 'JTOOLBAR_CANCEL');
        } else {
            if ($canDo->get('core.edit')) {
                // We can save the new record
                JToolBarHelper::apply('item.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('item.save', 'JTOOLBAR_SAVE');
                JToolBarHelper::custom('item.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            }

            JToolBarHelper::cancel('item.cancel', 'JTOOLBAR_CLOSE');
        }
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $isNew = $this->item->id == 0;
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_JOOMPORTFOLIO_ITEM_CREATING') : JText::_('COM_JOOMPORTFOLIO_ITEM_EDITING'));
        $document->addScript(JURI::root() . 'administrator/components/com_joomportfolio/assets/js/Picker.js');
        $document->addStyleSheet(JURI::root() . 'administrator/components/com_joomportfolio/assets/css/datepicker.css');
    }

}
