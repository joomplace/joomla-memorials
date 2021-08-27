<?php

/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of JoomPortfolio component
 */
class JoomPortfolioController extends JControllerLegacy {

    /**
     * display task
     *
     * @return void
     */
    function display($cachable = false, $urlparams = array()) {
        $document = JFactory::getDocument();
        //$document->addStyleSheet('components/com_joomportfolio/assets/css/joomportfolio.css');
        // set default view if not set
        $input = JFactory::getApplication()->input;
        $viewName = $input->get('view', 'about');

        $this->default_view = $viewName;
        // set submenu
        require_once dirname(__FILE__) . '/helpers/joomportfolio.php';
        JoomPortfolioHelper::addSubmenu($viewName);

        // call parent behavior
        parent::display($cachable);
    }

    public function latestNews() {
        require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/Snoopy.class.php' );

        $s = new Snoopy();
        $s->read_timeout = 10;
        $s->referer = JURI::root();
        @$s->fetch('http://www.joomplace.com/news_check/componentNewsCheck.php?component=joomportfolio');
        $news_info = $s->results;

        if ($s->error || $s->status != 200) {
            echo '<font color="red">Connection to update server failed: ERROR: ' . $s->error . ($s->status == -100 ? 'Timeout' : $s->status) . '</font>';
        } else {
            echo $news_info;
        }
        exit();
    }

    public function history() {
        echo '<h2>' . JText::_('COM_JOOMPORTFOLIO_VERSION_HISTORY') . '</h2><br/>';
        jimport('joomla.filesystem.file');
        if (!JFile::exists(JPATH_COMPONENT_ADMINISTRATOR . '/changelog.txt')) {
            echo 'History file not found.';
        } else {
            echo '<textarea class="editor" rows="30" cols="50" style="width:100%">';
            echo file_get_contents(JPATH_COMPONENT_ADMINISTRATOR . '/changelog.txt');
            echo '</textarea>';
        }
        exit();
    }

    public function installSampleData() {
        $link = 'index.php?option=com_joomportfolio&view=items';
        $model = $this->getModel('sampledata');

        if (!$model->makeInstall()) {
            $this->setRedirect($link, implode(',', $model->getErrors()), 'error');
            return;
        }

        $this->setRedirect($link, JText::_('COM_JOOMPORTFOLIO_SAMPLEDATA_INSTALL_SUCCESS'), 'msg');
    }

    public function importData() {
        $link = 'index.php?option=com_joomportfolio&view=items';
        $model = $this->getModel('importdata');

        if (!$model->makeImportData()) {
            $this->setRedirect($link, implode(',', $model->getErrors()), 'error');
            return;
        }

        $this->setRedirect($link, JText::_('COM_JOOMPORTFOLIO_IMPORTDATA_SUCCESS'), 'msg');
    }

    public function imagesImport() {
        $link = 'index.php?option=com_joomportfolio&view=items';
        $model = $this->getModel('imagesimport');

        if (!$model->makeImagesImport()) {
            $this->setRedirect($link, implode(', ', $model->getErrors()), 'error');
        } else {
            $this->setRedirect($link, JText::_('COM_JOOMPORTFOLIO_IMPORTDATA_SUCCESS'), 'msg');
        }
    }

    public function mode() {
        $elname = JFactory::getApplication()->input->get('elname');
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->update('#__extensions');
        $query->set('custom_data ="' . $db->escape($elname) . '"');
        $query->where('element="com_joomportfolio"');
        $db->setQuery($query);
        if ($db->execute()) {
            echo json_encode($elname);
        }
    }

}
