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

abstract class JoomportfolioHelperRoute
{
	protected static $lookup = array();

	protected static $lang_lookup = array();

    protected static $menu_items = array();

	public static function getMemorialRoute($id, $catid = 0, $language = 0)
	{
		$needles = array(
			'article'  => array($id)
		);

		$link = 'index.php?option=com_joomportfolio&view=item&id='. $id;

		if ((int) $catid > 1) {
			$categories = JCategories::getInstance('Joomportfolio');
			$category = $categories->get((int) $catid);
			if ($category) {
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&cid='.$category->alias;
			}
		}

		if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
			self::buildLanguageLookup();
			if (isset(self::$lang_lookup[$language])) {
				$link .= '&lang=' . self::$lang_lookup[$language];
				$needles['language'] = $language;
			}
		}

		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	public static function getCategoryRoute($catid, $language = 0)
	{
		if ($catid instanceof JCategoryNode) {
			$id = $catid->id;
			$category = $catid;
		} else {
            $categories = JCategories::getInstance('Joomportfolio');
            $category = $categories->get((int)$catid);
            $id = (int)$category->id;
		}

		if ($id < 1 || !($category instanceof JCategoryNode)) {
			$link = '';
		} else {
			$needles = array();
			$link = 'index.php?option=com_joomportfolio&view=category&id='.$category->alias;

			$catids = array_reverse($category->getPath());
			$needles['category'] = $catids;
			$needles['categories'] = $catids;

			if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
				self::buildLanguageLookup();
				if(isset(self::$lang_lookup[$language])) {
					$link .= '&lang=' . self::$lang_lookup[$language];
					$needles['language'] = $language;
				}
			}

			if ($item = self::_findItem($needles)) {
				$link .= '&Itemid='.$item;
			}
		}

		return $link;
	}

	protected static function buildLanguageLookup()
	{
		if (count(self::$lang_lookup) == 0) {
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('a.sef AS sef')
				->select('a.lang_code AS lang_code')
				->from('#__languages AS a');

			$db->setQuery($query);
			$langs = $db->loadObjectList();

			foreach ($langs as $lang) {
				self::$lang_lookup[$lang->lang_code] = $lang->sef;
			}
		}
	}

	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
		$language	= isset($needles['language']) ? $needles['language'] : '*';

		// Prepare the reverse lookup array.
		if (!isset(self::$lookup[$language])) {
			self::$lookup[$language] = array();

			$component	= JComponentHelper::getComponent('com_joomportfolio');

			$attributes = array('component_id');
			$values = array($component->id);

			if ($language != '*') {
				$attributes[] = 'language';
				$values[] = array($needles['language'], '*');
			}

			$items = $menus->getItems($attributes, $values);
            self::$menu_items[$language] = $items;

			foreach ($items as $item) {
				if (isset($item->query) && isset($item->query['view'])) {
					$view = $item->query['view'];
					if (!isset(self::$lookup[$language][$view])) {
						self::$lookup[$language][$view] = array();
					}
					if (isset($item->query['id'])) {
						// here it will become a bit tricky
						// language != * can override existing entries
						// language == * cannot override existing entries
						if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*') {
							self::$lookup[$language][$view][$item->query['id']] = $item->id;
						}
					}
				}
			}
		}

		if ($needles) {
			foreach ($needles as $view => $ids) {
				if (isset(self::$lookup[$language][$view])) {
					foreach ($ids as $id) {
						if (isset(self::$lookup[$language][$view][(int) $id])) {
							return self::$lookup[$language][$view][(int) $id];
						}
					}
				}
			}
		}

		$active = $menus->getActive();

		if ($active && ($active->language == '*' || !JLanguageMultilang::isEnabled())) {
		    if($active->component == 'com_joomportfolio'){
                return $active->id;
            } else {
		        //Search Results Page
		        if($active->component == 'com_search' && $active->link == 'index.php?option=com_search&view=search'
                    && !empty(self::$menu_items[$language][0])
                ) {
                    return self::$menu_items[$language][0]->id;
                }
            }
		}

		// if not found, return language specific home link
		$default = $menus->getDefault($language);
		return !empty($default->id) ? $default->id : null;
	}
}
