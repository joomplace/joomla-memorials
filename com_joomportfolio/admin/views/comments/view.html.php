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
 * Comments View
 */
class JoomPortfolioViewComments extends JViewLegacy
{

    /**
     * Comments view display method
     * @return void
     */
    function display($tpl = null)
    {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $submenu = 'comments';
        JoomPortfolioHelper::showTitle($submenu);
        JoomPortfolioHelper::getCSSJS();
        $this->leftmenu = JoomPortfolioHelper::getLeftMenu();
        // Get data from the model
        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        $errors = $this->get('Errors');
        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors), 500);
        }

        JoomPortfolioHelper::addManagementSubmenu('comments');
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
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO_MANAGER_COMMENTS'), 'items');
        if ($canDo->get('core.admin')) {
            JToolBarHelper::addNew('comment.add', 'JTOOLBAR_NEW');
            JToolBarHelper::editList('comment.edit', 'JTOOLBAR_EDIT');
            JToolBarHelper::deleteList('', 'comment.delete', 'JTOOLBAR_DELETE');
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
            'i.item_id' => JText::_('COM_JOOMPORTFOLIO_COMMENTS_ITEM_ID'),
            'i.date' => JText::_('COM_JOOMPORTFOLIO_COMMENTS_DATE'),
            'i.published' => JText::_('JSTATUS'),
            'u.name' => JText::_('COM_JOOMPORTFOLIO_COMMENTS_USERNAME')
        );
    }

}
