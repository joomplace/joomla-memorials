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

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Item View
 */
class JoomPortfolioViewImages extends JViewLegacy
{
    public function display($tpl = null)
    {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'html');

        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->leftmenu = JoomPortfolioHelper::getLeftMenu();
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        JoomPortfolioHelper::loadLanguage();
        $this->addToolBar();
         JoomPortfolioHelper::addManagementSubmenu('images');
        $this->sidebar = JHtmlSidebar::render();
        $this->setDocument();
        parent::display($tpl);
    }

    protected function addToolBar() {
        $this->canDo = $canDo = JoomPortfolioHelper::getAllActions();
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO_CONDOLE_SUBMENU'), 'items');
        if ($canDo->get('core.admin')) {
            JToolBarHelper::addNew('images.upload', 'JTOOLBAR_NEW');
            JToolBarHelper::deleteList('', 'images.delete', 'JTOOLBAR_DELETE');
        }
    }

    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle( JText::_('COM_JOOMPORTFOLIO') );
    }
}
