<?php
/**
 * JoomPortfolio component for Joomla 3
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$search = $this->escape($this->state->get('filter.search'));
$sortFields = $this->getSortFields();
?>
<link rel="stylesheet"
      href="<?php echo(JUri::root()); ?>administrator/components/com_joomportfolio/assets/css/joomportfolio.css"
      type="text/css"/>


<script type="text/javascript">
    Joomla.orderTable = function () {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        }
        else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>

<?php echo $this->loadTemplate('menu'); ?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10 j-toggle-main">
<table class="admin">
    <tbody>
    <tr>
        <td valign="top" class="leftmenutd"></td>
        <td valign="top" width="100%">
            <form action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=teamplates', false); ?>"
                  method="post" name="adminForm" id="adminForm">

                <table class="table table-striped" id="list-filds">
                    <thead>
                    <tr>
                        <th width="1%" class="center">
                            <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);"/>
                        </th>

                        <th class="left" width="20%">
                            <?php echo JText::_('COM_JOOMPORTFOLIO_FIELD_HEADING_NAME'); ?>
                        </th>
                        <th><?php echo JText::_('COM_JOOMPORTFOLIO_TEMPLATE_EDIT_CSS');?></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <td colspan="7">
                            <?php  echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    if ($this->items) {
                        $i = 0;
                        foreach ($this->items as $key => $items) {
                            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td class="center">
                                    <?php echo JHtml::_('grid.id', $i, $items->id); ?>
                                </td>
                                <td>
                                   <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=template&layout=edit&id='.(int)$items->id);?>"><?php echo JText::_($items->name); ?></a>
                                </td>
                                <td> <a class="btn btn-info modal"
                                        rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}"
                                        href="<?php echo JRoute::_('index.php?option=com_joomportfolio&task=template.editcss&id=' . $items->id . '&tmpl=component');?>"><?php echo JText::_('COM_JOOMPORTFOLIO_TEMPLATE_EDIT_CSS'); ?></a>

                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" class="center">
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
                <div>
                    <input type="hidden" name="task" value=""/>
                    <input type="hidden" name="boxchecked" value="0"/>
                    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
                    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form>
        </td>
    </tr>
    </tbody>
</table>
</div>