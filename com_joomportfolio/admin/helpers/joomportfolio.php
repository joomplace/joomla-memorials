<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

class JoomPortfolioHelper {


    public static function addSubmenu($submenu = 'comparisonchart') {

        if ($submenu == 'categories') {

            JoomPortfolioHelper::addManagementSubmenu('categories');
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('COM_JOOMPORTFOLIO_SUBMENU_CATEGORIES'));
            $controller = JControllerLegacy::getInstance('Categories');
            $view = $controller->getView('categories', 'html');
            $view->addTemplatePath(JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
            $view->setLayout('categories');
        }
    }

    public static function addManagementSubmenu($vName) {

        self::loadLanguage();

        JHtmlSidebar::addEntry('<i class="icon-list-view"></i> '.
                JText::_('COM_JOOMPORTFOLIO_SUBMENU_CATEGORIES'), 'index.php?option=com_joomportfolio&view=categories', $vName == 'categories'
        );
        JHtmlSidebar::addEntry('<i class="icon-tablet"></i> '.
            JText::_('COM_JOOMPORTFOLIO_SUBMENU_ITEMS'), 'index.php?option=com_joomportfolio&view=items', $vName == 'items');

        JHtmlSidebar::addEntry('<i class="icon-pencil"></i> '.
                JText::_('COM_JOOMPORTFOLIO_SUBMENU_FIELDS'), 'index.php?option=com_joomportfolio&view=fields', $vName == 'fields');

        JHtmlSidebar::addEntry('<i class="icon-comments-2"></i> '.
            JText::_('COM_JOOMPORTFOLIO_SUBMENU_COMMENTS'), 'index.php?option=com_joomportfolio&view=comments', $vName == 'comments');

        JHtmlSidebar::addEntry('<i class="icon-pictures"></i> '.
            JText::_('COM_JOOMPORTFOLIO_SUBMENU_TEMPLATES'), 'index.php?option=com_joomportfolio&view=templates', $vName == 'templates');

        if(self::getMode()=='memorials'){
            JHtmlSidebar::addEntry('<i class="icon-pictures"></i> '.
                JText::_('COM_JOOMPORTFOLIO_CONDOLE_SUBMENU'), 'index.php?option=com_joomportfolio&view=images', $vName == 'images');
        }
    }

    public static function showTitle($submenu) {
        $document = JFactory::getDocument();
        $title = JText::_('COM_JOOMPORTFOLIO_ADMINISTRATION_' . strtoupper($submenu));
        $document->setTitle($title);
        JToolBarHelper::title($title, $submenu);
    }

