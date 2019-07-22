<?php
/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

if (isset($this->main_err)) {?>
    <div class="main-error">
    <div class="mode-error" >
        <strong><?php echo $this->main_err; ?></strong>
    </div>
    </div>
<?php } else {
    $mainframe = JFactory::getApplication();

    $return = JUri::getInstance()->toString();
    $title = '';
    $mode = JoomPortfolioHelper::getVarMode();
    if(!$mode){
        $mode = JoomPortfolioHelper::getModeByCatId();
    }
   if(!$mode){
       $mode=JFactory::getApplication()->input->get('mode','');
   }
    if(!$mode){
        $mode=JFactory::getApplication()->input->get('extension','');
    }
    JoomPortfolioHelper::loadLanguage();
    $settings = $this->settings;
    require_once  JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'portfolio' . DIRECTORY_SEPARATOR . $mode . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'category.php';
}