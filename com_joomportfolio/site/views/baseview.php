<?php

/**
 * JoomPortfolio component for Joomla 2.5
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class BaseView extends JViewLegacy
{
    public function display($tpl = null)
    {
        $this->addTemplatePath(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');

        $this->setDocument();
        $this->setPathwayCategory();
        $this->setPathway();

        parent::display($tpl);
    }

    public function setDocument()
    {
        $doc = $this->document;
    }

    public function setPathway()
    {
    }

    public function setPathwayCategory()
    {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();

        $hash = '';
        $path = array();

        $menu = $app->getMenu();
        $active = $menu->getActive();

        if (!$active or ($active->component != 'com_joomportfolio' || $active->component != 'com_memorials')) {
            $portfolio = $menu->getItems('component', 'com_joomportfolio', true);
            if ($portfolio) {
                $hash = '&Itemid=' . $portfolio->id;
                $pathway->addItem($portfolio->title, JRoute::_($portfolio->link . $hash));
            } else {
                $pathway->addItem(JText::_('COM_JOOMPORTFOLIO'), JRoute::_('index.php?option=com_joomportfolio'));
            }
        }

        if (isset($this->category) and !empty($this->category)) {
            if ($this->category->id) {
                if (!isset($this->category->parent_id)) {
                    $this->category->parents = 1;
                    $parent = $this->category->parents[1];
                } else {
                    $this->category->parents=$this->category->parent_id;
                    $parent = $this->category->parents[0];

                }

                $stop = isset($active->query['id']) ? $active->query['id'] : 0;
                if(isset($parent->id)){
                while ($parent->id > 1 and $parent->id != $stop) {
                    array_unshift($path, array(
                        'title' => $parent->title,
                        'link' => 'index.php?option=com_joomportfolio&view=category&id=' . $parent->slug
                    ));
                    $parent = $this->category->parents[$parent->parent_id];
                }
                }
                if (isset($active) && isset($active->query['id'])) {
                    if ($active->query['view'] != 'category' or $active->query['id'] != $this->category->id) {
                        if(!isset($this->category->slug)){
                            $this->category->slug=$this->category->id;
                        }
                        $path[] = array(
                            'title' => $this->category->title,
                            'link' => 'index.php?option=com_joomportfolio&view=category&id=' . $this->category->slug
                        );
                    }
                }

                foreach ($path as $item) {
                    $pathway->addItem($item['title'], JRoute::_($item['link'] . $hash));
                }
            }
        }
    }

    public function getAccess($actions, $asset = 'com_joomportfolio')
    {
        $result = new JObject;

        foreach ($actions as $action) {
            $result->set($action, (int)$this->user->authorise($action, $assetName));
        }

        return $result;
    }
}

?>