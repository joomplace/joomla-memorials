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

class JoomPortfolioModelImportdata extends JModelLegacy
{
	protected $xmlStore = array();

	public function makeImportData()
	{
        $input =JFactory::getApplication()->input;
		$_PREFIX = $input->get('prefix', 'jos_');
		$_IMG_PATH = $input->get('path', 'images'.DIRECTORY_SEPARATOR.'com_joomportfolio');
		$_ITEMS_DIR = JPATH_SITE.DIRECTORY_SEPARATOR.$_IMG_PATH.DIRECTORY_SEPARATOR.'items';
		$_IMG_DIR = JPATH_SITE.DIRECTORY_SEPARATOR.$_IMG_PATH;
		
		jimport('joomla.installer.helper');
		jimport('joomla.filesystem.file' );
		jimport('joomla.filesystem.folder');
		jimport('joomla.filter.output');
		
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM ".$_PREFIX."ef_portfolio_files");
		$images_array = $db->loadObjectList();
		$images = array();
		
		if ($db->getErrorNum() == 1146)
		{
			$this->setError(JText::_('COM_JOOMPORTFOLIO_IMPORTDATA_ERROR_PREFIX'));
			return false;
		}
		
		if (!count($images_array))
		{
			$this->setError(JText::_('COM_JOOMPORTFOLIO_IMPORTDATA_ERROR_IMAGE'));
		} else {
			if (!JFile::exists($_ITEMS_DIR)) {
				JFolder::create($_ITEMS_DIR);
			}
			foreach($images_array as $image)
			{
				$images[$image->file_srv_name] = $image->file_name;
				if (JFile::exists($_IMG_DIR.DIRECTORY_SEPARATOR.$image->file_srv_name))
				{
					JFile::move($_IMG_DIR.DIRECTORY_SEPARATOR.$image->file_srv_name, $_ITEMS_DIR.DIRECTORY_SEPARATOR.$image->file_name);
					if (JFile::exists($_IMG_DIR.DIRECTORY_SEPARATOR.'tn_'.$image->file_srv_name)){
						JFile::delete($_IMG_DIR.DIRECTORY_SEPARATOR.'tn_'.$image->file_srv_name);
					}
				} else {
					$this->setError(JText::_('COM_JOOMPORTFOLIO_IMPORTDATA_ERROR_NOFILE'));
				}
			}			
		}
		
		// Create categories
		
		$db->setQuery("SELECT * FROM ".$_PREFIX."ef_portfolio_sections");
		$categories = $db->loadObjectList();
		
		if (count($categories)){
			
			$cid_href = array();
			$db->setQuery("SELECT MAX(id) from #__categories WHERE extension = 'com_joomportfolio' AND level = 1 ");
			$max_cat_id = $db->loadResult();
				
			foreach($categories as $category){
				$rules_array = array();
				$rules_array['core.edit.state'] = array();
				$rules_array['core.edit'] = array();
				$rules_array['core.delete'] = array();
		
				if($max_cat_id){
					$max_cat_id++;
					$cat_alias = strtolower(str_replace(" ", "-", $category->title))."-".$max_cat_id;
				}else{
					$cat_alias = strtolower(str_replace(" ", "-", $category->title));
				}
				
				$data = array( 
					'extension'=> "com_joomportfolio",
					'title'=> $category->title,
					'description'=>$category->description,
					'published'=> $category->published,
					'access'=> "1",
					'parent_id'=> "1",
					'level'=> "1",
					'path'=> "joomportfolio",
					'alias'=> $cat_alias,
					'params'=> "{\"category_layout\":\"\",\"image\":\"\"}",
					'metadata'=> "{\"author\":\"\",\"robots\":\"\"}",
					'created_user_id'=> $user->id,
					'created_time'=> ''.$date->toFormat().'',
					'modified_user_id'=> $user->id,
					'modified_time'=> "0000-00-00 00:00:00",
					'language'=> "*",
					'checked_out'=>$category->checked_out
				);
		
				$row = JTable::getInstance("Category");
		
				if ($row->parent_id != $data['parent_id'] || $data['id'] == 0) {
					$row->setLocation($data['parent_id'], 'last-child');
				}
		
				$row->bind($data);
				$row->store(true);
				
				$cid_href[$category->id] = $row->id;
			}		
		}
				
		// End Create categories
				
		// Create subcategories
		
		$db->setQuery("SELECT pc.* FROM ".$_PREFIX."ef_portfolio_categories AS pc");
		$subcategories = $db->loadObjectList();
		
		if (count($subcategories)){
			
			$scid_href = array();
			$db->setQuery("SELECT MAX(id) from #__categories WHERE extension = 'com_joomportfolio' AND level = 2 ");
			$max_cat_id = $db->loadResult();
				
			foreach($subcategories as $subcategory){
				$rules_array = array();
				$rules_array['core.edit.state'] = array();
				$rules_array['core.edit'] = array();
				$rules_array['core.delete'] = array();
		
				if($max_cat_id){
					$max_cat_id++;
					$cat_alias = strtolower(str_replace(" ", "-", $subcategory->title))."-".$max_cat_id;
				}else{
					$cat_alias = strtolower(str_replace(" ", "-", $subcategory->title));
				}
				
				$data = array( 
					'extension'=> "com_joomportfolio",
					'title'=> $subcategory->title,
					'description'=>$subcategory->description,
					'published'=> $subcategory->published,
					'access'=> "1",
					'parent_id'=> $cid_href[$subcategory->section],
					'level'=> "2",
					'path'=> "joomportfolio",
					'alias'=> $cat_alias,
					'params'=> "{\"category_layout\":\"\",\"image\":\"\"}",
					'metadata'=> "{\"author\":\"\",\"robots\":\"\"}",
					'created_user_id'=> $user->id,
					'created_time'=> ''.$date->toFormat().'',
					'modified_user_id'=> $user->id,
					'modified_time'=> "0000-00-00 00:00:00",
					'language'=> "*",
					'checked_out'=>$subcategory->checked_out
				);
		
				$row = JTable::getInstance("Category");
		
				if ($row->parent_id != $data['parent_id'] || $data['id'] == 0) {
					$row->setLocation($data['parent_id'], 'last-child');
				}
		
				$row->bind($data);
				$row->store(true);
				
				$scid_href[$subcategory->id] = $row->id;
			}		
		}
		
		// End Create subcategories
		
		// Create Custom Fields
			
			$db->setQuery("SELECT pc.* FROM ".$_PREFIX."ef_portfolio_custom AS pc");
			$fields = $db->loadObjectList();
						
			if(count($fields)){
				foreach($fields as $field){
										
					$data = array(
						'name'=>$field->name,
						'type'=>$field->type,
						'required'=>0,
						'catview'=>1,
						'label'=>$field->name,
						'default'=>''
					);
					
					$name = JFilterOutput::stringURLSafe($data['name']);
					$xml = JoomPortfolioHelper::loadData();
					$child = $xml->fields->fieldset[0]->addChild('field');
					
					if ($data['type'] == 'text' or $data['type'] == 'sql') {
						$data['size'] = 40;
					}

					foreach ($data as $key=>$attr) {
						$child[$key] = $attr;
					}
					
					if (!JoomPortfolioHelper::saveData($xml)) {
						return false;
					}
				}
			}
			
			$db->setQuery("SELECT pc.*, pif.* FROM ".$_PREFIX."ef_portfolio_custom AS pc LEFT JOIN ".$_PREFIX."ef_portfolio_items_fields AS pif ON pc.id = pif.field_id");
			$fields = $db->loadObjectList();
			$items_custom = array();
			
			if(count($fields)){
				foreach($fields as $field){
					if (isset($field->item_id))
					$items_custom[$field->item_id][$field->name] = $field->value;
				}
			}
			
		// End Create Custom Fields
		
		// Create items
		
		$db->setQuery("SELECT pi.* FROM ".$_PREFIX."ef_portfolio_items AS pi");
		$items = $db->loadObjectList();
		$item_images = array();
		
		if (count($items)){
			
			include_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_joomportfolio'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'item.php');			
			$aid_href = array();
			foreach($items as $item){
				
				if (isset($items_custom[$item->id])) {
					$items_custom[$item->id] = json_encode($items_custom[$item->id]);
				} else {
					$items_custom[$item->id] = array();
				}
				
				$alias = strtolower(str_replace(" ", "-", $item->title));				
				$data = array(
					'title'=>$item->title,
					'alias'=>$alias,
					'published'=>$item->published,
					'ordering'=>$item->ordering,
					'hits'=>$item->click_counter,
					'description_short'=>$item->shot_description,
					'description'=>$item->description,
					'custom'=>$items_custom[$item->id],
					'metakey'=>$item->meta_keywords,
					'metadesc'=>$item->meta_description,
					'metaauth'=>$item->meta_author
				);
							
				$row = new JoomPortfolioTableItem($db);
				$row->bind($data);
				$row->store();
				
				$tid = $row->id;
				
				if (isset($item->picture)) {
					$item_images[$tid]['path'][] = isset($images[$item->picture]) ? $images[$item->picture] : '';
					$item_images[$tid]['title'][] = (isset($item->picttitle)) ? $item->picttitle : '';
					$item_images[$tid]['copyright'][] = (isset($item->copyright)) ? $item->copyright : '';
				}
				if (isset($item->pictureb)) {
					$item_images[$tid]['path'][] = isset($images[$item->pictureb]) ? $images[$item->pictureb] : '';
					$item_images[$tid]['title'][] = (isset($item->picttitleb)) ? $item->picttitleb : '';
					$item_images[$tid]['copyright'][] = (isset($item->copyrightb)) ? $item->copyrightb : '';
				}
				if (isset($item->picturec)) {
					$item_images[$tid]['path'][] = isset($images[$item->picturec]) ? $images[$item->picturec] : '';
					$item_images[$tid]['title'][] = (isset($item->picttitlec)) ? $item->picttitlec : '';
					$item_images[$tid]['copyright'][] = (isset($item->copyrightc)) ? $item->copyrightc : '';
				}
				if (isset($item->pictured)) {
					$item_images[$tid]['path'][] = isset($images[$item->pictured]) ? $images[$item->pictured] : '';
					$item_images[$tid]['title'][] = (isset($item->picttitled)) ? $item->picttitled : '';
					$item_images[$tid]['copyright'][] = (isset($item->copyrightd)) ? $item->copyrightd : '';
				}
				
				$aid_href[$item->id] = $tid;
			}
		}
		// end create items
		
		// set images and categories links
			
			$db->setQuery("SELECT * FROM ".$_PREFIX."ef_portfolio_images");
			$imgs = $db->loadObjectList();
			
			$db->setQuery("SELECT * FROM ".$_PREFIX."ef_portfolio_cat_item");
			$cat_items = $db->loadObjectList();
			
			if(count($imgs))
			{
				foreach($imgs as $img){
					$item_images[$aid_href[$img->item_id]]['path'][] = isset($images[$img->picture]) ? $images[$img->picture] : '';
					$item_images[$aid_href[$img->item_id]]['title'][] = (isset($img->picttitle)) ? $img->picttitle : '';
					$item_images[$aid_href[$img->item_id]]['copyright'][] = (isset($img->copyright)) ? $img->copyright : '';
				}
			}
			
			if (count($item_images))
			{
				$values = array();
				foreach($item_images as $tid=>$element)
				{
					if (!empty($element['path'])){
						foreach($element['path'] as $index=>$path)
						{
							if ($element['path'][$index] != ''){
								$title = (isset($element['title'][$index])) ? $element['title'][$index] : '';
								$copyright = (isset($element['copyright'][$index])) ? $element['copyright'][$index] : '';
								$values[] = "(".$tid.", '".$path."', '".$title."', '".$copyright."', '')";
							}
						}
					}
				}
				
				$val = (!empty($values)) ? implode(',', $values) : '';
				if($val!=''){
					$db->setQuery("INSERT INTO #__jp3_images (`item_id`, `path`, `title`, `copyright`, `description`) VALUES ".$val);
					$db->execute();
				}
			}
			
			$values = array();
			if (count($cat_items))
			{
				$links_array = array();
				foreach($cat_items as $cat_item)
				{
					$links_array[$aid_href[$cat_item->item_id]] = $scid_href[$cat_item->cat_id];
				}
			}
			if (count($links_array))
			{
				foreach($links_array as $item_id=>$cat_id)
				{
					$values[] = "(".$item_id.", ".$cat_id.")";
				}
			}
			
			$val = (!empty($values)) ? implode(',', $values) : '';
			if($val!=''){
				$db->setQuery("INSERT INTO #__jp3_href (`item_id`, `cat_id`) VALUES ".$val);
				$db->execute();
			}
			
		//end set links
	
		return true;
	}
	
}
