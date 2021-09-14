<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.modal');
JHtml::_('behavior.tooltip');

$mainframe = JFactory::getApplication();
$com_params = JComponentHelper::getParams('com_joomportfolio');

if (intval($this->settings->item_preview_width) == 0) {
    $preview_width = 300;
} else {
    $preview_width = intval($this->settings->item_preview_width);
}
if (intval($this->settings->item_preview_width) == 0) {
    $preview_height = 300;
} else {
    $preview_height = intval($this->settings->item_preview_height);
}
$title = '';
if (intval($this->settings->show_title) == 1) {
    $title = $this->settings->title_text;
}

if ((intval($this->settings->item_title) == 1) and isset($this->item->title)) {
    if (!empty($title)) {
        $title .= ' &mdash; ';
    }
    $title .= $this->item->title;
}

$document = JFactory::getDocument();
$document->addScriptDeclaration("var base = '" . JURI::root() . "';");

$app = JFactory::getApplication();
$jinput = JFactory::getApplication()->input;
$Itemid = $jinput->get('Itemid', 0, 'INT');
$pathway = $mainframe->getPathway();
$breadcrumb = $pathway->setPathway(array());
$pathway->addItem('Memorials Directory', JRoute::_("index.php?option=com_joomportfolio"));
$pathway->addItem($this->item->cat_title, JRoute::_("index.php?option=com_joomportfolio&view=category&id=" . $this->item->cat_slug . '&Itemid=' . $Itemid));
$pathway->addItem($this->item->title, '');

?>
<style>

    .jp-item-wrapper {
        max-width: 250px;
        margin: auto;
    }

    .connected-carousels div.jp-navigation {
        border-top: none;
        border-bottom: none;
    }


</style>
<link rel="stylesheet" href="<?php echo(JUri::root()); ?>plugins/portfolio/memorials/css/style.css" type="text/css"/>

<link rel="stylesheet" href="<?php echo(JUri::root()); ?>plugins/portfolio/memorials/css/jcarousel.connected-carousels.css"
      type="text/css"/>
<script type="text/javascript"
        src="<?php echo(JUri::root()); ?>plugins/portfolio/memorials/js/jquery.jcarousel.js"></script>
<script type="text/javascript"
        src="<?php echo(JUri::root()); ?>plugins/portfolio/memorials/js/jcarousel.connected-carousels.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('[rel=popover]').popover();
    });
