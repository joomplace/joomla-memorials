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

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Field Controller
 */
class JoomPortfolioControllerSettings extends JControllerForm
{

    protected $ext = 'com_joomportfolio';

    public function __construct($config = array())
    {

        $this->view_item = 'settings';
        parent::__construct($config);
        return true;
    }

    public function save()
    {
        $model = $this->getModel();
        try {
            $model->saveItem();
            $this->setMessage(JText::_('COM_JOOMPORTFOLIO_SAVE_SUCCESS'));
            $this->setRedirect('index.php?option=' . $this->ext . '&view=settings&layout=edit');

        } catch (RuntimeException $e) {

            $this->setMessage($model->getError());
            $this->setRedirect('index.php?option=' . $this->ext . '&view=settings&layout=edit');
            return false;
        }

    }

    public function cancel($key = null)
    {
        $this->setRedirect('index.php?option=com_joomportfolio&view=about');
    }

}
