<?php
/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
if (isset($this->main_err)) {
    ?>
    <div class="main-error">
        <div class="mode-error">
            <strong><?php echo $this->main_err; ?></strong>
        </div>
    </div>
<?php
} else {
    $mainframe = JFactory::getApplication();
    $com_params = JComponentHelper::getParams('com_joomportfolio');
    $preview_width = $com_params->get('item_preview_width', 300);
    $preview_height = $com_params->get('item_preview_height', 300);
    $title = '';
    $mode = JoomPortfolioHelper::getModeByItemId();
    if(!$mode){
        $mode=JFactory::getApplication()->input->get('mode','');
    }
    if(!$mode){
        $mode=JFactory::getApplication()->input->get('extension','');
    }
    JoomPortfolioHelper::loadLanguage();
    require_once  JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'portfolio' . DIRECTORY_SEPARATOR . $mode . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'item.php';
}