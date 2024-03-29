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

class JoomPortfolioViewFields extends JViewLegacy
{
    function display($tpl = null)
    {
        //$submenu = 'fields';
        //JoomPortfolioHelper::showTitle($submenu);
        //JoomPortfolioHelper::getCSSJS();
        $leftmenu = JoomPortfolioHelper::getLeftMenu();
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $this->leftmenu = $leftmenu;
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->form = $this->get('Form');
        $this->pagination = $this->get('Pagination');

        $errors = $this->get('Errors');
        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors), 500);
        }

        JoomPortfolioHelper::addManagementSubmenu('fields');
        $this->sidebar = JHtmlSidebar::render();
        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        $this->canDo = $canDo = JoomPortfolioHelper::getAllActions();
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO_MANAGER_FIELDS'), 'customs');
        if ($canDo->get('core.admin')) {
            JToolBarHelper::addNew('field.add', 'JTOOLBAR_NEW');
            JToolBarHelper::editList('field.edit', 'JTOOLBAR_EDIT');
            JToolBarHelper::deleteList('', 'field.delete', 'JTOOLBAR_DELETE');
        }
    }

    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JOOMPORTFOLIO') . ': ' . JText::_('COM_JOOMPORTFOLIO_MANAGER_FIELDS'));
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