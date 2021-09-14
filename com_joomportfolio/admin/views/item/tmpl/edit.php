<?php
/**
 * JoomPortfolio component for Joomla 3
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$mode=JoomPortfolioHelper::getMode();

?>

<link rel="stylesheet"
      href="<?php echo(JUri::root()); ?>administrator/components/com_joomportfolio/assets/css/joomportfolio.css"
      type="text/css"/>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'item.cancel' || document.formvalidator.isValid(document.id('item-form')) || App.Valid.FormValid()) {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
    }

    jQuery(document).ready(function ($) {

        $('#item-form').bind('fileuploaddone', function (e, data) {
            for (var key in data.result) {
                if (data.result[key].status == 'ok') {
                    $('#jform_exist_images').val($('#jform_exist_images').val() + '|' + data.result[key].image);
                }
            }
        });
        $('#item-form').bind('fileuploaddestroy', function (e, data) {
            var filename = data.url.substring(data.url.indexOf("image=") + 6);
            $('#remove_image').val($('#remove_image').val() + '|' + filename);
        });


    });

    function cmtRemove(element) {
        var oldNodesCount = jQuery('.custom_metatags > table > tbody').children().length;
        element.parentNode.parentNode.parentNode.removeChild(element.parentNode.parentNode);
        if (oldNodesCount == 1)
            jQuery('.custom_metatags > table > tbody').append(
                '<tr id="ct_notags"><td colspan="3"><?php echo JText::_('COM_JOOMPORTFOLIO_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_NOTAGS'); ?></td>'
            );
    }

    function cmtAdd() {
        document.getElementById('jcustom_name').value = document.getElementById('jcustom_name').value.replace(/\"/g, '&quote;');
        document.getElementById('jcustom_value').value = document.getElementById('jcustom_value').value.replace(/\"/g, '&quote;');

        if (document.getElementById('jcustom_name').value != '' && document.getElementById('jcustom_value').value != '') {
            if (document.getElementById('ct_notags'))
                document.getElementById('ct_notags').parentNode.removeChild(document.getElementById('ct_notags'));

            jQuery('.custom_metatags > table > tbody').append(
                '<tr><td>' + document.getElementById('jcustom_name').value + '</td>'
                    + '<td>' + document.getElementById('jcustom_value').value + '</td>'
                    + '<td><span class="btn-small btn btn-danger" onclick="cmtRemove(this);"> X </span>'
                    + '<input type="hidden" name="cm_names[]" value="' + document.getElementById('jcustom_name').value + '" />'
                    + '<input type="hidden" name="cm_values[]" value="' + document.getElementById('jcustom_value').value + '" />'
                    + '</td>'
                    + '</tr>'
            );

            document.getElementById('jcustom_name').value = '';
            document.getElementById('jcustom_value').value = '';
        }
    }

</script>
<form
    action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=item&layout=edit&id=' . (int)$this->item->id); ?>"
    method="post" name="adminForm" id="item-form" class="form-validate form-horizontal">
    <div class="row-fluid">
        <!-- Begin Content -->
        <div class="span10 form-horizontal item-tabs">
            <?php
            echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general'));
           echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_JOOMPORTFOLIO_FIELDSET_DETAILS', true));
            ?>

            <?php
            foreach ($this->form->getFieldset('details') as $field) {
                ?>
                <div class="field">
                    <?php  echo $field->label;
                    echo $field->input;?>
                </div>
            <?php
            }
            ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'metadata', JText::_('COM_JOOMPORTFOLIO_FIELDSET_METADATA', true)); ?>
            <?php
            foreach ($this->form->getFieldset('metadata') as $field) {
                ?>
                <div class="field">
                    <?php
                    echo $field->label;
                    echo $field->input;
                    ?>
                </div>
            <?php
            }
            ?>

            <fieldset class="form-horizontal">
                <legend><?php echo JText::_('COM_JOOMPORTFOLIO_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_TITLE'); ?></legend>
                <div class="custom_metatags">
                    <table border="0" width="100%" class="table table-striped">
                        <thead>
                        <tr>
                            <th width="200"><?php echo JText::_('COM_JOOMPORTFOLIO_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_NAME'); ?></th>
                            <th><?php echo JText::_('COM_JOOMPORTFOLIO_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_CONTENT'); ?></th>
                            <th width="20"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        if ( empty($this->item->custom_metatags) )
                            echo '<tr id="ct_notags"><td colspan="3">'.JText::_('COM_JOOMPORTFOLIO_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_NOTAGS').'</td>';
                        else
                        {
                            foreach ( $this->item->custom_metatags as $ctag_name => $ctag_value )
                                echo '<tr>'
                                    .'<td>'.$ctag_name.'</td>'
                                    .'<td><input type="text" class="wellinput" style="width:100%" name="cm_values[]" value="'.$ctag_value.'" /></td>'
                                    .'<td><span class="btn-small btn btn-danger" onclick="cmtRemove(this);"> X </span>'
                                    .'<input type="hidden" name="cm_names[]" value="'.$ctag_name.'" />'
                                    .'</td>'
                                    .'</tr>';
                        } ?>
                        </tbody>
                    </table>
                    <div class="well">
                        <table border="0" width="100%">
                            <tr>
                                <td width="170" style="padding-right: 25px;">
                                    <input type="text" style="width:100%" class="inputbox" value="" id="jcustom_name" placeholder="<?php echo JText::_('COM_JOOMPORTFOLIO_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_NAME'); ?>">
                                </td>
                                <td style="padding-right: 25px;">
                                    <input type="text" style="width: 100%" class="inputbox" value="" id="jcustom_value" placeholder="<?php echo JText::_('COM_JOOMPORTFOLIO_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_CONTENT'); ?>">
                                </td>
                                <td width="20">
                                    <span class="btn btn-success" onclick="cmtAdd();"> + </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'custom', JText::_('COM_JOOMPORTFOLIO_FIELDSET_CUSTOM', true)); ?>
            <?php
            if (!empty($this->custom)) {
                $field = $this->custom;

                foreach($field as $i => $f) {
                    ?>
                    <div class="field">

                        <label
                            for="field<?php echo $field[$i]['id']; ?>"><?php echo $field[$i]['label'] ?><?php if (intval($field[$i]['req']) == 1) echo "*"; ?></label>
                        <input name="custom_id[<?php echo $i; ?>]" value="<?php echo $field[$i]['id']; ?>"
                               type="hidden"/>
                        <?php if ($field[$i]['type'] == 'textarea') {
                            ?>
                            <textarea id="field<?php echo $field[$i]['id']; ?>"
                                      name="custom[<?php echo $field[$i]['id']; ?>]"
                                      class="<?php if (intval($field[$i]['req']) == 1) echo "required"; ?>  <?php echo $field[$i]['type'] ?>">
                                <?php if (isset($field[$i]['value']) && $field[$i]['value'] != '') {
                                    echo trim($field[$i]['value']);
                                } else {
                                    echo trim($field[$i]['def']);
                                }
                                ?></textarea>
                        <?php
                        } else {
                            if ($field[$i]['type'] == 'calendar') {
                                if (!isset($field[$i]['value'])) {
                                    $field[$i]['value'] = '';
                                }?>
                                <input id="field<?php echo $field[$i]['id']; ?>"
                                       name="custom[<?php echo $field[$i]['id']; ?>]"
                                       value="<?php
                                       if (isset($field[$i]['value']) && $field[$i]['value'] != '') {
                                           echo $field[$i]['value'];
                                       } else {
                                           echo $field[$i]['def'];
                                       }
                                       ?>"
                                       class="<?php if (intval($field[$i]['req']) == 1) echo "required"; ?>   <?php echo $field[$i]['type'] ?>"
                                       type="date"/>
                            <?php
                            } else {

                                ?>

                                <input id="field<?php echo $field[$i]['id']; ?>"
                                       name="custom[<?php echo $field[$i]['id']; ?>]"
                                       value="<?php
                                       if (isset($field[$i]['value']) && $field[$i]['value'] != '') {
                                           echo $field[$i]['value'];
                                       } else {
                                           echo $field[$i]['def'];
                                       }
                                       ?>"
                                       class="<?php if (intval($field[$i]['req']) == 1) echo "required"; ?>   <?php echo $field[$i]['type'] ?> "
                                       type="<?php echo $field[$i]['type'] ?>"/>
                            <?php
                            }
                        }?>

                    </div>
                <?php
                }

            }

            ?>

            <?php echo JHtml::_('bootstrap.endTab'); ?>


            <!-- start img-->
            <?php  echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_JOOMPORTFOLIO_FIELDSET_IMAGES', true)); ?>
            <div class="field" id="img-form">
                <?php
                foreach ($this->form->getFieldset('images') as $field) {

                    echo $field->input;

                }
                ?>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <!-- end img-->
            <!-- start pdf-->
            <?php  echo JHtml::_('bootstrap.addTab', 'myTab', 'pdf', JText::_('COM_JOOMPORTFOLIO_FIELDSET_PDF', true)); ?>
            <div class="field" id="pdf-form">
                <?php
                foreach ($this->form->getFieldset('pdf') as $field) {
                    echo $field->input;
                }
                ?>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <!-- end pdf-->
            <!-- start audio-->
            <?php  echo JHtml::_('bootstrap.addTab', 'myTab', 'audio', JText::_('COM_JOOMPORTFOLIO_FIELDSET_AUDIO', true)); ?>
            <div class="field" id="audio-form">
                <?php
                foreach ($this->form->getFieldset('audio') as $field) {
                    foreach ($this->form->getFieldset('audio') as $field) {

                        echo $field->input;

                    }
                }
                ?>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <!-- end audio-->
            <?php  echo JHtml::_('bootstrap.addTab', 'myTab', 'video', JText::_('COM_JOOMPORTFOLIO_FIELDSET_VIDEO', true));  ?>
            <div class="field" id="video-form">
                <?php
                foreach ($this->form->getFieldset('video') as $field) {
                    foreach ($this->form->getFieldset('video') as $field) {

                        echo $field->input;

                    }
                }
                ?>
            </div>
            <?php echo JHtml::_('bootstrap.endTab');  ?>
            <?php echo JHtml::_('form.token'); ?>
            <input type="hidden" name="task" value="item.edit"/>

</form>

<?php ?>
