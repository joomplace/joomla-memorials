<?php

/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access to this file

defined('_JEXEC') or die('Restricted access');



// import Joomla modelform library

jimport('joomla.application.component.modeladmin');

jimport('joomla.filesystem.folder');

jimport('joomla.filesystem.file');



/**

 * Item Model

 */

class JoomPortfolioModelImages extends JModelLegacy

{

    public $total;

    public $limitstart;

    public $limit = 20;



    public function getItems()

    {

        $db = $this->getDbo();

        $this->limitstart = JRequest::getVar('limitstart',0);



        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__jp3_condolence` AS `i` ORDER BY `id` DESC LIMIT $this->limitstart, $this->limit";

        $items = $db->setQuery($query)->loadObjectList();

        $this->total = $db->setQuery("SELECT FOUND_ROWS()")->loadResult();

        return $items;

    }



    public function getPagination()

    {

        $pagination = new JPagination($this->total, $this->limitstart, $this->limit);

        return $pagination;

    }



}

