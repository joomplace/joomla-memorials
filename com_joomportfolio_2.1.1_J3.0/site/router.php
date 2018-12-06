<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

function JoomPortfolioBuildRoute(&$query) {
    $segments = array();
    $app = JFactory::getApplication();
    $menu = $app->getMenu();
    $item = $menu->getActive();

    if (isset($query['view'])) {
        if ($query['view'] == 'main') {
            $segments[] = 'main';
        }elseif($query['view'] == 'ornaments'){
            $segments[] = 'ornaments';
        }
        unset($query['view']);
    }
    if (isset($query['cid'])) {
        $segments[] = $query['cid'];
        unset($query['cid']);
    }
    if (isset($query['id'])) {
        $segments[] = $query['id'];
        unset($query['id']);
    };
    if (isset($query['extension'])) {
        $segments[] = $query['extension'];
        unset($query['extension']);
    };
    if (isset($query['task'])) {
        $segments[] = $query['task'];
        unset($query['task']);
    };

    return $segments;
}

function JoomPortfolioParseRoute($segments) {
    $vars = array();
    $count = count($segments);
    if ($count == 1) {
        if ($segments[0] == 'main') {
            $vars['view'] = 'main';
        }elseif($segments[0] == 'ornaments'){
            $vars['view'] = 'ornaments';
        }else {
            $vars['view'] = 'category';
            $cid =$segments;
            $vars['id'] = $cid[0];
        }
    } else {
        $id = $segments[$count-1];
        $cid = $segments[$count-2];
        $vars['id'] = $id;
        $vars['cid'] = $cid;
        $vars['view'] = 'item';
    }

    return $vars;

}
