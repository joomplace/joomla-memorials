<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// no direct access

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Item Controller
 */
class JoomPortfolioControllerImages extends JControllerForm
{
    /* upload new ornament image popup */
    public function upload()
    {
        ?>
        <form method="post" action="<?php echo JRoute::_('index.php?option=com_joomportfolio'); ?>" enctype="multipart/form-data" name="filename">
            <table>
                <tr>
                    <th>
                        <?php echo JText::_('COM_JOOMPORTFOLIO_FILE_UPLOAD'); ?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <input name="userfile" type="file" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="ornament-type" id="image-type">
                            <option value="1"><?php echo JText::_('COM_JOOMPORTFOLIO_FIRST_TYPE');?></option>
                            <option value="2"><?php echo JText::_('COM_JOOMPORTFOLIO_SECOND_TYPE');?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Upload" name="fileupload" />
                        <?php echo JText::sprintf('COM_JOOMPORTFOLIO_UPLOAD_MAX_SIZE', ini_get('upload_max_filesize')); ?>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="task" value="images.add">
            <input type="hidden" name="tmpl" value="component">
        </form>
        <?php
        die;
    }

    public function delete()
    {
        jimport('joomla.filesystem.file');
        $db = JFactory::getDbo();
        $app = JFactory::getApplication();

        $ids = $app->input->get('cid');
        $count = count($ids);
        foreach ($ids AS $id) {
            $query = $db->getQuery(true);
            $query->select('full');
            $query->from('#__jp3_condolence');
            $query->where('id='.(int)$id);
            $db->setQuery($query);
            $fileName  = $db->loadResult();
            $query = $db->getQuery(true);
            $query->delete()
                ->from('#__jp3_ornaments')
                ->where('`condole_id`='.(int)$id);

            $db->setQuery($query);
            $db->execute();
            $query = $db->getQuery(true);
            $query->delete()
                ->from('#__jp3_condolence')
                ->where('`id`='.(int)$id);
            $db->setQuery($query);
            $db->execute();

            if ( JFile::exists(JPATH_SITE."/images/joomportfolio/condolences/$fileName") ) {
                JFile::delete(JPATH_SITE."/images/joomportfolio/condolences/$fileName");
            }
        }
        $app->enqueueMessage( JText::sprintf('COM_JOOMPORTFOLIO_N_ITEMS_DELETED', $count) );
        $app->redirect('index.php?option=com_joomportfolio&view=images');
    }

    /* upload new ornament image action */
    public function add()
    {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();

        # only 2 image types allowed - 1 and 2
        $imageType = $app->input->getInt('ornament-type', 0);
        if ( $imageType != 1 && $imageType != 2 ) $imageType = 1;

        if (!file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . 'condolences')) {
            mkdir(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . 'condolences');
        }

        $userfile = $app->input->files->get('userfile', array(), 'array');

        if (!empty($userfile)) {
            $imageOne = new JImage($userfile['tmp_name']);
            $imageNewName = md5($userfile['tmp_name']).'.jpeg';
            $imageOne->toFile( JPATH_SITE.'/images/joomportfolio/condolences/'.$imageNewName);

            $db = JFactory::getDbo();
            # set image params
            $imageObj = new stdClass();
            $imageObj->type = $imageType;
            $imageObj->full = $imageNewName;
            $db->insertObject('#__jp3_condolence', $imageObj);
            $app->enqueueMessage(JText::_('COM_JOOMPORTFOLIO_IMAGE_UPLOADED_REDIRECTING'));
        } else {
            $app->enqueueMessage(JText::_('COM_JOOMPORTFOLIO_IMAGE_UPLOADED_ERROR'));
        }

        $doc->addScriptDeclaration("window.parent.location.reload();");
    }

}
