<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('JPATH_BASE') or die;

use Joomla\Utilities\ArrayHelper;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class JFormFieldCategoryEdit extends JFormFieldList
{
	/**
	 * A flexible category list that respects access controls
	 *
	 * @var        string
	 * @since   1.6
	 */
	public $type = 'CategoryEdit';

	/**
	 * Method to get a list of categories that respects access controls and can be used for
	 * either category assignment or parent category assignment in edit screens.
	 * Use the parent element to indicate that the field will be used for assigning parent categories.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
		$options = array();
		$published = $this->element['published'] ? $this->element['published'] : array(0, 1);
		$name = (string) $this->element['name'];
                $oldCat='';
		// Let's get the id for the current item, either category or content item.
		$jinput = JFactory::getApplication()->input;
		// Load the category options for a given extension.

		// For categories the old category is the category id or 0 for new category.
		/*if ($this->element['parent'] || $jinput->get('option') == 'com_categories')
		{
			$oldCat = $jinput->get('id', 0);
			$oldParent = $this->form->getValue($name, 0);
			$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $jinput->get('extension', 'com_content');
		}
		else
			// For items the old category is the category they are in when opened or 0 if new.
		{
			$oldCat = $this->form->getValue($name, 0);
			$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $jinput->get('option', 'com_content');
		}*/
                $mode = JoomPortfolioHelper::getMode();/*JFactory::getApplication()->input->cookie->get('name');*/
                $extension='com_'.$mode;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.title AS text, a.level, a.published')
			->from('#__categories AS a')
			->join('LEFT', $db->quoteName('#__categories') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

		// Filter by the extension type
		if ($this->element['parent'] == true || $jinput->get('option') == 'com_categories')
		{
			$query->where('(a.extension = ' . $db->quote($extension) . ' OR a.parent_id = 0 OR a.id=1)');
		}
		else
		{
			$query->where('(a.extension = ' . $db->quote($extension) . ' OR a.id=1)');
		}
		// If parent isn't explicitly stated but we are in com_categories assume we want parents
		if ($oldCat != 0 && ($this->element['parent'] == true || $jinput->get('option') == 'com_categories'))
		{
			// Prevent parenting to children of this item.
			// To rearrange parents and children move the children up, not the parents down.
			$query->join('LEFT', $db->quoteName('#__categories') . ' AS p ON p.id = ' . (int) $oldCat)
				->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');

			$rowQuery = $db->getQuery(true);
			$rowQuery->select('a.id AS value, a.title AS text, a.level, a.parent_id')
				->from('#__categories AS a')
				->where('a.id = ' . (int) $oldCat.' OR a.id=2');
			$db->setQuery($rowQuery);
			$row = $db->loadObject();
		}

		// Filter language
		if (!empty($this->element['language']))
		{

			$query->where('a.language = ' . $db->quote($this->element['language']));
		}

		// Filter on the published state

		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif (is_array($published))
		{
            $published = ArrayHelper::toInteger($published);
			$query->where('a.published IN (' . implode(',', $published) . ')');
		}

		$query->group('a.id, a.title, a.level, a.lft, a.rgt, a.extension, a.parent_id, a.published')
			->order('a.lft ASC');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
            throw new Exception($e->getMessage(), 500, $e);
		}

		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			// Translate ROOT
			if ($this->element['parent'] == true || $jinput->get('option') == 'com_categories')
			{
				if ($options[$i]->level == 0)
				{
					$options[$i]->text = JText::_('JGLOBAL_ROOT_PARENT');
				}
			}
			if ($options[$i]->published == 1)
			{
				$options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
			}
			else
			{
				$options[$i]->text = str_repeat('- ', $options[$i]->level) . '[' . $options[$i]->text . ']';
			}
		}

		// Get the current user object.
		$user = JFactory::getUser();

		// For new items we want a list of categories you are allowed to create in.
		if ($oldCat == 0)
		{
			foreach ($options as $i => $option)
			{
				// To take save or create in a category you need to have create rights for that category
				// unless the item is already in that category.
				// Unset the option if the user isn't authorised for it. In this field assets are always categories.
				if ($user->authorise('core.create', $extension . '.category.' . $option->value) != true)
				{
					unset($options[$i]);
				}
			}
		}
		// If you have an existing category id things are more complex.
		else
		{
			// If you are only allowed to edit in this category but not edit.state, you should not get any
			// option to change the category parent for a category or the category for a content item,
			// but you should be able to save in that category.
			foreach ($options as $i => $option)
			{
				if ($user->authorise('core.edit.state', $extension . '.category.' . $oldCat) != true && !isset($oldParent))
				{
					if ($option->value != $oldCat)
					{
						unset($options[$i]);
					}
				}
				if ($user->authorise('core.edit.state', $extension . '.category.' . $oldCat) != true
					&& (isset($oldParent))
					&& $option->value != $oldParent
				)
				{
					unset($options[$i]);
				}

				// However, if you can edit.state you can also move this to another category for which you have
				// create permission and you should also still be able to save in the current category.
				if (($user->authorise('core.create', $extension . '.category.' . $option->value) != true)
					&& ($option->value != $oldCat && !isset($oldParent))
				)
				{
					{
						unset($options[$i]);
					}
				}
				if (($user->authorise('core.create', $extension . '.category.' . $option->value) != true)
					&& (isset($oldParent))
					&& $option->value != $oldParent
				)
				{
					{
						unset($options[$i]);
					}
				}
			}
		}
		if (($this->element['parent'] == true || $jinput->get('option') == 'com_categories')
			&& (isset($row) && !isset($options[0]))
			&& isset($this->element['show_root'])
		)
		{
			if ($row->parent_id == '1')
			{
				$parent = new stdClass;
				$parent->text = JText::_('JGLOBAL_ROOT_PARENT');
				array_unshift($options, $parent);
			}
			array_unshift($options, JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT')));
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
