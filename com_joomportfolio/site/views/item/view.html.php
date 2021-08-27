<?php

/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

include_once(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'baseview.php');

class JoomPortfolioViewItem extends BaseView
{

    function display($tpl = null)
    {

        JHTML::_('behavior.framework');

        $model = $this->getModel();
        $item = $model->getItem();
        $jinput = JFactory::getApplication()->input;
        $this->Itemid=$jinput->get('Itemid',0,'INT');

        if (!isset($item->id)) {
            $item_id=$model->getItemId();
            $item=$model->returnItem($item_id);
            if (!$item) {
                throw new Exception(JText::_('COM_JOOMPORTFOLIO_NO_ITEM'), 404);
                $this->main_err = JText::_('COM_JOOMPORTFOLIO_ITEM_MODE_ERROR');
                parent::display($tpl);
            }
        }
        if ((int)$item->id) {
            $this->images = $model->getImages();
            $this->fields = $model->getFields($item->id);
            $this->category = $model->getCategory();
            $this->params = $model->getState('params');
            $this->pdf = $model->getPdf($item->id);
            $this->audio = $model->getAudio($item->id);
            $this->video = $model->getVideo($item->id);
            $this->comments = $model->getComments($item->id);
            $app = JFactory::getApplication();
            $mode= JoomPortfolioHelper::getModeByItemId();

            if(!$mode){
                $mode=JFactory::getApplication()->input->get('mode','');
            }
            if(!$mode){
                $mode=JFactory::getApplication()->input->get('extension','');
            }
            $settings = JComponentHelper::getParams('com_joomportfolio');
            $this->params = $settings;

            $dispatcher = JDispatcher::getInstance();
            JPluginHelper::importPlugin(JoomPortfolioHelper::getVarMode());
            $results = $dispatcher->trigger('onPortfolioPrepare', array(&$item, &$this->params));

            $item->event = new stdClass();

            $results = $dispatcher->trigger('onPortfolioBeforeDisplay', array(&$item, &$this->params));
            $item->event->beforeDisplay = trim(implode("\n", $results));

            $results = $dispatcher->trigger('onPortfolioAfterDisplay', array(&$item, &$this->params));
            $item->event->afterDisplay = trim(implode("\n", $results));

            $model->hit($item->id);

            if ($this->params->get('item_rating_enabled', 1)) {
                $this->rating = $this->getRatingStars($item->id, $item->rating_sum, $item->rating_count);
            }
            $this->settings = JoomPortfolioHelper::getSetting($mode);

            $errors = $this->get('Errors');
            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors), 500);
            }

            $this->item = $item;

            parent::display($tpl);
        }
    }

    public function setDocument()
    {
        $doc = $this->document;
        //$doc->addScript('components/com_joomportfolio/assets/lightbox/js/jquery-1.10.2.min.js');
        //$doc->addScript('components/com_joomportfolio/assets/lightbox/js/modernizr.custom.js');

        if (!empty($this->item->metadesc)) {
            $doc->setMetaData('description', $doc->getMetaData('description') . ',' . $this->item->metadesc);
        }
        if (!empty($this->item->metakey)) {
            $doc->setMetaData('keywords', $doc->getMetaData('keywords') . ',' . $this->item->metakey);
        }
        if (!empty($this->item->metaauth)) {
            $doc->setMetaData('author', $doc->getMetaData('author') . ' ' . $this->item->metaauth);
        }

        if (isset($this->item->id)) {
            $doc->setTitle($this->item->title . ' - ' . $doc->getTitle());
        }
    }

    public function setPathway()
    {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();

        $menu = $app->getMenu();
        $active = $menu->getActive();
        if (isset($this->item->id) && isset($active)) {
           if(isset($active->query['id'])){
            if ($active->query['view'] != 'item' or $active->query['id'] != $this->item->id) {
                $pathway->addItem($this->item->title, '');
            }
           }
        }
    }

    function getRatingStars($id, $rating_sum, $rating_count)
    {
        $rating_sum = $rating_sum ? $rating_sum : 0;
        $rating_count = $rating_count ? $rating_count : 0;

        $document = JFactory::getDocument();
        JHtml::_('script', 'components/com_joomportfolio/assets/js/vote.js', true);
        JHtml::_('stylesheet', 'components/com_joomportfolio/assets/css/vote.css');

        $document->addScriptDeclaration("var sfolder = '" . JURI::base(true) . "';
					var jportfrate_text=Array('" . JText::_('COM_JOOMPORTFOLIO_RATING_NO_AJAX') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_LOADING') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_THANKS') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_LOGIN') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_RATED') . "','" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_VOTES') . "','" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_VOTE') . "');");

        $live_path = JURI::base();

        $counter = 1;
        $percent = 0;

        if ($rating_count != 0) {
            $percent = number_format((intval($rating_sum) / intval($rating_count)) * 20, 2);
        }

        $html = "<span class=\"jportfrate-container\" style=\"margin-top:5px;\">
					  <ul class=\"jportfrate-stars\">
						<li id=\"rating_" . $id . "\" class=\"current-rating\" style=\"width:" . (int)$percent . "%;\"></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomportfRate(" . $id . ",1," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_VERY_POOR') . "\" class=\"jp-one-star\">1</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomportfRate(" . $id . ",2," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_POOR') . "\" class=\"jp-two-stars\">2</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomportfRate(" . $id . ",3," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_REGULAR') . "\" class=\"jp-three-stars\">3</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomportfRate(" . $id . ",4," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_GOOD') . "\" class=\"jp-four-stars\">4</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomportfRate(" . $id . ",5," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_VERY_GOOD') . "\" class=\"jp-five-stars\">5</a></li>
					  </ul>
				</span>
					  <span id=\"jportfrate_" . $id . "\" class=\"jportfrate-count\"><small>";

        $html .= "( ";
        if ($rating_count != 1) {
            $html .= $rating_count . " " . JTEXT::_('COM_JOOMPORTFOLIO_RATING_VOTES');
        } else {
            $html .= $rating_count . " " . JTEXT::_('COM_JOOMPORTFOLIO_RATING_VOTE');
        }
        $html .= " )";
        $html .= "</small></span>";
        return $html;
    }

}
