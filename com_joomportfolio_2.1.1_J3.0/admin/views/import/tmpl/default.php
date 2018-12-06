<?php

/**
 * JoomPortfolio component for Joomla 3
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');
?>
<style>
    .icon-48-importdata {
        background-image: url("<?php echo JURI::root();?>administrator/templates/bluestork/images/header/icon-48-download.png");
    }
</style>
<?php echo $this->loadTemplate('menu'); ?>
<form action="" method="post" name="adminForm" id="custom-form" class="form-validate">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->leftmenu;?>
    </div>
</form>
<form action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=importdata'); ?>" method="post"
      name="adminForm" id="adminform" class="adminform">
    <table class="admin">
        <tr>
            <td valign="top">
                <table class="adminlist" width="100%" style="border:1px solid #dddddd; border-radius:2px 2px 2px 2px;">
                    <tr>
                        <td>
                            <strong>
                                Data of Joomla! 1.5 based Joomportfolio can be imported. Make sure older tables remained
                                in the database and images are stored in folder images/com_joomportfolio
                            </strong>
                        </td>
                    </tr>
                </table>
                <table class="adminlist" width="100%" style="border:1px solid #dddddd; border-radius:2px 2px 2px 2px;">
                    <tr>
                        <td>
                            <label>Prefix of Joomla! 1.5 tables:</label>
                        </td>
                        <td>
                            <input type="text" name="prefix" id="prefix" value="jos_" size="40"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Images path:</label>
                        </td>
                        <td>
                            <input type="text" name="path" id="path" value="images/com_joomportfolio" size="40"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Do you want to import data from Joomla! 1.5?
                            <div style="padding-top:10px;">
                                <div class="button">
                                    <div class="blank">
                                        <a href="#" onclick="Joomla.submitbutton('importData')">
                                            <?php echo JText::_('JYES'); ?>
                                        </a>
                                    </div>
                                </div>
                                &nbsp;&nbsp;
                                <div class="button">
                                    <div class="blank">
                                        <a href="#"
                                           onclick="window.location.href = '<?php echo JURI::root(); ?>administrator/index.php?option=com_joomportfolio&view=items';">
                                            <?php echo JText::_('JNO'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" name="task" value=""/>
</form>