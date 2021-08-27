<?php
/**
* JoomPortfolio component for Joomla 3.0
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

class JoomPortfolioControllerDashboard_items extends JControllerAdmin
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function getModel($name = 'Dashboard_items', $prefix = 'JoomPortfolioModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function add()
	{
		$this->setRedirect('index.php?option=com_joomportfolio&task=dashboard_item.add');
	}

    public function delete()
    {
        // Get items to remove from the request.
        $cid = JFactory::getApplication()->input->get('cid',array(), '', 'array');
        $tmpl = JFactory::getApplication()->input->get('tmpl');
        if ($tmpl == 'component') $tmpl = '&tmpl=component'; else $tmpl = '';

        if (!is_array($cid) || count($cid) < 1) {
            JFactory::getApplication()->enqueueMessage(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), 'warning');
        } else {
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            jimport('joomla.utilities.arrayhelper');
            $cid = ArrayHelper::toInteger($cid);

            // Remove the items.
            try {
                $model->delete($cid);
                $this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
                $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $tmpl, false));

            } catch (RuntimeException $e) {

                $this->setMessage($e->getMessage(), 'warning');
                $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $tmpl, false));
                return false;
            }

        }

        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $tmpl, false));
    }

    public function edit()
    {
        $cid = JFactory::getApplication()->input->get('cid',array(), '', 'array');
        $item_id = $cid['0'];
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&task=dashboard_item.edit&id='. $item_id, false)); 
    }


}
