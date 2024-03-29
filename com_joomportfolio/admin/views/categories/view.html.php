<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

/**
 * Categories view class for the Category package.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class JoomPortfolioViewCategories extends JViewLegacy
{
    protected $items;

    protected $pagination;

    protected $state;

    protected $assoc;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->assoc = $this->get('Assoc');
        $this->filterForm = $this->get('FilterForm');

        // Check for errors.
        $errors = $this->get('Errors');
        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors), 500);
        }

        // Preprocess the list of items to find ordering divisions.
        foreach ($this->items as &$item) {
            $this->ordering[$item->parent_id][] = $item->id;
        }

        // Levels filter.
        $options = array();
        $options[] = JHtml::_('select.option', '1', JText::_('J1'));
        $options[] = JHtml::_('select.option', '2', JText::_('J2'));
        $options[] = JHtml::_('select.option', '3', JText::_('J3'));
        $options[] = JHtml::_('select.option', '4', JText::_('J4'));
        $options[] = JHtml::_('select.option', '5', JText::_('J5'));
        $options[] = JHtml::_('select.option', '6', JText::_('J6'));
        $options[] = JHtml::_('select.option', '7', JText::_('J7'));
        $options[] = JHtml::_('select.option', '8', JText::_('J8'));
        $options[] = JHtml::_('select.option', '9', JText::_('J9'));
        $options[] = JHtml::_('select.option', '10', JText::_('J10'));

        $this->f_levels = $options;

        $this->addToolbar();
        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        $categoryId = $this->state->get('filter.category_id');
        $component = $this->state->get('filter.component');
        $section = $this->state->get('filter.section');
        $canDo = null;
        $user = JFactory::getUser();
        $extension = JFactory::getApplication()->input->get('extension', '', 'word');

        // Get the toolbar object instance
        $bar = JToolBar::getInstance('toolbar');

        // Avoid nonsense situation.
        if ($component == 'com_categories') {
            return;
        }

        // Need to load the menu language file as mod_menu hasn't been loaded yet.
        $lang = JFactory::getLanguage();
        $lang->load($component, JPATH_BASE, null, false, false)
            || $lang->load($component, JPATH_ADMINISTRATOR . '/components/' . $component, null, false, false)
            || $lang->load($component, JPATH_BASE, $lang->getDefault(), false, false)
            || $lang->load($component, JPATH_ADMINISTRATOR . '/components/' . $component, $lang->getDefault(), false, false);

        // Load the category helper.
        require_once JPATH_COMPONENT . '/helpers/joomportfolio.php';

        // Get the results for each action.
        $canDo = JoomPortfolioHelper::getActions($component, $categoryId);

        // If a component categories title string is present, let's use it.
        if ($lang->hasKey($component_title_key = strtoupper($component . ($section ? "_$section" : '')) . '_CATEGORIES_TITLE')) {
            $title = JText::_($component_title_key);
        } // Else if the component section string exits, let's use it
        elseif ($lang->hasKey($component_section_key = strtoupper($component . ($section ? "_$section" : '')))) {
            $title = JText::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', $this->escape(JText::_($component_section_key)));
        } // Else use the base title
        else {
            $title = JText::_('COM_CATEGORIES_CATEGORIES_BASE_TITLE');
        }

        // Load specific css component
        JHtml::_('stylesheet', $component . '/administrator/categories.css', array(), true);

        // Prepare the toolbar.
        JToolbarHelper::title($title, 'categories ' . substr($component, 4) . ($section ? "-$section" : '') . '-categories');

        if ($canDo->get('core.create') || (count($user->getAuthorisedCategories($component, 'core.create'))) > 0) {
            JToolbarHelper::addNew('category.add');
        }

        if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
            JToolbarHelper::editList('category.edit');
        }


        if ($canDo->get('core.delete', $component)) {
            JToolBarHelper::deleteList('', 'category.delete', 'JTOOLBAR_DELETE');
        }


        // Compute the ref_key if it does exist in the component
        if (!$lang->hasKey($ref_key = strtoupper($component . ($section ? "_$section" : '')) . '_CATEGORIES_HELP_KEY')) {
            $ref_key = 'JHELP_COMPONENTS_' . strtoupper(substr($component, 4) . ($section ? "_$section" : '')) . '_CATEGORIES';
        }

        // Get help for the categories view for the component by
        // -remotely searching in a language defined dedicated URL: *component*_HELP_URL
        // -locally  searching in a component help file if helpURL param exists in the component and is set to ''
        // -remotely searching in a component URL if helpURL param exists in the component and is NOT set to ''
        if ($lang->hasKey($lang_help_url = strtoupper($component) . '_HELP_URL')) {
            $debug = $lang->setDebug(false);
            $url = JText::_($lang_help_url);
            $lang->setDebug($debug);
        } else {
            $url = null;
        }
        JToolbarHelper::help($ref_key, JComponentHelper::getParams($component)->exists('helpURL'), $url);

        JHtmlSidebar::setAction('index.php?option=com_categories&view=categories');

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_MAX_LEVELS'),
            'filter_level',
            JHtml::_('select.options', $this->f_levels, 'value', 'text', $this->state->get('filter.level'))
        );

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'filter_published',
            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
        );

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_ACCESS'),
            'filter_access',
            JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
        );

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_LANGUAGE'),
            'filter_language',
            JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
        );

        if (JHelperTags::getTypes('objectList', array($extension . '.category'), true)) {
            JHtmlSidebar::addFilter(
                JText::_('JOPTION_SELECT_TAG'),
                'filter_tag',
                JHtml::_('select.options', JHtml::_('tag.options', true, true), 'value', 'text', $this->state->get('filter.tag'))
            );
        }

    }

    /**
     * Returns an array of fields the table can be sorted by
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value
     *
     * @since   3.0
     */
    protected function getSortFields()
    {
        return array(
            'a.lft' => JText::_('JGRID_HEADING_ORDERING'),
            'a.published' => JText::_('JSTATUS'),
            'a.title' => JText::_('JGLOBAL_TITLE'),
            'a.access' => JText::_('JGRID_HEADING_ACCESS'),
            'language' => JText::_('JGRID_HEADING_LANGUAGE'),
            'a.id' => JText::_('JGRID_HEADING_ID')
        );
    }
}
