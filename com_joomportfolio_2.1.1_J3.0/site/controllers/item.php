<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JoomPortfolioControllerItem extends JControllerLegacy {


    public function image() {
        $input = JFactory::getApplication()->input;
        $app = JFactory::getApplication();
        $img = $input->get('i', '', 'get', 'string');
        $f = $input->get('f', '', 'get', 'string');
        $folder = $f ? JPATH_SITE . DIRECTORY_SEPARATOR . $f : JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'items';
        $image = $folder . DIRECTORY_SEPARATOR . $img;

        $w = $input->get('w', '', 'get', 'int');
        $h = $input->get('h', '', 'get', 'int');
        $width = $w ? $w : 48;
        $height = $h ? $h : 48;

        $file = $folder . DIRECTORY_SEPARATOR . 'thumbs' . DIRECTORY_SEPARATOR . 'w' . $width . 'h' . $height . '_' . $img;

        if (file_exists($file)) {
            header('Content-Type: image/png');
            echo(file_get_contents($file));
        } else {
            if (!is_file($image)) {
                $image = JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'no_photo.png';
            }

            $imagedata = getimagesize($image);

            if (!$imagedata)
                exit();

            switch ($imagedata[2]) {
                case IMAGETYPE_JPEG: $source = ImageCreateFromJpeg($image);
                    break;
                case IMAGETYPE_GIF: $source = ImageCreateFromGif($image);
                    break;
                case IMAGETYPE_PNG: $source = ImageCreateFromPng($image);
                    break;
                default: $source = ImageCreateFromJpeg($image);
                    break;
            }

            if ($imagedata[0] < $width and $imagedata[1] < $height) {
                $width_new = $imagedata[0];
                $height_new = $imagedata[1];
            } else {
                if ($imagedata[0] > $imagedata[1]) {
                    $width_new = $width;
                    $height_new = $imagedata[1] * $width / $imagedata[0];
                } else {
                    $width_new = $imagedata[0] * $height / $imagedata[1];
                    $height_new = $height;
                }
            }

            $dest = ImageCreateTrueColor($width, $height);
            $trans = ImageColorAllocate($dest, 255, 255, 255);
            ImageColorTransparent($dest, $trans);
            ImageFilledRectangle($dest, 0, 0, $width, $height, $trans);
            ImageCopyResampled($dest, $source, ($width - $width_new) / 2, ($height - $height_new) / 2, 0, 0, $width_new, $height_new, $imagedata[0], $imagedata[1]);

            header('Content-Type: image/png');
            ImageJpeg($dest);

            ImagePng($dest, $file);

            ImageDestroy($dest);
            ImageDestroy($source);
        }

        $app->close();
    }

    public function getRateAjax() {
        $input = JFactory::getApplication()->input;
        $user_rating = $input->get('user_rating');
        $id = $input->get('id');
        $db = JFactory::getDBO();
        if (($user_rating >= 1) and ($user_rating <= 5) && $id) {
            $currip = ( phpversion() <= '4.2.1' ? @getenv('REMOTE_ADDR') : $_SERVER['REMOTE_ADDR'] );
            $id = intval($id);
            $query = "SELECT * FROM #__jp3_rating WHERE item_id=" . $id;
            $db->setQuery($query);
            $votesdb = $db->loadObject();
            if (!$votesdb) {
                $query = "INSERT INTO #__jp3_rating (item_id,lastip,sum,count)"
                        . "\n VALUES (" . $id . "," . $db->Quote($currip) . "," . $user_rating . ",1)";
                $db->setQuery($query);
                $db->execute() or die($db->stderr());
                ;
            } else {
                if ($currip != ($votesdb->lastip)) {
                    $query = "UPDATE #__jp3_rating"
                            . "\n SET count = count + 1, sum = sum + " . $user_rating . ", lastip = " . $db->Quote($currip)
                            . "\n WHERE item_id=" . $id;
                    $db->setQuery($query);
                    $db->execute() or die($db->stderr());
                } else {
                    echo 'voted';
                    exit();
                }
            }
        }
        echo 'thanks';
        exit();
    }

    public function search() {
        $letter = JFactory::getApplication()->input->get('letter');
        $Itemid = JFactory::getApplication()->input->get('Itemid');
        $ext = JFactory::getApplication()->input->get('ext');
        $data = array();
        $model = $this->getModel('item');
        $items = $model->itemSearch($letter, $Itemid, $ext);
        $mode_cat = $this->getModel('category');

        for ($i = 0; $i < count($items); $i++) {
            $items[$i]['rating'] = $model->getRating($items[$i]['item_id'], NULL, NULL);
        }
        $data['items'] = $items;
        $fields = $mode_cat->searchFields($Itemid, $ext);
        $params = JoomPortfolioHelper::getSetting($ext);
        $data['params'] = $params;
        $data['fields'] = $fields;
        echo json_encode($data);

        JFactory::getApplication()->close();
    }

    public function changeStudentLimit() {
        $data = array();
        $Itemid = intval(JRequest::getVar('Itemid'));
        $limit = intval(JRequest::getVar('limit'));
        $ext = JFactory::getApplication()->input->get('ext');
        $model = $this->getModel('Item');
        $mode_cat = $this->getModel('category');
        $students = $model->studentsChangeLimit();
        $params = JoomPortfolioHelper::getSettings();
        $fields = $mode_cat->searchFields($Itemid, $ext);
        for ($i = 0; $i < count($students); $i++) {
            $students[$i]->rating = $model->getRating($students[$i]->item_id, NULL, NULL);
        }
        $data['params'] = $params;
        $data['items'] = $students;
        $data['fields'] = $fields;
        echo json_encode($data);
        JFactory::getApplication()->close();
    }

    public function changeStudentPage() {
        $data = array();
        $Itemid = intval(JRequest::getVar('Itemid'));
        $model = $this->getModel('Item');
        $mode_cat = $this->getModel('category');
        $students = $model->studentsChangePage();
        $params = JoomPortfolioHelper::getSettings();
        $ext = JFactory::getApplication()->input->get('ext');
        $fields = $mode_cat->searchFields($Itemid, $ext);
        for ($i = 0; $i < count($students); $i++) {
            $students[$i]->rating = $model->getRating($students[$i]->item_id, NULL, NULL);
        }
        $data['params'] = $params;
        $data['items'] = $students;
        $data['fields'] = $fields;
        echo json_encode($data);
        JFactory::getApplication()->close();
    }

    public function addOrnament(){
        $jinput = JFactory::getApplication()->input;
        $item_id=$jinput->get('item_id',0,'INT');
        $condolence_id=$jinput->get('condolence_id',0,'INT');
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->insert('`#__jp3_ornaments`')
            ->set('`item_id`='.$item_id)
            ->set('`condole_id`='.$condolence_id)
            ->set('`published`=1')
            ->set('`user_id`='.$user->id)
            ->set('`created`=NOW()');
        $db->setQuery($query);
        $db->execute();
        JFactory::getApplication()->close();
    }

    public function addComment(){
        $jinput = JFactory::getApplication()->input;
        $model = $this->getModel('Item');
        $item_id=$jinput->get('item_id',0,'INT');
        $comment_text=$jinput->get('comment-text','','HTML');
        //$username=$jinput->get('user-name','','HTML');
        $mode=$jinput->get('modename','joomportfolio','HTML');

        $user = JFactory::getUser();

        if(trim($comment_text!='')){

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->insert('`#__jp3_comments`')
                ->set('`item_id`='.$item_id)
                ->set('`comment`="'.$db->escape($comment_text).'"')
                ->set('`published`=1')
                ->set('`mode`="'.$mode.'"')
                ->set('`user_id`='.$user->id)
                //->set('`username`="'.$username.'"')
                ->set('`date`=NOW()');
            $db->setQuery($query);
            $db->execute();

            $query = $db->getQuery(true);
            $query->select('p.*');
            $query->from('#__jp3_comments AS p');
            $query->select($db->qn('u.name', 'username'));
            $query->leftJoin($db->qn('#__users', 'u') .' ON '. $db->qn('u.id') .'='. $db->qn('p.user_id'));
            $query->order('id DESC');
            $db->setQuery($query,0,1);
            $comment= $db->loadObject();
            $data['comment'] = $comment;
            $data['status']=1;
        }else{
            $data['status']=2;
        }

        echo json_encode($data);

        JFactory::getApplication()->close();
       // $this->setRedirect(JRoute::_('index.php?option=com_joomportfolio&view=item&cid=' . $alias->cat_slug . '&id=' . $alias->slug));
    }


}

?>