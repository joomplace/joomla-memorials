<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

class JoomPortfolioViewCategory extends JViewLegacy
{

    protected $form;
    protected $item;
    protected $state;
    protected $assoc;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        $this->canDo = JoomPortfolioHelper::getActions($this->state->get('category.component'));
        $this->assoc = $this->get('Assoc');

        $input = JFactory::getApplication()->input;

        // Check for errors.
        $errors = $this->get('Errors');
        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors), 500);
        }

        // Check for tag type
        $this->checkTags = JHelperTags::getTypes('objectList', array($this->state->get('category.extension') . '.category'), true);

        $input->set('hidemainmenu', true);

        if ($this->getLayout() == 'modal') {
            $this->form->setFieldAttribute('language', 'readonly', 'true');
            $this->form->setFieldAttribute('parent_id', 'readonly', 'true');
        }
        JoomPortfolioHelper::loadLanguage();
        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {

        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        $isNew = !isset($this->item->name);
        $canDo = JoomPortfolioHelper::getAllActions();
        JToolBarHelper::title($isNew ? JText::_('COM_CATEGORIES_CATEGORIES_TITLE') : JText::_('COM_CATEGORIES_CATEGORIES_TITLE'), 'categories');
        if ($canDo->get('core.admin')) {
            JToolBarHelper::apply('category.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('category.save', 'JTOOLBAR_SAVE');
            JToolBarHelper::custom('category.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            if (!$isNew) {
                JToolBarHelper::custom('category.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            }
            JToolBarHelper::cancel('category.cancel', 'JTOOLBAR_CLOSE');
        }
    }

}
