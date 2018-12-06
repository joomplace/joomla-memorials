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
class JFormFieldCustomsqlcat extends JFormField
{
    /**
     * The form field type.
     *
     * @var        string
     * @since   1.6
     */
    protected $type = 'Customsqlcat';

    /**
     * Method to get the field input markup.
     *
     * @return  string    The field input markup.
     * @since   1.6
     */
    protected function getInput()
    {
        require_once(JPATH_BASE.DIRECTORY_SEPARATOR.'components'. DIRECTORY_SEPARATOR .'com_joomportfolio'. DIRECTORY_SEPARATOR. 'helpers' . DIRECTORY_SEPARATOR . 'joomportfolio.php');
        $db = JFactory::getDbo();
        $input = JFactory::getApplication()->input;
        $id=$input->get('id', 0, 'INT');
        $entity='memorials';
        $cur_cat=0;
        if($id){
           $params=$this->getRelativeId($id);
                $settings=json_decode($params);
                $cur_cat=(int)$settings->id;
                $mode=$this->getModeName($cur_cat);
            $query = $db->getQuery(true);
            $query->select('id, title')
                ->from('`#__categories`')
                ->where('extension="' . $mode . '"')
                ->where('published=1');
            $db->setQuery($query);
            $cats = $db->loadObjectList();

        }else{
            $query = $db->getQuery(true);
            $query->select('id, title')
                ->from('`#__categories`')
                ->where('extension="com_' . $entity . '"')
                ->where('published=1');
            $db->setQuery($query);
            $cats = $db->loadObjectList();
        }


        $str = '<select id="select_cat" name="' . $this->name . '" >';



        $count = count($cats);
        if(!$count){
        $str='<select id="select_cat" name="jform[params][id]"></select>';
        }
        for ($i = 0; $i < $count; $i++) {
            $str .= '<option value="' . $cats[$i]->id.'"';
            if ((int)$cur_cat == $cats[$i]->id) {
                $str .= ' selected="selected" ';
            }
            $str .= '>' . $cats[$i]->title . '</option>';
        }
        $str .= '</select>';

        return $str;
    }

    protected function getRelativeId( $id)
    {
        $db = JFactory::getDbo();

                $query = $db->getQuery(true);
                $query->select('params')
                    ->from('`#__menu`')
                    ->where('id=' . (int)$id);
                $db->setQuery($query);
                return $db->loadResult();
    }

    protected function getModeName($id){
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('extension')
            ->from('`#__categories`')
            ->where('id=' . (int)$id);
        $db->setQuery($query);
        return $db->loadResult();
    }


}
