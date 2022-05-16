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

use Joomla\Utilities\ArrayHelper;

/**
 * Templates Controller
 */
class JoomPortfolioControllerTemplates extends JControllerAdmin
{
	
    public function getModel($name = 'Template', $prefix = 'JoomPortfolioModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    public function templateRequired() {
        $this->updateItems('required', 1);
    }

    public function templateNotRequired() {
        $this->updateItems('required', 0);
    }

    public function templateInCat() {
        $this->updateItems('catview', 1);
    }

    public function templateNotInCat() {
        $this->updateItems('catview', 0);
    }

    protected function updateItems($field, $state) {
        $input = JFactory::getApplication()->input;
        $input->checkToken() or die(JText::_('JINVALID_TOKEN'));

        $model = $this->getModel();
        $cid = $input->get('cid', array(), '', 'array');

        foreach ($cid as $id) {
            $data['original'] = $id;
            $data['name'] = $id;
            $data[$field] = $state;

            $model->save($data);
        }

        $this->setRedirect('index.php?option=com_joomportfolio&view=templates');
    }

    public function saveOrderAjax() {
        $pks = $this->input->post->get('cid', array(), 'array');
        $order = $this->input->post->get('order', array(), 'array');

        $pks = ArrayHelper::toInteger($pks);
        $order = ArrayHelper::toInteger($order);

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
