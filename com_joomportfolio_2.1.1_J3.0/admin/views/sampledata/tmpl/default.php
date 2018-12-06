<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');
?>
<?php echo $this->loadTemplate('menu'); ?>
<?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
<?php endif; ?>
<div class="hero-unit" style="padding: 20px ! important;">
    <h1>Sample Data</h1>
    <br/>

    <p style="clear: both"></p>


        <h2 class="text-center"><?php echo JText::_('COM_JOOMPORTFOLIO_WOULD_YOU_LIKE_INSTALL_SIMPLEDATA')?></h2>
    <a href="index.php?option=com_joomportfolio&task=sampledata.addsampledata" class="btn btn-success btn-large"><?php echo JText::_('COM_JOOMPORTFOLIO_INSTALL_DATA')?></a>
    <a class="btn btn-danger btn-large"  href="index.php?option=com_joomportfolio&view=about">No</a>
    <br/>

    <p></p>
</div>
