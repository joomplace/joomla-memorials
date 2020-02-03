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

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Item Model
 */
class JoomPortfolioModelItem extends JModelAdmin {

    protected $context = 'com_joomportfolio';

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
       /* $mode = JoomPortfolioHelper::getMode();
        $line = 37; // номер строки, которую нужно изменить
        $replace = "query=\"SELECT id, title FROM #__categories WHERE extension='com_" . $mode . "'\""; // на что нужно изменить
        $filename = JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'item.xml'; // имя файла 
        $file = file($filename);
        $file[$line - 1] = $replace . PHP_EOL;
        file_put_contents($filename, join('', $file));*/

        $form = $this->loadForm('com_joomportfolio.item', 'item', array('control' => 'jform', 'load_data' => false));

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
            if ( !empty($properties['custom_metatags']) && !is_array($properties['custom_metatags']) )
                $properties['custom_metatags'] = unserialize( $properties['custom_metatags'] );
            $this->item = new JObject($properties);
        }

        return $this->item;
    }

    protected function uploadFile($id = 0)
    {
        $userfile2 = (isset($_FILES['userfile']['tmp_name']) ? $_FILES['userfile']['tmp_name'] : "");
        $userfile_name = (isset($_FILES['userfile']['name']) ? $_FILES['userfile']['name'] : "");
        $ext = substr($userfile_name, -4);
        $directory = 'images/com_joomportfolio/items';
        if (isset($_FILES['userfile'])) {
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
            if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $base_Dir . '/' . $userfile_name) || !JPath::setPermissions($base_Dir . '/' . $userfile_name)) {
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

        if (isset($_FILES['userfile'])) {
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

        basepath = "<?php echo JURI::root() . 'images' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR; ?>";

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

    public function saveItem()
    {
        $input = JFactory::getApplication()->input;
        $db = JFactory::getDbo();

        $images = $input->get('images', array(), 'post', 'array');
        if (!isset($images['jform']['images'])) {
            return true;
        }

        $imgPaths = $images['jform']['images']['img_path'];
        $imgTitles = $images['jform']['images']['img_title'];
        $imgCopys = $images['jform']['images']['img_copyright'];
        $imgDescs = $images['jform']['images']['img_description'];
        $imgOrdering = $images['jform']['images']['img_ordering'];
        if (empty($imgPaths)) {
            return true;
        }

        for ($i = 0; $i < count($imgPaths); $i++) {
            $row = new JObject();
            $row->full = $imgPaths[$i];
            $row->title = $imgTitles[$i];
            $row->copyright = $imgCopys[$i];
            $row->description = $imgDescs[$i];
            $row->ordering = $imgOrdering[$i];

            $query = $db->getQuery(true);

            $query->update('#__jp3_pictures')
                ->set('title="' . $db->escape($row->title) . '"')
                ->set('copyright="' . $db->escape($row->copyright) . '"')
                ->set('description="' . $db->escape($row->description) . '"')
                ->set('ordering=' . (int)$row->ordering)
                ->where('full="' . $db->escape($row->full) . '"');
            $db->setQuery($query);

            if (!$db->execute()) {
                return false;
            }
        }
        return true;
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
        try {
            $db->execute();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());
            return false;
        }

        $query = $db->getQuery(true);
        $query->update('#__jp3_pictures')
            ->set('`is_default`="1"');
        if (ctype_digit($image_id)) {
            error_log('int');
            $query->where('`id`=' . $image_id);
        } else {
            error_log('string');
            $query->where('`full`="' . $image_id . '"');
        }
        $query->where('`item_id`="' . $item_id . '"');
        $db->setQuery($query);
        try {
            $db->execute();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return true;
    }

    public function lastId()
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT MAX(id) FROM `#__jp3_items`");
        $max_id = $db->loadResult();
        return $max_id;
    }

    public function save($data)
    {
        $custom = JFactory::getApplication()->input->get('custom', array(), 'post', 'array');

        $table = $this->getTable();

        $custom_tags = array();
        $custom_tags_names = JFactory::getApplication()->input->get('cm_names', array(), 'array');
        $custom_tags_values = JFactory::getApplication()->input->get('cm_values', array(), 'array');
        if (!empty($custom_tags_names)){
            foreach ( $custom_tags_names as $k => $custom_name ) {
                $custom_tags[$custom_name] = $custom_tags_values[$k];
            }
        }
        $data['custom_metatags'] = serialize($custom_tags);

        $data['mode'] = JoomPortfolioHelper::getMode();

        // Bind the data.
        if (!$table->bind($data)) {
            $this->setError($table->getError());
            return false;
        }

        // Check the data.
        if (!$table->check()) {
            $this->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store()) {
            $this->setError($table->getError());
            return false;
        } else {
            $data['id'] = (int)$table->id;
            $app = JFactory::getApplication();
            $app->setUserState('com_joomportfolio.default.item.data', $data);
        }

        if (isset($data["images"])) {
            if (!$this->imagesAditionalData($data)) {
                return false;
            }
        }
        if (isset($data["pdf"])) {
            if (!$this->pdfAditionalData($data)) {
                return false;
            }
        }
        if (isset($data["audio"])) {
            if (!$this->audioAditionalData($data)) {
                return false;
            }
        }
        if (!$this->updateHref($data['id'], $data)) {
            return false;
        }
        if (!$this->saveFieldsValue($data['id'], $custom)) {
            return false;
        }
        return true;
    }


    public function imagesAditionalData($data)
    {
        $db = JFactory::getDBO();
        $images = $data["images"];
        $query = "SELECT id"
            . "\n FROM #__jp3_pictures"
            . "\n WHERE  item_id=" . intval($data['id']);
        $db->setQuery($query);
        $img_id = $db->loadAssocList();
        $count_img = count($images);

        if ($count_img && !empty($img_id)) {
            for ($i = 0; $i < $count_img; $i++) {
                $db->setQuery("UPDATE #__jp3_pictures SET
                title='" . $db->escape($images["img_title"][$i]) . "', 
                copyright='" . $db->escape($images['img_copyright'][$i]) . "', 
                description='" . $db->escape($images['img_description'][$i]) . "',
                ordering=" . (int)$images['img_ordering'][$i] . "
                WHERE item_id=" . intval($data['id']) . " AND id=" . intval($img_id[$i]['id']));
                if (!$db->execute()) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function deleteItems($data)
    {
        $db = JFactory::getDBO();
        if (!empty($data)) {
            $count=count($data);
            for ($i = 0; $i < $count; $i++) {
                $query = $db->getQuery(true);
                $query->delete()
                    ->from('#__jp3_items')
                    ->where('id=' . (int)$data[$i]);
                $db->setQuery($query);

                try {
                    $db->execute();
                } catch (RuntimeException $e) {
                    $this->setError($e->getMessage());
                    return false;
                }

                //delete content
                for ($j = 0; $j < $count; $j++) {
                    $query = $db->getQuery(true);
                    $query->delete()
                        ->from('#__jp3_item_content')
                        ->where('item_id=' . (int)$data[$i]);
                    $db->setQuery($query);
                    try {
                        $db->execute();
                    } catch (RuntimeException $e) {
                        $this->setError($e->getMessage());
                        return false;
                    }
                }

                //delete img
                for ($j = 0; $j < $count; $j++) {
                    $query = $db->getQuery(true);
                    $query->delete()
                        ->from('#__jp3_pictures')
                        ->where('item_id=' . (int)$data[$i]);
                    $db->setQuery($query);
                    try {
                        $db->execute();
                    } catch (RuntimeException $e) {
                        $this->setError($e->getMessage());
                        return false;
                    }
                }

                //delete pdf
                for ($j = 0; $j < $count; $j++) {
                    $query = $db->getQuery(true);
                    $query->delete()
                        ->from('#__jp3_pdf')
                        ->where('item_id=' . (int)$data[$i]);
                    $db->setQuery($query);
                    try {
                        $db->execute();
                    } catch (RuntimeException $e) {
                        $this->setError($e->getMessage());
                        return false;
                    }
                }

                //delete video
                for ($j = 0; $j < $count; $j++) {
                    $query = $db->getQuery(true);
                    $query->delete()
                        ->from('#__jp3_video')
                        ->where('item_id=' . (int)$data[$i]);
                    $db->setQuery($query);
                    try {
                        $db->execute();
                    } catch (RuntimeException $e) {
                        $this->setError($e->getMessage());
                        return false;
                    }
                }

                //delete audio
                for ($j = 0; $j < $count; $j++) {
                    $query = $db->getQuery(true);
                    $query->delete()
                        ->from('#__jp3_audio')
                        ->where('item_id=' . (int)$data[$i]);
                    $db->setQuery($query);
                    try {
                        $db->execute();
                    } catch (RuntimeException $e) {
                        $this->setError($e->getMessage());
                        return false;
                    }
                }

                //delete comments
                for ($j = 0; $j < $count; $j++) {
                    $query = $db->getQuery(true);
                    $query->delete()
                        ->from('#__jp3_comments')
                        ->where('item_id=' . (int)$data[$i]);
                    $db->setQuery($query);
                    try {
                        $db->execute();
                    } catch (RuntimeException $e) {
                        $this->setError($e->getMessage());
                        return false;
                    }
                }
                if ((int)$data[$i]) {
                    JoomPortfolioHelper::deleteDirectory(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'joomportfolio' . DIRECTORY_SEPARATOR . (int)$data[$i]);
                }







            }
            return true;
        }
        return false;
    }

    public function customFields()
    {
        $db = JFactory::getDBO();
        $mode = JoomPortfolioHelper::getMode();
        $query = "SELECT *"
            . "\n FROM #__jp3_field"
            . "\n WHERE mode='" . $mode . "'";
        $db->setQuery($query);
        $fields = $db->loadAssocList();
        return $fields;
    }

    public function getFieldsValue()
    {
        $mode = JoomPortfolioHelper::getMode();

        $db = JFactory::getDBO();
        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);
        $query = "SELECT f.*"
            . "\n FROM #__jp3_field AS f"
            . "\n WHERE mode='" . $mode . "'";
        $db->setQuery($query);
        $fields = $db->loadAssocList();
        $count_fields = count($fields);
        for ($i = 0; $i < $count_fields; $i++) {
            $fields[$i]['value'] = '';
        }

        //get all items this cat
        $ids = array();
        if((int)$id) {
            $query = "SELECT  value AS custom, field_id"
                . "\n FROM #__jp3_item_content"
                . "\n WHERE item_id=" . (int)$id;
            $db->setQuery($query);
            $ids = $db->loadAssocList();
        }

        $custom = array();
        for ($i = 0; $i < count($ids); $i++) {
            $custom[$i]['custom'] = $ids[$i]['custom'];
            $custom[$i]['field_id'] = $ids[$i]['field_id'];
        }

        if (empty($custom)) {
            for ($i = 0; $i < $count_fields; $i++) {
                $custom[$i]['custom'] = $fields[$i]['def'];
                $custom[$i]['field_id'] = $fields[$i]['id'];
            }
        }

        $field_ids = array();
        for ($i = 0; $i < count($fields); $i++) {
            $field_ids[$i] = intval($fields[$i]['id']);
        }

        for ($i = 0; $i < count($custom); $i++) {
            if (count($custom[$i]['custom'])) {
                if (is_array($custom[$i]['custom'])) {
                    foreach ($custom[$i]['custom'] as $key => $value) {

                        if (!in_array($key, $field_ids)) {
                            unset($custom[$i]['custom'][$key]);
                        }
                    }
                }
            }
        }


        $custom_f = array();
        if (count($custom)) {
            //add value to fields
            for ($i = 0; $i < count($custom); $i++) {
                for ($j = 0; $j < count($fields); $j++) {
                    if (count($custom[$i]['custom'])) {
                        $value = $custom[$i]['custom'];

                        if ((int)$fields[$j]['id'] == (int)$custom[$i]['field_id']) {

                            $custom_f[$j]['value'] = $value;
                            $custom_f[$j]['name'] = $fields[$j]['name'];
                            $custom_f[$j]['label'] = $fields[$j]['label'];
                            $custom_f[$j]['def'] = $fields[$j]['def'];
                            $custom_f[$j]['type'] = $fields[$j]['type'];
                            $custom_f[$j]['req'] = $fields[$j]['req'];
                            $custom_f[$j]['catview'] = $fields[$j]['catview'];
                            $custom_f[$j]['id'] = $fields[$j]['id'];

                        }

                    }
                }
            }
        }
        //die();
        return $custom_f;

    }

    public function getFields($text1, $text2, $text3)
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT id FROM `#__jp3_field` ORDER BY id");
        $fields = $db->loadAssocList();
        $db->setQuery("SELECT MAX(id) FROM `#__jp3_field`");
        $max_id = $db->loadResult();
        $res = array();
        for ($i = 0; $i < count($fields); $i++) {
            switch ($i) {
                case ($i == (count($fields) - 3)):
                    $res[$fields[$i]['id']] = trim($text1);
                    break;
                case ($i == (count($fields) - 2)):
                    $res[$fields[$i]['id']] = trim($text2);
                    break;
                case ($i == (count($fields) - 1)):
                    $res[$fields[$i]['id']] = trim($text3);
                    break;
                default:
                    $res[$fields[$i]['id']] = '';
            }
        }

        $result = array(
            'fields' => $res,
            'max_id' => $max_id
        );
        return $result;
    }

       public function saveHref($id, $data)
    {
        $db = JFactory::getDBO();
        if (count($data)) {
            $db->setQuery("INSERT INTO `#__jp3_href` (`item_id` ,`cat_id`)
			VALUES (" .
                intval($id) . "," .
                intval($data["cat_id"]) . ");");
            try {
                $db->execute();
            } catch (RuntimeException $e) {
                $this->setError($e->getMessage());
                return false;
            }
            return true;
        }
        return false;
    }

    public function updateHref($id, $data)
    {
        $db = JFactory::getDBO();
        if (count($data)) {
            $db->setQuery("UPDATE #__jp3_href SET
                cat_id=" . intval($data['cat_id']) . "
                WHERE item_id=" . intval($id));
            try {
                $db->execute();
            } catch (RuntimeException $e) {
                $this->setError($e->getMessage());
                return false;
            }
            return true;
        }
        return false;
    }

    public function getItemFields()
    {
        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);
        $db = JFactory::getDBO();
        $mode = JoomPortfolioHelper::getMode();

        if (intval($id) != 0) {
            $db->setQuery("SELECT f.*, ic.value FROM `#__jp3_field` AS f
             LEFT JOIN `#__jp3_item_content` AS ic ON ic.field_id=f.id
             WHERE f.mode='" . $mode . "' AND ic.item_id=" . (int)$id);
            $fields = $db->loadAssocList();

            if (!count($fields)) {
                $query = "SELECT *"
                    . "\n FROM #__jp3_field"
                    . "\n WHERE mode='" . $mode . "'";
                $db->setQuery($query);
                $fields = $db->loadAssocList();

                for ($i = 0; $i < count($fields); $i++) {
                    $fields[$i]['value'] = '';
                }
            }
        } else {
            $query = "SELECT *"
                . "\n FROM #__jp3_field"
                . "\n WHERE mode='" . $mode . "'";
            $db->setQuery($query);
            $fields = $db->loadAssocList();
        }
        return $fields;
    }

    public function saveFieldsValue($id, $custom)
    {
        $count_custom = count($custom);
        if ($count_custom) {
            $db = JFactory::getDBO();
            $input = JFactory::getApplication()->input;
            $custom_id = $input->get('custom_id', array(), 'post', 'array');
            if ($count_custom) {
                foreach ($custom as $key => $value) {
                    $query = "SELECT *"
                        . "\n FROM #__jp3_item_content"
                        . "\n WHERE item_id=" . intval($id) . " AND field_id=" . (int)$key;
                    $db->setQuery($query);
                    $field = $db->loadObject();
                    if ($field) {
                        $query = $db->getQuery(true);
                        $query->update('#__jp3_item_content')
                            ->set('`value`="' . $db->escape($value) . '"')
                            ->where('`item_id`=' . (int)$id . ' AND field_id=' . (int)$key);
                        $db->setQuery($query);
                        try {
                            $db->execute();
                        } catch (RuntimeException $e) {
                            $this->setError($e->getMessage());
                            return false;
                        }
                    } else {
                        $db->setQuery("INSERT INTO `#__jp3_item_content` (`field_id` ,`item_id` ,`value`)
              VALUES ( " . intval($key) . ", " . intval($id) . ", '" . $db->escape(trim($value)) . "');");
                        try {
                            $db->execute();
                        } catch (RuntimeException $e) {
                            $this->setError($e->getMessage());
                            return false;
                        }
                    }

                }
            }
            return true;
        }
        return true;
    }

    public function pdfAditionalData($data)
    {
        $db = JFactory::getDBO();
        $pdf = $data["pdf"];
        $query = "SELECT id"
            . "\n FROM #__jp3_pdf"
            . "\n WHERE  item_id=" . intval($data['id']);
        $db->setQuery($query);
        $pdf_id = $db->loadAssocList();
        $count_img = count($pdf);

        if ($count_img && count($pdf_id)) {
            for ($i = 0; $i < $count_img; $i++) {
                $query = $db->getQuery(true);
                $query->update('#__jp3_pdf')
                    ->set('title="' . $db->escape($pdf["img_title"][$i]) . '"')
                    ->set('ordering=' . (int)$pdf['img_ordering'][$i])
                    ->where('item_id=' . (int)$data['id'] . ' AND id=' . (int)$pdf_id[$i]['id']);
                $db->setQuery($query);

                if (!$db->execute()) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function audioAditionalData($data)
    {
        $db = JFactory::getDBO();
        $audio = $data["audio"];
        $query = "SELECT id"
            . "\n FROM #__jp3_audio"
            . "\n WHERE  item_id=" . (int)$data['id'];
        $db->setQuery($query);
        $pdf_id = $db->loadAssocList();
        $count_img = count($audio);

        if ($count_img && count($pdf_id)) {
            for ($i = 0; $i < $count_img; $i++) {
                $query = $db->getQuery(true);
                $query->update('#__jp3_audio')
                    ->set('title="' . $db->escape($audio["img_title"][$i]) . '"')
                    ->set('ordering=' . (int)$audio['img_ordering'][$i])
                    ->where('item_id=' . (int)$data['id'] . ' AND id=' . (int)$pdf_id[$i]['id']);
                $db->setQuery($query);
                if (!$db->execute()) {

                    return false;
                }
            }

            return true;
        }
        return false;
    }


    public function check($data)
    {
        if (count($data)) {

            if ($data['title'] == '' || !isset($data['cat_id'])) {
                if ($data['title'] == '') {
                    JError::raiseWarning(404, JText::_('COM_JOOMPORTFOLIO_ERROR_TITLE'));

                }
                if (!isset($data['cat_id'])) {
                    JError::raiseWarning(404, JText::_('COM_JOOMPORTFOLIO_ERROR_CATEGORY'));
                }
                JFactory::getApplication()->redirect('index.php?option=com_joomportfolio&view=item&layout=edit&id=' . (int)$data['id']);
                return false;
            }

            return true;
        }

        JFactory::getApplication()->redirect('index.php?option=com_joomportfolio&view=item&layout=edit&id=' . (int)$data['id']);
        return false;
    }

}

