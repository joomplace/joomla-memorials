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

class JoomPortfolioModelField extends JModelAdmin
{

    protected $context = 'com_joomportfolio';
    protected $attrs = array('name', 'label', 'type', 'def', 'required');

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm($this->context . '.' . $this->getName(), $this->getName(), array('control' => 'jform', 'load_data' => false));

        $item = $this->getItem();
        $item->required = (int)$item->req == 1 ? 1 : 0;

        $form->bind($item);

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function getTable($type = 'Field', $prefix = 'JoomPortfolioTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function save($data)
    {
        $db = JFactory::getDBO();

        $mode = JoomPortfolioHelper::getMode();
        if (!(int)$data['id']) {
            $db->setQuery("INSERT INTO `#__jp3_field` (`name` ,`label` ,`type` ,`def` ,`req` ,`catview`, `mode`,`format`)
			VALUES ( '" . $db->escape(str_replace(' ','_',$data['name'])) . "', '" . $db->escape($data['label']) . "', '" . $data['type'] . "', '" . $db->escape($data['default']) . "', " . $data['required'] . ", " . $data['catview'] . ", '" . $mode . "','".$data['format']."');");
            try {
                $db->execute();
            } catch (RuntimeException $e) {
                $this->setError($e->getMessage());
                return false;
            }

            $db->setQuery("SELECT id FROM `#__jp3_items` WHERE mode='" . $mode . "'");
            $item_ids = $db->loadObjectList();
            $field_id = $this->lastId();
            for ($i = 0; $i < count($item_ids); $i++) {
                $db->setQuery("INSERT INTO `#__jp3_item_content` (`field_id` ,`item_id` ,`value`)
			VALUES ( " . (int)$field_id . ", " . (int)$item_ids[$i]->id . ", '');");
                if (!$db->execute()) {
                    return false;
                }
            }
            return true;
        } else {
            $query = $db->getQuery(true);
            $query->update('#__jp3_field');
            $query->set('name ="' . $db->escape($data['name']) . '", label="' . $db->escape($data['label']) . '", type="' . $db->escape($data['type']) . '", def="' . $db->escape($data['default']) . '", mode="' . $db->escape($mode) . '", req=' . (int)$data['required'] . ', catview=' . (int)$data['catview'].',format="' . $db->escape($data['format']) . '"');
            $query->where('id=' . (int)$data['id']);
            $db->setQuery($query);
            if (!$db->execute()) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function lastId()
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT MAX(id) FROM `#__jp3_field`");
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
        $db = JFactory::getDBO();

        if (count($pks)) {
            $query = $db->getQuery(true);
            $query->delete('#__jp3_field');
            $query->where('id IN (' . implode(',', $pks) . ')');
            $db->setQuery($query);
            try {
                $db->execute();
            } catch (RuntimeException $e) {
                $this->setError($e->getMessage());
                return false;
            }
            $query = $db->getQuery(true);
            $query->delete('#__jp3_item_content');
            $query->where('field_id IN (' . implode(',', $pks) . ')');
            $db->setQuery($query);
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

    public function check($data)
    {
        if (count($data)) {

            if ($data['name'] == '' || $data['label'] == '') {
                if ($data['name'] == '') {
                    JError::raiseWarning(404,JText::_('COM_JOOMPORTFOLIO_ERROR_MACHIN_NAME'), 'Warning' );

                }
                if ($data['label'] == '') {
                    JError::raiseWarning(404,JText::_('COM_JOOMPORTFOLIO_ERROR_LABEL'), 'Warning' );
                }
                JFactory::getApplication()->redirect('index.php?option=com_joomportfolio&view=field&layout=edit&id='.(int)$data['id']);
                return false;
            }

            return true;
        }

        JFactory::getApplication()->redirect('index.php?option=com_joomportfolio&view=field&layout=edit&id='.(int)$data['id']);
        return false;
    }


}
