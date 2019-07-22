<?php
/**
* JoomPortfolio component for Joomla 3.0
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

class JoomPortfolioTableDashboard_item extends JTable
{
	function __construct(&$db)
	{
		parent::__construct('#__jp3_dashboard_items', 'id', $db);
	}

    public function store($updateNulls = false) {
        return parent::store($updateNulls);
    }
}