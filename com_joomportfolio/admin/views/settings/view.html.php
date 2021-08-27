<?php

/**
 * JoomPortfolio component for Joomla 3
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JoomPortfolioViewSettings extends JViewLegacy
{

    protected $form = null;

    public function display($tpl = null)
    {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');


        $this->state = $this->get('State');

        $this->item = $this->get('Item');

        $this->form = $this->get('Form');

        $errors = $this->get('Errors');
        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors), 500);
        }

        $this->twitterLanguageOptions = array(
            JHTML::_('select.option', 'en', 'English'),
            JHTML::_('select.option', 'ar', 'Arabic'),
            JHTML::_('select.option', 'eu', 'Basque'),
            JHTML::_('select.option', 'ca', 'Catalan'),
            JHTML::_('select.option', 'cs', 'Czech'),
            JHTML::_('select.option', 'zh-cn', 'Simplified Chinese'),
            JHTML::_('select.option', 'zh-tw', 'Traditional Chinese'),
            JHTML::_('select.option', 'da', 'Danish'),
            JHTML::_('select.option', 'nl', 'Dutch'),
            JHTML::_('select.option', 'fa', 'Farsi'),
            JHTML::_('select.option', 'fil', 'Filipino'),
            JHTML::_('select.option', 'fi', 'Finnish'),
            JHTML::_('select.option', 'fr', 'French'),
            JHTML::_('select.option', 'de', 'German'),
            JHTML::_('select.option', 'el', 'Greek'),
            JHTML::_('select.option', 'he', 'Hebrew'),
            JHTML::_('select.option', 'hi', 'Hindi'),
            JHTML::_('select.option', 'hu', 'Hungarian'),
            JHTML::_('select.option', 'id', 'Indonesian'),
            JHTML::_('select.option', 'it', 'Italian'),
            JHTML::_('select.option', 'ja', 'Japanese'),
            JHTML::_('select.option', 'ko', 'Korean'),
            JHTML::_('select.option', 'msa', 'Malay'),
            JHTML::_('select.option', 'no', 'Norwegian'),
            JHTML::_('select.option', 'pl', 'Polish'),
            JHTML::_('select.option', 'pt', 'Portuguese'),
            JHTML::_('select.option', 'ru', 'Russian'),
            JHTML::_('select.option', 'es', 'Spanish'),
            JHTML::_('select.option', 'sv', 'Swedish'),
            JHTML::_('select.option', 'th', 'Thai'),
            JHTML::_('select.option', 'tr', 'Turkish'),
            JHTML::_('select.option', 'uk', 'Ukrainian'),
            JHTML::_('select.option', 'ur', 'Urdu'),
        );

        $this->facebookFontOptions = array(
            JHTML::_('select.option', 'arial', 'arial'),
            JHTML::_('select.option', 'lucida grande', 'lucida grande'),
            JHTML::_('select.option', 'segoe ui', 'segoe ui'),
            JHTML::_('select.option', 'tahoma', 'tahoma'),
            JHTML::_('select.option', 'trebuchet ms', 'trebuchet ms'),
            JHTML::_('select.option', 'verdana', 'verdana'),
        );


        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar()
    {
        JoomPortfolioHelper::loadLanguage();
        JToolBarHelper::title(JText::_('COM_JOOMPORTFOLIO_CONFIGURATION'));
        JToolBarHelper::apply('settings.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::divider();
        JToolBarHelper::help('JHELP_COMPONENTS_WEBLINKS_LINKS_EDIT');
        JToolBarHelper::cancel('settings.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument()
    {

        $doc = $this->document;
        $doc->addScript('components/com_joomportfolio/assets/js/BootstrapFormHelper.js');
        $doc->addScript('components/com_joomportfolio/assets/js/fields.js');
    }

}

?>