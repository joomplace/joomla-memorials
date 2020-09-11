<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class JoomPortfolioHelper
{

    static $_profile_id;
    static $_button_style;
    static $_custom_url;
    static $_toolbox_services;
    static $_icon_dimension;
    static $_addthis_brand;
    static $_addthis_header_color;
    static $_addthis_header_background;
    static $_addthis_services_compact;
    static $_addthis_services_exclude;
    static $_addthis_services_expanded;
    static $_addthis_services_custom;
    static $_addthis_offset_top;
    static $_addthis_offset_left;
    static $_addthis_hover_delay;
    static $_addthis_click;
    static $_addthis_hover_direction;
    static $_addthis_use_addressbook;
    static $_addthis_508_compliant;
    static $_addthis_data_track_clickback;
    static $_addthis_hide_embed;
    static $_addthis_language;
    static $_position;
    static $_show_frontpage;
    static $_toolbox_more_services_mode;
    static $_addthis_use_css;
    static $_addthis_ga_tracker;
    static $_addthis_link;
    static $_addthis_title;

    public static function renderComments($item, $params)
    {
        $disqus_name = trim($params->disqusSubDomain);
        if (!$disqus_name) {
            $disqus_name = 0;
        }
        $disqus_dev = $params->disqusDevMode;
        if (!$disqus_dev) {
            $disqus_dev = 0;
        }

        $disqus_uid = 'com_joomportfolio_item_' . $item->id;
        $disqus_url = JURI::getInstance()->toString();

        $script = "
		var disqus_shortname = '" . $disqus_name . "';
		var disqus_developer = " . $disqus_dev . ";
		var disqus_identifier = '" . $disqus_uid . "';
		var disqus_url = '" . $disqus_url . "';
		(function() {
			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		})();";

        $document = JFactory::getDocument();
        $document->addScriptDeclaration($script);

        $out = '
		<div id="disqus_thread"></div>
		<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>';
        return $out;
    }

    public static function renderBookmarks($item, $params)
    {
        $settings = $params;
        //die(var_dump($settings));
        self::$_profile_id = $settings->addthis_profile_id;
        self::$_button_style = $settings->addthis_button_style;
        self::$_custom_url = $settings->addthis_custom_url;
        self::$_toolbox_services = $settings->addthis_toolbox_services;
        self::$_icon_dimension = $settings->addthis_icon_dimension;
        self::$_addthis_brand = $settings->addthis_brand;
        self::$_addthis_header_color = $settings->addthis_header_color;
        self::$_addthis_header_background = $settings->addthis_header_background;
        self::$_addthis_services_compact = $settings->addthis_services_compact;
        self::$_addthis_services_exclude = $settings->addthis_services_exclude;
        self::$_addthis_services_expanded = $settings->addthis_services_expanded;
        self::$_addthis_services_custom = $settings->addthis_services_custom;
        self::$_addthis_offset_top = $settings->addthis_offset_top;
        self::$_addthis_offset_left = $settings->addthis_offset_left;
        self::$_addthis_hover_delay = $settings->addthis_hover_delay;
        self::$_addthis_click = $settings->addthis_click;
        self::$_addthis_hover_direction = $settings->addthis_hover_direction;
        self::$_addthis_use_addressbook = $settings->addthis_use_addressbook;
        self::$_addthis_508_compliant = $settings->addthis_508_compliant;
        self::$_addthis_data_track_clickback = $settings->addthis_data_track_clickback;
        self::$_addthis_hide_embed = $settings->addthis_hide_embed;
        self::$_addthis_language = $settings->addthis_language;
        //self::$_position = $settings->addthis_position;
        //self::$_show_frontpage = $settings->addthis_show_frontpage;
        self::$_toolbox_more_services_mode = $settings->addthis_toolbox_more_services_mode;
        self::$_addthis_use_css = $settings->addthis_use_css;
        self::$_addthis_ga_tracker = $settings->addthis_ga_tracker;
        self::$_addthis_link = JURI::getInstance()->toString();
        self::$_addthis_title = $item->title;

        $article = $return = null;
        $outputValue = " <div class='joomportf_add_this'>";
        $outputValue .= "<!-- AddThis Button BEGIN -->" . PHP_EOL;

        //Creates addthis configuration script
        $outputValue .= "<script type='text/javascript'>\r\n";
        $outputValue .= "var addthis_product = 'jlp-1.2';\r\n";
        $outputValue .= "var addthis_config =\r\n{";
        $configValue = "";
        $configValue .= self::getProfileId();
        $configValue .= self::getAddThisBrand();
        $configValue .= self::getAddThisHeaderColor();
        $configValue .= self::getAddThisHeaderBackground();
        $configValue .= self::getAddThisServicesCompact();
        $configValue .= self::getAddThisOffsetTop();
        $configValue .= self::getAddThisOffsetLeft();
        $configValue .= self::getAddThisHoverDelay();
        $configValue .= self::getAddThisLanguage();
        $configValue .= self::getAddThisHideEmbed();
        $configValue .= self::getAddThisServiceExclude();
        $configValue .= self::getAddThisServicesExpanded();
        $configValue .= self::getAddThisServicesCustom();
        $configValue .= self::getAddThisClick();
        $configValue .= self::getAddThisHoverDirection();
        $configValue .= self::getAddThisUseAddressBook();
        $configValue .= self::getAddThis508Compliant();
        $configValue .= self::getAddThisDataTrackClickback();
        $configValue .= self::getAddThisUseCss();
        $configValue .= self::getAddThisGATracker();

        //Removing the last comma and end of line characters
        if ("" != trim($configValue)) {
            $outputValue .= implode(',', explode(',', $configValue, -1));
        }
        $outputValue .= "}</script>" . PHP_EOL;

        //Creates the button code depending on the button style chosen
        $buttonValue = "";

        //Generates the button code for toolbox
        if ("toolbox" === self::$_button_style) {
            $buttonValue .= self::getToolboxScript(self::$_toolbox_services);
        } else {
            $buttonValue .= "<a  href='http://www.addthis.com/bookmark.php' " .
                " onmouseover=\"return addthis_open(this,'', '" . self::$_addthis_link . "', '" . self::$_addthis_title . "' )\" " .
                " onmouseout='addthis_close();' onclick='return addthis_sendto();'>";
            $buttonValue .= "<img src='";
            //Custom image for button
            if ("custom" === trim(self::$_button_style)) {
                if ('' == trim(self::$_custom_url)) {
                    $buttonValue .= "http://s7.addthis.com/static/btn/v2/" . self::getButtonImage('lg-share', self::$_addthis_language);
                } else
                    $buttonValue .= self::$_custom_url;
            } //Pointing to addthis button images
            else {
                $buttonValue .= "http://s7.addthis.com/static/btn/v2/" . self::getButtonImage(self::$_button_style, self::$_addthis_language);
            }
            $buttonValue .= "' border='0' alt='AddThis Social Bookmark Button' />";
            $buttonValue .= "</a>" . PHP_EOL;
        }
        $outputValue .= $buttonValue;

        //Adding AddThis script to the page
        $outputValue .= "<script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js'></script>\r\n";
        $outputValue .= "<!-- AddThis Button END -->" . PHP_EOL;
        $outputValue .= "</div>";

        $return = $outputValue;
        return $return;
    }

    private static function getToolboxScript($services)
    {
        //Deciding the toobox icon dimensions
        $dimensionStyle = self::$_icon_dimension == "16" ? '' : ' addthis_32x32_style';
        //Toolbox main div element, holds the url and title for sharing
        $toolboxScript = "<div class='addthis_toolbox" . $dimensionStyle . " addthis_default_style' addthis:url='" . self::$_addthis_link . "' addthis:title='" . self::$_addthis_title . "'>";
        $serviceList = explode(",", $services);
        //Adding the services one by one
        for ($i = 0, $max_count = sizeof($serviceList); $i < $max_count; $i++) {
            $toolboxScript .= "<a class='addthis_button_" . $serviceList[$i] . "'></a>";
        }
        //Adding more services button in user selected mode - (Expanded | Compact)
        $toolboxScript .= "<a class='addthis_button_" . self::$_toolbox_more_services_mode . "'>Share</a>";
        $toolboxScript .= "</div>";
        return $toolboxScript;
    }

    private static function escapeText($text)
    {
        $cleanedText = htmlspecialchars($text);
        return str_replace("'", "\'", $cleanedText);
    }

    private static function getButtonImage($name, $language)
    {
        $buttonImage = $name . '-' . $language . '.gif';
        if ($name == "sm-plus") {
            $buttonImage = $name . '.gif';
        } elseif ($language != 'en') {
            if ($name == 'lg-share' || $name == 'lg-bookmark' || $name == 'lg-addthis') {
                $buttonImage = 'lg-share-' . $language . '.gif';
            } elseif ($name == 'sm-share' || $name == 'sm-bookmark') {
                $buttonImage = 'sm-share-' . $language . '.gif';
            }
        } else {
            $buttonImage = $name . '-' . $language . '.gif';
        }
        return $buttonImage;
    }

    private static function getProfileId()
    {
        return ("Your Profile ID" != trim(self::$_profile_id) && trim(self::$_profile_id) != "") ? "pubid : '" . trim(self::$_profile_id) . "'," . PHP_EOL : "";
    }

    private static function getAddThisBrand()
    {
        return ("" != trim(self::$_addthis_brand)) ? "ui_cobrand : '" . trim(self::$_addthis_brand) . "'," . PHP_EOL : "";
    }

    private static function getAddThisHeaderColor()
    {
        return ("" != trim(self::$_addthis_header_color)) ? "ui_header_color : '" . trim(self::$_addthis_header_color) . "'," . PHP_EOL : "";
    }

    private static function getAddThisHeaderBackground()
    {
        return ("" != trim(self::$_addthis_header_background)) ? "ui_header_background : '" . trim(self::$_addthis_header_background) . "'," . PHP_EOL : "";
    }

    private static function getAddThisServicesCompact()
    {
        return ("" != trim(self::$_addthis_services_compact)) ? "services_compact : '" . trim(self::$_addthis_services_compact) . "'," . PHP_EOL : "";
    }

    private static function getAddThisOffsetTop()
    {
        return (0 != intval(trim(self::$_addthis_offset_top))) ? "ui_offset_top : '" . self::$_addthis_offset_top . "'," . PHP_EOL : "";
    }

    private static function getAddThisOffsetLeft()
    {
        return (0 != intval(trim(self::$_addthis_offset_left))) ? "ui_offset_left : '" . self::$_addthis_offset_left . "'," . PHP_EOL : "";
    }

    private static function getAddThisHoverDelay()
    {
        return (intval(trim(self::$_addthis_hover_delay)) > 0) ? "ui_delay : '" . self::$_addthis_hover_delay . "'," . PHP_EOL : "";
    }

    private static function getAddThisLanguage()
    {
        return ("" != trim(self::$_addthis_language)) ? "ui_language : '" . self::$_addthis_language . "'," . PHP_EOL : "";
    }

    private static function getAddThisHideEmbed()
    {
        return ('0' == trim(self::$_addthis_hide_embed)) ? "ui_hide_embed : false," . PHP_EOL : "";
    }

    private static function getAddThisServiceExclude()
    {
        return ("" != trim(self::$_addthis_services_exclude)) ? "services_exclude : '" . self::$_addthis_services_exclude . "'," . PHP_EOL : "";
    }

    private static function getAddThisServicesExpanded()
    {
        return ("" != trim(self::$_addthis_services_expanded)) ? "services_expanded : '" . self::$_addthis_services_expanded . "'," . PHP_EOL : "";
    }

    private static function getAddThisServicesCustom()
    {
        return ("" != trim(self::$_addthis_services_custom)) ? "services_custom : " . self::$_addthis_services_custom . "," . PHP_EOL : "";
    }

    private static function getAddThisClick()
    {
        return ('1' == trim(self::$_addthis_click)) ? "ui_click : true," . PHP_EOL : "";
    }

    private static function getAddThisHoverDirection()
    {
        return ('0' != trim(self::$_addthis_hover_direction) && ('' != (trim(self::$_addthis_hover_direction)))) ? "ui_hover_direction : " . self::$_addthis_hover_direction . "," . PHP_EOL : "";
    }

    private static function getAddThisUseAddressBook()
    {
        return ('1' == trim(self::$_addthis_use_addressbook)) ? "ui_use_addressbook : true," . PHP_EOL : "";
    }

    private static function getAddThis508Compliant()
    {
        return ('1' == trim(self::$_addthis_508_compliant)) ? "ui_508_compliant : true," . PHP_EOL : "";
    }

    private static function getAddThisDataTrackClickback()
    {
        return ('1' == trim(self::$_addthis_data_track_clickback)) ? "data_track_clickback : true," . PHP_EOL : "";
    }

    private static function getAddThisUseCss()
    {
        return ('0' == trim(self::$_addthis_use_css)) ? "ui_use_css : false," . PHP_EOL : "";
    }

    private static function getAddThisGATracker()
    {
        return ("" != trim(self::$_addthis_ga_tracker)) ? "data_ga_tracker : " . self::$_addthis_ga_tracker . "," . PHP_EOL : "";
    }

    public static function getDataFile($file)
    {
        $mediaFile = JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . $file;
        if (file_exists($mediaFile)) {
            $xmlFile = $mediaFile;
        } else {
            $xmlFile = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . $file;
        }
        return $xmlFile;
    }

    public static function loadLanguage()
    {
        $mode = JoomPortfolioHelper::getVarMode();

        if (!$mode) {
            $jinput = JFactory::getApplication()->input;
            $view = $jinput->get('view', '');
            if ($view == 'category') {
                $mode = JoomPortfolioHelper::getModeByCatId();
            } else {
                $mode = 'joomportfolio';
            }
        }
        $extension = 'com_' . $mode;
        JFactory::getLanguage()->load($extension, JPATH_ADMINISTRATOR, null, true);
        // JFactory::getLanguage()->load($extension, JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.JFactory::getLanguage()->getTag());
    }


    public static function loadLanguageinfront($mode)
    {

        $extension = 'com_' . $mode;
        JFactory::getLanguage()->load($extension, JPATH_ADMINISTRATOR, null, true);
    }

    public static function setMode($mode)
    {
        $db = JFactory::getDBO();
        $db->setQuery('UPDATE #__extensions SET  custom_data="' . $mode . '" WHERE element="com_joomportfolio"');
        $db->execute();
    }

    public static function getMode()
    {
        $db = JFactory::getDBO();
        $query = "SELECT custom_data  FROM #__extensions WHERE element='com_joomportfolio'";
        $db->setQuery($query);
        $mode = mb_strtolower($db->loadResult());
        return $mode;
    }

    public static function getSettings()
    {
        $mode = JoomPortfolioHelper::getVarMode();
        if (!$mode) {
            $mode = self::getModeByCatId();
        }
        if(!$mode){
            $mode=self::getModeByItemId();
        }
        $db = JFactory::getDBO();
        $query = "SELECT params  FROM #__jp3_settings WHERE mode='" . $mode . "'";
        $db->setQuery($query);
        $params = json_decode($db->loadResult());
        return $params;
    }

    public static function getSetting($mode)
    {
        $db = JFactory::getDBO();
        $query = "SELECT params  FROM #__jp3_settings WHERE mode='" . $mode . "'";
        $db->setQuery($query);
        $params = json_decode($db->loadResult());
        return $params;
    }


    public static function getVarMode()
    {
        $jinput = JFactory::getApplication()->input;
        $mode = $jinput->get('mode');

        if (!$mode) {
            $mode = $jinput->get('extension');
        }

        if ($mode == 'com_joomportfolio') {
            $mode = 'joomportfolio';
        }
        if ($mode == 'com_memorials') {
            $mode = 'memorials';
        }

        return $mode;
    }

    public static function socialButtons($id)
    {
        $settings = self::getSettings();

        if ($settings) {
            //==================================================
            // Social intergation.
            //==================================================

            if ((int)$settings->social_twitter_use == 1 ||
                (int)$settings->social_linkedin_use == 1 ||
                (int)$settings->social_facebook_use == 1 ||
                (int)$settings->social_pinterest_use == 1
            ) {
                $html[] = '<div class="portfolio-social">';

                $pageLink = JRoute::_('index.php?option=com_joomportfolio&view=item&id=' . (int)$id, false, -1);

                if ((int)$settings->social_twitter_use == 1) {
                    $html[] = '<div class="portfolio-social-btn">' .
                        '<a href="http://twitter.com/share" class="twitter-share-button"' .
                        ' data-url="' . $pageLink . '"' .
                        ' data-size="' . (int)$settings->social_twitter_size . '"' .
                        ' data-count="' . $settings->social_twitter_annotation . '"' .
                        ' data-lang="' . $settings->social_twitter_language . '"' .
                        '>Tweet</a>' .
                        '</div>';
                }

                if ((int)$settings->social_linkedin_use == 1) {
                    $html[] = '<div class="portfolio-social-btn">' .
                        '<script type="IN/Share"' .
                        ' data-url="' . $pageLink . '"' .
                        ' data-counter="' . $settings->social_linkedin_annotation . '"' .
                        '></script>' .
                        '</div>';
                }

                if ((int)$settings->social_facebook_use == 1) {
                    $html[] = '<div class="portfolio-social-btn" >' .
                        '<div class="fb-like" data-show-faces="false" data-width="50" data-colorscheme="light" data-share="false" ' .
                        ' data-action="' . $settings->social_facebook_verb . '"' .
                        ' data-layout="' . $settings->social_facebook_layout . '"' .
                        ' data-href="' . $pageLink . '"' .
                        '></div>' .
                        '</div>';
                }

                if ((int)$settings->social_pinterest_use == 1) {
                    $str='';

                    if($settings->social_pinterest_layout=='box_count'){
                        $str='data-pin-do="buttonPin" data-pin-config="above"';

                    }elseif($settings->social_pinterest_layout=='button_count'){
                        $str='data-pin-do="buttonPin" data-pin-config="beside"';

                    }else{
                        $str='data-pin-do="buttonPin" data-pin-config="none"';

                    }
                    $html[] = '<div class="portfolio-social-btn" >' .
                        '<div >'.
                        '<a  href="//www.pinterest.com/pin/create/button/?url='.$pageLink.'" '.$str.' ><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
        '.

                        '</div>' .
                        '</div>';
                    }
                $html[] = '</div><div style="clear: both;"><br clear="all"></div>';

                return implode("\r\n", $html);
            }

        }
    }


    public static function showItem($row, $rating, $custom_array, $image_array, $params, $pdf, $audio, $comments, $video)
    {
        $doc = JFactory::getDocument();
        if ($row->metadesc)
            $doc->setMetaData('description', $row->metadesc);
        if ($row->metakey)
            $doc->setMetaData('keywords', $row->metakey);
        if ($row->metaauth)
            $doc->setMetaData('author', $row->metaauth);
        if ($row->og_picture)
            $doc->setMetaData('og:image', $row->og_picture);
        if ($row->og_description)
            $doc->setMetaData('og:description', $row->og_description);

        $metatags='';
        if ( !empty($row->custom_metatags) )
        {
            if (!is_array($row->custom_metatags) ){
                $row->custom_metatags=@unserialize($row->custom_metatags);
            }

            if ( !empty($row->custom_metatags) )
            {
                foreach ($row->custom_metatags as $custom_tag_name => $custom_tag_value)
                {
                    $metatags.='<meta name="'.$custom_tag_name.'" content="'.$custom_tag_value.'" />'."\n";
                    $metatags.='<meta property="'.$custom_tag_name.'" content="'.$custom_tag_value.'" />'."\n";
                }
            }
        }
        $doc->addCustomTag($metatags);

        if ((int)$params->item_preview_width == 0) {
            $preview_width = 300;
        } else {
            $preview_width = (int)$params->item_preview_width;
        }
        if ((int)$params->item_preview_width == 0) {
            $preview_height = 300;
        } else {
            $preview_height = (int)$params->item_preview_height;
        }
        if (isset($params->item_preview_width)) {
            $width = 'width="' . $params->item_preview_width.'"';
        }

        if (isset($params->item_preview_height)) {
            $height = 'height:' . $params->item_preview_height;
        }
        $strimg = '';
        $main_img = '';


        if (!empty($image_array)) {
            $count_array_images=count($image_array);

            $main_img .= '<div class="jp-item-wrapper">';
            $main_img .= '<div class="connected-carousels">';
            $main_img .= '<div class="stage">';
            $main_img .= '<div class="carousel carousel-stage" style="width:'.$params->item_preview_width.'px;">';
            $main_img .= '<ul>';
            for ($i = 0; $i < $count_array_images; $i++) {
                if ($image_array[$i]->description) {
                    $img_description = $image_array[$i]->description;
                } else {
                    $img_description = '';
                }
                if ($image_array[$i]->copyright) {
                    $img_copyright = $image_array[$i]->copyright;
                } else {
                    $img_copyright = '';
                }
                if ($image_array[$i]->title) {
                    $img_title = $image_array[$i]->copyright;
                } else {
                    $img_title = '';
                }
                $main_img .= '<li><a class="ceraBox " data-copyrait="'. $img_copyright.'" data-title="' . $img_description . '" title="'.$img_title.'" href="' . JURI::base() . 'images/joomportfolio/' . $image_array[$i]->item_id . '/original/' . $image_array[$i]->full . '" ><img    class="ceraBox" src="' . JURI::base() . 'images/joomportfolio/' . $image_array[$i]->item_id . '/original/' . $image_array[$i]->full . '" '.$width.' height="400" alt="" /></a></li>';
            }
            $main_img .= '</ul>';
            $main_img .= '</div>';
            $main_img .= '</div>';
            if(((int)$params->item_preview_width-40)<50){
                $navigation_width=240;
            }else{
                $navigation_width=(int)$params->item_preview_width-40;
            }

            $main_img .= '<div class="jp-navigation" >';
            $main_img .= '<a href="#" class="prev prev-navigation">&lsaquo;</a>';
            $main_img .= '<a href="#" class="next next-navigation">&rsaquo;</a>';

            $main_img .= '<div class="carousel carousel-navigation" >';
            $main_img .= '<ul>';
            $style = '.carousel-navigation img {'
                . 'width:auto;'
                . 'height:90px;}';
            $doc->addStyleDeclaration($style);
           for ($i = 0; $i < count($image_array); $i++) {
                $main_img .= '<li><img  data-copyrait="'. $img_copyright.'" data-title="' . $img_description . '" title="'.$img_title.'" src="' . JURI::base() . 'images/joomportfolio/' . $image_array[$i]->item_id . '/thumb/' . $image_array[$i]->thumb . '"  alt=""></li>';
            }
            $main_img .= ' </ul>';
            $main_img .= '</div>';
            $main_img .= '</div>';
            $main_img .= '</div>';
            $main_img .= '</div>';

        }

        $html = self::getTemplateItemHtml();
        $html = self::_pluginProcess($html);

        $return_str = $html;
        $replaseFrom = $replaseTo = array();


        $replaseFrom[] = '[title]';
        $row->first_name = self::_pluginProcess($row->title);
        $replaseTo[] = (int)$params->item_title ? $row->title : '';

        $mainframe = JFactory::getApplication();

        $field = $custom_array;
        if (!empty($field)) {
            for ($k = 0;$k < count($field); $k++){
                $str = '';
                $keys = $field[$k]['name'];

                if (trim($field[$k]["value"]) != ''){
                    $value = $field[$k]["value"];
                }else {
                    $value = $field[$k]['def'];
                }
                if ($value) {

                    switch ($field[$k]['type']) {
                        case 'textemail':
                            $params = $mainframe->getParams('com_joomportfolio');
                            $dispatcher = JDispatcher::getInstance();
                            JPluginHelper::importPlugin('content', 'emailcloak');
                            $result = $dispatcher->trigger('onPrepareContent', array(& $value, & $params, 0));
                            $str .= '<a href="mailto:' . $value . '">' . $value . '</a>';
                            break;
                        case 'url':
                            $str .= '<a href="' . trim($value) . '" target="_blank" >' . $value . '</a>';
                            break;
                        case 'calendar':
                            if ($value != "") {
                                if (!$field[$k]['format']) {
                                    $field[$k]['format'] = 'd/m/Y';
                                }
                                $str .= JHTML::_('date', $value, $field[$k]['format'], NULL);
                            }
                            break;
                        default:
                            $str .= $value;
                            break;
                    }


                }

                if (!isset($params->item_custom_enabled)) {
                    $params->item_custom_enabled = 1;
                }
                if ((int)$params->item_custom_enabled) {
                    $replaseFrom[] = '[label-' . $field[$k]["name"] . ']';
                    $replaseTo[] = '<strong>' . $field[$k]["label"] . '</strong>';
                    $replaseFrom[] = '[' . $field[$k]["name"] . ']';
                    $replaseTo[] = $str;

                } else {
                    $replaseFrom[] = '[' . $field[$k]["name"] . ']';
                    $replaseTo[] = '';
                }

            }
        }

        $replaseFrom[] = '[hits]';
        $row->item_hits = '<strong>' . JText::_('COM_JOOMPORTFOLIO_HITS') . ':</strong>' . $row->hits;
        if (!isset($params->item_hits_enabled)) {
            $params->item_hits_enabled = 0;
        }
        $replaseTo[] = (int)$params->item_hits_enabled ? $row->item_hits : '';

        $replaseFrom[] = '[rating]';
        if (!empty($rating)) {
            $row->rating = '<strong>' . JText::_('COM_JOOMPORTFOLIO_RATING') . ':</strong>' . $rating;
        } else {
            $row->rating = '';
        }
        if (!isset($params->item_rating_enabled)) {
            $params->item_rating_enabled = 1;
        }

        $replaseTo[] = (int)$params->item_rating_enabled ? $row->rating : '';

        $replaseFrom[] = '[date_of_created]';
        $replaseTo[] = JHTML::_('date', $row->date, 'COM_JOOMPORTFOLIO_DATE_FORMAT', NULL);

        $replaseFrom[] = '[item_category_name]';
        $cathref='<a href="';
        $cathref.=JRoute::_("index.php?option=com_joomportfolio&view=category&id=" . $row->cat_slug);
        $cathref.='">'.$row->cat_title.'</a>';
        $replaseTo[] =$cathref;

        $replaseFrom[] = '[description]';
        $row->description = self::_pluginProcess($row->description);
        if (!isset($params->item_description)) {
            $params->item_description = 1;
        }

        $replaseTo[] = (int)$params->item_description ? $row->description : '';

        $replaseFrom[] = '[social_buttons]';
        $row->item_social_buttons = self::socialButtons((int)$row->id);

        $replaseTo[] =$row->item_social_buttons;


        $replaseFrom[] = '[photo]';
        $replaseTo[] = $main_img; //$this->config->get('category_item_image') ? $image : '';

        /*tabs start */
        $replaseFrom[] = '[tabs_start]';
        $replaseTo[] = JHtml::_('bootstrap.startPane', 'myTab', array('active' => 'pdf')); //JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'pdf'));


        $replaseFrom[] = '[pdf]';
        $replaseTo[] = JHtml::_('bootstrap.addPanel', 'myTab', 'pdf') . self::renderPdf($pdf, $row->id) . JHtml::_('bootstrap.endPanel'); //JHtml::_('bootstrap.addTab', 'myTab', 'pdf', JText::_('COM_JOOMPORTFOLIO_FIELDSET_PDF')) . self::rendePdf($pdf, $row->id);

        $replaseFrom[] = '[audio]';
        $replaseTo[] = JHtml::_('bootstrap.addPanel', 'myTab', 'audio') . self::renderAudio($audio, $row->id) . JHtml::_('bootstrap.endPanel'); //JHtml::_('bootstrap.addTab', 'myTab', 'audio', JText::_('COM_JOOMPORTFOLIO_FIELDSET_AUDIO')) . self::rendeAudio($audio, $row->id) ;

        $replaseFrom[] = '[comments]';
        $replaseTo[] = JHtml::_('bootstrap.addPanel', 'myTab', 'comments') . self::rendComments($comments, $row->id) . JHtml::_('bootstrap.endPanel');

        $replaseFrom[] = '[video]';
        $replaseTo[] = JHtml::_('bootstrap.addPanel', 'myTab', 'video') . self::renderVideo($video, $row->id) . JHtml::_('bootstrap.endPanel');

        $replaseFrom[] = '[ornaments]';
        $replaseTo[] = JHtml::_('bootstrap.addPanel', 'myTab', 'ornaments') . self::rendOrnaments($row->id) . JHtml::_('bootstrap.endPanel');

        $replaseFrom[] = '[tabs_end]';
        $replaseTo[] = JHtml::_('bootstrap.endPane', 'myTab'); //JHtml::_('tabs.end');

        $return_str = str_replace($replaseFrom, $replaseTo, $return_str);

        echo $return_str;
        return $return_str;
    }

    public static function showCatItems($row, $def_images, $params, $cat_id)
    {

        $mainframe = JFactory::getApplication();
        $custom = self::getFields($cat_id, $row->item_id);
        $item_photo = '';
        $item_photo .= '<div class="item-photo">';


        $img_def = '';
        if (empty($def_images)) {
            if (!empty($row->def_image)) {

                $img_def = $row->def_image;
            } elseif (isset($row->images[0])) {

                $img_def = $row->images[0];
            }
        } else {
            foreach ($def_images as $img) {
                if ($row->item_id == $img->item_id) {
                    $img_def = $img->full;
                }
            }
        }
        if ($img_def == '') {

            if (!empty($row->def_image)) {

                $img_def = $row->def_image;
            } elseif (isset($row->images[0])) {

                $img_def = $row->images[0];
            }

            $imgurl = JURI::base() . 'administrator/components/com_joomportfolio/assets/images/thumb_nophoto.jpg';
        } else {
            $imgurl = JURI::base() . 'images/joomportfolio/' . $row->item_id . '/thumb/thumb_' . $img_def;
        }

        $document = JFactory::getDocument();
        $style = '.item-photo a img {'
            . 'width:' . $params->category_preview_width . 'px;'
            . 'height: auto;'
            . '}';
        $document->addStyleDeclaration($style);
        $item_photo .= '<a href="' . JRoute::_('index.php?option=com_joomportfolio&view=item&cid=' . $row->cat_slug . '&id=' . $row->slug) . '" ';
        $item_photo .= 'class="image " >';
        $item_photo .= '<img src="' . $imgurl . '" alt="' . $row->item_title . '" border="0" />';
       $item_photo .= '</a>';
        $item_photo .= '</div>';

        $item_title = '';
        $item_title .= '<a href="' . JRoute::_('index.php?option=com_joomportfolio&view=item&cid=' . $row->cat_slug . '&id=' . $row->slug) . '">';
        $item_title .= $row->item_title;
        $item_title .= '</a>';

        $item_hits = '';
        $item_hits .= '<strong>' . JText::_('COM_JOOMPORTFOLIO_HITS') . ':</strong>';
        $item_hits .= $row->item_hits;

        $item_rating = '';
        if (!isset($row->rating_sum)) {
            $row->rating_sum = 0;
        }

        if (!isset($row->rating_count)) {
            $row->rating_count = 0;
        }

        $item_rating .= self::getRatingStars($row->id, $row->item_sum, $row->item_count);

        $item_read_more = '';
        $item_read_more .= '<a href="' . JRoute::_('index.php?option=com_joomportfolio&view=item&cid=' . $row->cat_slug . '&id=' . $row->slug) . '"';
        $item_read_more .= 'class="read-more" title="read more">Read more</a>';

        $item_description = '';
        $item_description .= $row->item_description_short;


        $html = self::getTemplateCatHtml();
        $html = self::_pluginProcess($html);

        $return_str = $html;
        $replaseFrom = $replaseTo = array();

        $replaseFrom[] = '[photo]';
        $replaseTo[] = $item_photo;

        $replaseFrom[] = '[title]';
        $replaseTo[] = $item_title;


        $replaseFrom[] = '[description]';
        $replaseTo[] = $item_description;

        $replaseFrom[] = '[hits]';
        $replaseTo[] = $item_hits;

        $replaseFrom[] = '[rating]';
        $replaseTo[] = $item_rating;


        $mainframe = JFactory::getApplication();
        $field = $custom;
        if (!empty($field)) {
            for ($k = 0; $k < count($field); $k++) {
                $str = '';
                $keys = $field[$k]['name'];

                if (trim($field[$k]["value"]) != '') {
                    $value = $field[$k]["value"];
                } else {
                    $value = $field[$k]['def'];
                }
                if ($value) {

                    // $str .= '<strong>' . $field[$k]["label"] . ':</strong>';

                    switch ($field[$k]['type']) {
                        case 'textemail':
                            $params = $mainframe->getParams('com_joomportfolio');
                            $dispatcher = JDispatcher::getInstance();
                            JPluginHelper::importPlugin('content', 'emailcloak');
                            $result = $dispatcher->trigger('onPrepareContent', array(& $value, & $params, 0));
                            $str .= '<a href="mailto:' . $value . '">' . $value . '</a>';
                            break;
                        case 'url':
                            $str .= '<a href="' . trim($value) . '" target="_blank" >' . $value . '</a>';
                            break;
                        case 'calendar':
                            if ($value != "") {
                                if (!$field[$k]['format']) {
                                    $field[$k]['format'] = 'd/m/Y';
                                }
                                $str .= JHTML::_('date', $value, $field[$k]['format'], NULL);
                            }
                            break;
                        default:
                            $str .= $value;
                            break;
                    }


                }

                $replaseFrom[] = '[label-' . $field[$k]["name"] . ']';
                $replaseTo[] = '<strong>' . $field[$k]["label"] . '</strong>';
                $replaseFrom[] = '[' . $field[$k]["name"] . ']';
                $replaseTo[] = $str;


            }
        }


        $replaseFrom[] = '[read_more]';
        $replaseTo[] = $item_read_more;

        $replaseFrom[] = '[social_buttons]';
        $row->item_social_buttons = self::socialButtons($row->item_id);

        $replaseTo[] = $row->item_social_buttons ? $row->item_social_buttons : '';

        $return_str = str_replace($replaseFrom, $replaseTo, $return_str);

        echo $return_str;
        return $return_str;
    }

    public static function _pluginProcess($text)
    {
        $text = JHTML::_('content.prepare', $text);
        return $text;
    }

    public static function getTemplateItemHtml()
    {
        $mode = self::getModeByItemId();
        if (!$mode) {
            $mode = self::getItemId();
        }
        $db = JFactory::getDBO();
        $query = "SELECT item_view  FROM #__jp3_templates WHERE mode='" . $mode . "'";
        $db->setQuery($query);
        return $db->loadResult();

    }

    public static function getItemId()
    {
        $jinput = JFactory::getApplication()->input;
        $itemid = $jinput->get('Itemid', 0, 'INT');
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('params')
            ->from('`#__menu`')
            ->where('id=' . (int)$itemid);
        $db->setQuery($query);
        $params = $db->loadResult();
        $settings = json_decode($params);
        $cur_cat = (int)$settings->id;

        $query = $db->getQuery(true);
        $query->select('mode')
            ->from('`#__jp3_items`')
            ->where('id=' . (int)$cur_cat);
        $db->setQuery($query);

        return $db->loadResult();
    }

    public static function getTemplateCatHtml()
    {
        $mode = self::getVarMode();
        if (!$mode) {
            $mode = JoomPortfolioHelper::getModeByCatId();
        }
        $db = JFactory::getDBO();
        $query = "SELECT cat_view  FROM #__jp3_templates WHERE mode='" . $mode . "'";
        $db->setQuery($query);
        return $db->loadResult();

    }


    public static function renderPdf($pdf, $id)
    {
        $count_pdf = count($pdf);
        $str = '';
        if ($count_pdf) {
            $str .= '<ul>';
            for ($i = 0; $i < $count_pdf; $i++) {
                if ($pdf[$i]->title == "") {
                    $str .= '<li>';
                    $str .= '<a href="' . JUri::root() . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . (int)$id . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $pdf[$i]->full . '">' . $pdf[$i]->id . '</a>';
                    $str .= '</li>';
                } else {
                    $str .= '<li>';
                    $str .= '<a href="' . JUri::root() . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . (int)$id . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $pdf[$i]->full . '">' . $pdf[$i]->title . '</a>';
                    $str .= '</li>';
                }
            }
            $str .= '</ul>';
        }else{
            $str .= '<div class="jp-post-not-comment jp-not-found-wrapp"><ul>'.JText::_("COM_JOOMPORTFOLIO_PDF_NOT_FOUND").'</ul></div>';
        }
        return $str;
    }

    public static function renderAudio($audio, $id)
    {

        $count_audio = !empty($audio) ? count($audio) : 0;
        $str = '';
        if ($count_audio) {
            $str .= '<ul >';
            for ($i = 0; $i < $count_audio; $i++) {

                $str .= '<ol  >';
                $str.='<span rel="popover" data-placement="top" data-trigger="hover" data-content="'.$audio[$i]->title.'" class="jp-audio-info" ></span>';
                $str .= '<audio controls="" name="media">';
                $str .= '<source
                            src="' . JUri::root() . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . (int)$id . DIRECTORY_SEPARATOR . 'audio' . DIRECTORY_SEPARATOR . $audio[$i]->full . '"';
                $str .= 'type="audio/mpeg">';
                $str .= '</audio>';
                $str .= '</ol>';
            }
            $str .= '</ul>';
        }else{
            $str .= '<div class="jp-post-not-comment jp-not-found-wrapp"><ul>'.JText::_("COM_JOOMPORTFOLIO_AUDIO_NOT_FOUND").'</ul></div>';
        }
        return $str;
    }

    public static function renderVideo($video, $id)
    {
        $count_video = !empty($video) ? count($video) : 0;
        $str = '';
        if ($count_video) {
            $str .= '<div class="item-video"><ul>';

            for ($i = 0; $i < $count_video; $i++) {
                $str .= '<ol>';
                $str .= '<video width="320" height="240" controls>';
                $str .= '<source src="' . JUri::root() . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . (int)$id . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $video[$i]->full . '" type="video/mp4">';
                $str .= '<source src="' . JUri::root() . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . (int)$id . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $video[$i]->full . '" type="video/ogg">';
                $str .= '<object data="' . JUri::root() . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . (int)$id . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $video[$i]->full . '" width="320" height="240">';
                $str .= '<embed src="' . JUri::root() . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . (int)$id . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $video[$i]->full . '" width="320" height="240">';
                $str .= '</object></video>';
                $str .= '</ol>';
            }
            $str .= '</ul></div>';
        }else{
            $str .= '<div class="jp-post-not-comment jp-not-found-wrapp"><ul>'.JText::_("COM_JOOMPORTFOLIO_VIDEO_NOT_FOUND").'</ul></div>';
        }
        return $str;
    }

    public static function rendComments($comments, $item_id)
    {
        $count_comments = !empty($comments) ? count($comments) : 0;
        $str = '';
        $str .= '<div id="commentListFormDiv">';
        if (JFactory::getUser()->id) {
            $str .= '<input type="button" class="btn addButons" href="javascript:void(0)" id="addComment" onclick="showCommentForm();" value="' . JText::_("COM_JOOMPORTFOLIO_ADD_COMMENT") . '" /><div class="clr"></div>';
            $str .= '<div id="comments-area"></div>';


        }

        if ($count_comments) {
            for ($i = 0; $i < $count_comments; $i++) {
                $str .= '<div class="jp-post-comment" id="comment' . $comments[$i]->id . '">';
                $str .= '<div class="jp-comment-info">';
                $str .= '<div class="jp-comment-infoblock">';
                $str.='<span class="jp-comuser-name">'.$comments[$i]->user_name.'</span>';
                if($comments[$i]->date && $comments[$i]->date != '0000-00-00') {
                    $str .= '<span class="jp-post-date">'.JHTML::_('date', $comments[$i]->date, 'COM_JOOMPORTFOLIO_DATE_FORMAT', null).'</span>';
                }
                $str .= '</div>';
                $str .= '</div>';
                $str .= '<div class="jp-commenttext" id="desc-comment-'.$comments[$i]->id.'">'.$comments[$i]->comment.'</div>';
                $str .= '</div>';
            }
        }else{
            $str .= '<div class="jp-post-not-comment" id="jp-comments-not-found">';
            $str .= '<ul class="author hcard">';
            $str .= '<li >'.JText::_("COM_JOOMPORTFOLIO_COMMENTS_NOT_FOUND").'</li>';
            $str .= '</ul>';
            $str .= '</div>';
        }

        $str .= '</div>';
        return $str;
    }

    public static function rendOrnaments($id)
    {

        $db = JFactory::getDBO();
        $jinput = JFactory::getApplication()->input;
        $Itemid = $jinput->get('Itemid', 0, 'INT');

        $str = '';
        $query = $db->getQuery(true);

        $query->select('c.full, u.name, o.created');
        $query->from('#__jp3_ornaments AS o');
        $query->innerJoin('#__jp3_condolence AS c ON c.id=o.condole_id');
        $query->innerJoin('#__users AS u ON u.id=o.user_id');
        $query->where('o.item_id=' . (int)$id . ' AND o.published=1');
        $query->order('`o`.`created` DESC');
        $db->setQuery($query);
        $ornaments = $db->loadObjectList();
        $count = !empty($ornaments) ? count($ornaments) : 0;

        if (JFactory::getUser()->id) {
            $str .= '<input type="button" class="condolence-form-toggle btn addButons" href="javascript:void(0)" id="addOrnament" onclick="showOrnamentForm();" value="' . JText::_("COM_JOOMPORTFOLIO_ADD_ORNAMENT") . '" /><div class="clr"></div>';
            $str .= '<div id="ornament-area" style="display:none"> ';
            $str .= '<div class="candle-image">';
            $str .= '<input type="text" value="" disabled="disabled" id="ornament-containter" name="candlecont" />';
            $str .= '<input type="hidden" value="" id="condolence-image-id" name="condolence-image-id" />';
            $str .= '<a id="candle-image" style="background-color:#0088CC;" class="modal jpbutton jpbutton-ext"';
            $str .= 'href="index.php?option=com_joomportfolio&view=ornaments&Itemid=' . $Itemid . '&tmpl=component">';
            $str .= JText::_("COM_JOOMPORTFOLIO_ORNAMENT_IMAGE") . '</a></div>';
            $str .= '<div><input type="button" id="ornament-submit" class="ornament-submit btn" onclick="addOrnament();" value="' . JText::_("COM_JOOMPORTFOLIO_ADD_ORNAMENT") . '"></div>';
            $str .= '</div>';
        }
        for ($i = 0; $i < $count; $i++) {

            $str .= '<div class="ornament-item ">';
            $str .= '<img  rel="popover" data-placement="top" data-trigger="hover" data-content="'.$ornaments[$i]->name.' <br />'.JFactory::getDate($ornaments[$i]->created)->format(JText::_('COM_JOOMPORTFOLIO_DATE_FORMAT')).'" src="' . JUri::root() . 'images/joomportfolio/condolences/' . $ornaments[$i]->full . '"/>';
            $str .= '</div>';

        }
        if(!$count){
            $str .= '<div class="jp-post-not-comment jp-not-found-wrapp"><ul>'.JText::_("COM_JOOMPORTFOLIO_VIDEO_NOT_FOUND").'</ul></div>';
        }
        $str .= '<div class="clr"></div>';

        return $str;

    }


    public static function getRatingStars($id, $rating_sum, $rating_count)
    {
        $rating_sum = $rating_sum ? $rating_sum : 0;
        $rating_count = !empty($rating_count) ? $rating_count : 0;

        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'components/com_joomportfolio/assets/css/vote.css');
        $document->addScript(JURI::root() . 'components/com_joomportfolio/assets/js/vote.js');
        //$document->addScript(JURI::root() . 'components/com_joomportfolio/assets/js/jquery-1.5.1.min.js');

        $document->addScriptDeclaration("var sfolder = '" . JURI::base(true) . "';
					var jportfrate_text=Array('" . JText::_('COM_JOOMPORTFOLIO_RATING_NO_AJAX') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_LOADING') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_THANKS') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_LOGIN') . "','" . JText::_('COM_JOOMPORTFOLIO_RATING_RATED') . "','" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_VOTES') . "','" . JTEXT::_('COM_JOOMPORTFOLIO_RATING_VOTE') . "');");

        $live_path = JURI::base();

        $counter = 1;
        $percent = 0;

        if ($rating_count != 0) {
            $percent = number_format((intval($rating_sum) / intval($rating_count)) * 20, 2);
        }

        $html = "<span class='jportfrate-container' style='margin-top:5px;'  >
				  <ul class='jportfrate-stars'>
					<li id=\"rating_" . $id . "\" class=\"current-rating\" style=\"width:" . (int)$percent . "%;\"></li>
				  </ul>
				</span>
					  <span id='jportfrate_" . $id . "' class='jportfrate-count'><small>";

        $html .= "( ";
        if ($rating_count != 1) {
            $html .= $rating_count . " " . JText::_('COM_JOOMPORTFOLIO_RATING_VOTES');
        } else {
            $html .= $rating_count . " " . JText::_('COM_JOOMPORTFOLIO_RATING_VOTE');
        }
        $html .= " )";
        $html .= "</small></span>";

        return $html;
    }

    public static function getFields($cat_id, $item_id)
    {

        $mode = JoomPortfolioHelper::getVarMode();
        if (!$mode) {
            $mode = JoomPortfolioHelper::getModeByCatId();
        }

        $db = JFactory::getDBO();

        $query = "SELECT f.*"
            . "\n FROM #__jp3_field AS f"
            . "\n WHERE mode='" . $mode . "' AND catview=1";
        $db->setQuery($query);
        $fields = $db->loadAssocList();
        for ($i = 0; $i < count($fields); $i++) {
            $fields[$i]['value'] = '';
        }

        //get all items this cat
        $query = "SELECT  ic.value AS custom, ic.field_id"
            . "\n FROM #__jp3_item_content AS ic"
            . "\n INNER JOIN #__jp3_items AS i ON ic.item_id=i.id"
            . "\n INNER JOIN #__jp3_field AS f ON f.id=ic.field_id"
            . "\n WHERE i.cat_id=" . $cat_id . " AND f.catview=1 AND ic.item_id=" . (int)$item_id;
        $db->setQuery($query);

        $ids = $db->loadAssocList();

        $custom = array();
        for ($i = 0; $i < count($ids); $i++) {
            $custom[$i]['custom'] = $ids[$i]['custom'];
            $custom[$i]['field_id'] = $ids[$i]['field_id'];
        }

        $field_ids = array();
        for ($i = 0; $i < count($fields); $i++) {
            $field_ids[$i] = intval($fields[$i]['id']);
        }

        for ($i = 0; $i < count($custom); $i++) {
            if (!empty($custom[$i]['custom']) && is_array($custom[$i]['custom'])) {
                foreach ($custom[$i]['custom'] as $key => $value) {
                    if (!in_array($key, $field_ids)) {
                        unset($custom[$i]['custom'][$key]);
                    }
                }
            }
        }

        $custom_f = array();
        if (!empty($custom)) {
            for ($i = 0; $i < count($custom); $i++) {
                for ($j = 0; $j < count($fields); $j++) {
                    if (!empty($custom[$i]['custom'])) {
                        $value = $custom[$i]['custom'];
                        if ((int)$fields[$j]['id'] == (int)$custom[$i]['field_id']) {
                            $custom_f[$j]['value'] = $value;
                            $custom_f[$j]['name'] = $fields[$j]['name'];
                            $custom_f[$j]['label'] = $fields[$j]['label'];
                            $custom_f[$j]['def'] = $fields[$j]['def'];
                            $custom_f[$j]['type'] = $fields[$j]['type'];
                            $custom_f[$j]['format'] = $fields[$j]['format'];

                        }

                    }
                }
            }
        }
        return $custom_f;
    }

    public static function getModeByCatId()
    {
        $jinput = JFactory::getApplication()->input;
        $alias = $jinput->get('id', '', 'HTML');
        $alias = str_replace(":", "-", $alias);
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('c.extension');
        $query->from('#__categories AS c');
        $query->where('c.alias="' . $alias . '"');
        $db->setQuery($query);
        $mode = $db->loadResult();
        if ($mode) {
            $mode = substr($mode, 4);
        }
        return $mode;
    }


    public static function getModeByItemId()
    {

        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get('id', '', 'HTML');
        $id = str_replace(":", "-", $id);
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('i.mode');
        $query->from('#__jp3_items AS i');
        $query->where('i.alias="' . $id . '"');
        $db->setQuery($query);
        $mode = $db->loadResult();
        return $mode;
    }

    public static function jbAddPathway($title, $link = '')
    {
        $mainframe = JFactory::getApplication();
        $pathway =& $mainframe->getPathway();

        $pathway->addItem($title, $link);
    }

    public static function catParent($id){
        $app = JFactory::getApplication();
        $jinput = $app->input;
        $Itemid = $jinput->get('Itemid', 0, 'INT');
        $mainframe = JFactory::getApplication();
        $pathway = $mainframe->getPathway();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('c.title, c.alias, c.parent_id, c.id');
        $query->from('#__categories AS c');
        $query->where('c.id=' . (int)$id);
        $db->setQuery($query);
        $cat = $db->loadObject();
        if ($cat->parent_id==1) {
            $pathway->addItem($cat->title, JRoute::_("index.php?option=com_joomportfolio&view=category&id=" .$cat->alias.'&Itemid='.$Itemid ));
            }
        else {
            return self::catParent($cat->parent_id);
        }

    }

    public static function catParentId($id){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(' c.parent_id');
        $query->from('#__categories AS c');
        $query->where('c.id=' . (int)$id);
        $db->setQuery($query);
        $cat = $db->loadResult();
        if((int)$cat!=1 && (int)$cat!=0){
            self::catParent((int)$id);
        }
    }

}