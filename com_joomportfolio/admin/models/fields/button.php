<?php

/**
* JoomPortfolio component for Joomla 2.5
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldButton extends JFormField
{
	protected $type = 'Button';
	
	function getLabel() {
		return '<label></label>';
	}
	
	function getInput() {
		$input  = '<button type="button" class="button" id="button-'.(string)$this->element['name'].'" onclick="'.(string)$this->element['onclick'].'" >';
		$input .= JText::_((string)$this->element['label']);
		$input .= '</button>';
		
		return $input;
	}
}