</script>
<script type="text/javascript">
    window.addEvent('domready', function () {
        $$('#gallery-main a.ceraBox').cerabox({
            titleFormat: '{title}'
        });
    });


    function showCommentForm() {

        var str = '';

        if (jQuery('.jp-comment-form').length) {
            jQuery('#comments-area').empty();
        } else {
            str+='<div class="jp-comment-form">';
            str+='<form name="commentForm" id="commentForm" action="index.php?option=com_joomportfolio&task=item.addComment" method="post">';
            str+='<input type="text" class="jp-inputbox jb-small-field" placeholder="Leave your comment..." id="jp-instead-editor" name="jp-instead-editor" style="display: none;">';
            str+=' <div class="jp-editor" style="display: block;">';
            str+='<textarea  id="comment-text" name="comment-text" rows="5" class="jp-textarea jp-comment-textarea" placeholder="Leave your comment..."></textarea>';
            str+='</div>';
            str+='<input type="hidden" id="jp-mem-item-id" name="item_id" value="<?php echo $this->item->id; ?>"/>';
            //str+='<input type="text" id="jp-mem-user-name" name="user-name" value="" placeholder="Enter name"/>';
            str+='<input type="hidden" name="modename" value="memorials">';
            str+='<div class="jp-unreg-fields" style="display: block;">';
            str+='<div class="jp-namereg-field clearfix" style="display: block;">';
            str+='<button class="jp-btn jb-comment-btn jb-btn-primary" type="button" onclick="addComment();">Add comment</button>';
            str+='</div>';
            str+='</div>';
            str+='</form>';
            str+='</div>';


            //str += '<div class="form-for-comments"><textarea name="comment-text" id="comment-text"></textarea></div><div><input type="button" onclick="addComment();" class="condolence-submit btn" id="comment-add" value="<?php echo JText::_("COM_JOOMPORTFOLIO_ADD_COMMENT")?>"></div>';
            jQuery('#comments-area').append(str);
        }

    }

    function addComment() {

        var
            item_id = jQuery('#mem-item-id').val(),
            comment_text = jQuery('#comment-text').val(),
            //user_name = jQuery('#jp-mem-user-name').val(),
            modename = 'memorials';
        if (comment_text != '') {
            jQuery.ajax({
                url: "index.php?option=com_joomportfolio&task=item.addComment",
                type: "POST",
                data: {
                    'item_id': item_id,
                    'comment-text': comment_text,
                    'modename': modename,
                    //'user-name':user_name
                },
                success: function (obj) {
                    var data = jQuery.parseJSON(obj),
                        str='';
                    if(data.status=='1'){

                    str += '<div class="jp-post-comment" id="comment' +data.comment.id+'">';
                        str += '<div class="jp-comment-info">';
                        str += '<div class="jp-comment-infoblock">';
                        str+='<span class="jp-comuser-name">';
                        str+=data.comment.username;
                        str+='</span><span class="jp-post-date">';
                        str +=  data.comment.date + '</span>';
                        str += '</div>';
                        str += '</div>';
                        str+='<div class="jp-commenttext" id="desc-comment-' +data.comment.id+ '">';
                        str += data.comment.comment;
                        str += '</div>';
                        str += '</div>';
                        jQuery('#commentListFormDiv').append(str);
                        jQuery('#comments-area').empty();
                        jQuery('#jp-comments-not-found').remove();
                        alert("Condolence was added");
                    }else{
                        alert("Condolence do not added");
                    }

                }
            });
        }

    }

    function showOrnamentForm() {

        jQuery('#ornament-area').toggle();
    }

    function addOrnament() {

        var
            condolence_id = jQuery('#condolence-image-id').val(),
            item_id = jQuery('#mem-item-id').val();
        jQuery.ajax({
            url: "index.php?option=com_joomportfolio&task=item.addOrnament",
            type: "POST",
            data: {
                'item_id': item_id,
                'condolence_id': condolence_id
            },
            success: function (obj) {
                location.reload();

            }
        });
    }


    jQuery(function () {
        var main_photo_container = jQuery('div.carousel-stage').width(),
            navig_blok = jQuery('.jp-navigation').width(),
            li_activ,
            li_next_after_activ,
            li_next_next_after_activ,
            navi_content_width;
        if (jQuery('.carousel-navigation ul li').length > 2) {
            li_activ = jQuery('.carousel-navigation ul li.active').width();
            li_next_after_activ = jQuery('.carousel-navigation ul li.active').next().width();
            li_next_next_after_activ = jQuery('.carousel-navigation ul li.active').next().next().width();
            navi_content_width = li_activ + li_next_after_activ + li_next_next_after_activ;
            jQuery('.carousel-navigation').css({"width": main_photo_container - 35});
            jQuery('.jp-navigation').css({"width": main_photo_container - 15});
            jQuery('.jp-navigation').css({"margin-left": 15});
            if (navi_content_width < navig_blok) {
                if (jQuery('.carousel-navigation ul li').length == 3) {
                    jQuery('a.prev-navigation, a.next-navigation').css({"display": "none"});
                    jQuery('.connected-carousels .carousel-navigation').css({"box-shadow": "0 0 0px #999"});
                }
            } else {
                navi_content_width = li_activ + li_next_after_activ;
                jQuery('.carousel-navigation').css({"width": main_photo_container - 35});
                jQuery('.jp-navigation').css({"width": main_photo_container - 15});
                jQuery('.jp-navigation').css({"margin-left": 15});
                if (navi_content_width > navig_blok) {
                    jQuery('a.prev-navigation, a.next-navigation').css({"display": "none"});
                    jQuery('.connected-carousels .carousel-navigation').css({"box-shadow": "0 0 0px #999"});

                }

            }
        }
        if (jQuery('.carousel-navigation ul li').length == 1) {
            jQuery('a.prev-navigation, a.next-navigation').css({"display": "none"});
            jQuery('.connected-carousels .carousel-navigation').css({"box-shadow": "0 0 0px #999"});

        }
        if (jQuery('.carousel-navigation ul li').length == 2) {
            li_activ = jQuery('.carousel-navigation ul li.active').width();
            li_next_after_activ = jQuery('.carousel-navigation ul li.active').next().width();
            navi_content_width = li_activ + li_next_after_activ;
            console.log('navi_content_width:' + navi_content_width);
            console.log('navig_blok:' + navig_blok);
            if (navi_content_width > navig_blok) {
                jQuery('.carousel-navigation').css({"width": main_photo_container - 35});
                jQuery('.jp-navigation').css({"width": main_photo_container - 15});
                jQuery('.jp-navigation').css({"margin-left": 15});
            } else {
                jQuery('a.prev-navigation, a.next-navigation').css({"display": "none"});
                jQuery('.connected-carousels .carousel-navigation').css({"box-shadow": "0 0 0px #999"});
            }

        }

    });


