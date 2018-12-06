<?php
/**
 * JoomPortfolio component for Joomla 3.0
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

class JoomPortfolioControllerDashboard_item extends JControllerForm
{

    public function save()
    {
        $requestData = $this->input->post->get('jform', array(), 'array');
        $task = $this->input->post->get('task', '', 'CMD');

        $model = $this->getModel();

        // Validate the posted data.
        $form	= $model->getForm();

        if (!$form)
        {
            JError::raiseError(500, $model->getError());
            return false;
        }


        if(!$model->check($requestData)){
            $this->setMessage($model->getError());
            $this->setRedirect('index.php?option=com_joomportfolio&view=dashboard_item&layout=edit&id='.(int)$requestData['id']);
            return false;
        }

        if ($model->save($requestData))
        {
            $this->setMessage('Comment successfully saved.');

            if ($task == 'comment.apply')
            {
                $last_id=$model->lastId();
                $this->setRedirect('index.php?option=com_joomportfolio&view=dashboard_item&layout=edit&id='.(int)$last_id);
            }
            else
            {
                $this->setRedirect('index.php?option=com_joomportfolio&view=dashboard_items');
            }
        }
        else
        {
            // Redirect back to the edit screen.
            $this->setMessage(JText::sprintf('COM_JOOMPORTFOLIO_INSCHRIJVEN_SAVE_FAILED', $model->getError()), 'warning');
            $this->setRedirect('index.php?option=com_joomportfolio&view=dashboard_item&layout=edit&id='.(int)$requestData['id']);
            return false;
        }

        return true;
    }


}
