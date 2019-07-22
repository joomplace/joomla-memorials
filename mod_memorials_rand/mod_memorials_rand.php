<?php

/**
 * Testimonials module for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.helper' );
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helper.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tmpl'.DIRECTORY_SEPARATOR.'Timg.php';
JLoader::register('TimgHelper', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tmpl'.DIRECTORY_SEPARATOR. 'Timg.php');

$helper = new modMemorialsRandHelper();

$user = JFactory::getUser();
$document = JFactory::getDocument();
$app = JFactory::getApplication();
$option = JRequest::getVar('option');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$iCan = new stdClass();
$iCan->manage = ($user->authorise('core.manage', 'com_joomportfolio'));
$iCan->create = ($user->authorise('core.create', 'com_joomportfolio'));

if (!$params->get('all_tags')) {
    $tags_id = $params->get('tags');

    if (count($tags_id) > 1 || (count($tags_id) == 1 && $tags_id[0] != 0))
        $list = $helper->getList($params);
    else
        $list = $helper->getItemsList($params);
}
else
    $list=null;
    $list = $helper->getItemsList($params);

if (empty($list)) {
    echo '<small>' . JText::_('Memorials not found') . '</small>';
    return;
}


$isStatic = $params->get('isstatic');

$show_add_new = (int) $params->get('show_add_new', 0);

$document->addStyleSheet(JURI::root() . '/modules/mod_memorials_rand/tmpl/style.css');

$modal = $params->get('ismodal');

$document = JFactory::getDocument();
JHtml::script(JURI::base() . 'modules/mod_memorials_rand/js/jplace.jquery.js');
JHtml::script(JURI::base() . 'modules/mod_memorials_rand/js/jquery-ui/jquery-ui.js');
JHtml::stylesheet(JURI::base() . 'modules/mod_memorials_rand/js/jquery-ui/jquery-ui.css');

require JModuleHelper::getLayoutPath('mod_memorials_rand', $params->get('layout', 'default'));
?>