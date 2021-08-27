<?php
/**
 * JoomPortfolio module for Joomla 3.0
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

$link = '';
$count = count($list);
if ($params->get('show_caption')) {
    ?>
    <div class='mod_memorials_caption'>
        <?php echo $list[$i]->item_title;?>
    </div>
<?php
}
?>
<span class="mod_memorials_text">
		    <span class='mod_memorials_text_line' style='line-height:inherit;display:none;'></span>

    <?php
    if ($params->get('show_avatar_module')) {
        if (!empty($list[$i]->photo)) {
            ?>
            <div class="tstmnl_avatar"><img
                src="<?php echo JURI::base() . 'images/joomportfolio/' . $list[$i]->item_id . '/thumb/' . $list[$i]->photo->thumb; ?>"
                style="float:left; max-height: 200px;"></div><?php
        }
    }

    //  if ((int)$params->get('show_readmore') == 1) {
    if ($modal) {

        $link .= '<a style="text-decoration:none;" id="tstmnl_link_cm" class="modtm_iframe" href="' . JRoute::_('index.php?option=com_joomportfolio&view=item&cid=' . $list[$i]->cat_slug . '&id=' . $list[0]->slug . '&tmpl=component') . '">';
    } else {
        $link .= '<a style="text-decoration:none;" id="tstmnl_link_cm"  href="' . JRoute::_('index.php?option=com_joomportfolio&view=item&cid=' . $list[$i]->cat_slug . '&id=' . $list[$i]->slug) . '">';

    }
    $link .=  "<span class='module_rand_text'>".$list[$i]->item_description_short."</span></a>";
    echo $link;
    // }
    ?>
		</span>

<br style="clear:both;"/>
