<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

class com_joomportfolioInstallerScript {

    function install($parent) {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        if (!JFile::exists(JPATH_SITE . DS . "images" . DS . "joomportfolio")) {
            JFolder::create(JPATH_SITE . DS . "images" . DS . "joomportfolio", 0755);
        }
        if (!JFile::exists(JPATH_SITE . DS . "media" . DS . "com_joomportfolio" . DS . "custom.xml")) {
            copy(JPATH_SITE . DS . "media" . DS . "com_joomportfolio" . DS . "custom_empty.xml", JPATH_SITE . DS . "media" . DS . "com_joomportfolio" . DS . "custom.xml");
        }
        echo '<font style="font-size:2em; color:#55AA55;" >JoomPortfolio component successfully installed.</font><br/><br/>';

        $db = JFactory::getDbo();

        //Adding default dashboard items
        $db->setQuery("SELECT COUNT(id) FROM #__jp3_dashboard_items");
        if (!$db->loadResult()) {
            $query = $db->getQuery(true);
            $query->insert('`#__jp3_dashboard_items`')
                ->set('`title`="Manage Categories"')
                ->set('`url`="index.php?option=com_joomportfolio&view=categories"')
                ->set('`icon`="'.JURI::root() . 'administrator/components/com_joomportfolio/assets/images/categories48.png"')
                ->set('`published`=1')
                ->set('`mode`="joomportfolio"');
            $db->setQuery($query);
            $db->execute();

            $query = $db->getQuery(true);
            $query->insert('`#__jp3_dashboard_items`')
                ->set('`title`="Manage Items"')
                ->set('`url`="index.php?option=com_joomportfolio&view=items"')
                ->set('`icon`="'.JURI::root() . 'administrator/components/com_joomportfolio/assets/images/items48.png"')
                ->set('`published`=1')
                ->set('`mode`="joomportfolio"');
            $db->setQuery($query);
            $db->execute();

            $query = $db->getQuery(true);
            $query->insert('`#__jp3_dashboard_items`')
                ->set('`title`="Custom Fields"')
                ->set('`url`="index.php?option=com_joomportfolio&view=fields"')
                ->set('`icon`="'.JURI::root() . 'administrator/components/com_joomportfolio/assets/images/help48.png"')
                ->set('`published`=1')
                ->set('`mode`="joomportfolio"');
            $db->setQuery($query);
            $db->execute();

            $query = $db->getQuery(true);
            $query->insert('`#__jp3_dashboard_items`')
                ->set('`title`="Manage Comments"')
                ->set('`url`="index.php?option=com_joomportfolio&view=comments"')
                ->set('`icon`="'.JURI::root() . 'administrator/components/com_joomportfolio/assets/images/comments.png"')
                ->set('`published`=1')
                ->set('`mode`="joomportfolio"');
            $db->setQuery($query);
            $db->execute();

            $query = $db->getQuery(true);
            $query->insert('`#__jp3_dashboard_items`')
                ->set('`title`="Manage Templates"')
                ->set('`url`="index.php?option=com_joomportfolio&view=templates"')
                ->set('`icon`="'.JURI::root() . 'administrator/components/com_joomportfolio/assets/images/icon_48_templates.png"')
                ->set('`published`=1')
                ->set('`mode`="joomportfolio"');
            $db->setQuery($query);
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
        $db->setQuery("SELECT COUNT(id) FROM #__jp3_templates");
        if (!$db->loadResult()) {
            $db->setQuery("INSERT INTO `#__jp3_templates` (`name`, `item_view`, `cat_view`, `mode`) VALUES
            ( 'default', '<div class=\"portfolioContent\">\r\n   <div class=\"item-heading-portfolio-in-item\">[title] </div>\r\n  <div class=\"gallery grid_4 alpha\">\r\n     <div id=\"gallery-main\" class=\"image-row\">\r\n           <div id=\"gallery-main\" class=\"image-row\">[photo]</div>\r\n     </div>\r\n  </div>\r\n   <div class=\"custom grid omega\">\r\n      [label-technologies_and_tools]: [technologies_and_tools]<br><br>\r\n       [label-efforts]: [efforts]<br>\r\n      <br>[label-url]: [url]<br><br>\r\n      <p>[hits]</p>\r\n     <p>[rating]</p>   \r\n   </div>\r\n<div class=\"clearfix\"></div>\r\n  <div class=\"jp-description-in-item description\">[description]\r\n      </div>\r\n<div class=\"portfolio-social-item\">\r\n   [social_buttons]\r\n  </div>\r\n<div class=\"separate\"></div>\r\n    <div class=\"jp-tabs\">\r\n    <ul class=\"nav nav-tabs\" id=\"myTab\">\r\n        <li class=\"active grey nn_tabs-tab\"><a data-toggle=\"tab\" href=\"#pdf\"><span aria-hidden=\"true\" class=\"icon-book\"></span>PDF</a></li>\r\n       <li class=\"gray nn_tabs-tab\"><a data-toggle=\"tab\" href=\"#audio\"><span aria-hidden=\"true\" class=\"icon-music\"></span>Audio</a></li>\r\n       <li class=\"gray nn_tabs-tab\"><a data-toggle=\"tab\" href=\"#video\"><span aria-hidden=\"true\" class=\"icon-video-2\"></span>Videos</a></li>\r\n       <li class=\"gray nn_tabs-tab\"><a data-toggle=\"tab\" href=\"#comments\"><span aria-hidden=\"true\" class=\"icon-comments-2\"></span>Comments</a></li>\r\n         </ul>\r\n      [tabs_start]\r\n       [pdf]\r\n       [audio]\r\n       [comments]\r\n       [video]\r\n      [tabs_end]\r\n    </div>\r\n	</div>', '<div class=\"item-containter\">  \r\n   <div class=\"jp-photo-container\">\r\n     <div class=\"item-photo\">[photo]</div>\r\n   </div>\r\n   <div class=\"grid_4 omega description-container\">\r\n           <div class=\"jp-item-heading\">\r\n             [title]\r\n           </div>\r\n           <div class=\"custom\">\r\n           <p>[label-technologies_and_tools]: [technologies_and_tools]</p>\r\n           <p>[label-efforts]: [efforts]</p>\r\n           </div>\r\n           <p class=\"hits\">[hits]</p>\r\n           <div class=\"item-s-description\">\r\n            [description]\r\n           </div>\r\n           <div class=\"rating\">[rating]</div>\r\n   </div>\r\n     <div class=\"clear\">&nbsp;</div>\r\n   <div class=\"portfolio-social-item\">\r\n   [social_buttons]\r\n  </div>\r\n</div>', 'joomportfolio');");
            $db->execute();
        }
		
    }

    function update($parent) {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
        jimport('joomla.html.html');
        $database = JFactory::getDBO();
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        if (!JFile::exists(JPATH_SITE . DS . "images" . DS . "joomportfolio")) {
            JFolder::create(JPATH_SITE . DS . "images" . DS . "joomportfolio", 0755);
        }
        if (!JFile::exists(JPATH_SITE . DS . "media" . DS . "com_joomportfolio" . DS . "custom.xml")) {
            copy(JPATH_SITE . DS . "media" . DS . "com_joomportfolio" . DS . "custom_empty.xml", JPATH_SITE . DS . "media" . DS . "com_joomportfolio" . DS . "custom.xml");
        }

        $q = "
			CREATE TABLE IF NOT EXISTS `#__jp3_pictures` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `item_id` int(11) NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `full` varchar(255) NOT NULL,
			  `thumb` varchar(255) NOT NULL,
			  `is_default` enum('0','1') NOT NULL,
			  `copyright` text NOT NULL,
			  `description` text NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `item_id` (`item_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		";
        $database->setQuery($q);
        $database->execute();
        echo '<font style="font-size:2em; color:#55AA55;" >JoomPortfolio component successfully updated.</font><br/><br/>';

        $q2 = "
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
        $database->setQuery($q2);
        $database->execute();
    }

    function postflight($type, $parent) {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        $db = JFactory::getDBO();
        $db->setQuery('SELECT extension_id FROM #__extensions WHERE name="com_joomportfolio"');
        $c_id = (int) $db->loadResult();

        $db->setQuery('UPDATE #__menu SET component_id=' . $c_id . ' WHERE link LIKE "index.php?option=com_joomportfolio%" AND client_id=0');
        $db->execute();

        //presetting some custom fields
        JLoader::register('JoomPortfolioHelper', JPATH_ADMINISTRATOR . '/components/com_joomportfolio/helpers/joomportfolio.php');
        $mode = JoomPortfolioHelper::getMode();
        if(!$mode) {
            $mode = 'memorials';
        }
        JLoader::register('JoomPortfolioTableField', JPATH_ADMINISTRATOR . '/components/com_joomportfolio/tables/field.php');
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_joomportfolio/models');
        $model_sampledata = JModelLegacy::getInstance('Sampledata', 'JoomPortfolioModel');
        if($mode == 'joomportfolio') {
            $model_sampledata->samplePortfolioCustomFields();
        } else if($mode == 'memorials') {
            $model_sampledata->sampleMemorialsCustomFields();
        }

        $imgpath = JURI::root() . '/administrator/components/com_joomportfolio/assets/images/';
        ?>
        <style type="text/css">
            .installtable
            {
                border: 1px solid #D5D5D5;
                background-color: #F7F8F9;
                width: 100%;
                padding: 10px; 
                border-collapse: collapse;
            }
            .installtable tr, .installtable th, .installtable td
            {
                border: 1px solid #D5D5D5;
            }
        </style>
        <table border="1" cellpadding="5" width="100%" class="installtable">		
            <tr>
                <td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:16px; font-weight:400; line-height:18px "><strong><img src="<?php echo $imgpath; ?>tick.png"/> Say your "Thank you" to Joomla community</strong></td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:20px">
                    <div style="font-size:1.2em">
                        <p style="font-size:12px;"><span style="font-size:14pt;">Say your "Thank you" to Joomla community</span> for WonderFull Joomla CMS and <span style="font-size:14pt;">help it</span> by sharing your experience with this component. It will only take 1 min for registration on <a href="http://extensions.joomla.org" target="_blank">http://extensions.joomla.org</a> and 3 minutes to write useful review! A lot of people will thank you!<br />
                            <a href="https://extensions.joomla.org/extension/vertical-markets/thematic-directory/online-memorials-directory/" target="_blank"><img src="http://www.joomplace.com/components/com_jparea/assets/images/rate-2.png" title="Rate Us!" alt="Rate us at Extensions Joomla.org"  style="padding-top:5px;"/></a></p>
                    </div>
                </td>
            </tr>
        </table>
        <?php
    }

}