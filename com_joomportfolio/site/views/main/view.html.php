<?php

/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

include_once(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'baseview.php');

class JoomPortfolioViewMain extends BaseView
{

    function display($tpl = null)
    {
        $model = $this->getModel();

        $mode = JoomPortfolioHelper::getVarMode();
        if (!isset($mode)) {
            JError::raiseNotice('404', JText::_('COM_JOOMPORTFOLIO_NO_ITEM'));

            $this->main_err =  JError::raiseNotice('404', JText::_('COM_JOOMPORTFOLIO_MAIN_MODE_ERROR'));
            parent::display($tpl);
        } else {

            $this->categories = $model->getCategories();

            $this->params = $model->getState('params');
            $this->settings = JoomPortfolioHelper::getSettings();
            if (!empty($errors = $this->get('Errors'))) {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
            }

            parent::display($tpl);
        }
    }

}
