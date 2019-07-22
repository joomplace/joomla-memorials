<?php
/**
* JoomPortfolio module for Joomla 3.0
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;
?>
<ul class="joomportfitems<?php echo isset($moduleclass_sfx)?$moduleclass_sfx:''; ?>">
<?php

for($i=0; $i<count($list); $i++){
    ?>
    <li>
        <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=item&cid=' . $list[$i]->cat_slug . '&id=' . $list[$i]->slug); ?>" ><?php echo $list[$i]->item_title; ?></a>
    </li>
<?php
}
?>
</ul>
