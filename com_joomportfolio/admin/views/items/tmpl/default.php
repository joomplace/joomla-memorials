<?php
/**
 * JoomPortfolio component for Joomla 3
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$search = $this->escape($this->state->get('filter.search'));
$saveOrder = ($listOrder == 'i.ordering');
$extension = 'com_joomportfolio';
$user = JFactory::getUser();
$userId = $user->get('id');
$app = JFactory::getApplication();
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_joomportfolio&task=items.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'itemsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<style>
    .row0 td {
        background-color: #f9f9f9;
    }
</style>
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

    Joomla.submitbutton = function(task) {
        if (task == 'items.export') {
            if (document.adminForm.boxchecked.value == 0) {
                alert(Joomla.JText._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'));
                return false;
            } else {
                Joomla.submitform(task);
            }

        }

        Joomla.submitform(task);
    }
</script>
<?php echo $this->loadTemplate('menu'); ?>
<?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
<?php endif; ?>
<div id="j-main-container" class="span10 j-toggle-main">
<table class="admin">
<tbody>
<tr>
<td valign="top" class="lefmenutd">
</td>
<td valign="top" width="100%">
    <form action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=items'); ?>" method="post"
          name="adminForm" id="adminForm">
        <div class="clearfix">
            <div id="filter-bar" class="btn-toolbar" style="float:left">
                <div class="filter-search btn-group pull-left">
                    <div class="filter-search fltlft">
                        <?php //if ($this->items or $search) { ?>


                        <div class="filter-search btn-group pull-left">
                            <label for="filter_search"
                                   class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                            <input type="text" name="filter_search" id="filter_search" value="<?php echo $search; ?>"
                                   style="font-size:12px !important"/>
                        </div>

                        <div class="btn-group pull-left">
                            <button class="btn hasTooltip" type="submit"
                                    title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
                                <i class="icon-search"></i>
                            </button>
                            <button class="btn hasTooltip" type="button"
                                    title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value = '';
        this.form.submit();">
                                <i class="icon-remove"></i>
                            </button>
                        </div>
                        <?php //} ?>


                    </div>
                </div>
            </div>
            <div class="filter-group">
                <div class=" pull-right hidden-phone">
                    <label for="limit"
                           class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <div class=" pull-right">
                    <label for="directionTable"
                           class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?>:</label>
                    <select name="directionTable" id="directionTable" class="input-medium"
                            onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?>:</option>
                        <option
                            value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                        <option
                            value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
                    </select>
                </div>
                <div class=" pull-right hidden-phone">
                    <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
                        <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                    </select>
                </div>
                <div style="margin-left: 5px;" class="btn-group pull-left">
                    <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                        <?php echo JHtml::_('select.options', JoomPortfolioHelper::getPublishOptions(), 'value', 'text', $this->state->get('filter.published'));?>
                    </select>
                </div>
                <div class="btn-group pull-left">
                    <select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('COM_JOOMPORTFOLIO_SELECT_CATEGORY');?></option>
                        <?php echo JHtml::_('select.options', $this->categories, 'id', 'title', $this->state->get('filter.category_id'));?>
                    </select>
                </div>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th width="2%">
                    <input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)"/>
                </th>
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'i.id', $listDirn, $listOrder); ?>
                </th>
                <th class="gl_left">
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'i.title', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="gl_center">
                    <?php echo JHtml::_('grid.sort', 'JSTATUS', 'i.published', $listDirn, $listOrder); ?>
                </th>
                <!--<th width="10%">
                                <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'i.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                                <?php /* if ($saveOrder) : ?>
                                  <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'items.saveorder'); ?>
                                  <?php endif; */ ?>
                                </th>-->
                <th width="10%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'i.hits', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JText::_('JCATEGORY'); ?>
                </th>

            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
            </tr>
            </tfoot>
            <tbody>
            <?php
            if ($this->items) {
                $i = 0;
                foreach ($this->items as $i => $item) {
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="center">
                            <?php echo $item->id; ?>
                        </td>
                        <td>
                            <?php if ($this->canDo->get('core.edit')) { ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=item&layout=edit&id=' . $item->id); ?>">
                                    <?php echo $this->escape($item->title); ?>
                                </a>
                            <?php } else { ?>
                                <?php echo $this->escape($item->title); ?>
                            <?php } ?>

                        </td>
                        <td>
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'items.', $this->canDo->get('core.edit'), 'cb'); ?>
                        </td>


                        <td>
                            <?php echo (int)$item->hits; ?>
                        </td>
                        <td>
                            <?php
                            echo $item->cat_name;
                            ?>
                        </td>

                    </tr>
                <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="7" class="center">
                        <?php if ($this->categories) { ?>
                            <?php echo JText::sprintf('COM_JOOMPORTFOLIO_FIELD_NONE', 'items'); ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=item&layout=edit'); ?>">
                                <?php echo JText::_('COM_JOOMPORTFOLIO_FIELD_NONE_A'); ?>
                            </a>
                        <?php } else { ?>
                            <?php echo JText::sprintf('COM_JOOMPORTFOLIO_FIELD_NONE', JText::_('COM_JOOMPORTFOLIO_NO_ITEMS')); ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=item&layout=edit'); ?>">
                                <?php echo JText::_('COM_JOOMPORTFOLIO_FIELD_NONE_A'); ?>
                            </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
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