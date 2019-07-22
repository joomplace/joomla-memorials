<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'item.php');

class JoomPortfolioModelItem extends BaseItem
{

    public function getItemQuery($id = false, $select = false)
    {
        $db = $this->getDBO();
        $query = $db->getQuery(true);

        if (!$id) {
            return $query;
        }

        if ($select) {
            $query->select('pi.' . $select.', c.title AS cat_title');
        } else {
            $query->select('pi.*');
        }
        $query->from('#__jp3_items AS pi ');
        $query->select('CASE WHEN CHAR_LENGTH(pi.alias) THEN pi.alias ELSE pi.id END as slug');

        $query->select('c.title AS cat_title, CASE WHEN CHAR_LENGTH(c.alias) THEN c.alias ELSE c.id END as cat_slug');
        $query->join('LEFT', '#__categories AS c ON c.id=pi.cat_id');

        $query->select('pr.lastip AS rating_lastip, pr.sum AS rating_sum, pr.count as rating_count');
        $query->join('LEFT', '#__jp3_rating AS pr ON pr.item_id=pi.id');

        $query->select('GROUP_CONCAT(ph.cat_id) AS catid');
        $query->join('LEFT', '#__jp3_href AS ph ON ph.item_id=pi.id');

        $query->where('pi.alias="' . $id . '" AND pi.published = 1');

        return $query;
    }

    public function getItem($id = false, $select = false)
    {
        if (!isset($this->item)) {
            $this->item = parent::getItem();

            if($this->item){
                $this->item->catid = explode(',', $this->item->cat_id);

                $this->item->custom = json_decode($this->item->custom);
            }

        }
        //die(var_dump($this->item));
        return $this->item;
    }


    public function getImages()
    {
        if (!isset($this->images)) {
            $id = $this->getState('item.id');
            if(!$id){
                $id=$this->getItemId();
            }
            $db = $this->getDBO();
            $query = $db->getQuery(true);
            $query->select('pii.id');
            $query->from('#__jp3_items AS pii');
            $query->where('pii.alias="' . $id.'"');
            $db->setQuery($query);

            $item_id = $db->loadResult();


            $query = $db->getQuery(true);
            $query->select('pii.*');
            $query->from('#__jp3_pictures AS pii');
            $query->where('pii.item_id=' . $item_id);
            $query->order('is_default DESC');
            $query->order('ordering');
            $db->setQuery($query);

            $this->images = $db->loadObjectList();
        }
        return $this->images;
    }

    public function getFields($id)
    {

        $mode = JoomPortfolioHelper::getModeByItemId();
       if(!$mode){
           $mode=$this->returnItemMode();
       }
        $db = JFactory::getDBO();

        $query = "SELECT f.*"
            . "\n FROM #__jp3_field AS f"
            . "\n WHERE mode='" . $mode . "'";
        $db->setQuery($query);
        $fields = $db->loadAssocList();
        for ($i = 0; $i < count($fields); $i++) {
            $fields[$i]['value'] = '';
        }

        //get all items this cat
        $query = "SELECT  value AS custom, field_id"
            . "\n FROM #__jp3_item_content"
            . "\n WHERE item_id=" . (int)$id;
        $db->setQuery($query);
        $ids = $db->loadAssocList();

        $custom = array();
        for ($i = 0; $i < count($ids); $i++) {
            $custom[$i]['custom'] = $ids[$i]['custom'];
            $custom[$i]['field_id'] = $ids[$i]['field_id'];
        }

        $field_ids = array();
        for ($i = 0; $i < count($fields); $i++) {
            $field_ids[$i] = intval($fields[$i]['id']);
        }
        for ($i = 0; $i < count($custom); $i++) {
            if (count($custom[$i]['custom'])) {
                if (is_array($custom[$i]['custom'])) {
                    foreach ($custom[$i]['custom'] as $key => $value) {
                        if (!in_array($key, $field_ids)) {
                            unset($custom[$i]['custom'][$key]);
                        }
                    }
                }
            }
        }
        $custom_f = array();
        if (!empty($custom)) {

            for ($i = 0; $i < count($custom); $i++) {
                for ($j = 0; $j < count($fields); $j++) {
                    if (count($custom[$i]['custom'])) {
                        $value = $custom[$i]['custom'];

                        if ((int)$fields[$j]['id'] == (int)$custom[$i]['field_id']) {

                            $custom_f[$j]['value'] = $value;
                            $custom_f[$j]['name'] = $fields[$j]['name'];
                            $custom_f[$j]['label'] = $fields[$j]['label'];
                            $custom_f[$j]['def'] = $fields[$j]['def'];
                            $custom_f[$j]['type'] = $fields[$j]['type'];
                            $custom_f[$j]['format'] = $fields[$j]['format'];

                        }

                    }
                }
            }
        }


        return $custom_f;
    }

    public function hit($pk = 0)
    {
        $pk = (!empty($pk)) ? $pk : (int)$this->getState('item.id');
        $db = $this->getDbo();

        $db->setQuery(
            'UPDATE #__jp3_items' .
                ' SET hits = hits + 1' .
                ' WHERE id = ' . (int)$pk
        );

        if (!$db->execute()) {
            $this->setError($db->getErrorMsg());
            return false;
        }

        return true;
    }

