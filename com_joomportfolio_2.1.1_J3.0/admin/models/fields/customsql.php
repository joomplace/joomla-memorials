<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('JPATH_BASE') or die;

/**
 * Supports a modal article picker.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       3.1
 */
class JFormFieldCustomsql extends JFormField
{
    /**
     * The form field type.
     *
     * @var        string
     * @since   1.6
     */
    protected $type = 'Customsql';

    /**
     * Method to get the field input markup.
     *
     * @return  string    The field input markup.
     * @since   1.6
     */
    protected function getInput()
    {
        require_once(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomportfolio.php');
        $db = JFactory::getDbo();
        $input = JFactory::getApplication()->input;
        $entity = $input->get('view', '');
        $id = $input->get('id', 'INT', 0);
        $mode = JoomPortfolioHelper::getMode();

        $str = '<select name="' . $this->name . '" >';
        $query = $db->getQuery(true);
        $query->select('id, title')
            ->from('`#__categories`')
            ->where('extension="com_' . $mode . '"');
        $db->setQuery($query);

        $cats = $db->loadObjectList();
        $count = count($cats);

        $cur_cat = 0;
        if ($id) {
            $cur_cat = $this->getRelativeId('item', $id);
        }
        for ($i = 0; $i < $count; $i++) {
            $str .= '<option value="' . $cats[$i]->id.'"';
            if ((int)$cur_cat == $cats[$i]->id) {
                $str .= ' selected="selected"';
            }
            $str .= '>' . $cats[$i]->title . '</option>';
        }
        $str .= '</select>';

        return $str;
    }

    protected function getRelativeId($entity, $id)
    {
        $db = JFactory::getDbo();
        switch ($entity) {
            case 'item':
                $query = $db->getQuery(true);
                $query->select('cat_id')
                    ->from('`#__jp3_items`')
                    ->where('id=' . (int)$id);
                $db->setQuery($query);
                return $db->loadResult();
                break;
        }

    }
}
