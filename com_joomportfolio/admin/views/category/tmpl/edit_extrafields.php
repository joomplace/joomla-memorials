<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;
?>

<?php $fieldSets = $this->form->getFieldsets('attribs'); ?>
<?php foreach ($fieldSets as $name => $fieldSet) : ?>
    <?php $label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_CATEGORIES_' . $name . '_FIELDSET_LABEL'; ?>
    <?php if ($name != 'editorConfig' && $name != 'basic-limited') : ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'attrib-' . $name, trim($label)); ?>
        <fieldset>
            <?php if (isset($fieldSet->description) && trim($fieldSet->description)) : ?>
                <p class="tip"><?php echo $this->escape(JText::_($fieldSet->description)); ?></p>
            <?php endif; ?>
            <?php foreach ($this->form->getFieldset($name) as $field) : ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $field->label; ?>
                    </div>
                    <div class="controls">
                        <?php echo $field->input; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </fieldset>

        <?php echo JHtml::_('bootstrap.endTab'); ?>
    <?php endif; ?>
<?php endforeach; ?>
