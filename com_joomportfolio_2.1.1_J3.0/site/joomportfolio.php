<?php

/**
* JoomPortfolio component for Joomla 3.x
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

JLoader::register('JoomPortfolioHelper', dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'joomportfolio.php');

jimport('joomla.application.component.controller');

$input =JFactory::getApplication()->input;

$controller = JControllerLegacy::getInstance('JoomPortfolio');
$controller->execute($input->get('task'));
$controller->redirect();
