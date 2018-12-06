<?php defined('_JEXEC') or die('Restricted Access');
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

JFormHelper::loadFieldClass('list');

//==================================================
// Allows adding additional options from PHP (instead of from XML only in default field).
//==================================================
class JFormField_Advanced_List extends JFormFieldList
{
	protected $type = '_advanced_list';
	protected $additionalOptions;
	//----------------------------------------------------------------------------------------------------
	public function __construct($form = null)
	{
		$this->additionalOptions = array();
		
		parent::__construct($form);
	}
	//----------------------------------------------------------------------------------------------------
	public function getLabel()
	{
		return parent::getLabel();
	}
	//----------------------------------------------------------------------------------------------------
	public function getInput()
	{
		return parent::getInput();
	}
	//----------------------------------------------------------------------------------------------------
	public function setProperty($name, $value)
	{
		$this->element[$name] = $value;
	}
	//----------------------------------------------------------------------------------------------------
	public function addOptions($options)
	{
		$this->additionalOptions = (isset($options) && is_array($options) ? $options : array());
	}
	//----------------------------------------------------------------------------------------------------
	protected function getOptions()
	{
		$options = array_merge(parent::getOptions(), $this->additionalOptions);
		
		return $options;
	}
}