</script>

<link rel="stylesheet" href="<?php echo(JUri::root()); ?>plugins/portfolio/memorials/css/cerabox.css" type="text/css" media="all"/>

<style>
    .switcher a.current {
        margin-left: -5px;
    }
</style>

<script type="text/javascript" src="<?php echo(JUri::root()); ?>plugins/portfolio/memorials/js/cerabox.js"></script>
<div class="portfolio-item-containter" id="portfolio-item-containter">


    <?php if (isset($this->item->id)) {
        ?>
        <div id="portfolio" class="container" style="width: auto !important;">
            <input type="hidden" id="mem-item-id" value="<?php echo $this->item->id; ?>"/>
            <input type="hidden" id="mem-Itemid" value="<?php echo $this->Itemid; ?>"/>

            <?php JoomPortfolioHelper::showItem($this->item, $this->rating, $this->fields, $this->images, $this->settings, $this->pdf, $this->audio, $this->comments, $this->video); ?>


            <div class="portfolio-social-item">

                <?php if ((int)$this->settings->social_twitter_use == 1) { ?>

                    <script type="text/javascript">
                        (function () {
                            var twitterScriptTag = document.createElement('script');
                            twitterScriptTag.type = 'text/javascript';
                            twitterScriptTag.async = true;
                            twitterScriptTag.src = '//platform.twitter.com/widgets.js';
                            var s = document.getElementsByTagName('script')[0];
                            s.parentNode.insertBefore(twitterScriptTag, s);
                        })();
                    </script>

                <?php } ?>

                <?php if ((int)$this->settings->social_linkedin_use == 1) { ?>

                    <script type="text/javascript" src="//platform.linkedin.com/in.js"></script>

                <?php } ?>

                <?php if ((int)$this->settings->social_facebook_use == 1) { ?>

                    <div id="fb-root"></div>
                    <script>(function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) return;
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//connect.facebook.net/<?php echo JFactory::getLanguage()->getTag(); ?>/all.js#xfbml=1";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>

                <?php }?>
                <?php if ((int)$this->settings->social_pinterest_use == 1) { ?>

                    <script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
                <?php } ?>
            </div>

            <!-- <a href="//www.pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.flickr.com%2Fphotos%2Fkentbrew%2F6851755809%2F&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="above"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
             -->
            <div class="clr"></div>
        </div>
    <?php } ?>

    <?php if ((int)$this->settings->item_comment_enable == 1) { ?>
        <div class="afterContent">

            <?php echo JoomPortfolioHelper::renderComments($this->item, $this->settings); ?>
        </div>
    <?php } ?>
</div>


