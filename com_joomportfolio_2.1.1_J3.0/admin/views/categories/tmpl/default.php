<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');


$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');
$extension = $this->escape($this->state->get('filter.extension'));
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$ordering = ($listOrder == 'a.lft');
$saveOrder = ($listOrder == 'a.lft' && $listDirn == 'asc');
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_categories&task=categories.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
$sortFields = $this->getSortFields();

?>
<link rel="stylesheet"
      href="<?php echo(JUri::root()); ?>administrator/components/com_joomportfolio/assets/css/joomportfolio.css"
      type="text/css"/>
<script type="text/javascript">
    $ = jQuery.noConflict();
    jQuery(document).ready(function () {
        $('#categoryList').on("click", ". publish", function (e) {
            var id = $(e.target).parent('a').attr('cat-id');
            $.ajax({
                url: "index.php?option=com_joomportfolio&task=field.catviewupdate",
                type: "POST",
                data: {
                    'id': id
                },
                success: function (obj) {
                    var data = $.parseJSON(obj);
                    if (data.cat == 0) {
                        $('#cat' + data.id).attr('cat-id', data.id).addClass('active');
                        $('#cat' + data.id).attr('cat-id', data.id).html('<i class="icon-publish"></i>');
                    } else {
                        $('#cat' + data.id).attr('cat-id', data.id).removeClass('active');
                        $('#cat' + data.id).attr('cat-id', data.id).html('<i class="icon-unpublish"></i>');
                    }
                }
            });
        });

    });
</script>
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
<form action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=categories'); ?>" method="post"
      name="adminForm" id="adminForm" style="margin-top: 8px;">

<?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
<?php endif; ?>
<div id="j-main-container"<?php echo !empty($this->sidebar) ? ' class="span10"' : ''; ?>>
<div id="filter-bar" class="btn-toolbar">
    <div class="filter-search btn-group pull-left">
        <label for="filter_search"
               class="element-invisible"><?php echo JText::_('COM_CATEGORIES_ITEMS_SEARCH_FILTER'); ?></label>
        <input type="text" name="filter_search" id="filter_search"
               placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
               value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip"
               title="<?php echo JText::_('COM_CATEGORIES_ITEMS_SEARCH_FILTER'); ?>"/>
    </div>
    <div class="btn-group hidden-phone">
        <button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
            <i class="icon-search"></i></button>
        <button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"
                onclick="document.id('filter_search').value='';this.form.submit();">
            <i class="icon-remove"></i></button>
    </div>
    <div class="btn-group pull-right hidden-phone">
        <label for="limit"
               class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
        <?php echo $this->pagination->getLimitBox(); ?>
    </div>
    <div class="btn-group pull-right hidden-phone">
        <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
        <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
            <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
            <option
                value="asc"<?php echo $listDirn == 'asc' ? ' selected="selected"' : ''; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
            <option
                value="desc"<?php echo $listDirn == 'desc' ? ' selected="selected"' : ''; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
        </select>
    </div>
    <div class="btn-group pull-right">
        <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
        <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
            <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
            <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
        </select>
    </div>
    <div class="clearfix"></div>
</div>

