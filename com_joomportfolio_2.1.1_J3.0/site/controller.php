<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Portfolio Component Controller
 */
class JoomPortfolioController extends JControllerLegacy {

    public function display($cachable = false, $urlparams = false) {
        $input = JFactory::getApplication()->input;
        // view check
        $input->set('view', $input->get('view', 'main'));

        $doc = JFactory::getDocument();
        $doc->addStyleSheet('components/com_joomportfolio/assets/css/grid.css');
        $doc->addStyleSheet('components/com_joomportfolio/assets/css/style.css');
        JoomPortfolioHelper::loadLanguage();
        parent::display($cachable, $urlparams);
    }

}
