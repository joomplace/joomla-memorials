<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Search.memorials
 *
 * @copyright   Copyright (C) JoomPlace, www.joomplace.com . All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Memorials search plugin.
 *
 * @since  1.6
 */
class PlgSearchMemorials extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Determine areas searchable by this plugin.
	 *
	 * @return  array  An array of search areas.
	 *
	 * @since   1.6
	 */
	public function onContentSearchAreas()
	{
		static $areas = array(
			'memorials' => 'PLG_SEARCH_MEMORIALS_MEMORIALS'
		);

		return $areas;
	}

	/**
	 * Search content (memorials).
	 *
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   string  $areas     An array if the search is to be restricted to areas or null to search all areas.
	 *
	 * @return  array  Search results.
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db         = JFactory::getDbo();
        $serverType = $db->getServerType();
		$app        = JFactory::getApplication();
		$user       = JFactory::getUser();
		$groups     = implode(',', $user->getAuthorisedViewLevels());

        JLoader::register('JoomportfolioHelperRoute', JPATH_SITE . '/components/com_joomportfolio/helpers/route.php');
        JLoader::register('SearchHelper', JPATH_ADMINISTRATOR . '/components/com_search/helpers/search.php');

		if (is_array($areas) && !array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
			return array();
		}

		$limit = $this->params->def('search_limit', 50);
		if((int)$limit < 1) {
            return array();
        }

        $search_comments = (int)$this->params->def('search_comments', 1);
        $search_fields   = (int)$this->params->def('search_fields', 1);
        $search_category = (int)$this->params->def('search_category', 1);

        $state = array('1'); //1-published

		$text = trim($text);
		if ($text === '') {
			return array();
		}
        $search_text = $text;

        switch ($phrase) {
            case 'exact':
                $text      = $db->q('%' . $db->escape($search_text, true) . '%', false);
                $wheres2   = array();
                $wheres2[] = 'a.title LIKE ' . $text;
                $wheres2[] = 'a.description_short LIKE ' . $text;
                $wheres2[] = 'a.description LIKE ' . $text;

                if($search_comments) {
                    $subQuery = $db->getQuery(true);
                    $subQuery->select("cm.item_id")
                        ->from("#__jp3_comments AS cm")
                        ->where('cm.comment LIKE ' . $text);

                    if ($serverType == "mysql") {
                        $db->setQuery($subQuery);
                        $comments = $db->loadColumn();

                        if (!empty($comments)) {
                            $wheres2[] = 'a.id IN(' . implode(",", $comments) . ')';
                        }
                    } else {
                        $wheres2[] = $subQuery->castAsChar('a.id') . ' IN( ' . (string)$subQuery . ')';
                    }
                }

                if($search_fields) {
                    //format of the field type 'calendar' is YYYY-MM-DD:
                    $text_for_fields = preg_replace('/(0[1-9]|[12][0-9]|3[01])[- \/.](0[1-9]|1[012])[- \/.](\d{4})/','$3-$2-$1', $search_text);
                    $text_for_fields = $db->q('%' . $db->escape($text_for_fields, true) . '%', false);
                    $subQuery = $db->getQuery(true);
                    $subQuery->select("ic.item_id")
                        ->from("#__jp3_item_content AS ic")
                        ->where('ic.value LIKE ' . $text_for_fields);

                    if ($serverType == "mysql") {
                        $db->setQuery($subQuery);
                        $fieldids = $db->loadColumn();

                        if (!empty($fieldids)) {
                            $wheres2[] = 'a.id IN(' . implode(",", $fieldids) . ')';
                        }
                    } else {
                        $wheres2[] = $subQuery->castAsChar('a.id') . ' IN( ' . (string)$subQuery . ')';
                    }
                }

                $where = '(' . implode(') OR (', $wheres2) . ')';
                break;

            case 'all':
            case 'any':
            default:
                $words = explode(' ', $search_text);
                $wheres = array();
                $cfwhere = array();

                foreach ($words as $word) {
                    $word_for_fields = $word;
                    $word      = $db->q('%' . $db->escape($word, true) . '%', false);
                    $wheres2   = array();
                    $wheres2[] = 'LOWER(a.title) LIKE LOWER(' . $word . ')';
                    $wheres2[] = 'LOWER(a.description_short) LIKE LOWER(' . $word . ')';
                    $wheres2[] = 'LOWER(a.description) LIKE LOWER(' . $word . ')';

                    if($search_comments) {
                        $subQuery = $db->getQuery(true);
                        $subQuery->select("cm.item_id")
                            ->from("#__jp3_comments AS cm")
                            ->where('LOWER(cm.comment) LIKE LOWER(' . $word . ')');

                        if ($serverType == "mysql") {
                            $db->setQuery($subQuery);
                            $comments = $db->loadColumn();

                            if (!empty($comments)) {
                                $wheres2[] = 'a.id IN(' . implode(",", $comments) . ')';
                            }
                        } else {
                            $wheres2[] = $subQuery->castAsChar('a.id') . ' IN( ' . (string)$subQuery . ')';
                        }
                    }

                    if($search_fields) {
                        //format of the field type 'calendar' is YYYY-MM-DD:
                        $word_for_fields = preg_replace('/(0[1-9]|[12][0-9]|3[01])[- \/.](0[1-9]|1[012])[- \/.](\d{4})/', '$3-$2-$1', $word_for_fields);
                        $word_for_fields = $db->q('%' . $db->escape($word_for_fields, true) . '%', false);

                        if ($phrase === 'all') {
                            $subQuery = $db->getQuery(true);
                            $subQuery->select("ic.item_id")
                                ->from("#__jp3_item_content AS ic")
                                ->where('LOWER(ic.value) LIKE LOWER(' . $word_for_fields . ')');

                            if ($serverType == "mysql") {
                                $db->setQuery($subQuery);
                                $fieldids = $db->loadColumn();

                                if (!empty($fieldids)) {
                                    $wheres2[] = 'a.id IN(' . implode(",", $fieldids) . ')';
                                }
                            } else {
                                $wheres2[] = $subQuery->castAsChar('a.id') . ' IN( ' . (string)$subQuery . ')';
                            }
                        } else {
                            $cfwhere[] = 'LOWER(ic.value) LIKE LOWER(' . $word_for_fields . ')';
                        }
                    }

                    $wheres[] = implode(' OR ', $wheres2);
                }

                if($search_fields) {
                    if ($phrase === 'any') {
                        $subQuery = $db->getQuery(true);
                        $subQuery->select("ic.item_id")
                            ->from("#__jp3_item_content AS ic")
                            ->where('(' . implode(($phrase === 'all' ? ') AND (' : ') OR ('), $cfwhere) . ')');

                        if ($serverType == "mysql") {
                            $db->setQuery($subQuery);
                            $fieldids = $db->loadColumn();

                            if (!empty($fieldids)) {
                                $wheres[] = 'a.id IN(' . implode(",", $fieldids) . ')';
                            }
                        } else {
                            $wheres[] = $subQuery->castAsChar('a.id') . ' IN( ' . (string)$subQuery . ')';
                        }
                    }
                }

                $where = '(' . implode(($phrase === 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
                break;
        }

        switch ($ordering) {
            case 'oldest':
                $order = 'a.date ASC';
                break;

            case 'popular':
                $order = 'a.title DESC';
                break;

            case 'alpha':
                $order = 'a.title ASC';
                break;

            case 'category':
                $order = 'c.title ASC, a.title ASC';
                break;

            case 'newest':
            default:
                $order = 'a.date DESC';
                break;
        }

        $query = $db->getQuery(true);
        $query->select(
            'a.id, a.alias, a.cat_id, a.title, a.date AS created, a.description AS text, a.description_short, '
            . ' c.title AS section, '
            . '\'2\' AS browsernav'
        );

        $query->from('#__jp3_items AS a')
            ->join('INNER', '#__categories AS c ON c.id = a.cat_id')
            ->where(
                '(' . $where . ') AND a.published IN (' . implode(',', $state) . ') AND c.published=1 '
                . ' AND c.access IN (' . $groups . ')'
            )
            ->group('a.id, a.title, a.date')
            ->order($order);

        if($search_comments) {
            $query->select($db->qn('cm.comment'));
            $query->join('LEFT', '#__jp3_comments AS cm ON cm.item_id = a.id');
        }

        // Filter by language.
        if ($app->isClient('site') && JLanguageMultilang::isEnabled()) {
            $tag = JFactory::getLanguage()->getTag();
            $query->where('c.language in (' . $db->q($tag) . ',' . $db->q('*') . ')');
        }

        $db->setQuery($query, 0, $limit);

        try {
            $rows = $db->loadObjectList();
        } catch (RuntimeException $e) {
            $rows = array();
            $app->enqueueMessage(JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
        }

        if(!empty($rows)) {
            foreach ($rows as $key => $item) {
                $rows[$key]->href = JoomportfolioHelperRoute::getMemorialRoute($item->alias, $item->cat_id);
            }

            $limit -= count($rows);
        }

        //Search by category names and descriptions
        if($search_category && $limit > 0) {

            switch ($phrase) {
                case 'exact':
                    $text      = $db->q('%' . $db->escape($search_text, true) . '%', false);
                    $wheres2   = array();
                    $wheres2[] = 'c.title LIKE ' . $text;
                    $wheres2[] = 'c.description LIKE ' . $text;
                    $where     = '(' . implode(') OR (', $wheres2) . ')';
                    break;

                case 'all':
                case 'any':
                default:
                    $words = explode(' ', $search_text);
                    $wheres = array();

                    foreach ($words as $word) {
                        $word      = $db->q('%' . $db->escape($word, true) . '%', false);
                        $wheres2   = array();
                        $wheres2[] = 'LOWER(c.title) LIKE LOWER(' . $word . ')';
                        $wheres2[] = 'LOWER(c.description) LIKE LOWER(' . $word . ')';
                        $wheres[]  = implode(' OR ', $wheres2);
                    }

                    $where = '(' . implode(($phrase === 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
                    break;
            }

            switch ($ordering) {
                case 'oldest':
                    $order = 'c.created_time ASC';
                    break;

                case 'popular':
                case 'alpha':
                case 'category':
                    $order = 'c.title DESC';
                    break;

                case 'newest':
                default:
                    $order = 'c.created_time DESC';
                    break;
            }

            $query = $db->getQuery(true);
            $query->select(
                'c.id, c.alias, c.title, c.created_time AS created, c.modified_time, c.description AS text, '
                . ' c.title AS section, '
                . '\'2\' AS browsernav'
            );

            $query->from('#__categories AS c')
                ->where($db->qn('extension') .'='. $db->q('com_memorials'))
                ->where(
                    '(' . $where . ') AND c.published=1 AND c.access IN (' . $groups . ')'
                )
                ->group('c.title, c.alias, c.id')
                ->order($order);

            // Filter by language.
            if ($app->isClient('site') && JLanguageMultilang::isEnabled()) {
                $tag = JFactory::getLanguage()->getTag();
                $query->where('c.language in (' . $db->q($tag) . ',' . $db->q('*') . ')');
            }

            $db->setQuery($query, 0, $limit);

            try {
                $rows_cat = $db->loadObjectList();
            } catch (RuntimeException $e) {
                $rows_cat = array();
                $app->enqueueMessage(JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
            }

            if(!empty($rows_cat)) {
                foreach ($rows_cat as $key => $item) {
                    $rows_cat[$key]->href = JoomportfolioHelperRoute::getCategoryRoute($item->id);

                    if($rows_cat[$key]->created == '0000-00-00 00:00:00' && $rows_cat[$key]->modified_time != '0000-00-00 00:00:00') {
                        $rows_cat[$key]->created = $rows_cat[$key]->modified_time;
                    }
                }

                $rows = array_merge($rows, (array)$rows_cat);
            }
        }

        return $rows;
	}
}