<table class="table table-striped" id="categoryList">
    <thead>
    <tr>
        <th width="1%" class="hidden-phone">
            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                   onclick="Joomla.checkAll(this);"/>
        </th>
        <th width="1%" class="nowrap center">
            <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
        </th>
        <th>
            <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
        </th>
        <th width="10%" class="nowrap hidden-phone">
            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
        </th>
        <?php if ($this->assoc) : ?>
            <th width="5%" class="hidden-phone">
                <?php echo JHtml::_('grid.sort', 'COM_CATEGORY_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
            </th>
        <?php endif; ?>
        <th width="5%" class="nowrap hidden-phone">
            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
        </th>
        <th width="1%" class="nowrap hidden-phone">
            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
        </th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="15">

            <?php  echo $this->pagination->getListFooter(); ?>
        </td>
    </tr>
    </tfoot>
    <tbody>
    <?php $originalOrders = array(); ?>
    <?php foreach ($this->items as $i => $item) : ?>
        <?php
        $orderkey = array_search($item->id, $this->ordering[$item->parent_id]);
        $canEdit = $user->authorise('core.edit', $extension . '.category.' . $item->id);
        $canCheckin = $user->authorise('core.admin', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
        $canEditOwn = $user->authorise('core.edit.own', $extension . '.category.' . $item->id) && $item->created_user_id == $userId;
        $canChange = $user->authorise('core.edit.state', $extension . '.category.' . $item->id) && $canCheckin;

        // Get the parents of item for sorting
        if ($item->level > 1) {
            $parentsStr = "";
            $_currentParentId = $item->parent_id;
            $parentsStr = " " . $_currentParentId;
            for ($i2 = 0; $i2 < $item->level; $i2++) {
                foreach ($this->ordering as $k => $v) {
                    $v = implode("-", $v);
                    $v = "-" . $v . "-";
                    if (strpos($v, "-" . $_currentParentId . "-") !== false) {
                        $parentsStr .= " " . $k;
                        $_currentParentId = $k;
                        break;
                    }
                }
            }
        } else {
            $parentsStr = "";
        }
        ?>
        <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->parent_id; ?>"
            item-id="<?php echo $item->id ?>" parents="<?php echo $parentsStr ?>" level="<?php echo $item->level ?>">
            <td class="center hidden-phone">
                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
            </td>
            <td class="center publish">
                <?php echo JHtml::_('jgrid.published', $item->published, $i, 'categories.', $canChange); ?>
            </td>
            <td>
                <?php echo str_repeat('<span class="gi">&mdash;</span>', $item->level - 1) ?>
                <?php if ($item->checked_out) : ?>
                    <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'categories.', $canCheckin); ?>
                <?php endif; ?>
                <?php if ($canEdit || $canEditOwn) : ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=category&layout=edit&id=' . $item->id); ?>">
                        <?php echo $this->escape($item->title); ?></a>
                <?php else : ?>
                    <?php echo $this->escape($item->title); ?>
                <?php endif; ?>
                <span class="small" title="<?php echo $this->escape($item->path); ?>">
							<?php if (empty($item->note)) : ?>
                        <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                    <?php else : ?>
                        <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($item->alias), $this->escape($item->note)); ?>
                    <?php endif; ?>
						</span>
            </td>
            <td class="small hidden-phone">
                <?php echo $this->escape($item->access_level); ?>
            </td>
            <?php if ($this->assoc) : ?>
                <td class="center hidden-phone">
                    <?php if ($item->association): ?>
                        <?php echo JHtml::_('CategoriesAdministrator.association', $item->id, $extension); ?>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
            <td class="small nowrap hidden-phone">
                <?php if ($item->language == '*') : ?>
                    <?php echo JText::alt('JALL', 'language'); ?>
                <?php else: ?>
                    <?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
                <?php endif; ?>
            </td>
            <td class="center hidden-phone">
						<span title="<?php echo sprintf('%d-%d', $item->lft, $item->rgt); ?>">
							<?php echo (int)$item->id; ?></span>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php    if (empty($this->items)) {
        ?>
        <tr>
            <td colspan="7" class="center">
                <?php echo JText::_('COM_JOOMPORTFOLIO_CATEGORY_CREATE_NEW1'); ?> <a
                    onclick="javascript:Joomla.submitbutton('category.add')"
                    href="javascript:void(0)"><?php echo JText::_('COM_JOOMPORTFILIO_CREATE_NEW_ONE'); ?></a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php //Load the batch processing form. ?>
<?php echo $this->loadTemplate('batch'); ?>

<input type="hidden" name="extension" value="<?php echo $extension; ?>"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
<input type="hidden" name="original_order_values" value="<?php echo implode($originalOrders, ','); ?>"/>
<?php echo JHtml::_('form.token'); ?>
</div>
</form>
