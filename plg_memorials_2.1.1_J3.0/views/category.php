<?php
$app = JFactory::getApplication();
$jinput = $app->input;
$title = '';
if (intval($this->settings->show_title) == 1) {
    $title = $this->settings->title_text;
}
if (isset($this->category->id) and (intval($this->settings->category_title) == 1)) {
    if (!empty($title)) {
        $title .= ' &mdash; ';
    }
    if (!isset($this->category->title)) {
        $title .= '';
    } else {
        $title .= $this->category->title;
    }
}

$Itemid = $jinput->get('Itemid', 0, 'INT');
$menu   = $app->getMenu();
$active   = $menu->getActive();
$pathway =$app->getPathway();
$breadcrumb = $pathway->setPathway(array());
$pathway->addItem('Memorials Directory','index.php?option=com_joomportfolio');
if($this->category){
  if($this->category->parent_id!=1){
      JoomPortfolioHelper::catParent($this->category->id);
  }
}

$pathway->addItem($this->category->title,'');
?>
<link rel="stylesheet" href="<?php echo(JUri::root()); ?>plugins/portfolio/memorials/css/style.css" type="text/css"/>

<div class="list-cat">
    <div class="jp-main-cat">
        <h1 class="title">
            <?php echo $title; ?>
        </h1>
        <?php

        if (isset($this->category->params->image) and ((int)$this->settings->category_image == 1)) {
            if ($this->category->params->image) {
                ?>
                <div class="image">
                    <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=category&id=' . $this->category->slug); ?>">
                        <img src="<?php echo JURI::base() . $this->category->params->image; ?>"
                             alt="<?php echo $title; ?>"/>
                    </a>
                </div>
            <?php } ?>
        <?php } ?>
        <?php if (isset($this->category->description) and (intval($this->settings->category_description) == 1)) { ?>
            <div class="description">
                <?php echo $this->category->description; ?>
            </div>
        <?php } ?>
    </div>

    <form
        action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=category&id='.$this->category->alias); ?>"
        method="post" name="adminForm" id="adminForm">



        <?php if (isset($this->category->id)) { ?>

            <?php if ($this->children) { ?>

                <div class="jp-categories">
                    <div
                        class="subcategories_wrap"><?php echo JText::_('COM_JOOMPORTFOLIO_SUBCATEGORIES_COUNT'); ?></div>
                    <?php
                    $i = 0;
                    $n = count($this->children);

                    if ($n) {
                        foreach ($this->children as $category) {
                            $category->params = json_decode($category->params);
                            $subs_count = ($category->rgt - $category->lft - 1) / 2;
                            if (!$i) {
                                $class = 'alpha';
                            } elseif ($n == $i + 1) {
                                $class = 'omega';
                            } else {
                                $class = '';
                            }
                            $i++;
                            ?>

                            <div class="grid_2 <?php echo $class; ?> cat-wrap">
                                <div class="jp-subcategory_title">
                                    <?php if (isset($this->settings->subcategory_title) && (int)$this->settings->subcategory_title == 1) { ?>
                                       <h3><a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=category&id=' . $category->slug); ?>"
                                           style="display: block;">
                                            <?php echo $category->title; ?>
                                        </a></h3>
                                    <?php } ?>
                                    <?php if ((int)$this->settings->category_image) { ?>
                                        <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=category&id=' . $category->slug); ?>">
                                            <?php if ($category->params->image) { ?>
                                                <img src="<?php echo str_replace('\\', '/', $category->params->image); ?>"
                                                     title="<?php echo $category->title; ?>"
                                                     border="0"/>
                                            <?php } ?>
                                        </a>
                                    <?php } ?>
                                </div>
                                <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=category&id=' . $category->slug); ?>"
                                <?php if (intval($this->settings->category_show_categories_count) == 1) { ?>
                                    <p class="jp-subline jp-subline-subcat">
                                        <?php echo JText::_('COM_JOOMPORTFOLIO_SUBCATEGORIES_COUNT'); ?>
                                        <strong><?php echo $subs_count; ?></strong>
                                    </p>
                                <?php } ?>
                                <?php /*if (intval($this->settings->category_show_items_count) == 1) { ?>
                                    <p class="jp-subline">
                                        <?php echo JText::_('COM_JOOMPORTFOLIO_ITEMS_COUNT'); ?>
                                        <strong><?php echo (int)$category->count_items; ?></strong>
                                    </p>
                                <?php }*/ ?>
                                </a>
                            </div>

                        <?php }
                    }?>
                </div>
                <div class="clear">&nbsp;</div>
            <?php } ?>
            <div class="alpha_search"><?php
                for ($i = 65; $i <= 90; $i++) {
                    $x = chr($i);
                    echo '<a href="#">' . $x . '</a>';
                }
                ?>

            </div>
            <div class="wrap">
                <?php if ($this->items) {

                    ?>
                    <div id="items-list">
                        <?php
                        $def_images = array();
                        $db = JFactory::getDbo();

                        foreach ($this->items as $item) {

                            $query = $db->getQuery(true);

                            $query->select('*')
                                ->from('#__jp3_pictures');

                            $query->where('`item_id`="' . $item->item_id . '"');

                            $query->order('is_default DESC');

                            $db->setQuery($query,0,1);
                            $result = $db->loadObject();

                            if (sizeof($result)) {
                                $def_images[] = $result;
                            }
                        }
                        $n = count($this->items);
                        for ($i = 0; $i < $n; $i++) {
                            $item = $this->items[$i];
                            if (!isset($item->images)) {
                                $item->images = array();
                            } else {
                                $item->images = explode(',', $item->images);
                            }

                            JoomPortfolioHelper::showCatItems($item, $def_images, $this->settings, $this->category->id);

                            ?>

                        <?php } ?>
                    </div>



                    <div class="clear">&nbsp;</div>
                    <?php if ((int)$this->settings->social_google_plus_use == 1) { ?>

                    <script type="text/javascript">
                        window.___gcfg = {lang: '<?php echo (int)$this->settings->social_google_plus_language; ?>'};
                        (function () {
                            var po = document.createElement('script');
                            po.type = 'text/javascript';
                            po.async = true;
                            po.src = 'https://apis.google.com/js/plusone.js';
                            console.log(document.getElementsByTagName('script'))
                            var s = document.getElementsByTagName('script')[0];
                            s.parentNode.insertBefore(po, s);
                        })();
                    </script>

                <?php } ?>

                    <?php if ((int)$this->settings->social_twitter_use == 1) { ?>

                    <script type="text/javascript">
                        (function () {
                            var twitterScriptTag = document.createElement('script');
                            twitterScriptTag.type = 'text/javascript';
                            twitterScriptTag.async = true;
                            twitterScriptTag.src = 'http://platform.twitter.com/widgets.js';
                            var s = document.getElementsByTagName('script')[0];
                            s.parentNode.insertBefore(twitterScriptTag, s);
                        })();
                    </script>

                <?php } ?>

                    <?php if ((int)$this->settings->social_linkedin_use == 1) { ?>

                    <script type="text/javascript" src="//platform.linkedin.com/in.js"></script>

                <?php } ?>

                    <?php if ((int)$this->settings->social_facebook_use == 1) {  ?>

                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) return;
                            js = d.createElement(s); js.id = id;
                            js.src = "//connect.facebook.net/<?php echo JFactory::getLanguage()->getTag(); ?>/all.js#xfbml=1";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>

                <?php  }?>

                    <?php if ((int)$this->settings->social_pinterest_use == 1) { ?>

                    <script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
                <?php } ?>

                <?php } ?>

            </div>

        <?php } ?>

        

</div>
<?php
echo $this->pagination->getLimitBox(); ?>
<?php //if (($this->pagination->get('pages.total') > 1) and ($this->params->get('pagination') == 2 or $this->params->get('pagination') == 1)) { ?>
<div class="pagination">
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php //} ?>
<input name="option" value="com_joomportfolio" type="hidden"/>
<input name="view" value="category" type="hidden"/>
<input name="letter" id="letter" value="<?php echo $jinput->get('letter',''); ?>" type="hidden"/>
<input type="hidden" id="mode" name="extension" value="memorials"/>
<input type="hidden" name="limitstart" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>
<div id="loadImg">
    <img src="<?php echo JURI::root() . "administrator/components/com_joomportfolio/assets/images/loading81.gif"; ?>"/>
</div>
<script type="text/javascript">


    var $jq = jQuery.noConflict();
    $ = jQuery.noConflict();
    $(".alpha_search").click('a', function (e) {
        e.preventDefault();
        var letter = $(e.target).text();
        $('#letter').val(letter);
        $('#adminForm').submit();

    });

    $(document).ready(function () {
        $('#system-message').hide()
    });

</script>
