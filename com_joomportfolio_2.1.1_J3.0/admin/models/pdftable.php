<?php

/**
* JoomPortfolio component for Joomla 3.x
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class JFormFieldImagetable extends JFormField
{
	public $type = 'pdftable';

	public function getInput()
	{
        $input =JFactory::getApplication()->input;
        $document = JFactory::getDocument();
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('*')
            ->from('`#__jp3_pdf`')
            ->where('item_id='.$input->get('id', 0))
            ->order('ordering');

        $db->setQuery($query);

        $images = $db->loadObjectList();

        if ($input->get('id')) {

        ob_start();
		include(dirname(__FILE__).DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'imagetable.tmpl.php');
		$inputer = ob_get_contents();
		ob_get_clean();
            return $inputer;
        } else {
            return JText::_('COM_JOOMPORTFOLIO_SAVE_ITEM_BEFORE_ADD_IMAGES');
        }

	}

	public function getLabel()
	{
		return '';
	}


	protected function getAction()
	{
		$action = '';
		//'index.php?option=com_media&task=file.upload&'.$session->getName().'='.$session->getId().'&'.JUtility::getToken().'=1&format=json&folder=com_joomportfolio';
		
		// get base
		$base = $this->element['action'] ? (string) $this->element['action'] : 'index.php?option=com_media&task=file.upload&format=json&tmpl=component';
		
		// get session
		$session = JFactory::getSession();
		$session = '&'.$session->getName().'='.$session->getId();
		
		// get token
		$token = '&'.JUtility::getToken().'=1';
		
		// get folder
		$folder = $this->element['folder'] ? '&folder='.$this->element['folder'] : '';
		
		$action .= $base.$folder.$session.$token;
		
		return $action;
	}
}