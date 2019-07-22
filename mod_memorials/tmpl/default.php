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
<ul class="joomportfsec<?php echo isset($moduleclass_sfx)?$moduleclass_sfx:''; ?>">
<?php
    $Itemid=$params->get('Itemid',0);

    if ($Itemid) {
        $Itemid = modMemorialsHelper::getItemId();
    }
foreach ($list as $item) {
	modMemorialsHelper::printNode($item, $Itemid);
}
?>
</ul>
