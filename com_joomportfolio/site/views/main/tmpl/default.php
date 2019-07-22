<?php
/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
$mode = JoomPortfolioHelper::getVarMode();
$app = JFactory::getApplication();
$menu   = $app->getMenu();
$active   = $menu->getActive();
$pathway =$app->getPathway();
$breadcrumb = $pathway->setPathway(array());
$pathway->addItem($active->title,'');
if (isset($this->main_err)) {
    ?>
    <div class="main-error">
        <div class="mode-error">
            <strong><?php echo $this->main_err; ?></strong>
        </div>
    </div>
<?php } else { ?>
    <div class="main-view">
        <?php
        if ($mode != NULL && $mode != 'Select Mode') {
            JoomPortfolioHelper::loadLanguage();
            require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'portfolio' . DIRECTORY_SEPARATOR . $mode . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'main.php';
        } else {
            $filename = JPATH_ROOT . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en-GB' . DIRECTORY_SEPARATOR . 'en-GB.com_joomportfolio.ini';
            if (!file_exists($filename)) {
                @copy(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en-GB' . DIRECTORY_SEPARATOR . 'en-GB.com_joomportfolio.ini', JPATH_ROOT . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en-GB' . DIRECTORY_SEPARATOR . 'en-GB.com_joomportfolio.ini');
                ?>
                <script>
                    location.reload();
                </script>
            <?php
            }
            ?>
            <div class="portfolio-error">
                <?php
                echo JText::_('COM_JOOMPORTFOLIO_SELECTED_MODE_FOR_MAIN_VIEW');
                ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>