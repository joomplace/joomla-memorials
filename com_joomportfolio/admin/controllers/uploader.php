<?php

/**
* JoomPortfolio component for Joomla 3.x
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');



/**
 * Uploader Controller
 */
class JoomPortfolioControllerUploader extends JControllerForm
{
    public function imageThumb()
    {
        $input = JFactory::getApplication()->input;
        $JimgHelper = new JimgHelper();
        $image = $input->get('image');
        $image = basename($image);
        $width = $input->get('width',80);
        $height = $input->get('height',80);
        $item_id = $input->get('id');
        if(file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $item_id . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . $image)){
            echo $JimgHelper->show($JimgHelper->resize(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $item_id . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . $image, $width, $height));
        } elseif (file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $item_id . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . 'thumb_' . $image)) {
        	echo $JimgHelper->show($JimgHelper->resize(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $item_id . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . 'thumb_' . $image, $width, $height));
        } else {
            echo JText::_('COM_JOOMPORTFOLIO_NO_FILE');
        }
        die();
    }


    function addImage()
    {
        $input = JFactory::getApplication()->input;
        $rEFileTypes =  "/^\.(jpg|jpeg|gif|png|bmp|xcf|odg){1}$/i";
        $return = array();
        $files = $input->files->get('files', array(), 'array');

        if(!empty($files['tmp_name'])) {
            $id = (int)$input->get('item_id');
            $user = JFactory::getUser();
            if(!$user->authorise('core.create', 'com_joomportfolio') && !$user->authorise('core.edit', 'com_joomportfolio')){
                $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                echo(json_encode($return));
                die();
            }
            if(!$user->authorise('core.edit', 'com_joomportfolio') && $id != 0){
                $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                echo(json_encode($return));
                die();
            }
            jimport( 'joomla.filesystem.file' );
            $totalFiles = count($files['tmp_name']);
            
            $params = JComponentHelper::getParams('com_joomportfolio');
            $image_path = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
            $preview_width = $params->get('item_preview_width', 500);
            $preview_height = $params->get('item_preview_height', 500);
            
            if (!file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id)) {
            	mkdir(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id);
            }
            if(!file_exists($image_path.'original')) {
            	mkdir($image_path.'original');
            }
            if(!file_exists($image_path.'thumb')) {
            	mkdir($image_path.'thumb');
            }
            if (!file_exists($image_path.$preview_width.'x'.$preview_height)) {
            	mkdir($image_path.$preview_width.'x'.$preview_height);
            }

            for($i=0;$i<$totalFiles;$i++){
                $old_name = JFile::getName($files['name'][$i]);
                $ext = JFile::getExt($files['name'][$i]);
                $new_name = md5(time() . $files['name'][$i]) . '.' . $ext;
                if (preg_match($rEFileTypes, strrchr($new_name, '.'))) {

                    if(JFile::upload($files['tmp_name'][$i], $image_path .'original' . DIRECTORY_SEPARATOR . $new_name)){
                        if(JFile::exists($image_path . 'original' . DIRECTORY_SEPARATOR . $new_name)){
                            $JimgHelper = new JimgHelper();
                            $thumb = $JimgHelper->captureImage($JimgHelper->resize($image_path .'original' . DIRECTORY_SEPARATOR . $new_name, $params->get('th_width', '500'), $params->get('th_height', '500')), $new_name);
                            JFile::write($image_path.$preview_width.'x'.$preview_height.DIRECTORY_SEPARATOR.'th_'.$new_name, $thumb);
                            $thumb = $JimgHelper->captureImage($JimgHelper->resize($image_path . 'original' . DIRECTORY_SEPARATOR . $new_name, $params->get('thumb_width', '110'), $params->get('thumb_height', '110')), $new_name);
                            JFile::write($image_path.'thumb'.DIRECTORY_SEPARATOR.'thumb_'.$new_name, $thumb);
                            $return['files'][0]['file_type']='IMG';
                            $return['files'][$i]['image'] = $new_name;
                            $return['files'][$i]['status'] = 'ok';
                            $return['files'][$i]['type'] = 'image/'.$ext;
                            $return['files'][$i]['name'] = $new_name;
                            $return['files'][$i]['size'] = filesize(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR . $new_name);
                            $return['files'][$i]['url'] = JUri::root().'images/joomportfolio/'.$id.'/original/' . $new_name;
                             $return['files'][$i]['thumbnail_url'] = JUri::root().'images/joomportfolio/'.$id.'/thumb/thumb_' . $new_name;
                            $return['files'][$i]['delete_url'] = 'index.php?option=com_joomportfolio&task=uploader.deleteImage&id='.$id.'&image='.urlencode($new_name);
                           // $return['files'][$i]['thumbnail_url'] = 'index.php?option=com_joomportfolio&task=uploader.imageThumb&id='.$id.'&image='.urlencode($new_name);
                           // $return['files'][$i]['delete_url'] = 'index.php?option=com_joomportfolio&task=uploader.deleteImage&id='.$id.'&image='.urlencode($new_name);
                            $return['files'][$i]['delete_type'] = 'POST';
                            $return['files'][$i]['item_id'] = $id;

                            $db = JFactory::getDbo();
                            $query = $db->getQuery(true);

                            $query->insert('`#__jp3_pictures`')
                            ->set('`title`=""')
                            ->set('`item_id`="'.$id.'"')
                            ->set('`full`="'.$new_name.'"')
                            //->set('`preview`="th_"'.$new_name.'"')
                            ->set('`thumb`="thumb_'.$new_name.'"')
                            ->set('`is_default`="0"')
                            ->set('`copyright`=""')
                            ->set('`description`=""');

                            $db->setQuery($query);

                            try {
                                $db->execute();
                            } catch (RuntimeException $e) {
                                throw new Exception($e->getMessage(), 500, $e);
                            }


                        }else{
                            $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                        }
                    }else{
                        $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_CHECK_PERMITIONS');
                    }
                }else{
                    $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_WRONG_FILE_TYPE');
                }
            }
        } else {
            $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_NO_FILE');
        }
        echo(json_encode($return));
        die();
    }

    public function deleteImage ()
    {
        $input =JFactory::getApplication()->input;
    	$params = JComponentHelper::getParams('com_joomportfolio');
    	$preview_width = $params->get('item_preview_width', 300);
    	$preview_height = $params->get('item_preview_height', 300);

        $id = $input->get('id');
        $newName = $input->get('image');
        $db = JFactory::getDbo();
        
        $db->setQuery('SELECT `item_id`,`full` FROM `#__jp3_pictures` WHERE `full`="'.$newName.'"');
        $result = $db->loadObjectList();
        if (!empty($result)) {
        	foreach ($result as $file) {
        		unlink(JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'joomportfolio'.DIRECTORY_SEPARATOR.$file->item_id.DIRECTORY_SEPARATOR.'original'.DIRECTORY_SEPARATOR.$file->full);
        		unlink(JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'joomportfolio'.DIRECTORY_SEPARATOR.$file->item_id.DIRECTORY_SEPARATOR.$preview_width.'x'.$preview_height.DIRECTORY_SEPARATOR.'th_'.$file->full);
        		unlink(JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'joomportfolio'.DIRECTORY_SEPARATOR.$file->item_id.DIRECTORY_SEPARATOR.'thumb'.DIRECTORY_SEPARATOR.'thumb_'.$file->full);
        	}
        }

        $query = $db->getQuery(true);
        $query->delete()
        ->from('#__jp3_pictures')
        ->where('`full`="'.$newName.'"');

        $db->setQuery($query);

        if (!$db->execute()) {
            return false;
        } else {
            return true;
        }
    }

    public function defaultImage ()
    {
        $input =JFactory::getApplication()->input;
        $item_id = $input->get('id');
        $name = $input->get('image');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update('#__jp3_pictures')
        ->set('`is_default`="0"')
        ->where('`item_id`="'.$item_id.'"');
        $db->setQuery($query);
        if ($db->execute()) {

            $query = $db->getQuery(true);
            $query->update('#__jp3_pictures')
            ->set('`is_default`="1"')
            ->where('`full`="'.$name.'"');

            $db->setQuery($query);

            if (!$db->execute()) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    function addPdf()
    {
        $input = JFactory::getApplication()->input;
        $rEFileTypes = "/^\.(pdf){1}$/i";
        $return = array();
        $files = $input->files->get('files', array(), 'array');

        if(!empty($files['tmp_name'])) {
            $id = (int)$input->get('item_id');

            $user = JFactory::getUser();
            if(!$user->authorise('core.create', 'com_joomportfolio') && !$user->authorise('core.edit', 'com_joomportfolio')){
                $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                echo(json_encode($return));
                die();
            }
            if(!$user->authorise('core.edit', 'com_joomportfolio') && $id != 0){
                $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                echo(json_encode($return));
                die();
            }
            jimport( 'joomla.filesystem.file' );
            $totalFiles = count($files['tmp_name']);

            $params = JComponentHelper::getParams('com_joomportfolio');
            $image_path = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;

            if (!file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id)) {
                mkdir(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id);
            }
            if(!file_exists($image_path.'media')) {
                mkdir($image_path.'media');
            }


            for($i=0;$i<$totalFiles;$i++){
                $old_name = JFile::getName($files['name'][$i]);
                $ext = JFile::getExt($files['name'][$i]);
                $new_name = md5(time() . $files['name'][$i]) . '.' . $ext;

                if (preg_match($rEFileTypes, strrchr($new_name, '.'))) {

                    if(JFile::upload($files['tmp_name'][$i], $image_path .'media' . DIRECTORY_SEPARATOR . $new_name)){
                        if(JFile::exists($image_path . 'media' . DIRECTORY_SEPARATOR . $new_name)){

                            $return['files'][0]['file_type']='PDF';
                            $return['files'][$i]['image'] = $new_name;
                            $return['files'][$i]['status'] = 'ok';
                            $return['files'][$i]['type'] = 'image/'.$ext;
                            $return['files'][$i]['name'] = $new_name;
                            $return['files'][$i]['size'] = filesize(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $new_name);
                            $return['files'][$i]['url'] = JUri::root().'images/joomportfolio/'.$id.'/original/' . $new_name;
                            //$return['files'][$i]['thumbnail_url'] = JUri::root().'images/joomportfolio/'.$id.'/thumb/thumb_' . $new_name;
                            $return['files'][$i]['delete_url'] = 'index.php?option=com_joomportfolio&task=uploader.deletePdf&id='.$id.'&image='.urlencode($new_name);
                            // $return['files'][$i]['thumbnail_url'] = 'index.php?option=com_joomportfolio&task=uploader.imageThumb&id='.$id.'&image='.urlencode($new_name);
                            // $return['files'][$i]['delete_url'] = 'index.php?option=com_joomportfolio&task=uploader.deleteImage&id='.$id.'&image='.urlencode($new_name);
                            $return['files'][$i]['delete_type'] = 'POST';
                            $return['files'][$i]['item_id'] = $id;
                            $return['files'][$i]['title'] = $old_name;

                            $db = JFactory::getDbo();
                            $query = $db->getQuery(true);

                            $query->insert('`#__jp3_pdf`')
                                ->set('`title`="'.$db->escape($old_name).'"')
                                ->set('`item_id`='.(int)$id)
                                ->set('`full`="'.$new_name.'"')
                                ->set('`ordering`=0');

                            $db->setQuery($query);

                            try {
                                $db->execute();
                            } catch (RuntimeException $e) {
                                throw new Exception($e->getMessage(), 500, $e);
                            }


                        }else{
                            $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                        }
                    }else{
                        $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_CHECK_PERMITIONS');
                    }
                }else{
                    $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_WRONG_FILE_TYPE');
                }
            }
        } else {
            $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_NO_FILE');
        }

        echo(json_encode($return));
        die();
    }

    public function deletePdf ()
    {
        $input =JFactory::getApplication()->input;
        $params = JComponentHelper::getParams('com_joomportfolio');
        $id = $input->get('id');
        $newName = $this->input->get('image');
        $db = JFactory::getDbo();
        $db->setQuery('SELECT `item_id`,`full` FROM `#__jp3_pdf` WHERE `full`="'.$newName.'"');
        $result = $db->loadObjectList();
        if (!empty($result)) {
            foreach ($result as $file) {
                unlink(JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'joomportfolio'.DIRECTORY_SEPARATOR.$file->item_id.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$file->full);
            }
        }

        $query = $db->getQuery(true);
        $query->delete()
            ->from('#__jp3_pdf')
            ->where('`full`="'.$newName.'"');

        $db->setQuery($query);

        $db->execute();
        JFactory::getApplication()->close();

    }


    public function deleteAudio ()
    {
        $input =JFactory::getApplication()->input;
        $params = JComponentHelper::getParams('com_joomportfolio');
        $id = $input->get('id');
        $newName = $this->input->get('image');
        $db = JFactory::getDbo();
        $db->setQuery('SELECT `item_id`,`full` FROM `#__jp3_audio` WHERE `full`="'.$newName.'"');
        $result = $db->loadObjectList();
        if (!empty($result)) {
            foreach ($result as $file) {
                unlink(JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'joomportfolio'.DIRECTORY_SEPARATOR.$file->item_id.DIRECTORY_SEPARATOR.'audio'.DIRECTORY_SEPARATOR.$file->full);
            }
        }

        $query = $db->getQuery(true);
        $query->delete()
            ->from('#__jp3_audio')
            ->where('`full`="'.$newName.'"');

        $db->setQuery($query);

        $db->execute();
        JFactory::getApplication()->close();

    }

    function addAudio()
    {
        $input = JFactory::getApplication()->input;
        $rEFileTypes =  "/^\.(wav|mp3){1}$/i";
        $return = array();
        $files = $input->files->get('files', array(), 'array');

        if(!empty($files['tmp_name'])) {
            $id = (int)$input->get('item_id');
            $user = JFactory::getUser();
            if(!$user->authorise('core.create', 'com_joomportfolio') && !$user->authorise('core.edit', 'com_joomportfolio')){
                $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                echo(json_encode($return));
                die();
            }
            if(!$user->authorise('core.edit', 'com_joomportfolio') && $id != 0){
                $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                echo(json_encode($return));
                die();
            }
            jimport( 'joomla.filesystem.file' );
            $totalFiles = count($files['tmp_name']);

            $params = JComponentHelper::getParams('com_joomportfolio');
            $image_path = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
            //$preview_width = $params->get('item_preview_width', 500);
            //$preview_height = $params->get('item_preview_height', 500);

            if (!file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id)) {
                mkdir(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id);
            }
            if(!file_exists($image_path.'audio')) {
                mkdir($image_path.'audio');
            }

            /* if (!file_exists($image_path.$preview_width.'x'.$preview_height)) {
                 mkdir($image_path.$preview_width.'x'.$preview_height);
             }*/

            for($i=0;$i<$totalFiles;$i++){
                $old_name = JFile::getName($files['name'][$i]);
                $ext = JFile::getExt($files['name'][$i]);
                $new_name = md5(time() . $files['name'][$i]) . '.' . $ext;
                //die(var_dump(preg_match($rEFileTypes, strrchr($new_name, '.'))));
                if (preg_match($rEFileTypes, strrchr($new_name, '.'))) {
                    if(JFile::upload($files['tmp_name'][$i], $image_path .'audio' . DIRECTORY_SEPARATOR . $new_name)){
                        if(JFile::exists($image_path . 'audio' . DIRECTORY_SEPARATOR . $new_name)){
                            $return['files'][0]['file_type']='AUDIO';
                            $return['files'][$i]['image'] = $new_name;
                            $return['files'][$i]['status'] = 'ok';
                            $return['files'][$i]['type'] = 'image/'.$ext;
                            $return['files'][$i]['name'] = $new_name;
                            $return['files'][$i]['size'] = filesize(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'audio' . DIRECTORY_SEPARATOR . $new_name);
                            $return['files'][$i]['url'] = JUri::root().'images/'. DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'audio' . DIRECTORY_SEPARATOR . $new_name;
                            //$return['files'][$i]['thumbnail_url'] = JUri::root().'images/joomportfolio/'.$id.'/audio/' . $new_name;
                            $return['files'][$i]['delete_url'] = 'index.php?option=com_joomportfolio&task=uploader.deleteAudio&id='.$id.'&image='.urlencode($new_name);
                            // $return['files'][$i]['thumbnail_url'] = 'index.php?option=com_joomportfolio&task=uploader.imageThumb&id='.$id.'&image='.urlencode($new_name);
                            // $return['files'][$i]['delete_url'] = 'index.php?option=com_joomportfolio&task=uploader.deleteImage&id='.$id.'&image='.urlencode($new_name);
                            $return['files'][$i]['delete_type'] = 'POST';
                            $return['files'][$i]['item_id'] = $id;
                            $return['files'][$i]['title'] = $old_name;

                            $db = JFactory::getDbo();
                            $query = $db->getQuery(true);

                            $query->insert('`#__jp3_audio`')
                                ->set('`title`="'.$db->escape($old_name).'"')
                                ->set('`item_id`="'.$id.'"')
                                ->set('`full`="'.$new_name.'"')
                                ->set('`ordering`=0');

                            $db->setQuery($query);

                            try {
                                $db->execute();
                            } catch (RuntimeException $e) {
                                throw new Exception($e->getMessage(), 500, $e);
                            }


                        }else{
                            $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                        }
                    }else{
                        $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_CHECK_PERMITIONS');
                    }
                }else{
                    $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_WRONG_FILE_TYPE');
                }
            }
        } else {
            $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_NO_FILE');
        }
        echo(json_encode($return));
        die();
    }


    function addVideo()
    {
        $input = JFactory::getApplication()->input;
        $rEFileTypes =  "/^\.(webm|mp4|ogg){1}$/i";
        $return = array();
        $files = $input->files->get('files', array(), 'array');

            if(!empty($files['tmp_name'])) {
                $id = (int)$input->get('item_id');
                $user = JFactory::getUser();
                if(!$user->authorise('core.create', 'com_joomportfolio') && !$user->authorise('core.edit', 'com_joomportfolio')){
                    $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                    echo(json_encode($return));
                    die();
                }
                if(!$user->authorise('core.edit', 'com_joomportfolio') && $id != 0){
                    $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                    echo(json_encode($return));
                    die();
                }
                jimport( 'joomla.filesystem.file' );
                $totalFiles = count($files['tmp_name']);

                $params = JComponentHelper::getParams('com_joomportfolio');
                $image_path = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
                //$preview_width = $params->get('item_preview_width', 500);
                //$preview_height = $params->get('item_preview_height', 500);

                if (!file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id)) {
                    mkdir(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id);
                }
                if(!file_exists($image_path.'video')) {
                    mkdir($image_path.'video');
                }

                /* if (!file_exists($image_path.$preview_width.'x'.$preview_height)) {
                     mkdir($image_path.$preview_width.'x'.$preview_height);
                 }*/

                for($i=0;$i<$totalFiles;$i++){
                    $old_name = JFile::getName($files['name'][$i]);
                    $ext = JFile::getExt($files['name'][$i]);
                    $new_name = md5(time() . $files['name'][$i]) . '.' . $ext;
                    //die(var_dump(preg_match($rEFileTypes, strrchr($new_name, '.'))));
                    if (preg_match($rEFileTypes, strrchr($new_name, '.'))) {
                        if(JFile::upload($files['tmp_name'][$i], $image_path .'video' . DIRECTORY_SEPARATOR . $new_name)){
                            if(JFile::exists($image_path . 'video' . DIRECTORY_SEPARATOR . $new_name)){
                                $return['files'][0]['file_type']='VIDEO';
                                $return['files'][$i]['image'] = $new_name;
                                $return['files'][$i]['status'] = 'ok';
                                $return['files'][$i]['type'] = 'image/'.$ext;
                                $return['files'][$i]['name'] = $new_name;
                                $return['files'][$i]['size'] = filesize(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $new_name);
                                $return['files'][$i]['url'] = JUri::root().'images/'. DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $new_name;
                                $return['files'][$i]['delete_url'] = 'index.php?option=com_joomportfolio&task=uploader.deleteVideo&id='.$id.'&image='.urlencode($new_name);
                                $return['files'][$i]['delete_type'] = 'POST';
                                $return['files'][$i]['item_id'] = $id;
                                $return['files'][$i]['title'] = $old_name;
                                $return['files'][$i]['ext'] = $ext;

                                $db = JFactory::getDbo();
                                $query = $db->getQuery(true);

                                $query->insert('`#__jp3_video`')
                                    ->set('`title`="'.$db->escape($old_name).'"')
                                    ->set('`item_id`="'.$id.'"')
                                    ->set('`full`="'.$new_name.'"')
                                    ->set('`ordering`=0');

                                $db->setQuery($query);

                                try {
                                    $db->execute();
                                } catch (RuntimeException $e) {
                                    throw new Exception($e->getMessage(), 500, $e);
                                }


                            }else{
                                $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_ERROR_UPLOADING');
                            }
                        }else{
                            $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_CHECK_PERMITIONS');
                        }
                    }else{
                        $return['files'][$i]['message'] = JText::_('COM_JOOMPORTFOLIO_WRONG_FILE_TYPE');
                    }
                }
            } else {
                $return[0]['message'] = JText::_('COM_JOOMPORTFOLIO_NO_FILE');
            }
            echo(json_encode($return));
            die();

    }

    function deleteVideo(){

            $input =JFactory::getApplication()->input;
            $params = JComponentHelper::getParams('com_joomportfolio');
            $id = $input->get('id');
            $newName = $this->input->get('image');
            $db = JFactory::getDbo();
            $db->setQuery('SELECT `item_id`,`full` FROM `#__jp3_video` WHERE `full`="'.$newName.'"');
            $result = $db->loadObjectList();
            if (!empty($result)) {
                foreach ($result as $file) {
                    unlink(JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'joomportfolio'.DIRECTORY_SEPARATOR.$file->item_id.DIRECTORY_SEPARATOR.'video'.DIRECTORY_SEPARATOR.$file->full);
                }
            }

            $query = $db->getQuery(true);
            $query->delete()
                ->from('#__jp3_video')
                ->where('`full`="'.$newName.'"');

            $db->setQuery($query);

            $db->execute();
            JFactory::getApplication()->close();

    }


}
