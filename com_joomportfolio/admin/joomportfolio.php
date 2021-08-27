<?php

/**
* JoomPortfolio component for Joomla 2.5
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_joomportfolio')) 
{
    throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

// require helper file
JLoader::register('JoomPortfolioHelper', dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'joomportfolio.php');
JLoader::register('JimgHelper', dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'jimg.php');
// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by JoomPortfolio
$controller = JControllerLegacy::getInstance('JoomPortfolio');

// Perform the Request task
$input =JFactory::getApplication()->input;
$controller->execute($input->get('task'));

// Redirect if set by the controller
$controller->redirect();