    public function itemSearch($letter, $cat_id, $ext)
    {
        $db = JFactory::getDBO();

        $query = "SELECT i.title AS item_title, i.alias AS item_alias, i.published AS item_published, 
                  i.description_short AS item_description_short,i.hits AS item_hits, i.custom AS item_custom,
                  i.def_image AS item_def_img,i.id AS item_id, c.*, p.thumb, p.is_default,
                  CASE WHEN CHAR_LENGTH(i.alias) THEN  i.alias ELSE i.id END as slug,
                  CASE WHEN CHAR_LENGTH(c.alias) THEN c.alias ELSE c.id END as cat_clug"
            . "\n FROM #__jp3_items AS i"
            . " LEFT JOIN  #__categories AS c ON c.id=i.cat_id"
            . " LEFT JOIN #__jp3_pictures AS p ON p.item_id=i.id"
            . "\n WHERE  i.cat_id=" . $cat_id . " AND i.title LIKE '" . $letter . "%' AND i.mode='" . $ext . "' AND i.published=1 GROUP BY i.id LIMIT 0,5";

        $db->setQuery($query);
        $items = $db->loadAssocList();
        $count_items = count($items);
        if ($count_items) {
            for ($i = 0; $i < $count_items; $i++) {
                $items[$i]['custom'] = $this->getFields((int)$items[$i]['item_id']);
            }
        }

        return $items;
    }

