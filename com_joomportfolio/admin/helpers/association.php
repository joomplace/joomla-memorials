<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// no direct access

defined('_JEXEC') or die;

JLoader::register('CategoriesHelper', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/categories.php');

/**
 * Category Component Association Helper
 *
 * @package     Joomla.Site
 * @subpackage  com_categories
 * @since       3.0
 */
abstract class CategoryHelperAssociation
{
	public static $category_association = true;

	/**
	 * Method to get the associations for a given category
	 *
	 * @param   integer  $id         Id of the item
	 * @param   string   $extension  Name of the component
	 *
	 * @return  array    Array of associations for the component categories
	 *
	 * @since  3.0
	 */

	public static function getCategoryAssociations($id = 0, $extension = 'com_content')
	{
		$return = array();

		if ($id)
		{
			// Load route helper
			jimport('helper.route', JPATH_COMPONENT_SITE);
			$helperClassname = ucfirst(substr($extension, 4)) . 'HelperRoute';

			$associations = CategoriesHelper::getAssociations($id, $extension);
			foreach ($associations as $tag => $item)
			{
				if (class_exists($helperClassname) && is_callable(array($helperClassname, 'getCategoryRoute')))
				{
					$return[$tag] = $helperClassname::getCategoryRoute($item, $tag);
				}
				else
				{
					$return[$tag] = 'index.php?option=' . $extension . '&view=category&id=' . $item;
				}
			}
		}

		return $return;
	}
}
