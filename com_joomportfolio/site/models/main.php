<?php

/**
* JoomPortfolio component for Joomla 3.x
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR.'item.php');

class JoomPortfolioModelMain extends BaseItem
{
	public function getCategories()
	{
		if (!isset($this->categories)) {
			$model = JModelLegacy::getInstance('Categories', 'JoomPortfolioModel', array('ignore_request' => true));
                        $mode=JoomPortfolioHelper::getVarMode();
                      
			$model->setState('filter.id', 1);
			$model->setState('filter.level', 1);
			$model->setState('query.select', "(SELECT COUNT(pc.item_id) FROM #__jp3_href AS pc
                                                           LEFT JOIN #__jp3_items AS pi ON pi.id = pc.item_id
                                                           WHERE pc.cat_id=cc.id AND 
                                                           pi.published = 1 AND pi.mode='".$mode."') AS items_count");
			$model->setState('array.key', 'id');

			$this->categories = $model->getItems();
		}
		return $this->categories;
	}
}
