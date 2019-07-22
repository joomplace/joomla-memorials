<?php

/**
 * JoomPortfolio component for Joomla 3
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JoomPortfolioViewField extends JViewLegacy
{
    protected $form = null;

    public function display($tpl = null)
    {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $submenu = 'field';
        JoomPortfolioHelper::showTitle($submenu);
        JoomPortfolioHelper::getCSSJS();
        $leftmenu = JoomPortfolioHelper::getLeftMenu();

        $this->leftmenu = $leftmenu;

        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        //die(var_dump($this->item));
        $this->form = $this->get('Form');
        JoomPortfolioHelper::addManagementSubmenu('fields');
        $this->sidebar = JHtmlSidebar::render();
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        $isNew = !isset($this->item->name);
        $canDo = JoomPortfolioHelper::getAllActions();
        JToolBarHelper::title($isNew ? JText::_('COM_JOOMPORTFOLIO_FIELD_CREATING') : JText::_('COM_JOOMPORTFOLIO_FIELD_EDITING'), 'customs');
        if ($canDo->get('core.admin')) {
            JToolBarHelper::apply('field.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('field.save', 'JTOOLBAR_SAVE');
            JToolBarHelper::custom('field.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            if (!$isNew) {
                JToolBarHelper::custom('field.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            }
            JToolBarHelper::cancel('field.cancel', 'JTOOLBAR_CLOSE');
        }
    }

    protected function setDocument()
    {
        $isNew = !isset($this->item->name);
        $doc = $this->document;
        $doc->setTitle($isNew ? JText::_('COM_JOOMPORTFOLIO_FIELD_CREATING') : JText::_('COM_JOOMPORTFOLIO_FIELD_EDITING'));
        $doc->addScript('components/com_joomportfolio/assets/js/fields.js');
        $doc->addScript(JURI::root() . 'administrator/components/com_joomportfolio/assets/js/Picker.js');
        $doc->addStyleSheet(JURI::root() . 'administrator/components/com_joomportfolio/assets/css/datepicker.css');
    }
}

?>