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

class JoomPortfolioViewHelp extends JViewLegacy
{
    function display($tpl = null)
    {
        //01.09.2020 Temporary solution:
        JFactory::getApplication()->redirect('index.php?option=com_joomportfolio');

        $submenu = 'help';
        JoomPortfolioHelper::showTitle($submenu);
        JoomPortfolioHelper::getCSSJS();
        $leftmenu = JoomPortfolioHelper::getLeftMenu();
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $this->leftmenu = $leftmenu;
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

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
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO_MANAGER_HELP'), 'help');
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JOOMPORTFOLIO_MANAGER_HELP'));
    }
}