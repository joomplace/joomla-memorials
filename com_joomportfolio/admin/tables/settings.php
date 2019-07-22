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
jimport('joomla.filter.output');

class JoomPortfolioTableSettings extends JTable
{
	

	function __construct(&$db)
	{

		parent::__construct('#__jp3_settings', 'id', $db);
	}
	
	
}
