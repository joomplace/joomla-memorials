<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');
jimport('joomla.filter.output');

class JoomPortfolioModelComment extends JModelAdmin
{

    protected $context = 'com_joomportfolio';
    protected $attrs = array('name', 'label', 'type', 'def', 'required');

    public function getForm($data = array(), $loadData = true)
    {
        $mode = JoomPortfolioHelper::getMode();
        $line = 36; // номер строки, которую нужно изменить
        $replace = "query=\"SELECT id, title FROM #__jp3_items WHERE mode='" . $mode . "'\""; // на что нужно изменить
        $filename = JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'comment.xml'; // имя файла
        $file = file($filename);
        $file[$line - 1] = $replace . PHP_EOL;
        file_put_contents($filename, join('', $file));
        $form = $this->loadForm($this->context . '.' . $this->getName(), $this->getName(), array('control' => 'jform', 'load_data' => false));
        $form->bind($this->getItem());

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function getTable($type = 'Comment', $prefix = 'JoomPortfolioTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function save($data)
    {
        $table = $this->getTable();

        $data['mode'] = JoomPortfolioHelper::getMode();
        $data['user_id'] = (int)JFactory::getUser()->id;

        if(!$data['date'] || $data['date'] == '0000-00-00 00:00:00') {
            $data['date'] = date('Y-m-d H:i:s');
        }

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
            $app = JFactory::getApplication();
            $app->setUserState('com_joomportfolio.default.comment.data', $data);
        }

        return true;
    }


    public function lastId()
    {

        $mode=JoomPortfolioHelper::getMode();
        $db = JFactory::getDBO();
        $db->setQuery("SELECT MAX(id) FROM `#__jp3_comments` WHERE mode='".$mode."'");
        $max_id = $db->loadResult();
        return $max_id;
    }

    public function catview($id)
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT catview FROM `#__jp3_field` WHERE id=" . $id);
        $catview = $db->loadResult();
        if (intval($catview) == 1) {
            $query = $db->getQuery(true);
            $query->update('#__jp3_field');
            $query->set('catview = 0');
            $query->where('id=' . (int)$id);
            $db->setQuery($query);
            $db->execute();
            return intval($catview);
        } else {
            $query = $db->getQuery(true);
            $query->update('#__jp3_field');
            $query->set('catview = 1');
            $query->where('id=' . (int)$id);
            $db->setQuery($query);
            $db->execute();
            return intval($catview);
        }
    }

    public function required($id)
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT req FROM `#__jp3_field` WHERE id=" . $id);
        $req = $db->loadResult();
        if ((int)$req == 1) {
            $query = $db->getQuery(true);
            $query->update('#__jp3_field');
            $query->set('req = 0');
            $query->where('id=' . (int)$id);
            $db->setQuery($query);
            $db->execute();
            return intval($req);
        } else {
            $query = $db->getQuery(true);
            $query->update('#__jp3_field');
            $query->set('req = 1');
            $query->where('id=' . (int)$id);
            $db->setQuery($query);
            $db->execute();
            return (int)$req;
        }
    }

    public function delete(&$pks)
    {
        return parent::delete($pks);
    }

    public function check($data)
    {
        if (count($data)) {

            if ($data['title'] == '' || !isset($data['item_id'])) {
                if ($data['title'] == '') {
                    JError::raiseWarning(404,JText::_('COM_JOOMPORTFOLIO_ERROR_TITLE'), 'Warning' );

                }
                if (!isset($data['item_id'])) {
                    JError::raiseWarning(404,JText::_('COM_JOOMPORTFOLIO_ERROR_ITEM'), 'Warning' );
                }
                JFactory::getApplication()->redirect('index.php?option=com_joomportfolio&view=comment&layout=edit&id='.(int)$data['id']);
                return false;
            }

            return true;
        }

        JFactory::getApplication()->redirect('index.php?option=com_joomportfolio&view=comment&layout=edit&id='.(int)$data['id']);
        return false;
    }



}
