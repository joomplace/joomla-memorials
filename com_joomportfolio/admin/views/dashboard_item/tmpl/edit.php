<?php
/**
 * JoomPortfolio component for Joomla 3.0
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.modal');;


?>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'dashboard_item.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
        else {
            if (!$('jform_title').get('value')) {
                alert('<?php echo JText::sprintf('COM_JOOMPORTFOLIO_ERROR_NOT_TITLE','goal'); ?>');
                $('jform_title').focus();
            }
            else if (!$('jform_url').get('value')) {
                alert('<?php echo JText::_('COM_JOOMPORTFOLIO_ERROR_ENTER_DASHBOARD_ITEM_URL'); ?>');
                $('jform_url').focus();
            }
            else if (!$('jform_icon').get('value')) {
                alert('<?php echo JText::_('COM_JOOMPORTFOLIO_ERROR_ENTER_DASHBOARD_ITEM_ICON'); ?>');
                $('jform_icon').focus();
            }
        }
    }
</script>
<link rel="stylesheet"
      href="<?php echo(JUri::root()); ?>administrator/components/com_joomportfolio/assets/css/joomportfolio.css"
      type="text/css"/>
<form
    action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=dashboard_item&layout=edit&id=' . (int)$this->item->id); ?>"
    method="post" name="adminForm" id="item-form" class="form-validate form-horizontal">
    <div class="row-fluid">
        <!-- Begin Content -->
        <legend><?php echo empty($this->item->c_id) ? JText::_('COM_JOOMPORTFOLIO_FIELDSET_DASHBOARD_ITEMS_DETAILS') : JText::sprintf('COM_JOOMPORTFOLIO_FIELDSET_DASHBOARD_ITEMS_DETAILS', $this->item->c_id); ?></legend>
        <div class="span10 form-horizontal">
            <?php foreach ($this->form->getFieldsets() as $fieldset) {
                $fields = $this->form->getFieldset($fieldset->name);
                echo '<div class="tab-content">';
                // Begin Tabs
                echo '<div class="tab-pane active" id="' . $fieldset->name . '">';
                foreach ($this->form->getFieldset($fieldset->name) as $field) {
                    echo ($field->hidden == 1) ? $field->input : '<div class="control-group"><div class="control-label">' . $field->label . '</div><div class="controls">' . $field->input . '</div></div>';
                }

                // End tab details
                echo '</div>';
            }?>
        </div>
    </div>
    <div>
        <input type="hidden" name="task" value="item.edit"/>
        <?php echo JHtml::_('form.token'); ?>
    </div>