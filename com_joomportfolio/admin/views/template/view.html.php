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

class JoomPortfolioViewTemplate extends JViewLegacy
{
    function display($tpl = null)
    {

        $leftmenu = JoomPortfolioHelper::getLeftMenu();
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $jinput = JFactory::getApplication()->input;

        $this->leftmenu = $leftmenu;
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->id=$jinput->get('id',0,'INT');
        $this->form = $this->get('Form');
        $this->pagination = $this->get('Pagination');
        $this->cat_custom_fields=$this->get('CatCustomFields');
        $this->item_custom_fields=$this->get('ItemCustomFields');

        $errors = $this->get('Errors');
        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors), 500);
        }

        JoomPortfolioHelper::addManagementSubmenu('templates');
        $this->sidebar = JHtmlSidebar::render();
        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        $this->canDo = $canDo = JoomPortfolioHelper::getAllActions();
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO_MANAGER_TEMPLATES'), 'template');
        if ($canDo->get('core.admin')) {
            JToolBarHelper::apply('template.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('template.save', 'JTOOLBAR_SAVE');
        }
        JToolBarHelper::cancel('template.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JOOMPORTFOLIO') . ': ' . JText::_('COM_JOOMPORTFOLIO_MANAGER_TEMPLATES'));
        $document->addStyleSheet(JURI::root() . 'administrator/components/com_joomportfolio/assets/css/template.css');
    }

    protected function getSortFields()
    {
        return array(
            'name' => JText::_('JGLOBAL_TITLE'),
            'type' => JText::_('COM_JOOMPORTFOLIO_TYPE'),
            'req' => JText::_('COM_JOOMPORTFOLIO_REQ'),
            'catview' => JText::_('COM_JOOMPORTFOLIO_SHOW_IN_CATEGORY')
        );
    }
}

?>