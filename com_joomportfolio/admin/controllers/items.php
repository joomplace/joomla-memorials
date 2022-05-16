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

jimport('joomla.document.tcpdf.tcpdf');

use Joomla\Utilities\ArrayHelper;
/**
 * Items Controller
 */
class JoomPortfolioControllerItems extends JControllerAdmin
{

    /**
     * Proxy for getModel.
     * @since    1.6
     */
    public function getModel($name = 'Item', $prefix = 'JoomPortfolioModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    public function upload()
    {
        $model = $this->getModel('Item');
        return $model->upload();
    }

    public function selectimages()
    {
        $model = $this->getModel('Item');
        return $model->showSelectImages();
    }

    public function saveOrderAjax()
    {
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

    public function export()
    {
        $cid = $this->input->post->get('cid', array(), 'array');
        $count_cid = count($cid);
        $model = $this->getModel('Items');

        include_once(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'tcpdf'. DIRECTORY_SEPARATOR . 'tcpdf.php');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        for ($i = 0; $i < $count_cid; $i++) {
            $html = '';
            $pdf->AddPage();
            $image = $model->getImages($cid[$i]);
            $item = $model->getItem($cid[$i]);
            $fields = $model->getFields($cid[$i]);

            if (!empty($item)) {
                $html .= '<h3 style="text-align:center;">' . $item->title . '</h3>';
                $html .= '<table style="height:100%;">';
                $html .= '<tr>';

                if (!empty($image)) {
                    $html .= '<td>';
                    $img = '/images/joomportfolio/' . $image->item_id . '/original/' . $image->full;
                    $html .= '<img src="'.$img.'" style="height:150px; width:auto;">';
                    $html .= '</td>';
                }

                if (!empty($fields)) {
                    $html .= '<td>';
                    for ($k = 0; $k < count($fields); $k++) {
                        $str = '';
                        $str .= '<br><strong>' . $fields[$k]["label"] . '</strong>: ';
                        if (trim($fields[$k]['value']) != '') {
                            $value = trim($fields[$k]['value']);
                        } else {
                            $value = trim($fields[$k]['def']);
                        }
                        if ($value) {
                            switch ($fields[$k]['type']) {
                                case 'textemail':
                                    $params = JComponentHelper::getParams('com_joomportfolio');
                                    $dispatcher = JDispatcher::getInstance();
                                    JPluginHelper::importPlugin('content', 'emailcloak');
                                    $result = $dispatcher->trigger('onPrepareContent', array(& $value, & $params, 0));
                                    $str .= '<a href="mailto:'.$value.'">'.$value.'</a>';
                                    break;
                                case 'url':
                                    $str .= '<a href="'.trim($value).'" target="_blank" >'.$value.'</a>';
                                    break;
                                case 'calendar':
                                    if (!$fields[$k]['format']) {
                                        $fields[$k]['format'] = 'd/m/Y';
                                    }
                                    $str .= JHTML::_('date', $value, $fields[$k]['format'], NULL);
                                    break;
                                default:
                                    $str .= $value;
                                    break;
                            }
                        }
                        $html .=  $str ;
                    }

                    $html.='</td>';
                }

                $html .= '</tr>';
                $html .= '<tr><td colspan="2">' . $item->description. '</td>';
                $html .= '</tr></table>';
            }

            $pdf->writeHTML($html, true, 0, true, true);
        }

        $pdf->Output('result.pdf', 'D');
        exit;
    }
}