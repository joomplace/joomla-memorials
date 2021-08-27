<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('JPATH_BASE') or die;

/**
 * Supports a modal article picker.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       3.1
 */
class JFormFieldModal_Category extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Modal_Category';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   1.6
	 */
	protected function getInput()
	{
		$extension 		= $this->element['extension'] ? (string) $this->element['extension'] : (string) JFactory::getApplication()->input->get('extension', 'com_content');

		$allowEdit		= ((string) $this->element['edit'] == 'true') ? true : false;
		$allowClear		= ((string) $this->element['clear'] != 'false') ? true : false;

		// Load language
		JFactory::getLanguage()->load('com_categories', JPATH_ADMINISTRATOR);

		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');

		// Build the script.
		$script = array();

		// Select button script
		$script[] = '	function jSelectCategory_'.$this->id.'(id, title, object) {';
		$script[] = '		document.getElementById("'.$this->id.'_id").value = id;';
		$script[] = '		document.getElementById("'.$this->id.'_name").value = title;';

		if ($allowEdit) {
			$script[] = '		jQuery("#'.$this->id.'_edit").removeClass("hidden");';
		}

		if ($allowClear) {
			$script[] = '		jQuery("#'.$this->id.'_clear").removeClass("hidden");';
		}

		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Clear button script
		static $scriptClear;

		if ($allowClear && !$scriptClear) {
			$scriptClear = true;

			$script[] = '	function jClearCategory(id) {';
			$script[] = '		document.getElementById(id + "_id").value = "";';
			$script[] = '		document.getElementById(id + "_name").value = "'.htmlspecialchars(JText::_('COM_CATEGORIES_SELECT_A_CATEGORY', true), ENT_COMPAT, 'UTF-8').'";';
			$script[] = '		jQuery("#"+id + "_clear").addClass("hidden");';
			$script[] = '		if (document.getElementById(id + "_edit")) {';
			$script[] = '			jQuery("#"+id + "_edit").addClass("hidden");';
			$script[] = '		}';
			$script[] = '		return false;';
			$script[] = '	}';
		}

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_categories&amp;view=categories&amp;layout=modal&amp;tmpl=component&amp;extension='.$extension.'&amp;function=jSelectCategory_'.$this->id;

		if (isset($this->element['language'])) {
			$link .= '&amp;forcedLanguage='.$this->element['language'];
		}

        $mode = JoomPortfolioHelper::getMode(); /*JFactory::getApplication()->input->cookie->get('name');*/

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('title'))
            ->from($db->qn('#__categories'))
            ->where($db->qn('extension') .'='. $db->q('com_'.$mode))
            ->where($db->qn('id') .'='. $db->q((int)$this->value));
		$db->setQuery($query);

		try {
			$title = $db->loadResult();
		}
		catch (RuntimeException $e) {
            throw new Exception($e->getMessage(), 500, $e);
		}

		if (empty($title)) {
			$title = JText::_('COM_CATEGORIES_SELECT_A_CATEGORY');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The active category id field.
		if ((int)$this->value == 0) {
			$value = '';
		}
		else {
			$value = (int)$this->value;
		}

		// The current category display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
		$html[] = '<a class="modal btn hasTooltip" title="'.JHtml::tooltipText('COM_CATEGORIES_CHANGE_CATEGORY').'"  href="'.$link.'&amp;'.JSession::getFormToken().'=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('JSELECT').'</a>';

		// Edit category button
		if ($allowEdit) {
			$html[] = '<a class="btn hasTooltip'.($value ? '' : ' hidden').'" href="index.php?option=com_categories&layout=modal&tmpl=component&task=category.edit&id=' . $value. '" target="_blank" title="'.JHtml::tooltipText('COM_CATEGORIES_EDIT_CATEGORY').'" ><span class="icon-edit"></span> ' . JText::_('JACTION_EDIT') . '</a>';
		}

		// Clear category button
		if ($allowClear) {
			$html[] = '<button id="'.$this->id.'_clear" class="btn'.($value ? '' : ' hidden').'" onclick="return jClearCategory(\''.$this->id.'\')"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</button>';
		}

		$html[] = '</span>';

		// class='required' for client side validation
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return implode("\n", $html);
	}
}
