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

<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'comment.cancel' || document.formvalidator.isValid(document.id('fields-form'))) {
            Joomla.submitform(task, document.getElementById('fields-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }

    var $ = jQuery.noConflict();
    $(document).ready(function () {

        $('#jform_field_type').change(function () {
            if ($("#jform_field_type option:selected").val() == 'calendar') {
                $('#jform_default').attr('type', 'date');
            } else {
                $('#jform_default').attr('type', 'text');

            }
        });
    });
</script>
<form
    action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=field&layout=edit&id=' . (int)$this->item->id); ?>"
    method="post" name="adminForm" id="fields-form" class="form-validate">
    <div class="group-fields">
        <?php foreach ($this->form->getFieldsets() as $fieldset) {
            $fields = $this->form->getFieldset($fieldset->name);
            foreach ($this->form->getFieldset($fieldset->name) as $field) {
                echo  '<div class="field fields-data">';
                echo ($field->hidden == 1) ? $field->input : $field->label . $field->input;
                echo '</div>';
            }


        }?>
    </div>

    <div>
        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>
    </div>
