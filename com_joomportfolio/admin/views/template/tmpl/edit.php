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
JHtml::_('behavior.modal');
?>

<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'template.cancel' || document.formvalidator.isValid(document.id('fields-form'))) {
            Joomla.submitform(task, document.getElementById('fields-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }

    function insertCatTag(field) {
        //jQuery('#jform_cat_view').prepend('[' + field.text + ']');
        insertEmoticonAtTextareaCursor('jform_cat_view', '[label-field.text]: [' + field.text + ']');
        return true;
    }
    function insertCatTagWithoutLabel(field) {

        insertEmoticonAtTextareaCursor('jform_cat_view', '[' + field.text + ']');
        return true;
    }
    function insertItemTag(field) {
        insertEmoticonAtTextareaCursor('jform_item_view', '[label-'+field.text+']: [' + field.text + ']');
        return true;
    }
    function insertItemTagWithoutLabel(field) {
        insertEmoticonAtTextareaCursor('jform_item_view', '[' + field.text + ']');
        return true;
    }


    function insertEmoticonAtTextareaCursor(ID,text) {

        var txtarea = jQuery("#"+ID).get(0);
        var scrollPos = txtarea.scrollTop;
        var strPos = 0;
        var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
            "ff" : (document.selection ? "ie" : false ) );
        if (br == "ie") {
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart ('character', -txtarea.value.length);
            strPos = range.text.length;
        }
        else if (br == "ff") strPos = txtarea.selectionStart;

        var front = (txtarea.value).substring(0,strPos);
        var back = (txtarea.value).substring(strPos,txtarea.value.length);
        txtarea.value=front+text+back;
        strPos = strPos + text.length;
        if (br == "ie") {
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart ('character', -txtarea.value.length);
            range.moveStart ('character', strPos);
            range.moveEnd ('character', 0);
            range.select();
        }
        else if (br == "ff") {
            txtarea.selectionStart = strPos;
            txtarea.selectionEnd = strPos;
            txtarea.focus();
        }
        txtarea.scrollTop = scrollPos;
    }



</script>
<style>
    textarea {
        width: 80%;
    }
</style>
<form
    action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=field&layout=edit&id=' . (int)$this->id); ?>"
    method="post" name="adminForm" id="fields-form" class="form-validate">
<div class="group-fields">
    <a class="btn btn-info modal"
       rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}"
       href="<?php echo JRoute::_('index.php?option=com_joomportfolio&task=template.editcss&id=' . $this->id . '&tmpl=component'); ?>"><?php echo JText::_('COM_JOOMPORTFOLIO_TEMPLATE_EDIT_CSS'); ?></a>


    <?php
    foreach ($this->form->getFieldset('details') as $field) {
        echo  '<div class="field fields-data" style="max-width: 100px;">';
        echo ($field->hidden == 1) ? $field->input : $field->label . $field->input;
        echo '</div>';
    }
    ?>
    <fieldset class="template_custom">
        <legend><?php echo JText::_('COM_JOOMPORTFOLIO_TAGS_FOR_TEMPLATE_CAT');?></legend>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertCatTagWithoutLabel(this);"
                   id="<?php echo 'field_photo' ?>">photo</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertCatTag(this);"
                   id="<?php echo 'field_title' ?>">title</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertCatTag(this);"
                   id="<?php echo 'field_description' ?>">description</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertCatTag(this);"
                   id="<?php echo 'field_hits' ?>">hits</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertCatTag(this);"
                   id="<?php echo 'field_rating' ?>">rating</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertCatTagWithoutLabel(this);"
                   id="<?php echo 'field_read_more' ?>">read_more</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertCatTagWithoutLabel(this);"
                   id="<?php echo 'field_social_buttons' ?>">social_buttons</a>
            </div>
        </div>
    </fieldset>
    <?php $custom_fields = $this->cat_custom_fields;
    if (count($custom_fields)) {
        ?>
        </br>
        <fieldset class="template_custom">
            <legend><?php echo JText::_('COM_JOOMPORTFOLIO_TEMPLATE_CUSTOM_FOR_CAT');?></legend>
            <?php

            foreach ($custom_fields as $custom_field) {
                ?>
                <div class="button2-left">
                    <div class="blank">
                        <a class="btn" rel="" onClick="insertCatTag(this);"
                           id="<?php echo 'field_' . $custom_field->id; ?>"><?php echo $custom_field->name;?></a>
                    </div>
                </div>
            <?php
            }

            ?>
        </fieldset>
    <?php } ?>

    <?php
    foreach ($this->form->getFieldset('cat') as $field) {
        echo  '<div class="field fields-data">';
        echo ($field->hidden == 1) ? $field->input : $field->label . $field->input;
        echo '</div>';
    }
    ?>

    <!-- view items-->

    <fieldset class="template_custom">
        <legend><?php echo JText::_('COM_JOOMPORTFOLIO_TAGS_FOR_TEMPLATE_ITEM');?></legend>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTag(this);"
                   id="<?php echo 'field_title' ?>">title</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTagWithoutLabel(this);"
                   id="<?php echo 'field_photo' ?>">photo</a>
            </div>
        </div>

        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTag(this);"
                   id="<?php echo 'field_description' ?>">description</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTag(this);"
                   id="<?php echo 'field_hits' ?>">hits</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTag(this);"
                   id="<?php echo 'field_rating' ?>">rating</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTagWithoutLabel(this);"
                   id="<?php echo 'field_social_buttons' ?>">social_buttons</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTagWithoutLabel(this);"
                   id="<?php echo 'field_tabs_start' ?>">tabs_start</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTagWithoutLabel(this);"
                   id="<?php echo 'field_pdf' ?>">pdf</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTagWithoutLabel(this);"
                   id="<?php echo 'field_audio' ?>">audio</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTagWithoutLabel(this);"
                   id="<?php echo 'field_video' ?>">video</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTagWithoutLabel(this);"
                   id="<?php echo 'field_comments' ?>">comments</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTagWithoutLabel(this);"
                   id="<?php echo 'field_tabs_end' ?>">tabs_end</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTagWithoutLabel(this);"
                   id="<?php echo 'item_category_name' ?>">item_category_name</a>
            </div>
        </div>
        <div class="button2-left">
            <div class="blank">
                <a class="btn" rel="" onClick="insertItemTagWithoutLabel(this);"
                   id="<?php echo 'date_of_created' ?>">date_of_created</a>
            </div>
        </div>
    </fieldset>

    <?php  $custom_fields = $this->item_custom_fields;
    if (count($custom_fields)) {
        ?>
        </br><fieldset class="template_custom">
            <legend><?php echo JText::_('COM_JOOMPORTFOLIO_TEMPLATE_CUSTOM_FOR_ITEM');?></legend>
            <?php

            foreach ($custom_fields as $custom_field) {
                ?>
                <div class="button2-left">
                    <div class="blank">
                        <a class="btn" rel="" onClick="insertItemTag(this);"
                           id="<?php echo 'field_' . $custom_field->id; ?>"><?php echo $custom_field->name;?></a>
                    </div>
                </div>
            <?php
            }

            ?>
        </fieldset>
    <?php } ?>
    <?php
    foreach ($this->form->getFieldset('item') as $field) {
        echo  '<div class="field fields-data">';
        echo ($field->hidden == 1) ? $field->input : $field->label . $field->input;
        echo '</div>';
    }
    ?>
</div>

<div>
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</div>
