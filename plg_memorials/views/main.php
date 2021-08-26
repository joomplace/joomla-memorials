<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$menu   = $app->getMenu();
$active   = $menu->getActive();
$pathway =$app->getPathway();
$breadcrumb = $pathway->setPathway(array());
if(!empty($active)) {
    $pathway->addItem($active->title,'');
}
?>
<link rel="stylesheet" href="<?php echo(JUri::root()); ?>plugins/portfolio/memorials/css/style.css" type="text/css" />
<div id="portfolio" class="container container_6" style="width: auto !important;">
<?php if (!isset($this->main_err)) { ?>
    <?php if (intval($this->settings->show_title) == 1) { ?>
            <h1 class="title" >
            <?php echo $this->settings->title_text; ?>
            </h1>
            <?php } ?>
            <?php if ($this->categories) { ?>
            <div class="jp-categories" >
            <?php
            $i = 0;
            $n = count($this->categories);
                $main_class='';
                if($n==1){
                    $main_class='jp-main_page_1_cat';
                }elseif($n==2){
                    $main_class='jp-main_page_2_cat';
                }elseif($n==3){
                    $main_class='jp-main_page_3_cat';
                }

            foreach ($this->categories as $category) {
                $category->params = json_decode($category->params);
                $subs_count = ($category->rgt - $category->lft - 1) / 2;
                if (!$i or ($i + 1) % 3 == 1) {
                    $class = 'alpha';
                } elseif ($n == $i + 1 or ($i + 1) % 3 == 0) {
                    $class = 'omega';
                } else {
                    $class = '';
                }
                $i++;
                ?>
                    <div class="jp-main_page grid_2 <?php if($n==1 || $n==2 || $n==3){ echo $main_class; }else{echo $class;} ?>" >

                    <?php if ($category->params->image) { ?>


                            <a class="main-img" href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=category&cid=' . $category->slug); ?>" >
                                <img src="<?php echo str_replace('\\', '/', $category->params->image); ?>" alt="<?php echo $category->title; ?>" />
                            </a>
            <?php } ?>
            <?php if (intval($this->settings->category_title) == 1) { ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=category&id=' . $category->slug); ?>" >
                            <h2><?php echo $category->title; ?></h2>
                            </a>
                            <?php } ?>
                            <?php if (intval($this->settings->category_description) == 1) { ?>
                            <div class="description" >
                            <?php

                                echo $category->description;

                            ?>
                            </div>
                            <?php } ?>
                            <?php if (intval($this->settings->category_show_categories_count) == 1) { ?>
                            <p class="jp-subline" >
                            <?php echo JText::_('COM_JOOMPORTFOLIO_SUBCATEGORIES_COUNT'); ?>
                                <strong><?php echo $subs_count; ?></strong>
                            </p>
                            <?php } ?>
            <?php /* if (intval($this->settings->category_show_items_count) == 1) { ?>
                            <p class="jp-subline" >
                            <?php echo JText::_('COM_JOOMPORTFOLIO_ITEMS_COUNT'); ?>
                                <strong><?php echo $category->items_count; ?></strong>
                            </p>
                            <?php }*/ ?>
                    </div>
                        <?php if ($class == 'omega') { ?>
                        <div class="clear" ></div>
                    <?php } ?>
                <?php } ?>
            </div>
            <?php } ?>
        <div class="clear">&nbsp;</div>

        <?php } else {
            ?>
        <div class="mode-err">
        <?php echo JText::_($this->main_err); ?>
        </div>
            <?php }
        ?>
</div>

