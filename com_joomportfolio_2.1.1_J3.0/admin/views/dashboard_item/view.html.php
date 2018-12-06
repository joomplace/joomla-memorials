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

class JoomPortfolioViewDashboard_item extends JViewLegacy
{

    protected $form;
    protected $item;
    protected $state;

    public function display($tpl = null)
    {

        $this->addTemplatePath(JPATH_BASE . '/components/com_joomportfolio/helpers/html');
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        JoomPortfolioHelper::loadLanguage();
        $isNew = $this->item->id == 0;
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO') . ': ' . JText::_('COM_JOOMPORTFOLIO_DASHBOARD_ITEM_EDITING'));
        $this->addToolBar();

        parent::display($tpl);
    }

    protected function addToolBar()
    {

        JToolBarHelper::apply('dashboard_item.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('dashboard_item.save', 'JTOOLBAR_SAVE');
        JToolBarHelper::cancel('dashboard_item.cancel', 'JTOOLBAR_CANCEL');
    }

}
