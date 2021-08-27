<?php
/**
 * JoomPortfolio plugin for Joomla 3.0
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

$db = JFactory::getDbo();

$q = "
                CREATE TABLE IF NOT EXISTS `#__jp3_settings` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `mode` varchar(255) NOT NULL,
                `params` text NOT NULL,
                 PRIMARY KEY (`id`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		";
$db->setQuery($q);
$db->execute();
$db->setQuery("SELECT COUNT(id) FROM #__jp3_settings WHERE mode='memorials'");
if (!$db->loadResult()) {
    $params = json_encode(array(
        'show_title' => 0,
        'title_text' => 'Memorials',
        'category_title' => 1,
        'category_description' => 1,
        'category_image' => 1,
        'category_items_count' => 10,
        'category_items_description' => 1,
        'category_hits_enabled' => 1,
        'category_rating_enabled' => 1,
        'category_custom_enabled' => 1,
        'category_preview_width' => 100,
        'category_preview_height' => 100,
        'item_title' => 1,
        'item_description' => 1,
        'item_hits_enabled' => 1,
        'item_rating_enabled' => 1,
        'item_custom_enabled' => 1,
        'item_bookmark_enable' => 1,
        'item_preview_width' => 300,
        'item_preview_height' => 300,
        'item_comment_enable' => 0,
        'disqusSubDomain' => '',
        'disqusListingCounter' => 1,
        'disqusArticleCounter' => 1,
        'disqusDevMode' => 0,
        'debugMode' => 0,
        'addthis_profile_id' => 'Your Profile ID',
        'addthis_button_style' => 'lg-share',
        'addthis_custom_url' => '',
        'addthis_toolbox_services' => '',
        'addthis_toolbox_more_services_mode' => 'expanded',
        'addthis_icon_dimension' => 16,
        'addthis_language' => 'en',
        'addthis_brand' => '',
        'addthis_header_color' => '',
        'addthis_header_background' => '',
        'addthis_services_compact' => '',
        'addthis_services_exclude' => '',
        'addthis_services_expanded' => '',
        'addthis_services_custom' => '',
        'addthis_offset_top' => '',
        'addthis_offset_left' => '',
        'addthis_hover_delay' => '',
        'addthis_click' => 0,
        'addthis_hover_direction' => 0,
        'addthis_use_addressbook' => 0,
        'addthis_508_compliant' => 0,
        'addthis_data_track_clickback' => 1,
        'addthis_hide_embed' => 0,
        'addthis_use_css' => 1,
        'addthis_ga_tracker' => '',
        'position' => 'top',
        'category_show_categories_count' => 1,
        'category_show_items_count' => 1,
        'social_google_plus_use' => 0,
        'social_google_plus_size' => 'medium',
        'social_google_plus_annotation' => 'bubble',
        'social_google_plus_language' => 'en-US',
        'social_twitter_use' => 0,
        'social_twitter_size' => 'standart',
        'social_twitter_annotation' => 'horizontal',
        'social_twitter_language' => 'en',
        'social_linkedin_use' => 1,
        'social_linkedin_annotation' => 'right',
        'social_facebook_use' => 0,
        'social_facebook_verb' => 'like',
        'social_facebook_layout' => 'button_count',
        'social_facebook_font' => 'arial',
        'social_pinterest_use'=>0,
        'social_pinterest_layout'=>'button_count',
        'item_days'=>30,
        'item_hide_old_item'=>1
    ));

    $db->setQuery("INSERT INTO `#__jp3_settings` (`params`, `mode`) VALUES
			( '" . $params . "', 'memorials');");
    $db->execute();
}
$q2 = "
                CREATE TABLE IF NOT EXISTS `#__jp3_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `item_view` text NOT NULL,
  `cat_view` text NOT NULL,
  `mode` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		";
$db->setQuery($q2);
$db->execute();
$db->setQuery("SELECT COUNT(id) FROM #__jp3_templates WHERE mode='memorials'");
if (!$db->loadResult()) {

    $query = $db->getQuery(true);
    $query->insert('`#__jp3_templates`')
        ->set('`name`="default"')
        ->set('`item_view`="<div class=\"portfolioContent\">\r\n   <h2 align=\"center\">[title] </h2>\r\n<div class=\"jp-item-dates\">[date_of_birth] - [date_of_death]</div>\r\n <div class=\"jp-info muted\"></div><div class=\"jp-info\"><div class=\"jp-info-term\"><strong>Details</strong></div>\r\n<div class=\"jp-category-name\">Category: [item_category_name]</div>\r\n <div class=\"jp-published\"><span class=\"icon-calendar\"></span> Created: [date_of_created]</div>\r\n  <div class=\"jp-hits\"><span class=\"icon-eye-open\"></span> [hits]</div></div>\r\n  <div class=\"gallery grid_4 alpha\">\r\n     <div class=\"image-row\">\r\n           <div id=\"gallery-main\" class=\"image-row\">[photo]</div>\r\n     </div>\r\n  </div>\r\n   <div class=\"jp-item-dates\"></div>\r\n <div class=\"jp-item-dates-funeral\">[label-date_of_funeral]: [date_of_funeral] </div>\r\n    <div class=\"grid omega\">\r\n      <div class=\"description \">[description]\r\n      </div>\r\n   <p>[rating]</p>\r\n   </div>\r\n<br><br><div class=\"portfolio-social-item\">\r\n   [social_buttons]\r\n  </div>\r\n<div class=\"jp-separate clr\"></div>\r\n      <div class=\"jp-tabs\">\r\n    <ul class=\"nav nav-tabs\" id=\"myTab\">\r\n        <li class=\"active grey nn_tabs-tab\"><a data-toggle=\"tab\" href=\"#pdf\"><span aria-hidden=\"true\" class=\"icon-book\"></span>PDF</a></li>\r\n       <li class=\"gray nn_tabs-tab\"><a data-toggle=\"tab\" href=\"#audio\"><span aria-hidden=\"true\" class=\"icon-music\"></span>Audio</a></li>\r\n       <li class=\"gray nn_tabs-tab\"><a data-toggle=\"tab\" href=\"#video\"><span aria-hidden=\"true\" class=\"icon-video-2\"></span>Videos</a></li>\r\n       <li class=\"gray nn_tabs-tab\"><a data-toggle=\"tab\" href=\"#comments\"><span aria-hidden=\"true\" class=\"icon-comments-2\"></span>Condolences</a></li>\r\n   <li class=\"gray nn_tabs-tab\"><a data-toggle=\"tab\" href=\"#ornaments\"><span aria-hidden=\"true\" class=\"icon-comments-2\"></span>Ornaments</a></li>\r\n      </ul>\r\n      [tabs_start]\r\n       [pdf]\r\n       [audio]\r\n       [comments]\r\n       [video]\r\n   [ornaments]\r\n   [tabs_end]\r\n    </div>\r\n	</div>"')
        ->set('`cat_view`="<div class=\"item-containter\">  \r\n   <div class=\"jp-photo-container\">\r\n     <div class=\"item-photo\">[photo]</div>\r\n   </div>\r\n   <div class=\"grid_4 omega description-container\">\r\n           <h2 >\r\n             [title]\r\n           </h2>\r\n  <br/>\r\n  <div >\r\n          [date_of_birth]-[date_of_death]\r\n           </div>\r\n           <div class=\"item-s-description\">\r\n            [description]\r\n           </div>\r\n         \r\n           <p class=\"hits\">[hits]</p>\r\n          <div class=\"rating\">[rating]</div>\r\n   </div>\r\n  \r\n   <div class=\"clear\">&nbsp;</div>\r\n   <div class=\"portfolio-social-item\">\r\n   [social_buttons]\r\n  </div>\r\n <div class=\"jp-category_readmore\">[read_more]</div>\r\n</div>"')
        ->set('`mode`="memorials"');
    $db->setQuery($query);
    $db->execute();
}
    $q3 = "
               CREATE TABLE IF NOT EXISTS `#__jp3_dashboard_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `mode` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
		";
    $db->setQuery($q2);
    $db->execute();
    $db->setQuery("SELECT COUNT(id) FROM #__jp3_dashboard_items WHERE mode='memorials'");
    if (!$db->loadResult()) {

        $db->setQuery("INSERT INTO `#__jp3_dashboard_items` (`title`, `url`, `icon`, `published`,`mode`) VALUES
			('Manage Categories', 'index.php?option=com_joomportfolio&view=categories', '".JURI::root()."administrator/components/com_joomportfolio/assets/images/categories48.png', 1, 'memorials'),
			('Manage Obituaries', 'index.php?option=com_joomportfolio&view=items', '".JURI::root()."administrator/components/com_joomportfolio/assets/images/items48.png', 1, 'memorials'),
			('Manage Fields', 'index.php?option=com_joomportfolio&view=fields', '".JURI::root()."administrator/components/com_joomportfolio/assets/images/help48.png', 1, 'memorials'),
			('Manage Condolences', 'index.php?option=com_joomportfolio&view=comments', '".JURI::root()."administrator/components/com_joomportfolio/assets/images/comments.png', 1, 'memorials'),
            ('Manage Templates', 'index.php?option=com_joomportfolio&view=templates', '".JURI::root()."administrator/components/com_joomportfolio/assets/images/icon_48_templates.png', '1', 'memorials');");
        $db->execute();

    }

?>