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

class JoomPortfolioViewTemplates extends JViewLegacy
{
    function display($tpl = null)
    {

        $leftmenu = JoomPortfolioHelper::getLeftMenu();
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $this->leftmenu = $leftmenu;
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->form = $this->get('Form');
        $this->pagination = $this->get('Pagination');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
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
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO_MANAGER_TEMPLATES'), 'customs');
        if ($canDo->get('core.admin')) {
            JToolBarHelper::editList('template.edit', 'JTOOLBAR_EDIT');

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