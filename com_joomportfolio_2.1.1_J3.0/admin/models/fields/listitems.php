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
class JFormFieldListitems extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Listitems';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   1.6
	 */
	protected function getInput()
	{
            ob_start();
            ?>
                    <select id="<?php echo $this->id;?>" name="<?php echo $this->name;?>">
                   <option value="1" >New York</option>
                   <option value="2" >Chicago</option>
                   <option value="3" >San Francisco</option>
                   </select>
              <?php      
              $content = @ob_get_contents();
        @ob_end_clean();
        return $content;
	}
}
