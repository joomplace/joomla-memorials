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
 * Comment Controller
 */
class JoomPortfolioControllerComment extends JControllerForm {

    protected $ext = 'com_joomportfolio';

    public function delete() {

        $model = $this->getModel();
        $tmpl = JFactory::getApplication()->input->get('tmpl');
        $input = JFactory::getApplication()->input;
        $ids = $input->get('cid', array(), 'post', 'array');
        if ($model->delete($ids)) {
            $this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($ids)));
        } else {
            $this->setMessage($model->getError());
        }
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $tmpl, false));
    }

    public function save($key = null, $urlVar = null)
    {
        $requestData = $this->input->post->get('jform', array(), 'array');
        $task = $this->input->post->get('task', '', 'CMD');

        $model = $this->getModel();

        // Validate the posted data.
        $form = $model->getForm();

        if (!$form)
        {
            $this->setMessage($model->getError(), 'error');
            $this->setRedirect('index.php?option=com_joomportfolio&view=comment&layout=edit&id='.(int)$requestData['id']);
            return false;
        }

        if(!$model->check($requestData)){
            $this->setMessage($model->getError(), 'error');
            $this->setRedirect('index.php?option=com_joomportfolio&view=comment&layout=edit&id='.(int)$requestData['id']);
            return false;
        }

        if ($model->save($requestData))
        {
            $this->setMessage('Comment successfully saved.');

            if ($task == 'comment.apply')
            {
                   $last_id=$model->lastId();
                    $this->setRedirect('index.php?option=com_joomportfolio&view=comment&layout=edit&id='.(int)$last_id);
            }
            else
            {
                $this->setRedirect('index.php?option=com_joomportfolio&view=comments');
            }
        }
        else
        {
            // Redirect back to the edit screen.
            $this->setMessage(JText::sprintf('COM_JOOMPORTFOLIO_INSCHRIJVEN_SAVE_FAILED', $model->getError()), 'warning');
            $this->setRedirect('index.php?option=com_joomportfolio&view=comment&layout=edit&id='.(int)$requestData['id']);
            return false;
        }

        return true;
    }

    public function requiredupdate() {
        $data = array();
        $id = $order = intval($this->input->post->get('id'));
        $model = $this->getModel();
        $req = $model->required($id);
        $data['id'] = $id;
        $data['req'] = $req;
        echo json_encode($data);
        JFactory::getApplication()->close();
    }

    public function catviewupdate() {
        $data = array();
        $id = $order = intval($this->input->post->get('id'));
        $model = $this->getModel();
        $req = $model->catview($id);
        $data['id'] = $id;
        $data['catview'] = $req;
        echo json_encode($data);
        JFactory::getApplication()->close();
    }

    public function edit($key = null, $urlVar = null) {
        $input = JFactory::getApplication()->input;
        $data = $input->get('cid', array(), 'array');
        if (!empty($data)) {
            $this->setRedirect('index.php?option=com_joomportfolio&view=comment&layout=edit&id=' . $data[0]);
        } else {
            $this->setRedirect('index.php?option=com_joomportfolio&view=fied&layout=edit');
        }
    }


}
