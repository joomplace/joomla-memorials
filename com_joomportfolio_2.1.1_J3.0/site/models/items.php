<?php

/**
* JoomPortfolio component for Joomla 3.x
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR.'list.php');
require_once(JPATH_BASE . DIRECTORY_SEPARATOR .'components'. DIRECTORY_SEPARATOR .'com_joomportfolio'.DIRECTORY_SEPARATOR .'helpers' . DIRECTORY_SEPARATOR . 'joomportfolio.php');
class JoomPortfolioModelItems extends BaseList
{
	public function getListQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select('SQL_CALC_FOUND_ROWS pi.*');
		$query->select('CASE WHEN CHAR_LENGTH(pi.alias) THEN CONCAT(CONCAT_WS(":", pi.id, pi.alias),"&extension='.JoomPortfolioHelper::getVarMode().'") ELSE CONCAT(pi.id,"&extension='.JoomPortfolioHelper::getVarMode().'") END as slug');
		$query->from('#__jp3_items AS pi');

		$query->select('pr.lastip AS rating_lastip, pr.sum AS rating_sum, pr.count as rating_count');
		$query->join('LEFT', '#__jp3_rating AS pr ON pr.item_id=pi.id');

		$query->select('GROUP_CONCAT(pii.full) AS images');
		$query->join('LEFT', '#__jp3_pictures AS pii ON pii.item_id=pi.id');

		$query->select('GROUP_CONCAT(pic.cat_id) AS cids');
		$query->join('LEFT', '#__jp3_href AS pic ON pic.item_id=pi.id');

		$query->select('CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT(CONCAT_WS(":", cc.id, cc.alias),"&extension='.JoomPortfolioHelper::getVarMode().'") ELSE CONCAT(cc.id,"&extension='.JoomPortfolioHelper::getVarMode().'") END as first_slug');
		$query->join('LEFT', '#__categories AS cc ON cc.id=pic.cat_id');

		$cid = $this->getState('filter.cid');
		if ($cid) {
			$query->where('(SELECT COUNT(*) FROM #__jp3_href AS c WHERE c.item_id=pi.id AND c.cat_id='.(int)$cid.') > 0 AND pi.published = 1');
		}

		$id = $this->getState('filter.id');
		if ($id) {
			$query->where('pi.id='.$id);
		}
        $sort = $this->getState('list.desc');
        if ($sort) {
            $query->order('pi.date, pi.ordering DESC');
        } else {
            $query->order('pi.ordering');
        }
        $query->group('pi.id');
//echo "<pre>"; print_r($query); echo "</pre>";
		return $query;
	}
}