    public function getRating($id, $rating_sum, $rating_count)
    {
        $rating_sum = $rating_sum ? $rating_sum : 0;
        $rating_count = $rating_count ? $rating_count : 0;
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'components/com_joomportfolio/assets/css/vote.css');
        $document->addScript(JURI::root() . 'components/com_joomportfolio/assets/js/vote.js');
        $document->addScriptDeclaration("var sfolder = '" . JURI::base(true) . "';
					var jportfrate_text=Array('" . JText::_('COM_JOOMPORTFOLIO_RATING_NO_AJAX') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_LOADING') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_THANKS') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_LOGIN') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_RATED') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_VOTES') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_VOTE') . "');");
        $live_path = JURI::base();
        $counter = 1;
        $percent = 0;
        if ($rating_count != 0) {
            $percent = number_format((intval($rating_sum) / intval($rating_count)) * 20, 2);
        }
        $html = "<span class=\"jportfrate-container\" style=\"margin-top:5px;\">
				  <ul class=\"jportfrate-stars\">
					<li id=\"rating_" . $id . "\" class=\"current-rating\" style=\"width:" . (int)$percent . "%;\"></li>
				  </ul>
				</span>
					  <span id=\"jportfrate_" . $id . "\" class=\"jportfrate-count\"><small>";
        $html .= "( ";
        if ($rating_count != 1) {
            $html .= $rating_count . " Votes";
        } else {
            $html .= $rating_count . " Vote";
        }
        $html .= " )";
        $html .= "</small></span>";
        return $html;
    }



    function getRatingStars($id, $rating_sum, $rating_count)
    {
        $rating_sum = $rating_sum ? $rating_sum : 0;
        $rating_count = $rating_count ? $rating_count : 0;
        /*
                $document = JFactory::getDocument();
                $document->addStyleSheet(JURI::root() . 'components/com_joomportfolio/assets/css/vote.css');
                $document->addScript(JURI::root() . 'components/com_joomportfolio/assets/js/vote.js');
                $document->addScript(JURI::root() . 'components/com_joomportfolio/assets/js/jquery-1.5.1.min.js');

                $document->addScriptDeclaration("var sfolder = '" . JURI::base(true) . "';
                            var jportfrate_text=Array('" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_NO_AJAX') . "','" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_LOADING') . "','" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_THANKS') . "','" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_LOGIN') . "','" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_RATED') . "','" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_VOTES') . "','" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_VOTE') . "');");
        */
        $live_path = JURI::base();

        $counter = 1;
        $percent = 0;

        if ($rating_count != 0) {
            $percent = number_format((intval($rating_sum) / intval($rating_count)) * 20, 2);
        }

        $html = "<span class=\"jportfrate-container\" style=\"margin-top:5px;\">
				  <ul class=\"jportfrate-stars\">
					<li id=\"rating_" . $id . "\" class=\"current-rating\" style=\"width:" . (int)$percent . "%;\"></li>
				  </ul>
				</span>
					  <span id=\"jportfrate_" . $id . "\" class=\"jportfrate-count\"><small>";

        $html .= "( ";
        if ($rating_count != 1) {
            $html .= $rating_count . " " . JText::_('COM_JOOMPORTFOLIO_RATING_VOTES');
        } else {
            $html .= $rating_count . " " . JText::_('COM_JOOMPORTFOLIO_RATING_VOTE');
        }
        $html .= " )";
        $html .= "</small></span>";

        return $html;
    }

    public function getPdf($id){
    $db = $this->getDBO();
    $query = $db->getQuery(true);
    $query->select('p.*');
    $query->from('#__jp3_pdf AS p');
    $query->where('p.item_id=' . (int)$id);
    $query->order('ordering');
    $db->setQuery($query);
    $pdf= $db->loadObjectList();
    return $pdf;
}

    public function getAudio($id){
    $db = $this->getDBO();
    $query = $db->getQuery(true);
    $query->select('p.*');
    $query->from('#__jp3_audio AS p');
    $query->where('p.item_id=' . (int)$id);
    $query->order('ordering');
    $db->setQuery($query);
    $audio= $db->loadObjectList();
    return $audio;
}

    public function getComments($id){
        $db = $this->getDBO();
        $query = $db->getQuery(true);
        $query->select('p.*, u.name AS user_name');
        $query->from('#__jp3_comments AS p');
        $query->innerJoin('`#__users` AS `u` ON `u`.id=`p`.`user_id`');
        $query->where('p.item_id=' . (int)$id);
        $query->order('p.date DESC');
        $db->setQuery($query);
        $comments= $db->loadObjectList();
        return $comments;
    }

    public function getVideo($id){
        $db = $this->getDBO();
        $query = $db->getQuery(true);
        $query->select('p.*');
        $query->from('#__jp3_video AS p');
        $query->where('p.item_id=' . (int)$id);
        $query->order('ordering');
        $db->setQuery($query);
        $video= $db->loadObjectList();
        return $video;
    }


    public function changeMenu()
    {
        $jinput = JFactory::getApplication()->input;
        $itemid = $jinput->get('Itemid', 0, 'INT');

        $mode = JoomPortfolioHelper::getVarMode();
        if (!$mode) {
            $mode = JoomPortfolioHelper::getModeByCatId();
        }
        if (!$mode) {
            $mode = $jinput->get('mode', '');
        }
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('link');
        $query->from('#__menu');
        $query->where('id=' . $itemid);
        $db->setQuery($query);
        $link = $db->loadResult();
        //"index.php?option=com_joomportfolio&view=category&mode=joomportfolio"
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__jp3_items');
        $query->where('mode="' . $db->escape($mode) . '"');
        $query->where('published=1');
        $db->setQuery($query, 0, 1);
        $id = $db->loadObject();
        if ($link && $id) {
            $query = $db->getQuery(true);
            $query->update('#__menu')
                ->set('`link`="index.php?option=com_joomportfolio&view=item&id=' . (int)$id->id . '&extension=' . $db->escape($mode) . '&mode=' . $db->escape($mode) . '"')
                ->where('`id`=' . $itemid . '');
            $db->setQuery($query);
            if (!$db->execute()) {
                return NULL;
            } else {
                $_REQUEST['id']=(int)$id->id;
                return $id;
            }
        }
        return NULL;

    }

    public function getItemId(){
        $jinput = JFactory::getApplication()->input;
        $itemid = $jinput->get('Itemid', 0, 'INT');
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('params')
            ->from('`#__menu`')
            ->where('id=' . (int)$itemid);
        $db->setQuery($query);
        $params= $db->loadResult();
        $settings=json_decode($params);
        $cur_cat=(int)$settings->id;
        return $cur_cat;
    }


    public function returnItem($id){
        $db = $this->getDBO();
        $query = $db->getQuery(true);
            $query->select('pi.*');


        $query->from('#__jp3_items AS pi');
        $query->select('CASE WHEN CHAR_LENGTH(pi.alias) THEN  pi.alias ELSE pi.id END as slug');

        $query->select('pr.lastip AS rating_lastip, pr.sum AS rating_sum, pr.count as rating_count');
        $query->join('LEFT', '#__jp3_rating AS pr ON pr.item_id=pi.id');

        $query->select('GROUP_CONCAT(ph.cat_id) AS catid');
        $query->join('LEFT', '#__jp3_href AS ph ON ph.item_id=pi.id');

        $query->where('pi.id=' . $id . ' AND pi.published = 1');
        $db->setQuery($query);

        return $db->loadObject();
    }

    public function returnItemMode(){
        $jinput = JFactory::getApplication()->input;
        $itemid = $jinput->get('Itemid', 0, 'INT');
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('params')
            ->from('`#__menu`')
            ->where('id=' . (int)$itemid);
        $db->setQuery($query);
        $params= $db->loadResult();
        $settings=json_decode($params);
        $cur_cat=(int)$settings->id;

        $query = $db->getQuery(true);
        $query->select('mode')
            ->from('`#__jp3_items`')
            ->where('id=' . (int)$cur_cat);
        $db->setQuery($query);
        $mode= $db->loadResult();
        return  $mode;
    }

    public function returnAliasById($id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('CASE WHEN CHAR_LENGTH(pi.alias) THEN pi.alias ELSE pi.id END as slug')
        ->select('c.title AS cat_title, CASE WHEN CHAR_LENGTH(c.alias) THEN c.alias ELSE c.id END as cat_slug')
            ->from('#__jp3_items AS pi ')
            ->join('LEFT', '#__categories AS c ON c.id=pi.cat_id')
            ->where('pi.id=' . (int)$id);
        $db->setQuery($query);
        $alias= $db->loadObject();

        return $alias;
    }

}