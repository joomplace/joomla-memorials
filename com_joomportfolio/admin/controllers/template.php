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
 * Template Controller
 */
class JoomPortfolioControllerTemplate extends JControllerForm {

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

    public function save($key = NULL, $urlVar = NULL)
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

        if ($model->save($requestData))
        {
            $this->setMessage('template successfully saved.');
            if ($task == 'template.apply')
            {
                // Save the data in the session.
                $this->setRedirect('index.php?option=com_joomportfolio&view=template&layout=edit&id='.(int)$requestData['id']);

            }
            else
            {
                $this->setRedirect('index.php?option=com_joomportfolio&view=templates');
            }
        }
        else
        {
            // Redirect back to the edit screen.
            $this->setMessage(JText::sprintf('COM_SCHOOL_INSCHRIJVEN_SAVE_FAILED', $model->getError()), 'warning');
            $this->setRedirect('index.php?option=com_joomportfolio&view=template&layout=edit&id='.(int)$requestData['id']);
            return false;
        }

        return true;
    }

    public function editcss(){
        $mode =JoomPortfolioHelper::getMode();
        jimport('joomla.filesystem.file');
        $jinput = JFactory::getApplication()->input;
        $id=$jinput->get('id',0,'INT');
        $template_name='default';

        $file_path = JPATH_PLUGINS.DIRECTORY_SEPARATOR.'portfolio'.DIRECTORY_SEPARATOR.$mode.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css';
        if (is_writeable($file_path))
        {
            $editor = JFactory::getEditor('codemirror');
            $params = array( 'smilies'=> '0' ,
                'style'  => '0' ,
                'layer'  => '0' ,
                'table'  => '0' ,
                'buttons'  => 'no' ,
                'class'=>'template-editor',
                'clear_entities'=>'0',
                ' editor=>'=>'codemirror',
                'filter'=>'raw'
            );
            ob_start();
            ?>	Joomla.submitbutton = function(task)
            {
            <?php echo $editor->save( 'Array' ) ; ?>
            Joomla.submitform(task, document.getElementById('adminForm'));
            }
            <?php
            $js = ob_get_contents();
            ob_get_clean();
            $document = JFactory::getDocument();
            $document->addScriptDeclaration($js);
            ?>
            <form action="<?php echo JRoute::_('index.php?option=com_joomportfolio&task=template.editcss&id='.$id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
                <?php
                echo '<div>'.JText::_('COM_JOOMPORTFOLIO_TEMPLATE_CSS').': '.$file_path.'</div>';
                echo $editor->display( 'css', JFile::read($file_path), '650', '400', '60', '20', false, $params ) ;
                ?>
                <div>
                    <input type="button" class="button" name="save" value="<?php echo JText::_('JTOOLBAR_APPLY'); ?>" onclick="javascript:Joomla.submitbutton('template.csssave');" />
                    <input type="hidden" name="template" value="<?php echo $template_name;?>" />
                    <input type="hidden" name="task" value="" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form>
        <?php
        }

    }

    public function csssave(){

        jimport('joomla.filesystem.file');
        $jinput = JFactory::getApplication()->input;

        $mode =JoomPortfolioHelper::getMode();
        $file_path = JPATH_PLUGINS.DIRECTORY_SEPARATOR.'portfolio'.DIRECTORY_SEPARATOR.$mode.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css';
        if (is_writeable($file_path))
        {

            $content = $jinput->get('css','','HTML');//JRequest::getVar('css');
            if (JFile::write($file_path,$content))
            {
                $this->setMessage(JText::_('COM_JOOMPORTFOLIO_TEMPLATE_SAVESUCCESS'));
            }
            else {
                $this->setMessage('Failed to open file for writing!');
            }


        }
        else {$this->setMessage(JText::_('COM_MEMORIALS_TEMPLATE_UNWRITEABLE'));}
        $this->setRedirect(JRoute::_($_SERVER['HTTP_REFERER'], false));
    }

}
