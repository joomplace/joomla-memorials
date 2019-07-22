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
class JoomPortfolioControllerField extends JControllerForm {

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

    public function save($key = NULL, $urlVar = NULL) {
        $model = $this->getModel();
        $input = JFactory::getApplication()->input;
        $data = $input->get('jform', array(), 'post', 'array');
        $id = $input->get('id',0,'INT');
        $task = $input->get('task');
        if($model->check($data)){
        if ($model->save($data)) {
            $this->setMessage(JText::_('COM_JOOMPORTFOLIO_SAVE_SUCCESS'));
        } else {
            $this->setMessage($model->getError());
        }
        }else{
            $this->setMessage($model->getError());
        }
        switch ($task) {
            case 'apply':
                if ($id == 0) {
                    $id = $model->lastId();
                    $this->setRedirect('index.php?option=' . $this->ext . '&view=field&layout=edit&id=' . (int)$id);
                } else {
                    $this->setRedirect('index.php?option=' . $this->ext . '&view=field&layout=edit&id=' . $id);
                }
                break;
            case 'save':
                $this->setRedirect('index.php?option=' . $this->ext . '&view=fields');
                break;
            case 'save2new':
                $this->setRedirect('index.php?option=' . $this->ext . '&view=field&layout=edit');
                break;
        }
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

    public function edit($key = NULL, $urlVar = NULL) {
        $input = JFactory::getApplication()->input;
        $data = $input->get('cid', array(), 'post', 'array');
        if (!empty($data)) {
            $this->setRedirect('index.php?option=com_joomportfolio&view=field&layout=edit&id=' . $data[0]);
        } else {
            $this->setRedirect('index.php?option=com_joomportfolio&view=fied&layout=edit');
        }
    }

    public function fieldsimpledata() {
        $model = $this->getModel();
        $data1 = array("name" => "efforts", "label" => "efforts", "type" => "calendar", "default" => "", "required" => 0, "catview" => 1);
        $id = 0;
        if ($model->save($data1, $id)) {
            $data2 = array("name" => "techandtools", "label" => "Technologies and tools", "type" => "textarea", "default" => "", "required" => 0, "catview" => 1);
            if ($model->save($data2, $id)) {
                $data3 = array("name" => "url", "label" => "Url", "type" => "url", "default" => "", "required" => 0, "catview" => 0);
                if ($model->save($data3, $id)) {
                    $this->setRedirect('index.php?option=com_joomportfolio&task=category.catsimpledata');
                }
            }
        }
    }

}
