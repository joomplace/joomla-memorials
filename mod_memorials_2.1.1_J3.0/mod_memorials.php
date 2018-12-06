<?php
/**
* JoomPortfolio module for Joomla 3.0
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');
JFactory::getDocument()->addStyleSheet(JURI::root().'/modules/mod_memorials/tmpl/style.css');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$show = $params->get('show',0);
$autoItemid = $params->get('autoItemid',0);
$Itemid=$params->get('Itemid',0);

if ($Itemid) {
	$Itemid = modMemorialsHelper::getItemId();
}

switch ( $show ) {
	case 1: 
		$layout='items';
		$list = modMemorialsHelper::getItemsList($params);
        break;
	case 0: 
	default:
		$layout='default';	
		$list = modMemorialsHelper::getCatsList($params);
		break;
}

if (!count($list)) {
	return;
}

require JModuleHelper::getLayoutPath('mod_memorials',$layout);
