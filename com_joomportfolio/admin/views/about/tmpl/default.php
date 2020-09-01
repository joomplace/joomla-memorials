<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted Access');

$imgpath = JURI::root() . '/administrator/components/com_joomportfolio/assets/images/';
JHtml::_('behavior.tooltip');
?>
<?php echo $this->loadTemplate('menu'); ?>
    <div id="pgm_dashboard">
        <?php
        if (!empty($this->menu)) {
            $menu = $this->menu;
            for ($i = 0; $i < count($menu); $i++) {
                ?>
                <div onclick="window.location = '<?php echo $menu[$i]['url']; ?>'" class="pgm-dashboard_button btn">
                    <img src="<?php
                    echo $menu[$i]['icon'];
                    ?>"/>

                    <div class="pgm-dashboard_button_text"><?php echo $menu[$i]['title']; ?></div>
                </div>
            <?php
            }
        } else {
            echo JText::_('COM_JOOMPORTFOLIO_NOT_PUBLISHED_ITEMS');
        }
        ?>

        <div id="dashboard_items"><a
                href="index.php?option=com_joomportfolio&view=dashboard_items"><?php echo JText::_('COM_JOOMPORTFOLIO_MANAGE_DASHBOARD_ITEMS'); ?></a>
        </div>
    </div>





    <div id="j-main-container" class="span6 form-horizontal portfolio_control_panel_container well" style="margin-right: 0px;">

        <table class="table">
            <tr>
                <th colspan="100%" class="portfolio_control_panel_title">
                    <?php echo JText::_('COM_JOOMPORTFOLIO'); ?>&nbsp; <?php echo JText::_('COM_JOOMPORTFOLIO_ABOUT_FOR') .
                        " 3.x. " . JText::_('COM_JOOMPORTFOLIO_BE_CONTROL_PANEL_DEVELOPED_BY'); ?> <a href="http://www.joomplace.com/" target="_blank">JoomPlace</a>.
                </th>
            </tr>
            <tr>
                <td width="120"><?php echo JText::_('COM_JOOMPORTFOLIO_ABOUT_IVER') . ':'; ?></td>
                <td class="portfolio_control_panel_current_version"><?php echo JoomPortfolioHelper::getVersion();  ?></td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_JOOMPORTFOLIO_ABOUT_SF') . ':'; ?></td>
                <td>
                    <a target="_blank" href="http://www.JoomPlace.com/support" >http://www.JoomPlace.com/support</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a style="text-decoration: underline !important" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                                <strong>
                                    <img src="<?php echo $imgpath; ?>tick.png"><?php echo JText::_('COM_JOOMPORTFOLIO_ABOUT_STH'); ?>
                                </strong>
                            </a>
                        </div>
                        <div id="collapseTwo" class="accordion-body collapse">
                            <div class="accordion-inner">
                                <table border="1" cellpadding="5" width="100%" class="thank_tabl">
                                    <tr>

                                    </tr>
                                    <tr>
                                        <td style="padding-left:20px">
                                            <div class="thank_fdiv">
                                                <p style="font-size:12px;">
                                                    <span style="font-size:14pt;"><?php echo JText::_('COM_JOOMPORTFOLIO_ABOUT_STH'); ?></span> <?php echo JText::_('COM_JOOMPORTFOLIO_ABOUT_STH_AND'); ?> <span style="font-size:14pt;"><?php echo JText::_('COM_JOOMPORTFOLIO_ABOUT_STH_HELP_IT'); ?></span> <?php echo JText::_('COM_JOOMPORTFOLIO_ABOUT_STH_SHAR'); ?> <a href="http://extensions.joomla.org/extensions/communities-a-groupware/ratings-a-reviews/11305" target="_blank">http://extensions.joomla.org/</a> <?php echo JText::_('COM_JOOMPORTFOLIO_ABOUT_STH_ANWR'); ?>
                                                </p>
                                            </div>
                                            <div style="float:right; margin:3px 5px 5px 5px;">
                                                <a href="http://extensions.joomla.org/extensions/communities-a-groupware/ratings-a-reviews/11305" target="_blank">
                                                    <img src="http://www.joomplace.com/components/com_jparea/assets/images/rate-2.png" />
                                                </a>
                                            </div>
                                            <div style="clear:both"></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

    </div>
