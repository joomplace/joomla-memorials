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
 * Items View
 */
class JoomPortfolioViewItems extends JViewLegacy
{

    /**
     * Items view display method
     * @return void
     */
    function display($tpl = null)
    {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $submenu = 'items';
        JoomPortfolioHelper::showTitle($submenu);
        JoomPortfolioHelper::getCSSJS();
        $this->leftmenu = JoomPortfolioHelper::getLeftMenu();
        // Get data from the model
        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->categories = $this->get('Categories');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        JoomPortfolioHelper::addManagementSubmenu('items');
        $this->sidebar = JHtmlSidebar::render();
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
        $this->canDo = $canDo = JoomPortfolioHelper::getAllActions();
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO_MANAGER_ITEMS'), 'items');
        if ($canDo->get('core.admin')) {
            JToolBarHelper::addNew('item.add', 'JTOOLBAR_NEW');
            JToolBarHelper::editList('item.edit', 'JTOOLBAR_EDIT');
            JToolBarHelper::deleteList('', 'item.delete', 'JTOOLBAR_DELETE');
            JToolBarHelper::custom('items.export', 'download.png', 'download.png', 'JTOOLBAR_EXPORT', false);
        }
           }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JOOMPORTFOLIO') . ': ' . JText::_('COM_JOOMPORTFOLIO_MANAGER_ITEMS'));
    }

    protected function getSortFields()
    {
        return array(
            'i.title' => JText::_('JGLOBAL_TITLE'),
            'i.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'i.published' => JText::_('JSTATUS'),
            'i.id' => JText::_('JGRID_HEADING_ID'),
            'i.hits' => JText::_('COM_JOOMPORTFOLIO_HITS'),
            'c.title' => JText::_('COM_JOOMPORTFOLIO__CATEGORY')
        );
    }

}
