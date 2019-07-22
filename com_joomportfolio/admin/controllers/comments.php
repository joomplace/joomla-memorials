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

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Comments Controller
 */
class JoomPortfolioControllerComments extends JControllerAdmin {

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function getModel($name = 'Comment', $prefix = 'JoomPortfolioModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    public function upload() {
        $model = $this->getModel('Comment');
        return $model->upload();
    }

    public function selectimages() {
        $model = $this->getModel('Comment');
        return $model->showSelectImages();
    }

    public function saveOrderAjax() {
        $pks = $this->input->post->get('cid', array(), 'array');
        $order = $this->input->post->get('order', array(), 'array');

        // Sanitize the input
        JArrayHelper::toInteger($pks);
        JArrayHelper::toInteger($order);

        // Get the model
        $model = $this->getModel();
        // Save the ordering
        $return = $model->saveorder($pks, $order);
        if ($return) {
            echo "1";
        }
        // Close the application
        JFactory::getApplication()->close();
    }

   

}
