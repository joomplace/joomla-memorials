<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;
$app = JFactory::getApplication();


JPluginHelper::importPlugin('mode');
$this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
$plugins = JoomPortfolioHelper::getPlugins();
switch (count($plugins)) {
    case 0:
        JoomPortfolioHelper::setMode('');
        break;
    case 1:
        JoomPortfolioHelper::setMode($plugins[0]["el"]);
        break;
}
JoomPortfolioHelper::loadLanguage();
?>
<style type="text/css">
    div#chart-box {
        position: absolute;
        background-color: #000;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 200%;
        opacity: 0.7;
        z-index: 5;
    }

    #popup-prop {
        z-index: 6;
        position: relative;
        background-color: white;
        width: 300px;
        height: 50px;
        margin-left: -150px;
        margin-top: -25px;
        top: 50%;
        left: 50%;
        text-align: center;
    }

    .dropdown-menu input[name="mode"]:checked+label {
        color: #fff;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        background-color: #5bb75b;
        background-image: -moz-linear-gradient(top, #62c462, #51a351);
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#51a351));
        background-image: -webkit-linear-gradient(top, #62c462, #51a351);
        background-image: -o-linear-gradient(top, #62c462, #51a351);
        background-image: linear-gradient(to bottom, #62c462, #51a351);
        background-repeat: repeat-x;
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff62c462', endColorstr='#ff51a351', GradientType=0);
        border-color: #51a351 #51a351 #387038;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
        min-width: 120px;
        margin-left: 5px;
        margin-bottom: 2px;
    }

    .dropdown-menu input[name="mode"] + label {
        display: inline-block;
        padding: 4px 12px;
        margin-bottom: 0;
        font-size: 13px;
        line-height: 18px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        color: #333;
        text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
        background-color: #f5f5f5;
        background-image: -moz-linear-gradient(top, #fff, #e6e6e6);
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fff), to(#e6e6e6));
        background-image: -webkit-linear-gradient(top, #fff, #e6e6e6);
        background-image: -o-linear-gradient(top, #fff, #e6e6e6);
        background-image: linear-gradient(to bottom, #fff, #e6e6e6);
        background-repeat: repeat-x;
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe5e5e5', GradientType=0);
        border-color: #e6e6e6 #e6e6e6 #bfbfbf;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
        border: 1px solid #bbb;
        border-bottom-color: #a2a2a2;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, .2), 0 1px 2px rgba(0, 0, 0, .05);
        -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, .2), 0 1px 2px rgba(0, 0, 0, .05);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .2), 0 1px 2px rgba(0, 0, 0, .05);
        margin-left: 5px;
        margin-bottom: 2px;
        min-width: 120px;
    }

    .dropdown-menu input[name="mode"] {
        display: none;
    }

</style>
<?php if (count($plugins) != 0) { ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var i = 0;
            $('[aria-labelledby="mode"]').on('click', '[name="mode"]', function (e) {
                var elname = $(e.target).val(),
                    link = window.location.href,
                    count;

                jQuery.ajax({
                    url: 'index.php?option=com_joomportfolio&task=mode',
                    type: "POST",
                    data: {
                        elname: elname,
                        link: link
                    },
                    success: function (json) {
                        $("input[name='mode']").attr('checked', false);
                        $("#" + elname).attr('checked', true);
                        count = $("input[name='mode']:checked").size();
                        location.reload();
                    }
                });
            });

        });
    </script>

<?php
}
if (count($plugins) == 0) {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $("#popup-prop").show();
            $("#chart-box").show();
        });
    </script>
<?php
}
?>

<?php

$mode=JoomPortfolioHelper::getMode();

?>
<div id="chart-box" style="display:none">
</div>
<div id="popup-prop" style="display:none"><a
        href="index.php?option=com_installer"><?php echo JText::_('COM_JOOMPORTFOLIO_ERR_MODE'); ?></a></div>
<div id="navbar-example" class="navbar navbar-static navbar-inverse">
    <div class="navbar-inner">
        <div class="container" style="width: auto;">
            <a class="brand" style="float:left;" href="index.php?option=com_joomportfolio&view=about"><img
                    class="jp-panel-logo"
                    src="<?php echo JURI::root() ?>administrator/components/com_joomportfolio/assets/images/joomplace_logo-48.png"/> <?php echo JText::_('JoomPlace') ?>
            </a>
            <ul class="nav" role="navigation">
                <li class="dropdown">
                    <a id="control-panel" href="index.php?option=com_joomportfolio&view=about" role="button"
                       class="dropdown-toggle">Control Panel</a>

                </li>
            </ul>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse-goals">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="nav-collapse-goals nav-collapse collapse">
                <ul class="nav" role="navigation">
                    <li class="dropdown">
                        <a href="#" id="drop-customization" role="button" class="dropdown-toggle"
                           data-toggle="dropdown"><?php echo JText::_('COM_JOOMPORTFOLIO_MENU_MANAGEMENT') ?><b
                                class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="drop-customization">
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="index.php?option=com_joomportfolio&view=categories"><i
                                        class="icon-list-view"></i> <?php echo JText::_('COM_JOOMPORTFOLIO_SUBMENU_CATEGORIES'); ?>
                                </a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="index.php?option=com_joomportfolio&view=items"><i
                                        class="icon-tablet"></i> <?php echo JText::_('COM_JOOMPORTFOLIO_SUBMENU_ITEMS'); ?>
                                </a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="index.php?option=com_joomportfolio&view=fields"><i
                                        class="icon-pencil"></i> <?php echo JText::_('COM_JOOMPORTFOLIO_SUBMENU_FIELDS'); ?>
                                </a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="index.php?option=com_joomportfolio&view=comments"><i
                                        class="icon-comments-2"></i> <?php echo JText::_('COM_JOOMPORTFOLIO_SUBMENU_COMMENTS'); ?>
                                </a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="index.php?option=com_joomportfolio&view=templates"><i
                                        class="icon-pictures"></i> <?php echo JText::_('COM_JOOMPORTFOLIO_SUBMENU_TEMPLATES'); ?>
                                </a></li>
                            <?php

                            if($mode == 'memorials'){ ?>
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                                           href="index.php?option=com_joomportfolio&view=images"><i
                                            class="icon-image"></i> <?php echo JText::_('COM_JOOMPORTFOLIO_CONDOLE_SUBMENU'); ?>
                                    </a></li>
                           <?php  }
                            ?>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" id="drop-settings" role="button" class="dropdown-toggle"
                           data-toggle="dropdown"><?php echo JText::_('COM_JOOMPORTFOLIO_SUBMENU_SETTINGS') ?><b
                                class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="drop-customization">
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="index.php?option=com_joomportfolio&view=settings&layout=edit"><i
                                        class="icon-wrench"></i> <?php echo JText::_('COM_JOOMPORTFOLIO_MENU_CONFIGURATIONS'); ?>
                                </a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="index.php?option=com_config&view=component&component=com_joomportfolio"><i
                                        class="icon-cogs"></i> <?php echo JText::_('COM_JOOMPORTFOLIO_MENU_SETTINGS'); ?>
                                </a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" id="drop-tools" role="button" class="dropdown-toggle"
                           data-toggle="dropdown"><?php echo JText::_('COM_JOOMPORTFOLIO_TOOLS') ?><b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="drop-customization">
                            <li role="presentation"><a role="menuitem" tabindex="-1"
                                                       href="index.php?option=com_joomportfolio&view=sampledata"><i
                                        class="icon-list-view"></i> <?php echo JText::_('COM_JOOMPORTFOLIO_MENU_SAMPLEDATA'); ?>
                                </a></li>
                        </ul>
                    </li>
                </ul>

                <?php
                if (!empty($plugins)) {
                    $count_plugins = count($plugins);

                    if ($count_plugins > 1) {
                        ?>
                        <ul class="nav ">
                            <li id="fat-menu" class="dropdown">
                                <a href="#" id="mode" role="button" class="dropdown-toggle"
                                   data-toggle="dropdown"><?php echo JText::_('COM_JOOMPORTFOLIO_SUBMENU_MODE') ?><b
                                        class="caret"></b></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="mode">
                                    <?php for ($i = 0; $i < $count_plugins; $i++) { ?>
                                        <li role="presentation">
                                            <input type="radio" name="mode" <?php
                                                if ($plugins[$i]['el'] == JoomPortfolioHelper::getMode()) {
                                                    echo 'checked="checked"';
                                                }
                                                ?> id="<?php echo $plugins[$i]['el']; ?>"
                                                   value="<?php echo $plugins[$i]['el']; ?>"/>
                                            <label
                                                for="<?php echo $plugins[$i]['el']; ?>"><?php echo ucfirst($plugins[$i]['el']); ?></label>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                        <script type="text/javascript">
                            if (!jQuery('[name="mode"]').is(':checked')) {
                                jQuery('[name="mode"]:first').attr('checked', true);
                                var elname = jQuery('[name="mode"]:checked').val(),
                                    link = window.location.href,
                                    count;

                                jQuery.ajax({
                                    url: 'index.php?option=com_joomportfolio&task=mode',
                                    type: "POST",
                                    data: {
                                        elname: elname,
                                        link: link
                                    },
                                    success: function (json) {
                                        jQuery("input[name='mode']").attr('checked', false);
                                        jQuery("#" + elname).attr('checked', true);
                                        count = jQuery("input[name='mode']:checked").size();
                                        location.reload();
                                    }
                                });

                            }
                        </script>
                    <?php
                    }
                    if ($count_plugins == 1) {
                        ?>
                        <ul class="nav ">
                            <li id="fat-menu" class="dropdown">
                                <a href="#" id="mode" role="button" class="dropdown-toggle"
                                   data-toggle="dropdown"><?php //echo ucfirst($plugins[0]['el']); ?></a>
                            </li>
                        </ul>
                    <?php
                    }
                }
                ?>

                <ul class="nav pull-right">
                    <li id="fat-menu" class="dropdown">
                        <a href="#" id="help" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_JOOMPORTFOLIO_SUBMENU_HELP') ?><b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="help">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="https://www.joomplace.com/video-tutorials-and-documentation/online-memorials-directory/index.html" target="_blank"><?php echo JText::_('COM_JOOMPORTFOLIO_HELPMENU_HELP') ?></a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="https://www.joomplace.com/support/helpdesk.html" target="_blank"><?php echo JText::_('COM_JOOMPORTFOLIO_HELPMENU_HELPDESK') ?></a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="https://www.joomplace.com/support/helpdesk/post-purchase-questions/ticket/create.html" target="_blank"><?php echo JText::_('COM_JOOMPORTFOLIO_HELPMENU_REQUEST') ?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
