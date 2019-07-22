<?php

/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML View class for the Joomportfolio Component
 */
class JoomPortfolioViewAbout extends JViewLegacy
{
    function display($tpl = null)
    {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $submenu = 'about';
        JoomPortfolioHelper::showTitle($submenu);
        JoomPortfolioHelper::getCSSJS();
        $this->menu = JoomPortfolioHelper::getControlPanel();
        $this->leftmenu = JoomPortfolioHelper::getLeftMenu();
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
        JoomPortfolioHelper::addManagementSubmenu('about');
        $this->sidebar = JHtmlSidebar::render();
        // Set the toolbar
        $this->addToolBar();
        // Set the document
        $this->setDocument();
        parent::display($tpl);
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO_MANAGER_ABOUT'), 'joomportfolio');
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JOOMPORTFOLIO_MANAGER_ABOUT'));
        $document->addScript(JURI::root() . 'administrator/components/com_joomportfolio/assets/js/js.js');
    }
}