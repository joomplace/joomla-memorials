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

class JoomPortfolioViewImagesimport extends JViewLegacy
{
    function display($tpl = null)
    {
        $submenu = 'imagesimport';
        JoomPortfolioHelper::showTitle($submenu);
        JoomPortfolioHelper::getCSSJS();
        $leftmenu = JoomPortfolioHelper::getLeftMenu();
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $this->leftmenu = $leftmenu;
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');

        $errors = $this->get('Errors');
        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors), 500);
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
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO_MANAGER_IMPORTDATA'), 'importdata');
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JOOMPORTFOLIO_MANAGER_SAMPLEDATA'));
    }
}