    public static function getCSSJS() {
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'administrator/components/com_joomportfolio/assets/css/joomportfolio.css');
    }

    public static function getPublishOptions()
    {
        // Build the filter options.
        $options	= array();
        $options[]	= JHtml::_('select.option',	'1',	JText::_('JPUBLISHED'));
        $options[]	= JHtml::_('select.option',	'0',	JText::_('JUNPUBLISHED'));
        return $options;
    }

    public static function getLeftMenu() {
        jimport('joomla.html.html.bootstrap');
        JHtml::_('bootstrap.framework');
        $view = JFactory::getApplication()->input->getCmd('view');
        $task = JFactory::getApplication()->input->getCmd('task');
        $task = $view ? $view : $task;
        switch ($task) {
            case 'categories': case 'category':
            case 'items': case 'item':
            case 'fields': case 'field':
                $my_params = array('active' => 2);
                break;
            case 'settings':
            case 'import':
            case 'imagesimport':
                $my_params = array('active' => 3);
                break;
            case 'sampledata':
                $my_params = array('active' => 4);
                break;
            case 'help':
                $my_params = array('active' => 5);
                break;
            default:
                $my_params = array('active' => 1);
                break;
        }

        $menu = '';
        //$menu .= '<div style="text-align:center"><img src="'.JURI::root().'administrator/components/com_joomportfolio/assets/images/EF_logo.jpg" alt="JoomPlace.com" title="" border="0" style="margin: 0 auto;" /><h5 class="h3tcomname">'.JText::_('COM_JOOMPORTFOLIO').' '.JoomPortfolioHelper::getVersion().'</h5></div>';
        $menu .= JHtml::_('bootstrap.startAccordion', 'tm_sidebar_accordion', $my_params);
        $menu .= JHtml::_('bootstrap.addSlide', 'tm_sidebar_accordion', JText::_('COM_JOOMPORTFOLIO_MENU_ABOUT'), 1);
        $menu .= '
                    <ul class="nav nav-list">
                        <li' . ($task == 'about' || $task == 'About' ? ' class="active"' : '') . '>
                            <a href="index.php?option=com_joomportfolio&view=about">' . JText::_('COM_JOOMPORTFOLIO_MENU_ITEM_ABOUT') . '</a>
                        </li>
                    </ul>';
        $menu .= JHtml::_('bootstrap.endSlide');
        $menu .= JHtml::_('bootstrap.addSlide', 'tm_sidebar_accordion', JText::_('COM_JOOMPORTFOLIO_MENU_MANAGEMENT'), 2);
        $menu .= '
                    <ul class="nav nav-list">
                        <li' . ($task == 'categories' || $task == 'category' ? ' class="active"' : '') . '>
                            <a href="index.php?option=com_categories&extension=com_joomportfolio&view=categories">' . JText::_('COM_JOOMPORTFOLIO_SUBMENU_CATEGORIES') . '</a>
                        </li>
                        <li' . ($task == 'items' || $task == 'item' ? ' class="active"' : '') . '>
                            <a href="index.php?option=com_joomportfolio&view=items">' . JText::_('COM_JOOMPORTFOLIO_SUBMENU_ITEMS') . '</a>
                        </li>
                        <li' . ($task == 'fields' || $task == 'field' ? ' class="active"' : '') . '>
                            <a href="index.php?option=com_joomportfolio&view=fields">' . JText::_('COM_JOOMPORTFOLIO_SUBMENU_FIELDS') . '</a>
                        </li>
                    </ul>';
        $menu .= JHtml::_('bootstrap.endSlide');
        $menu .= JHtml::_('bootstrap.addSlide', 'tm_sidebar_accordion', JText::_('COM_JOOMPORTFOLIO_MENU_SETTINGS'), 3);
        $menu .= '
                    <ul class="nav nav-list">
                        <li>
                            <a href="index.php?option=com_config&view=component&component=com_joomportfolio" rel="{handler: \'iframe\', size: {x: 875, y: 550}, onClose: function() {}}">' . JText::_('COM_JOOMPORTFOLIO_MENU_ITEM_SETTINGS') . '</a>
                        </li>
                        <li>
                            <a href="index.php?option=com_joomportfolio&view=import">' . JText::_('COM_JOOMPORTFOLIO_SUBMENU_IMPORT') . '</a>
                        </li>
                    </ul>';
        //<li>
        //    <a href="index.php?option=com_joomportfolio&view=imagesimport">'.JText::_('COM_JOOMPORTFOLIO_SUBMENU_IMPORT_IMAGES').'</a>
        //</li>
        //</ul>';
        $menu .= JHtml::_('bootstrap.endSlide');
        $menu .= JHtml::_('bootstrap.addSlide', 'tm_sidebar_accordion', JText::_('COM_JOOMPORTFOLIO_MENU_SAMPLEDATA'), 4);
        $menu .= '
                    <ul class="nav nav-list">
                        <li>
                            <a href="index.php?option=com_joomportfolio&view=sampledata">' . JText::_('COM_JOOMPORTFOLIO_MENU_ITEM_SAMPLEDATA') . '</a>
                        </li>
                    </ul>';
        $menu .= JHtml::_('bootstrap.endSlide');
        $menu .= JHtml::_('bootstrap.addSlide', 'tm_sidebar_accordion', JText::_('COM_JOOMPORTFOLIO_SUBMENU_HELP'), 5);
        $menu .= '
                    <ul class="nav nav-list">
                        <li' . ($task == 'help' ? ' class="active"' : '') . '>
                            <a target="_blank" href="index.php?option=com_joomportfolio&view=help">' . JText::_('COM_JOOMPORTFOLIO_SUBMENU_HELP') . '</a>
                        </li>
                        <li>
                            <a target="_blank" href="http://www.joomplace.com/forum-joomportfolio.html">' . JText::_('COM_JOOMPORTFOLIO_ADMINISTRATION_SUPPORT_FORUM') . '</a>
                        </li>
                        <li>
                            <a target="_blank" href="http://www.joomplace.com/support/helpdesk">' . JText::_('COM_JOOMPORTFOLIO_ADMINISTRATION_SUPPORT_DESC') . '</a>
                        </li>
                        <li>
                            <a target="_blank" href="http://www.joomplace.com/support/helpdesk/department/presale-questions">' . JText::_('COM_JOOMPORTFOLIO_ADMINISTRATION_SUPPORT_REQUEST') . '</a>
                        </li>
                    </ul>';
        $menu .= JHtml::_('bootstrap.endSlide');
        $menu .= JHtml::_('bootstrap.endAccordion');
        return $menu;
    }

    public static function getAllActions($itemID = 0) {
        $user = JFactory::getUser();
        $result = new JObject;

        if (empty($itemID)) {
            $assetName = 'com_joomportfolio';
        } else {
            $assetName = 'com_joomportfolio.item.' . (int) $itemID;
        }

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }

    public static function getActions($extension, $categoryId = 0) {
        $user = JFactory::getUser();
        $result = new JObject;
        $parts = explode('.', $extension);
        $component = $parts[0];

        if (empty($categoryId)) {
            $assetName = $component;
            $level = 'component';
        } else {
            $assetName = $component . '.category.' . (int) $categoryId;
            $level = 'category';
        }

        $actions = JAccess::getActions($component, $level);

        foreach ($actions as $action) {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }

        return $result;
    }

    public static function getAssociations($pk, $extension = 'com_content') {
        $associations = array();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
                ->from('#__categories as c')
                ->join('INNER', '#__associations as a ON a.id = c.id AND a.context=' . $db->quote('com_categories.item'))
                ->join('INNER', '#__associations as a2 ON a.key = a2.key')
                ->join('INNER', '#__categories as c2 ON a2.id = c2.id AND c2.extension = ' . $db->quote($extension))
                ->where('c.id =' . (int) $pk)
                ->where('c.extension = ' . $db->quote($extension));
        $select = array(
            'c2.language',
            $query->concatenate(array('c2.id', 'c2.alias'), ':') . ' AS id'
        );
        $query->select($select);
        $db->setQuery($query);
        $contentitems = $db->loadObjectList('language');

        // Check for a database error.
        if ($error = $db->getErrorMsg()) {
            JError::raiseWarning(500, $error);
            return false;
        }

        foreach ($contentitems as $tag => $item) {
            // Do not return itself as result
            if ((int) $item->id != $pk) {
                $associations[$tag] = $item->id;
            }
        }

        return $associations;
    }

    public static function getVersion() {
        $params = self::getManifest();
        return $params->version;
    }

    public static function getManifest() {
        $db = JFactory::getDbo();
        $db->setQuery('SELECT `manifest_cache` FROM #__extensions WHERE element="com_joomportfolio"');
        $params = json_decode($db->loadResult());
        return $params;
    }

    public static function loadData($file = 'custom.xml') {
        $xmlFile = self::getDataFile($file);
        $xml = simplexml_load_file($xmlFile);
        return $xml;
    }

    public static function saveData($xml, $file = 'custom.xml') {
        $xmlString = $xml->asXML();
        $xmlFile = self::getDataFile($file);
        if ($xmlFile = fopen($xmlFile, 'w')) {
            fwrite($xmlFile, $xmlString);
            fclose($xmlFile);
            return true;
        }

        return false;
    }

    public static function getDataFile($file) {
        $mediaFile = JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_joomportfolio' . DIRECTORY_SEPARATOR . $file;
        if (file_exists($mediaFile)) {
            $xmlFile = $mediaFile;
        } else {
            $xmlFile = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . $file;
        }
        return $xmlFile;
    }

    public static function featured($value = 0, $i) {
        
    }

    public static function getPlugins() {
        $db = JFactory::getDBO();
        $query = "SELECT element AS el FROM #__extensions WHERE type='plugin' AND enabled=1 AND folder='portfolio' ORDER BY el DESC";
        $db->setQuery($query);
        $plugins = $db->loadAssocList();
        return $plugins;
    }

    public static function loadLanguage() {
        $mode = JoomPortfolioHelper::getMode();
        $extension = 'com_' . $mode;
        $language = JFactory::getLanguage();
        $language->load($extension, JPATH_ADMINISTRATOR, 'en-GB', true);
        $language->load($extension, JPATH_ADMINISTRATOR, null, true);
        //JFactory::getLanguage()->load($extension, JPATH_ADMINISTRATOR);
    }

    public static function getCategories() {
        $db = JFactory::getDBO();
        $mode = JoomPortfolioHelper::getMode();
        $query = "SELECT *  FROM #__categories WHERE extension='com_" . $mode . "'";
        $db->setQuery($query);
        $plugins = $db->loadObjectList();
        return $plugins;
    }

    public static function setMode($mode) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->update('#__extensions');
        $query->set('custom_data ="' . $db->escape($mode) . '"');
        $query->where('element="com_joomportfolio"');
        $db->setQuery($query);
        $db->execute();
    }

    public static function getMode() {
        $db = JFactory::getDBO();
        $query = "SELECT custom_data  FROM #__extensions WHERE element='com_joomportfolio'";
        $db->setQuery($query);
        $mode = mb_strtolower($db->loadResult());
        return $mode;
    }

     public  static function addFileUploadFull($uploadHandler='server/php/', $fileInputId='fileupload', $preloadImages = array()){
      $document = JFactory::getDocument();
      JHTML::stylesheet(JURI::root().'components/com_testimonials/assets/bootstrap/css/font-awesome.css');
      JHTML::script(JURI::root().'components/com_testimonials/assets/jplace.jquery.js');
      JHTML::script(JURI::root().'components/com_testimonials/assets/bootstrap/js/bootstrap.min.js');
      JHTML::stylesheet(JURI::root().'components/com_testimonials/assets/file-upload/css/jquery.fileupload-ui.css');
      JHTML::script(JURI::root().'components/com_testimonials/assets/file-upload/js/vendor/jquery.ui.widget.js');
	  JHTML::script(JURI::root().'administrator/components/com_testimonials/assets/js/tmpl.min.js');
	  JHTML::script(JURI::root().'administrator/components/com_testimonials/assets/js/load-image.min.js');
	  JHTML::script(JURI::root().'administrator/components/com_testimonials/assets/js/canvas-to-blob.min.js');
      JHTML::script(JURI::root().'components/com_testimonials/assets/file-upload/js/jquery.iframe-transport.js');
      JHTML::script(JURI::root().'components/com_testimonials/assets/file-upload/js/jquery.fileupload.js');
      JHTML::script(JURI::root().'components/com_testimonials/assets/file-upload/js/jquery.fileupload-fp.js');
      JHTML::script(JURI::root().'components/com_testimonials/assets/file-upload/js/jquery.fileupload-ui.js');
      $document->addCustomTag('<!--[if gte IE 8]><script src="'.JURI::root().'components/com_testimonials/assets/file-upload/js/cors/jquery.xdr-transport.js"></script><![endif]-->');
      $uploadInit = "
    <script type='text/javascript'>
    (function($) {
        $(document).ready(function () {
      $('#$fileInputId').fileupload({
          // Uncomment the following to send cross-domain cookies:
          //xhrFields: {withCredentials: true},
          url: '$uploadHandler',
          formData: {task: 'images.addImage'}
      });
      var result = ".  json_encode($preloadImages).";
      if (result && result.length) {
          $('#$fileInputId').fileupload('option', 'done').call($('#$fileInputId'), null, {result: result});
      }
        });
    })(jplace.jQuery);
    </script>
      ";
      $document->addCustomTag($uploadInit);
      
   }
   
    public static function getControlPanel() {
        $mode = JoomPortfolioHelper::getMode();
        if (!$mode) {
            $mode = 'joomportfolio';
        }

        $db =JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('d.*');
        $query->from('`#__jp3_dashboard_items` AS d');
        $query->where('d.mode="' . $mode . '"');
        $query->where('d.published=1');
        $db->setQuery($query);
        $items = $db->loadAssocList();
        return $items;
    }

    public static function deleteDirectory($dir) {
        if (file_exists($dir)) {
        $it = new RecursiveDirectoryIterator($dir);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        @rmdir($dir);
        }
    }

}
