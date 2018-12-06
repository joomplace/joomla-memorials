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
class JFormFieldListdate extends JFormField
{
    /**
     * The form field type.
     *
     * @var        string
     * @since   1.6
     */
    protected $type = 'Listdate';

    /**
     * Method to get the field input markup.
     *
     * @return  string    The field input markup.
     * @since   1.6
     */
    protected function getInput()
    {
        
        $str='<select id="jform_field_format" name="'.$this->name.'">';
        $str .= '<option value="l, d F Y" ';
            if($this->value=="l, d F Y"){
                $str .= ' selected="selected" ';
            }
        $str .='>'.JHTML::_("date",'' , "l, d F Y", NULL).'</option>';
        $str .= '<option value="d F Y" ';
        if($this->value=="d F Y"){
            $str .= ' selected="selected" ';
        }
        $str.='>'.JHTML::_("date",'' , "d F Y", NULL).'</option>';
        $str .= '<option value="l, d F Y H:i"';
        if($this->value=="l, d F Y H:i"){
            $str .= ' selected="selected" ';
        }
        $str.=' >'.JHTML::_("date",'' , "l, d F Y H:i", NULL).'</option>';
        $str .= '<option value="d F Y" ';
        if($this->value=="d F Y"){
            $str .= ' selected="selected" ';
        }
        $str.='>'.JHTML::_("date",'' , "d F Y", NULL).'</option>';
        $str .= '<option value="Y-m-d" ';
        if($this->value=="Y-m-d"){
            $str .= ' selected="selected" ';
        }
        $str.='>'.JHTML::_("date",'' , "Y-m-d", NULL).'</option>';
        $str .= '<option value="y-m-d" ';
        if($this->value=="y-m-d"){
            $str .= ' selected="selected" ';
        }
        $str.='>'.JHTML::_("date",'' , "y-m-d", NULL).'</option>';
        $str .= '</select>';
        return $str;
    }


}
