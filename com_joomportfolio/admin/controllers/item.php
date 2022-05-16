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
 * Item Controller
 */
class JoomPortfolioControllerItem extends JControllerForm {

    protected $context = 'com_joomportfolio';

    public function __construct($config = array())
    {

        $this->view_item = 'item';
        parent::__construct($config);
        return true;
    }

    /**
     * Method override to check if you can edit an existing record.
     *
     * @param    array $data    An array of input data.
     * @param    string $key    The name of the key for the primary key.
     *
     * @return    boolean
     * @since    1.6
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        // Check specific edit permission then general edit permission.
        return JFactory::getUser()->authorise('core.edit', 'com_joomportfolio.item.' . ((int)isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
    }

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param    type    The table type to instantiate
     * @param    string    A prefix for the table class name. Optional.
     * @param    array    Configuration array for model. Optional.
     * @return    JTable    A database object
     * @since    1.6
     */
    public function getTable($type = 'Item', $prefix = 'JoomPortfolioTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param    array $data        Data for the form.
     * @param    boolean $loadData    True if the form is to load its own data (default case), false if not.
     * @return    mixed    A JForm object on success, false on failure
     * @since    1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_joomportfolio.item', 'item', array('control' => 'jform', 'load_data' => false));
        $file = JoomPortfolioHelper::getDataFile('custom.xml');
        $form->loadFile($file, 'custom');

        $form->bind($this->getItem());

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function getItem($pk = null)
    {
        if (!isset($this->item)) {
            $pk = (!empty($pk)) ? $pk : (int)$this->getState($this->getName() . '.id');
            $table = $this->getTable();
            if ($pk > 0) {
                $return = $table->load($pk);

                if ($return === false && $table->getError()) {
                    $this->setError($table->getError());
                    return false;
                }
            }
            $properties = $table->getProperties(1);
            $this->item = new JObject($properties);
        }
        return $this->item;
    }

    public function cancel($key = null)
    {
        $this->setRedirect('index.php?option=com_joomportfolio&view=items');
    }

    public function edit($key = NULL, $urlVar = NULL)
    {
        $input = JFactory::getApplication()->input;
        $data = $input->get('cid', array(), 'post', 'array');
        if (!empty($data)) {
            $this->setRedirect('index.php?option=com_joomportfolio&view=item&layout=edit&id=' . $data[0]);
        } else {
            $this->setRedirect('index.php?option=com_joomportfolio&view=item&layout=edit');
        }
    }

    protected function uploadFile($id = 0)
    {
        $userfile = JFactory::getApplication()->input->files->get('userfile', array(), 'array');
        $userfile_name = !empty($userfile['name']) ? $userfile['name'] : "";
        $ext = substr($userfile_name, -4);
        $directory = 'images/com_joomportfolio/items';
        if (!empty($userfile)) {
            $base_Dir = JPATH_SITE . "/images/com_joomportfolio/items";
            if (!file_exists($base_Dir)) {
                @mkdir($base_Dir, 0777);
            }

            if (empty($userfile_name)) {
                ?>
                <script
                    type="text/javascript">alert("<?php echo JText::_('COM_JOOMPORTFOLIO_UPLOAD_ERROR1'); ?>")</script><?php
                return;
            }

            $filename = preg_split("/\./", $userfile_name);

            if (preg_match("/[^0-9a-zA-Z_-]/", $filename[0])) {
                ?>
                <script
                    type="text/javascript">alert("<?php echo JText::_('COM_JOOMPORTFOLIO_UPLOAD_ERROR2'); ?>")</script><?php
                return;
            }

            if (file_exists($base_Dir . '/' . $userfile_name))
                $userfile_name = str_replace($ext, '', $userfile_name) . '_' . rand(1, 9999) . $ext;
            if (file_exists($base_Dir . '/' . $userfile_name)) {
                ?>
                <script
                    type="text/javascript">alert("<?php echo JText::_('COM_JOOMPORTFOLIO_UPLOAD_ERROR3'); ?>")</script><?php
                return;
            }

            if ((strcasecmp($ext, ".gif")) && (strcasecmp($ext, ".jpg")) && (strcasecmp($ext, ".png")) && (strcasecmp($ext, "jpeg"))) {
                ?>
                <script
                    type="text/javascript">alert("<?php echo JText::_('COM_JOOMPORTFOLIO_UPLOAD_ERROR4'); ?>")</script><?php
                return;
            }
            if (!move_uploaded_file($userfile['tmp_name'], $base_Dir . '/' . $userfile_name) || !JPath::setPermissions($base_Dir . '/' . $userfile_name)) {
                ?>
                <script
                    type="text/javascript">alert("<?php echo JText::_('COM_JOOMPORTFOLIO_UPLOAD_ERROR5'); ?>")</script><?php
                return;
            } else {
                ?>
                <script type="text/javascript">
                    alert("Upload successfully");
                    window.parent.createListRow('<?php echo $userfile_name; ?>');
                    window.parent.SqueezeBox.close();
                </script><?php
            }
        }
        return;
    }

    public function upload()
    {
        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);
        $userfile = $input->files->get('userfile', array(), 'array');

        if (!empty($userfile)) {
            $this->uploadFile($id);
        }
        ?>
        <form method="post"
              action="<?php echo JRoute::_('index.php?option=com_joomportfolio&task=items.upload&id=' . (int)$id); ?>"
              enctype="multipart/form-data" name="filename">
            <table class="adminform">
                <tr>
                    <th class="title">

                        <?php echo JText::_('COM_JOOMPORTFOLIO_UPLOAD_FU'); ?>
                    </th>
                </tr>
                <tr>
                    <td align="center">
                        <input class="inputbox" name="userfile" type="file"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="button" type="submit" value="Upload" name="fileupload"/>
                        <?php echo JText::_('COM_JOOMPORTFOLIO_UPLOAD_MS'); ?> <?php echo ini_get('upload_max_filesize'); ?>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="option" value="com_joomportfolio"/>
            <input type="hidden" name="task" value="items.upload">
            <input type="hidden" name="tmpl" value="component">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
        </form>
    <?php
    }

