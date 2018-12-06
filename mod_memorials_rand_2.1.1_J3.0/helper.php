<?php

/**
 * memorials Module for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// no direct access

defined('_JEXEC') or die;


jimport('joomla.application.component.model');



JModelLegacy::addIncludePath(JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'models');


class modMemorialsRandHelper
{
    public static $itemid;
    public function getList($params)
    {

        $db = JFactory::getDBO();
        $Itemid=$params->get('Itemid',0);
        if(!$Itemid){
            $Itemid=self::getItemId();
        }
        $query = $db->getQuery(true);

        $query->select('i.title AS item_title, i.alias AS item_alias, i.published AS item_published');
        $query->select('i.description_short AS item_description_short,i.hits AS item_hits, i.custom AS item_custom');
        $query->select('i.def_image AS item_def_img,i.id AS item_id, c.*');
        $query->select('i.def_image AS item_def_img,i.id AS item_id, c.*');
        $query->select('CASE WHEN CHAR_LENGTH(i.alias) THEN  i.alias ELSE i.id END as slug');
        $query->select('CASE WHEN CHAR_LENGTH(c.alias) THEN c.alias ELSE c.id END as cat_slug');

        $query->from('#__jp3_items AS i');
        $query->leftJoin('#__categories AS c ON c.id=i.cat_id');
        $query->where('i.mode="memorials" AND i.published=1');
        $query->order('RAND()');
        $db->setQuery($query, 0,1);
        $items = $db->loadObjectList();

        $img=$this->getImg($items[0]->item_id);
        $items[0]->photo=$img;
        //$items[0]->item_description_short = (strlen($items[0]->item_description_short) > 300) ? substr($items[0]->item_description_short,0,10).'...' : $items[0]->item_description_short;
        return $items;
    }

    public function getItemsList($params)
    {
        $Itemid=$params->get('Itemid',0);
        if(!$Itemid){
            $Itemid=self::getItemId();
        }
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('i.title AS item_title, i.alias AS item_alias, i.published AS item_published');
        $query->select('i.description_short AS item_description_short,i.hits AS item_hits, i.custom AS item_custom');
        $query->select('i.def_image AS item_def_img,i.id AS item_id, c.*');
        $query->select('i.def_image AS item_def_img,i.id AS item_id, c.*');
        $query->select('CASE WHEN CHAR_LENGTH(i.alias) THEN  i.alias ELSE i.id END as slug');
        $query->select('CASE WHEN CHAR_LENGTH(c.alias) THEN  c.alias ELSE c.id END as cat_slug');

        $query->from('#__jp3_items AS i');
        $query->leftJoin('#__categories AS c ON c.id=i.cat_id');
        $query->where('i.mode="memorials" AND i.published=1');
        $query->order('RAND()');
        $db->setQuery($query);
        $items = $db->loadObjectList();

        $count=count($items);
        for($i=0; $i<$count; $i++){
            $items[$i]->photo=$this->getImg($items[$i]->item_id);
            $items[$i]->slug=$items[$i]->slug.'&Itemid='.$Itemid;
           // $items[$i]->item_description_short = (strlen($items[$i]->item_description_short) > 300) ? substr($items[$i]->item_description_short,0,10).'...' : $items[$i]->item_description_short;
        }

        return $items;
    }

    public function getImg($id)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('pii.*');
        $query->from('#__jp3_pictures AS pii');
        $query->where('pii.item_id=' . (int)$id);
        $query->order('is_default DESC');
        $query->order('ordering');
        $db->setQuery($query);

        $images = $db->loadObject();
        return $images;
    }

    public function getModuleSettings()
    {

        $module = JModuleHelper::getModule('mod_memorials_rand');

        $params = new JRegistry;

        $params->loadString($module->params);

        return $params;
    }

    public function getComponentSettings()
    {

        return JComponentHelper::getParams('com_joomportfolio');
    }

    public static function getItemId()
    {
        if (!isset(self::$itemid)) {

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

