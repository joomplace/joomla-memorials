<?php

/**
* JoomPortfolio module for Joomla 3.0
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

JModelLegacy::addIncludePath(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_joomportfolio'.DIRECTORY_SEPARATOR.'models');

class modMemorialsHelper
{
	public static $itemid;

	public static function getCatsList($params)
	{

        $limit=5;
        if((int)$params->get('count_cat',0)){
            $limit=(int)$params->get('count_cat',0);
        }
        $Itemid=$params->get('Itemid',0);
        if(!$Itemid){
            $Itemid=self::getItemId();
        }
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('cc.id, cc.level, cc.parent_id, cc.title, cc.description, cc.lft, cc.rgt, cc.params');
        $query->select('CASE WHEN CHAR_LENGTH(cc.alias) THEN cc.alias ELSE cc.id END as slug');
        $query->from('#__categories AS cc');
        $query->where('cc.extension="com_memorials"');
        $query->where('cc.published=1');
        $query->order('cc.id DESC');


        $db->setQuery($query, 0, $limit);
        $results = $db->loadObjectList();
        $count=count($results);

        for($i=0;$i<$count;$i++){
            $results[$i]->slug=$results[$i]->slug.'&Itemid='.$Itemid;
        }

        foreach ($results as $category) {
            $out[$category->id] = $category;
        }

        $out = array_reverse($out, true);
        foreach ($out as $key=>$category) {
            if ($category->parent_id != 1) {
                if(!isset($out[$category->parent_id]->childs)) {
                    $out[$category->parent_id]->childs = array();
                }
                $out[$category->parent_id]->childs[] = $category;
                unset($out[$key]);
            }
        }
        $out = array_reverse($out, true);

        return $out;

	}
	
	public static function printNode($item, $hash) {
        if(!$hash){
            $hash=self::getItemId();
        }

		echo '<li>';
		echo '<a href="'.JRoute::_('index.php?option=com_joomportfolio&view=category&id='.$item->slug).'" >'.$item->title.'</a>';
		if (isset($item->childs)) {
			foreach ($item->childs as $child) {
				echo '<ul class="joomportfcat" >';
				self::printNode($child, $hash);
				echo '</ul>';
			}
		}
		echo '</li>';
	}

	public static function getItemsList($params)
	{
         $limit=5;
        if((int)$params->get('count_items',0)){
            $limit=(int)$params->get('count_items',0);
        }

        $Itemid=$params->get('Itemid',0);
        if(!$Itemid){
            $Itemid=self::getItemId();
        }

        $db = JFactory::getDBO();
        $query = "SELECT i.title AS item_title, i.alias AS item_alias, i.published AS item_published,
                  i.description_short AS item_description_short,i.hits AS item_hits, i.custom AS item_custom,
                  i.def_image AS item_def_img,i.id AS item_id, c.*,
                  CASE WHEN CHAR_LENGTH(i.alias) THEN i.alias ELSE i.id END as slug,
                  CASE WHEN CHAR_LENGTH(c.alias) THEN  c.alias ELSE c.id END as cat_slug"
            . "\n FROM #__jp3_items AS i"
            . " LEFT JOIN  #__categories AS c ON c.id=i.cat_id"
            . "\n WHERE i.mode='memorials' AND i.published=1 ORDER BY i.id DESC LIMIT ".$limit;
        $db->setQuery($query);

        $results = $db->loadObjectList();

        $count=count($results);
        for($i=0;$i<$count;$i++){
            $results[$i]->slug=$results[$i]->slug.'&Itemid='.$Itemid;

        }

        return $results;


	}

	public static function getItemId()
	{
		if (!isset(self::$itemid))
		{
            $menu = JFactory::getApplication()->getMenu();
			$active = $menu->getActive();

			if (!$active or ($active->component != 'com_joomportfolio')) {
				$port = $menu->getItems('component', 'com_joomportfolio', true);

				if (!empty($port)) {
                    $id=self::MemorialsMenuItemid($port->id);
                    if($id){
                        $port->id=$id;
                    }
                    if($port->id){
					self::$itemid = '&Itemid='.$port->id;
                    }
				}
			}else{
                    return $active->id;
            }
		}
        return self::$itemid;
	}

    public static function MemorialsMenuItemid($id){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('m.id');
        $query->from('#__menu AS m');
        $query->where('m.link like "%memorials%"');
        $query->where('m.component_id='.(int)$id);
        $query->where('m.published=1');
        $db->setQuery($query, 0, 1);
        $Itemid=$db->loadResult();
        return $Itemid;

    }
}
