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

class JoomPortfolioViewOrnaments extends BaseView
{

    function display($tpl = null)
    {
        $this->images = $this->getOrnaments();

        $errors = $this->get('Errors');
        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors), 500);
        }

        parent::display($tpl);
    }

    function getOrnaments()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('full, id');
        $query->from('#__jp3_condolence');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
}




