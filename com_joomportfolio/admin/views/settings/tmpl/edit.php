<?php
/**
 * JoomPortfolio component for Joomla 3
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<style type="text/css">

    .hasTooltip {
        display: inline-block;
        margin-right: 10px;
        margin-top: 5px;
    }

    .fields {
        min-height: 70px;
    }
</style>
<link rel="stylesheet"
      href="<?php echo(JUri::root()); ?>administrator/components/com_joomportfolio/assets/css/joomportfolio.css"
      type="text/css"/>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'field.cancel' || document.formvalidator.isValid(document.id('fields-form'))) {
            Joomla.submitform(task, document.getElementById('fields-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>
<script type="text/javascript">

    var form = null;

    jQuery(document).ready(function () {
        jQuery('#viewTabs a:first').tab('show');
        //jQuery('#socialTabs a:first').tab('show');

        updateTwitterPreview();
        updateLinkedinPreview();
        updateFacebookPreview();
        updatePinterestPreview();

        jQuery('#jform_social_twitter_size').on("click", "input", function (e) {
            var value = jQuery(e.target).val();
            onRadioTwitterSizeClick(value, e);
        });

        jQuery('#jform_social_twitter_annotation').on("click", "input", function (e) {
            var value = jQuery(e.target).val();
            onRadioTwitterAnnotationClick(value, e);
        });

        jQuery('#jform_social_linkedin_annotation').on("click", "input", function (e) {
            var value = jQuery(e.target).val();
            onRadioLinkedinAnnotationClick(value, e);
        });

        jQuery('#jform_social_facebook_verb').on("click", "input", function (e) {
            var value = jQuery(e.target).val();
            onRadioFacebookVerbClick(value, e);
        });

        jQuery('#jform_social_facebook_layout').on("click", "input", function (e) {
            var value = jQuery(e.target).val();
            onRadioFacebookLayoutClick(value, e);
        });

        jQuery('#jform_social_pinterest_layout').on("click", "input", function (e) {
            var value = jQuery(e.target).val();
            onRadioPinterestLayoutClick(value, e);
        });

    });

    function updateTwitterPreview() {
        var size = BootstrapFormHelper.getRadioGroupValue('jform_social_twitter_size');
        var annotation = BootstrapFormHelper.getRadioGroupValue('jform_social_twitter_annotation');

        var previewImg = document.getElementById('social_twitter_preview');

        previewImg.setAttribute('src', '<?php echo JURI::root().'administrator/components/com_joomportfolio/assets/images/social/'; ?>' + 'twitter-' + size + '-' + annotation + '.png');

        // Showing notice.

        var noticeDiv = document.getElementById('social_twitter_preview_notice');

        if (size == 'large' && annotation == 'vertical') {
            noticeDiv.innerHTML = '<?php echo JText::_('COM_JOOMPORTFOLIO_BE_CONFIG_TWITTER_PREVIEW_NOTICE'); ?>';
        }
        else {
            noticeDiv.innerHTML = '';
        }
    }

    function updateLinkedinPreview() {
        var annotation = BootstrapFormHelper.getRadioGroupValue('jform_social_linkedin_annotation');

        var previewImg = document.getElementById('social_linkedin_preview');

        previewImg.setAttribute('src', '<?php echo JURI::root().'administrator/components/com_joomportfolio/assets/images/social/'; ?>' + 'linkedin-' + annotation + '.png');
    }

    function updateFacebookPreview() {
        var verb = BootstrapFormHelper.getRadioGroupValue('jform_social_facebook_verb');
        var layout = BootstrapFormHelper.getRadioGroupValue('jform_social_facebook_layout');

        var previewImg = document.getElementById('social_facebook_preview');

        previewImg.setAttribute('src', '<?php echo JURI::root().'administrator/components/com_joomportfolio/assets/images/social/'; ?>' + 'facebook-' + verb + '-' + layout + '.png');
    }

    function updatePinterestPreview() {

        var layout = BootstrapFormHelper.getRadioGroupValue('jform_social_pinterest_layout');

        var previewImg = document.getElementById('social_pinterest_preview');

        previewImg.setAttribute('src', '<?php echo JURI::root().'administrator/components/com_joomportfolio/assets/images/social/'; ?>' + 'pinterest-' + layout + '.png');
    }

    function onRadioTwitterSizeClick(sender, event) {
        updateTwitterPreview();
    }

    function onRadioTwitterAnnotationClick(sender, event) {
        updateTwitterPreview();
    }

    function onRadioLinkedinAnnotationClick(sender, event) {
        updateLinkedinPreview();
    }

    function onRadioFacebookVerbClick(sender, event) {
        updateFacebookPreview();
    }

    function onRadioFacebookLayoutClick(sender, event) {
        updateFacebookPreview();
    }

    function onRadioPinterestLayoutClick(sender, event) {
        updatePinterestPreview();
    }

</script>
<?php echo $this->loadTemplate('menu'); ?>

<form action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=settings&layout=edit'); ?>" method="post"
      name="adminForm" id="fields-form" class="form-validate">

<?php
echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general'));
echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_JOOMPORTFOLIO_SETTINGS_GLOBAL', true));

?>
<div class="group-fields">
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_TITLE');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_OPTION');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_DESCRIPTION');?></th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($this->form->getFieldset('main') as $field) {

            echo '<tr><td>' . $field->label . '</td>';
            echo '<td>' . $field->input . '</td>';
            echo '<td>' . JText::_($field->description) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'category', JText::_('COM_JOOMPORTFOLIO_SETTINGS_CATEGORY', true));
?>
<div class="group-fields">
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_TITLE');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_OPTION');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_DESCRIPTION');?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($this->form->getFieldset('category') as $field) {
            echo '<tr><td>' . $field->label . '</td>';
            echo '<td>' . $field->input . '</td>';
            echo '<td>' . JText::_($field->description) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'item', JText::_('COM_JOOMPORTFOLIO_SETTINGS_ITEM', true));
?>
<div class="group-fields">
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_TITLE');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_OPTION');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_DESCRIPTION');?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($this->form->getFieldset('item') as $field) {
            echo '<tr><td>' . $field->label . '</td>';
            echo '<td>' . $field->input . '</td>';
            echo '<td>' . JText::_($field->description) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<?php echo JHtml::_('bootstrap.endTab'); ?>

<?php  echo JHtml::_('bootstrap.addTab', 'myTab', 'disqus', JText::_('COM_JOOMPORTFOLIO_SETTINGS_DISQUS', true));
?>
<div class="group-fields">
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_TITLE');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_OPTION');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_DESCRIPTION');?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($this->form->getFieldset('disqus') as $field) {
            echo '<tr><td>' . $field->label . '</td>';
            echo '<td>' . $field->input . '</td>';
            echo '<td>' . JText::_($field->description) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<?php echo JHtml::_('bootstrap.endTab');  ?>

<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'social', JText::_('COM_JOOMPORTFOLIO_SETTINGS_SOCIAL', true));
?>
<div class="group-fields">


<div id="j-main-container" class="span12 form-horizontal">

<ul class="nav nav-tabs" id="viewTabs">
    <li><a href="#tab_social_twitter"
           data-toggle="tab"><?php echo  JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_TWITTER_SUBPANEL");?></a></li>
    <li><a href="#tab_social_linkedin"
           data-toggle="tab"><?php echo  JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_LINKEDIN_SUBPANEL");?></a></li>
    <li><a href="#tab_social_facebook"
           data-toggle="tab"><?php echo  JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_FACEBOOK_SUBPANEL");?></a></li>
    <li><a href="#tab_social_pinterest"
           data-toggle="tab"><?php echo  JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_PINTEREST_SUBPANEL");?></a></li>
</ul>

<div class="tab-content">

<?php
//==================================================
// Twitter.
//==================================================
?>

<div class="tab-pane" id="tab_social_twitter">
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_TITLE');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_OPTION');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_DESCRIPTION');?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?php echo $this->form->getLabel('social_twitter_use'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('social_twitter_use'); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_TWITTER_USE_DESC"); ?></td>
        </tr>
        <tr>
            <td>
                <?php echo $this->form->getLabel('social_twitter_size'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('social_twitter_size'); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_TWITTER_SIZE_DESC"); ?></td>
        </tr>
        <tr>
            <td>
                <?php echo $this->form->getLabel('social_twitter_annotation'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('social_twitter_annotation'); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_TWITTER_ANNOTATION_DESC");?></td>
        </tr>
        <tr>
            <td>
                <?php
                $input = $this->form->getField('social_twitter_language');
                $input->addOptions($this->twitterLanguageOptions);
                ?>

                <?php echo $input->getLabel(); ?>
            </td>
            <td>
                <?php echo $input->getInput(); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_TWITTER_LANGUAGE_DESC"); ?></td>
        </tr>
        <tr>
            <td>
                <?php
                echo JHTML::_("tooltip", JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_TWITTER_PREVIEW_DESC") . '<br/><br/>' .
                    '<span>' . "* " . JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_TWITTER_PREVIEW_NOLANG") . '</span>',
                    JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_TWITTER_PREVIEW"), null,
                    '<label>' . JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_TWITTER_PREVIEW") . '</label>', null);
                ?>
            </td>
            <td>
                <img id="social_twitter_preview" class="html5fb_twitter_preview"/>

                <div id="social_twitter_preview_notice" class="html5fb_twitter_preview_notice"></div>
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>

<?php
//==================================================
// LinkedIn.
//==================================================
?>

<div class="tab-pane" id="tab_social_linkedin">
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_TITLE');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_OPTION');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_DESCRIPTION');?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?php echo $this->form->getLabel('social_linkedin_use'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('social_linkedin_use'); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_LINKEDIN_USE_DESC");?></td>
        </tr>
        <tr>
            <td>
                <?php echo $this->form->getLabel('social_linkedin_annotation'); ?>
            </td>
            <td>
            <?php echo $this->form->getInput('social_linkedin_annotation'); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_LINKEDIN_ANNOTATION_DESC");?></td>
        </tr>
        <tr>
            <td>
                <?php
                echo JHTML::_("tooltip", JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_LINKEDIN_PREVIEW_DESC"),
                    JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_LINKEDIN_PREVIEW"), null,
                    '<label>' . JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_LINKEDIN_PREVIEW") . '</label>', null);
                ?>
            </td>
            <td>
            <img id="social_linkedin_preview" class="html5fb_linkedin_preview"/>
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>

<?php
//==================================================
// Facebook.
//==================================================
?>

<div class="tab-pane" id="tab_social_facebook">
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_TITLE');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_OPTION');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_DESCRIPTION');?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?php echo $this->form->getLabel('social_facebook_use'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('social_facebook_use'); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_FACEBOOK_USE_DESC");?></td>
        <tr>
            <td>

                <?php echo $this->form->getLabel('social_facebook_verb'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('social_facebook_verb'); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_FACEBOOK_VERB_DESC");?></td>
        <tr>
            <td>
                <?php echo $this->form->getLabel('social_facebook_layout'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('social_facebook_layout'); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_FACEBOOK_LAYOUT_DESC");?></td>
        <tr>
            <td>
                <?php
                $input = $this->form->getField('social_facebook_font');
                $input->addOptions($this->facebookFontOptions);
                ?>

                <?php echo $input->getLabel(); ?>
            </td>
            <td>
                <?php echo $input->getInput(); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_FACEBOOK_FONT_DESC");?></td>
        <tr>
            <td>
                <?php
                echo JHTML::_("tooltip", JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_FACEBOOK_PREVIEW_DESC") . '<br/><br/>' .
                    '<span>' . "* " . JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_FACEBOOK_PREVIEW_NOFONT") . '</span>',
                    JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_FACEBOOK_PREVIEW"), null,
                    '<label>' . JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_FACEBOOK_PREVIEW") . '</label>', null);
                ?>
            </td>
            <td>
                <img id="social_facebook_preview" class="html5fb_facebook_preview"/>
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>

<?php
//==================================================
// Pinterest.
//==================================================
?>

<div class="tab-pane" id="tab_social_pinterest">
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_TITLE');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_OPTION');?></th>
            <th><?php echo JText::_('COM_JOOMPORTFOLIO_CONFIGURATIONS_DESCRIPTION');?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?php echo $this->form->getLabel('social_pinterest_use'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('social_pinterest_use'); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_PRINTEREST_USE_DESC");?></td>
        </tr>

        <tr>
            <td>
                <?php echo $this->form->getLabel('social_pinterest_layout'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('social_pinterest_layout'); ?>
            </td>
            <td><?php echo JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_PRINTEREST_LAYOUT_DESC");?></td>
        </tr>

        <tr>
            <td>
                <?php
                echo JHTML::_("tooltip", JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_PRINTEREST_PREVIEW_DESC") . '<br/><br/>' .
                    '<span>' . "* " . JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_PRINTEREST_PREVIEW_NOFONT") . '</span>',
                    JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_PRINTEREST_PREVIEW"), null,
                    '<label>' . JText::_("COM_JOOMPORTFOLIO_BE_CONFIG_PRINTEREST_PREVIEW") . '</label>', null);
                ?>
            </td>
            <td>
                <img id="social_pinterest_preview" class="html5fb_pinterest_preview"/>
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>

</div>


</div>
<?php echo JHtml::_('bootstrap.endTab'); ?>

<div>
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</div>
</form>
