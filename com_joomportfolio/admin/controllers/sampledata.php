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
class JoomPortfolioControllerSampledata extends JControllerForm {

    protected $ext = 'com_joomportfolio';
    public function addsampledata() {
        $mode=JoomPortfolioHelper::getMode();
        $model = $this->getModel();
         switch ($mode) {
                case 'joomportfolio':
                    $model->samplePortfolioCat();
                    $model->samplePortfolioCustomFields();
                    $model->samplePortfolioItem();
                    break;
                case 'memorials':
                    $model->sampleMemorialsCat();
                    $model->sampleMemorialsCustomFields();
                    $model->sampleMemorialsItem();
            }
        $this->setMessage(JText::_('COM_JOOMPORTFOLIO_SIMPLE_DATA_INSTALL'));
        $this->setRedirect('index.php?option=com_joomportfolio&view=about');
    }

}
