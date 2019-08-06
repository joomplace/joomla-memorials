<?php

/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pagination');
class JoomPortfolioViewCategory extends JViewLegacy
{
    function display($tpl = null)
    {
        $model = $this->getModel();
        $app = JFactory::getApplication('site');
        $jinput = $app->input;

        $cat_id = $jinput->get('id', '', 'HTML');
        $cid = $jinput->get('cid', '', 'HTML');
        if ($cat_id == '' && $cid == '') {
            $cat_id = $model->getCatIdByItemId();
            if (!$cat_id) {
                JError::raiseNotice('404', JText::_('COM_JOOMPORTFOLIO_NO_CATEGORY'));
                $this->main_err = "Mode is not selected in category view";
                parent::display($tpl);
            }
        }
        if ($cat_id != '' || $cid != '') {
            $this->category = $model->getCategory($cat_id);

            if (!isset($this->category->id)) {
                if (!$this->category) {
                    if ($cid != '') {
                        $this->category = $model->getCategory($cid);
                    } else {
                        $this->category = $model->getCategory($cat_id);
                    }

                    //$this->category->id = intval($cid);
                } /*else {
                    $this->category->id = intval($cid);
                }*/
            }

            $mode = JoomPortfolioHelper::getModeByCatId();
            if (!$mode) {
                $mode = $mode = JoomPortfolioHelper::getModeByCatId();
            }
            if (!$mode) {
                $mode = JFactory::getApplication()->input->get('mode', '');
            }
            if (!$mode) {
                $mode = JFactory::getApplication()->input->get('extension', '');
            }
            $this->children = $model->getChildren($this->category->id);

            $this->state = $this->get('State');

            $this->pagination = $this->get('Pagination');

            $this->items = $this->get('Items');

            if (empty($this->items)) {
                $this->items = $model->getCatItems($this->category->id);
            }

            $this->params = $model->getState('params');
            $this->pagination = $this->get('Pagination');

            $app = JFactory::getApplication();
            $settings = JComponentHelper::getParams('com_joomportfolio');
            $this->params = $settings;

            $this->settings = JoomPortfolioHelper::getSettings();

            if (count($errors = $this->get('Errors'))) {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
            }

            parent::display($tpl);
        }
    }

    function getRatingStars($id, $rating_sum, $rating_count)
    {
        $rating_sum = $rating_sum ? $rating_sum : 0;
        $rating_count = $rating_count ? $rating_count : 0;

        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'components/com_joomportfolio/assets/css/vote.css');
        $document->addScript(JURI::root() . 'components/com_joomportfolio/assets/js/vote.js');
        //$document->addScript(JURI::root() . 'components/com_joomportfolio/assets/js/jquery-1.5.1.min.js');

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

}