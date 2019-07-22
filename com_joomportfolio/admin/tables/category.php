<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

/**
 * Category table
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class JoomPortfolioTableCategory extends JTableCategory
{
	/**
	 * Method to delete a node and, optionally, its child nodes from the table.
	 *
	 * @param   integer  $pk        The primary key of the node to delete.
	 * @param   boolean  $children  True to delete child nodes, false to move them up a level.
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     http://docs.joomla.org/JTableNested/delete
	 * @since   2.5
	 */
	public function delete($pk = null, $children = false)
	{
		return parent::delete($pk, $children);
	}
}
