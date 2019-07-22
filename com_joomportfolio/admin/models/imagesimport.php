<?php

/**
* JoomPortfolio component for Joomla 3.x
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');

class JoomPortfolioModelImagesimport extends JModelLegacy
{
	public function makeImagesImport()
	{
        $input =JFactory::getApplication()->input;
		$_IMG_PATH_DEF = $input->get('path', 'images'.DIRECTORY_SEPARATOR.'com_joomportfolio');
        $_IMG_DIR = JPATH_SITE.DIRECTORY_SEPARATOR.$_IMG_PATH_DEF.DIRECTORY_SEPARATOR.'items';
		$_IMG_PATH = str_replace('/', '\\', $_IMG_PATH_DEF);
		$_IMG_PATH_NEW = JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'joomportfolio';
		
		jimport('joomla.installer.helper');
		jimport('joomla.filesystem.file' );
		jimport('joomla.filesystem.folder');
		jimport('joomla.filter.output');
		
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__jp3_images");
		$images_array = $db->loadObjectList();
		$images = array();

		require_once JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_joomportfolio'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'jimg.php';
		$JimgHelper = new JimgHelper();
		
		if (!count($images_array))
		{
			$this->setError(JText::_('COM_JOOMPORTFOLIO_IMPORTDATA_ERROR_IMAGE'));
		} else {
			foreach($images_array as $image)
			{
				$images[$image->path] = $image->path;
				if (file_exists($_IMG_DIR.DIRECTORY_SEPARATOR.$image->path))
				{
					if (!file_exists($_IMG_PATH_NEW.DIRECTORY_SEPARATOR.$image->item_id)) {
						mkdir($_IMG_PATH_NEW.DIRECTORY_SEPARATOR.$image->item_id);
						mkdir($_IMG_PATH_NEW.DIRECTORY_SEPARATOR.$image->item_id.DIRECTORY_SEPARATOR.'original');
						mkdir($_IMG_PATH_NEW.DIRECTORY_SEPARATOR.$image->item_id.DIRECTORY_SEPARATOR.'thumb');
					}
					JFile::copy($_IMG_DIR.DIRECTORY_SEPARATOR.$image->path, $_IMG_PATH_NEW.DIRECTORY_SEPARATOR.$image->item_id.DIRECTORY_SEPARATOR.'original'.DIRECTORY_SEPARATOR.$image->path);

					if (JFile::exists($_IMG_DIR.DIRECTORY_SEPARATOR.'tn_'.$image->path)){
						JFile::delete($_IMG_DIR.DIRECTORY_SEPARATOR.'tn_'.$image->path);
					}
					$thumb = $JimgHelper->captureImage($JimgHelper->resize($_IMG_PATH_NEW.DIRECTORY_SEPARATOR.$image->item_id.DIRECTORY_SEPARATOR.'original'.DIRECTORY_SEPARATOR.$image->path, 110, 110), $image->path);
					JFile::write($_IMG_PATH_NEW.DIRECTORY_SEPARATOR.$image->item_id.DIRECTORY_SEPARATOR.'thumb'.DIRECTORY_SEPARATOR.'thumb_'.$image->path, $thumb);
					

					$q = "INSERT INTO #__jp3_pictures
                        (`item_id`, `title`, `full`, `thumb`, `is_default`, `copyright`, `description`)
                        VALUES ('".$image->item_id."', '".$image->title."', '".$image->path."', 'thumb_".$image->path."', '0', '".$image->copyright."', '".$image->description."')
                    ";
					$db->setQuery($q);

					if (!$db->execute()) {
						return false;
					}  else {
                       $db->setQuery("RENAME TABLE #__jp3_images TO #__jp3_images_old");
                       $db->execute();
					}
				} else {
					$this->setError(JText::_('COM_JOOMPORTFOLIO_IMPORTDATA_ERROR_NOFILE'));
				}
			}			
		}
	
		return true;
	}
	
}
