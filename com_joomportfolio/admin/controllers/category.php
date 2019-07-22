<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// no direct access

defined('_JEXEC') or die;

/**
 * The Category Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class JoomPortfolioControllerCategory extends JControllerForm
{

    /**
     * The extension for which the categories apply.
     *
     * @var    string
     * @since  1.6
     */
    protected $extension;

    /**
     * Constructor.
     *
     * @param  array $config  An optional associative array of configuration settings.
     * @since  1.6
     * @see    JController
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

// Guess the JText message prefix. Defaults to the option.
        if (empty($this->extension)) {
            $this->extension = $this->input->get('extension', 'com_content');
        }
    }

    /**
     * Method to check if you can add a new record.
     *
     * @param   array $data  An array of input data.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowAdd($data = array())
    {
        $user = JFactory::getUser();
        return ($user->authorise('core.create', $this->extension) || count($user->getAuthorisedCategories($this->extension, 'core.create')));
    }

    /**
     * Method to check if you can edit a record.
     *
     * @param   array $data  An array of input data.
     * @param   string $key   The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowEdit($data = array(), $key = 'parent_id')
    {
        $recordId = (int)isset($data[$key]) ? $data[$key] : 0;
        $user = JFactory::getUser();
        $userId = $user->get('id');

// Check general edit permission first.
        if ($user->authorise('core.edit', $this->extension)) {
            return true;
        }

// Check specific edit permission.
        if ($user->authorise('core.edit', $this->extension . '.category.' . $recordId)) {
            return true;
        }

// Fallback on edit.own.
// First test if the permission is available.
        if ($user->authorise('core.edit.own', $this->extension . '.category.' . $recordId) || $user->authorise('core.edit.own', $this->extension)) {
// Now test the owner is the user.
            $ownerId = (int)isset($data['created_user_id']) ? $data['created_user_id'] : 0;
            if (empty($ownerId) && $recordId) {
// Need to do a lookup from the model.
                $record = $this->getModel()->getItem($recordId);

                if (empty($record)) {
                    return false;
                }

                $ownerId = $record->created_user_id;
            }

// If the owner matches 'me' then do the test.
            if ($ownerId == $userId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Method to run batch operations.
     *
     * @param   object $model  The model.
     *
     * @return  boolean  True if successful, false otherwise and internal error is set.
     *
     * @since   1.6
     */
    public function batch($model = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

// Set the model
        $model = $this->getModel('Category');

// Preset the redirect
        $this->setRedirect('index.php?option=com_categories&view=categories&extension=' . $this->extension);

        return parent::batch($model);
    }

    /**
     * Gets the URL arguments to append to an item redirect.
     *
     * @param   integer $recordId  The primary key id for the item.
     * @param   string $urlVar    The name of the URL variable for the id.
     *
     * @return  string  The arguments to append to the redirect URL.
     *
     * @since   1.6
     */
    protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
    {
        $append = parent::getRedirectToItemAppend($recordId);
        $append .= '&extension=' . $this->extension;

        return $append;
    }

    /**
     * Gets the URL arguments to append to a list redirect.
     *
     * @return  string  The arguments to append to the redirect URL.
     *
     * @since   1.6
     */
    protected function getRedirectToListAppend()
    {
        $append = parent::getRedirectToListAppend();
        $append .= '&extension=' . $this->extension;

        return $append;
    }

    /**
     * Function that allows child controller access to model data after the data has been saved.
     *
     * @param   JModelLegacy $model      The data model object.
     * @param   array $validData  The validated data.
     *
     * @return  void
     *
     * @since   3.1
     */
    protected function postSaveHook(JModelLegacy $model, $validData = array())
    {
        $item = $model->getItem();

        if (isset($item->params) && is_array($item->params)) {
            $registry = new JRegistry;
            $registry->loadArray($item->params);
            $item->params = (string)$registry;
        }
        if (isset($item->metadata) && is_array($item->metadata)) {
            $registry = new JRegistry;
            $registry->loadArray($item->metadata);
            $item->metadata = (string)$registry;
        }

        return;
    }

    public function cancel($key = null)
    {
        $this->setRedirect('index.php?option=com_joomportfolio&view=categories');
    }

    public function save($key = NULL, $urlVar = NULL)
    {

        $model = $this->getModel();
        $input = JFactory::getApplication()->input;
        $data = $input->get('jform', array(), 'post', 'array');
        $task = $input->get('task');
        if ($model->check($data)) {
            if (!$model->save($data)) {
                $this->setMessage($model->getError());
            }
        } else {
            $this->setMessage($model->getError());
        }
        switch ($task) {
            case 'apply':
                if (intval($data['id']) == 0) {
                    $id = $model->lastId();
                    $this->setRedirect('index.php?option=com_joomportfolio&view=category&layout=edit&id=' . $id);
                } else {
                    $this->setRedirect('index.php?option=com_joomportfolio&view=category&layout=edit&id=' . $data['id']);
                }
                break;
            case 'save':
                $this->setRedirect('index.php?option=com_joomportfolio&view=categories');
                break;
            case 'save2new':
                $this->setRedirect('index.php?option=com_joomportfolio&view=category&layout=edit');
                break;
        }
    }

    public function delete()
    {
        $model = $this->getModel();
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
        $line = 43; // номер строки, которую нужно изменить
       /* $replace = 'extension="com_' . $element . '"'; // на что нужно изменить
        $filename = JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . 'default.xml'; // имя файла
        $file = file($filename);
        $file[$line - 1] = $replace . PHP_EOL;
        file_put_contents($filename, join('', $file));*/
        echo(json_encode($this->renderCats()));
        JFactory::getApplication()->close();
    }

    public function renderCats()
    {
        $element = JFactory::getApplication()->input->get('element');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id,title');
        $query->from('#__categories');
        $query->where('extension="com_' . $db->escape($element) . '"');
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
