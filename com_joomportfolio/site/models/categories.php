<?php
/**
* JoomPortfolio component for Joomla 2.5
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR.'list.php');
require_once(JPATH_BASE . DIRECTORY_SEPARATOR .'components'. DIRECTORY_SEPARATOR .'com_joomportfolio'.DIRECTORY_SEPARATOR .'helpers' . DIRECTORY_SEPARATOR . 'joomportfolio.php');
class JoomPortfolioModelCategories extends BaseList
{
	public function getListQuery()
	{
            $mode='com_'.JoomPortfolioHelper::getVarMode();
          
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select('cc.id, cc.level, cc.parent_id, cc.title, cc.description, cc.lft, cc.rgt, cc.params');
        $query->select('CASE WHEN CHAR_LENGTH(cc.alias) THEN cc.alias ELSE cc.id END as slug');



        $select = $this->getState('query.select');
		if ($select) {
			$query->select($select);
		}

		$query->from('#__categories AS cp');
		$query->from('#__categories AS cc');

		$query->where('cc.extension="'.$mode.'"');
		$query->where('cc.published=1');
		$query->where('cc.access=1');
		$query->where('cc.lft BETWEEN cp.lft+1 AND cp.rgt');

		$query->order('cc.lft');
		$query->group('cc.id');

		$cid = $this->getState('filter.id');
		if ($cid) {
			$query->where('cp.id='.$cid);
		}

		$maxlevel = $this->getState('filter.level.max');
		if ($maxlevel) {
			$query->where('cc.level<'.$maxlevel);
		}

		$level = $this->getState('filter.level');
		if ($level) {
			$query->where('cc.level='.$level);
		}
//die(var_dump( $query->__toString()));
		return $query;
	}
}