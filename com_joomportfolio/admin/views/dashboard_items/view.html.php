<?php
/**
 * JoomPortfolio component for Joomla 3.0
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JoomPortfolioViewDashboard_Items extends JViewLegacy
{
    protected $items = null;

    function display($tpl = null)
    {
        $this->addTemplatePath(JPATH_BASE . '/components/com_joomportfolio/helpers/html');

        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar()
    {

        JoomPortfolioHelper::loadLanguage();
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO') . ': ' . JText::_('COM_JOOMPORTFOLIO_MANAGER_DASHBOARD_ITEMS'), 'dashboard items');
        JToolBarHelper::addNew('dashboard_items.add', 'JTOOLBAR_NEW');
        JToolBarHelper::editList('dashboard_items.edit', 'JTOOLBAR_EDIT');

        JToolBarHelper::divider();

    }

    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JOOMPORTFOLIO') . ': ' . JText::_('COM_JOOMPORTFOLIO_MANAGER_DASHBOARD_ITEMS'));
    }
}