    protected function getTree($dir)
    {
        $images = array();
        $childs = array();

        $files = JFolder::files($dir);
        if (!empty($files)) {
            foreach ($files as $file) {
                if (preg_match('#(bmp|gif|jpg|jpeg|png)$#', $file)) {
                    $images[] = $file;
                }
            }
        }

        $folders = JFolder::folders($dir);
        if (!empty($folders)) {
            foreach ($folders as $folder) {
                $childs[] = $this->getTree($dir . DIRECTORY_SEPARATOR . $folder);
            }
        }

        $out = new JObject();
        $out->path = basename($dir);
        $out->images = $images;
        $out->childs = $childs;

        return $out;
    }

public function showSelectImages() {
    $tree = $this->getTree(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'items');
    ?>
    <script type="text/javascript">

        basepath = "<?php echo JURI::root() . 'images'. DIRECTORY_SEPARATOR .'com_joomportfolio'. DIRECTORY_SEPARATOR .'items'. DIRECTORY_SEPARATOR ; ?>";

    </script>
    <table class="adminlist" id="imagesSelector">
        <thead>
        <tr>
            <th width="1%">
            </th>
            <th width="50px">
                <?php echo JText::_('COM_JOOMPORTFOLIO_IMAGE'); ?>
            </th>
            <th>
                <?php echo JText::_('COM_JOOMPORTFOLIO_IMAGE_NAME'); ?>
            </th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="3" align="left">
                <button class="button" onclick="window.parent.insertImageRow2($$('input.checker'))">
                    <?php echo JText::_('COM_JOOMPORTFOLIO_APPLY'); ?>
                </button>
            </td>
        </tr>
        </tfoot>
        <tbody>
        <?php
        if ($tree->images) {
            foreach ($tree->images as $i => $file) {
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
                        <input type="checkbox" class="checker" name="elmts[]" value="<?php echo $file; ?>"/>
                    </td>
                    <td align="center">
                        <img
                            src="<?php echo JURI::root() . 'images' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR . $file; ?>"
                            width="48px" height="48px"/>
                    </td>
                    <td>
                        <?php echo $file; ?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </tbody>
    </table>
<?php
}

    public function saveImages()
    {
        $input = JFactory::getApplication()->input;
        $db = JFactory::getDbo();

        $id = $input->get('id', array(), 'post', 'array');
        $images = $input->get('images', array(), 'post', 'array');

        if (!isset($images['jform']['images'])) {
            return true;
        }

        $imgPaths = $images['jform']['images']['img_path'];
        $imgTitles = $images['jform']['images']['img_title'];
        $imgCopys = $images['jform']['images']['img_copyright'];
        $imgDescs = $images['jform']['images']['img_description'];

        if (empty($imgPaths)) {
            return true;
        }

        for ($i = 0; $i < count($imgPaths); $i++) {
            $row = new JObject();
            $row->full = $imgPaths[$i];
            $row->title = $imgTitles[$i];
            $row->copyright = $imgCopys[$i];
            $row->description = $imgDescs[$i];

            $query = $db->getQuery(true);

            $query->update('#__jp3_pictures')
                ->set('title="' . $row->title . '"')
                ->set('copyright="' . $row->copyright . '"')
                ->set('description="' . $row->description . '"')
                ->where('full="' . $row->full . '"');
            $db->setQuery($query);

            if (!$db->execute()) {
                return false;
            }
        }
        return true;
    }

    public function saveItem($id, $max_item_id)
    {
        $input = JFactory::getApplication()->input;
        $db = JFactory::getDbo();
        $mode = JoomPortfolioHelper::getMode(); /* str_replace('name', '', $input->get('mode', 'post')); */
        if ($id != 0) {
            $query = $db->getQuery(true);
            $query->update('#__jp3_items');
            $query->set('mode ="' . $db->escape($mode) . '"');
            $query->where('id=' . (int)$id);
            $db->setQuery($query);
            if (!$db->execute()) {
                return false;
            } else {
                return true;
            }
        } else {
            $query = $db->getQuery(true);
            $query->update('#__jp3_items');
            $query->set('mode ="' . $db->escape($mode) . '"');
            $query->where('id=' . (int)$max_item_id);
            $db->setQuery($query);
            if (!$db->execute()) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function defaultSwitch()
    {
        $input = JFactory::getApplication()->input;
        $image_id = $input->get('image_id', 0);
        $item_id = $input->get('item_id', 0);
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->update('#__jp3_pictures')
            ->set('`is_default`="0"')
            ->where('`item_id`="' . $item_id . '"');
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }

        $query = $db->getQuery(true);
        $query->update('#__jp3_pictures')
            ->set('`is_default`="1"');
        if (ctype_digit($image_id)) {
            $query->where('`id`=' . $image_id);
        } else {
            $query->where('`full`="' . $image_id . '"');
        }
        $query->where('`item_id`="' . $item_id . '"');
        $db->setQuery($query);
        if (!$db->execute()) {
            return false;
        }

        return true;
    }

    public function save($key = NULL, $urlVar = NULL)
    {

        $model = $this->getModel('item');
        $input = JFactory::getApplication()->input;
        $data = $this->input->post->get('jform', array(), 'array');

        $id = $input->get('id');
        $task = $input->get('task');
        $custom = $input->get('custom', array(), 'post', 'array');
        if ($model->check($data)) {
            if ($model->save($data) && $model->saveItem()) {
                $this->setMessage(JText::_('COM_JOOMPORTFOLIO_SAVE_SUCCESS'));
            } else {
                $this->setMessage($model->getError());
            }
        } else {
            $this->setMessage($model->getError());
        }
        switch ($task) {
            case 'apply':
                if ($id == 0) {
                    $id = $model->lastId();
                    $this->setRedirect('index.php?option=com_joomportfolio&view=item&layout=edit&id=' . $id);
                } else {
                    $this->setRedirect('index.php?option=com_joomportfolio&view=item&layout=edit&id=' . $id);
                }
                break;
            case 'save':
                $this->setRedirect('index.php?option=com_joomportfolio&view=items');
                break;
            case 'save2new':
                $this->setRedirect('index.php?option=com_joomportfolio&view=item&layout=edit');
                break;
        }
    }

    public function delete()
    {
        $model = $this->getModel('item');
        $tmpl = JFactory::getApplication()->input->get('tmpl');
        $input = JFactory::getApplication()->input;
        $ids = $input->get('cid', array(), 'post', 'array');
        if ($model->deleteItems($ids)) {
            $this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($ids)));
        } else {
            $this->setMessage($model->getError());
        }
        $this->setRedirect(JRoute::_('index.php?option=com_joomportfolio&view=' . $this->view_list . $tmpl, false));
    }



    public function replace_str_in_xml()
    {
        $element = JFactory::getApplication()->input->get('element');
        $line = 39; // номер строки, которую нужно изменить
        $str = "mode='" . $element . "'";
        /*$replace = 'query="SELECT id, title FROM #__jp3_items WHERE ' . $str . '" AND published=1'; // на что нужно изменить
        $filename = JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'item' . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . 'default.xml'; // имя файла 
        $file = file($filename);
        $file[$line - 1] = $replace . PHP_EOL;
        file_put_contents($filename, join('', $file));*/
        echo(json_encode($this->renderItems()));
        JFactory::getApplication()->close();
    }

    public function renderItems()
    {
        $element = JFactory::getApplication()->input->get('element');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id,title');
        $query->from('#__jp3_items');
        $query->where('mode="' . $db->escape($element) . '"');
        $query->where('published=1');
        $db->setQuery($query);
        $cats = $db->loadObjectList();
        $count = count($cats);
        $str = '';
        if (!$count) {
            $str = '<option value=""></option>';
        }
        for ($i = 0; $i < $count; $i++) {
            $str .= '<option value="' . (int)$cats[$i]->id . '">' . $cats[$i]->title . '</option>';
        }


        return $str;
    }

}
