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
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$search = $this->escape($this->state->get('filter.search'));
$sortFields = $this->getSortFields();
?>
<link rel="stylesheet"
      href="<?php echo(JUri::root()); ?>administrator/components/com_joomportfolio/assets/css/joomportfolio.css"
      type="text/css"/>

<script type="text/javascript">
    $ = jQuery.noConflict();
    jQuery(document).ready(function () {
        $('#list-filds').on("click", ".catview", function (e) {
            startLoadingAnimation();
            var id = $(e.target).parent('a').attr('catviw-id');
            $.ajax({
                url: "index.php?option=com_joomportfolio&task=field.catviewupdate",
                type: "POST",
                data: {
                    'id': id
                },
                success: function (obj) {
                    var data = $.parseJSON(obj);
                   if (data.catview == 0) {
                        $('#catview' + data.id).attr('catviw-id', data.id).addClass('active');
                        $('#catview' + data.id).attr('catviw-id', data.id).html('<i class="icon-publish"></i>');

                    } else {
                        $('#catview' + data.id).attr('catviw-id', data.id).removeClass('active');
                        $('#catview' + data.id).attr('catviw-id', data.id).html('<i class="icon-unpublish"></i>');

                    }

                }
            });
            stopLoadingAnimation()
        });

        $('#list-filds').on("click", ".req", function (e) {
            startLoadingAnimation();
            var id = $(e.target).parent('a').attr('req-id');
            $.ajax({
                url: "index.php?option=com_joomportfolio&task=field.requiredupdate",
                type: "POST",
                data: {
                    'id': id
                },
                success: function (obj) {
                    var data = $.parseJSON(obj);
                    if (data.req == 0) {
                        $('#req' + data.id).attr('req-id', data.id).addClass('active');
                        $('#req' + data.id).attr('req-id', data.id).html('<i class="icon-publish"></i>');
                    } else {
                        $('#req' + data.id).attr('req-id', data.id).removeClass('active');
                        $('#req' + data.id).attr('req-id', data.id).html('<i class="icon-unpublish"></i>');
                    }

                }
            });
            stopLoadingAnimation()
        });

        function startLoadingAnimation() // - функция запуска анимации
        {
            // найдем элемент с изображением загрузки и уберем невидимость:
            var imgObj = $("#loadImg");
            imgObj.show();
            // вычислим в какие координаты нужно поместить изображение загрузки,
            // чтобы оно оказалось в серидине страницы:
            var centerY = $(window).scrollTop() + ($(window).height() + imgObj.height()) / 2;
            var centerX = $(window).scrollLeft() + ($(window).width() + imgObj.width()) / 2;
            // поменяем координаты изображения на нужные:
            //imgObj.offset(top:centerY, left:centerX);
        }

        function stopLoadingAnimation() // - функция останавливающая анимацию
        {
            $("#loadImg").hide();
        }

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
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10 j-toggle-main">
<table class="admin">
    <tbody>
    <tr>
        <td valign="top" class="leftmenutd"></td>
        <td valign="top" width="100%">
            <form action="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=fields', false); ?>"
                  method="post" name="adminForm" id="adminForm">
                <div class="clearfix">
                    <div id="filter-bar" class="btn-toolbar" style="float:left">
                        <div class="filter-search btn-group pull-left">
                            <div class="filter-search fltlft">
                                <?php //if ($this->items or $search) { ?>


                                <div class="filter-search btn-group pull-left">
                                    <label for="filter_search"
                                           class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                                    <input type="text" name="filter_search" id="filter_search"
                                           value="<?php echo $search; ?>" style="font-size:12px !important"/>
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
                    <div class="filter-group" style="margin-top: 10px;">
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
                            <label for="sortTable"
                                   class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                            <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                                <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
                                <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table table-striped" id="list-filds">
                    <thead>
                    <tr>
                        <th width="1%">
                            <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);"/>
                        </th>
                        <th class="gl_left" width="20%">
                            <?php echo JHtml::_('grid.sort', 'COM_JOOMPORTFOLIO_FIELD_HEADING_LABEL', 'label', $listDirn, $listOrder); ?>
                        </th>
                        <th class="gl_left" width="20%">
                            <?php echo JHtml::_('grid.sort', 'COM_JOOMPORTFOLIO_FIELD_HEADING_NAME', 'name', $listDirn, $listOrder); ?>
                        </th>
                        <th width="10%">
                            <?php echo JHtml::_('grid.sort', 'COM_JOOMPORTFOLIO_FIELD_HEADING_TYPE', 'type', $listDirn, $listOrder); ?>
                        </th>
                        <th class="gl_left" width="20%">
                            <?php echo JHtml::_('grid.sort', 'COM_JOOMPORTFOLIO_FIELD_HEADING_DEFAULT', 'def', $listDirn, $listOrder); ?>
                        </th>
                        <th width="10%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_JOOMPORTFOLIO_FIELD_HEADING_REQUIRED', 'req', $listDirn, $listOrder); ?>
                        </th>
                        <th width="15%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_JOOMPORTFOLIO_FIELD_HEADING_CATVIEW', 'catview', $listDirn, $listOrder); ?>
                        </th>
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
                                    <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&view=field&layout=edit&id=' . $items->id); ?>">
                                        <?php echo $items->label; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php echo JText::_($items->name); ?>
                                </td>
                                <td>
                                    <?php echo $items->type; ?>
                                </td>
                                <td>
                                    <?php echo $items->def; ?>
                                </td>
                                <td class="center req">
                                    <?php if ($items->req == 0) { ?>
                                        <a class="btn btn-micro" href="javascript:void(0);"
                                           req-id="<?php echo $items->id; ?>" id="req<?php echo $items->id; ?>">
                                            <i class="icon-unpublish"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-micro active" href="javascript:void(0);"
                                           req-id="<?php echo $items->id; ?>" id="req<?php echo $items->id; ?>">
                                            <i class="icon-publish"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                                <td class="center catview">
                                    <?php if ($items->catview == 0) { ?>
                                        <a class="btn btn-micro" href="javascript:void(0);"
                                           catviw-id="<?php echo $items->id; ?>" id="catview<?php echo $items->id; ?>">
                                            <i class="icon-unpublish"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-micro active" href="javascript:void(0);"
                                           catviw-id="<?php echo $items->id; ?>" id="catview<?php echo $items->id; ?>">
                                            <i class="icon-publish"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" class="center">
                                <?php echo JText::sprintf('COM_JOOMPORTFOLIO_FIELD_NONE', 'fields'); ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_joomportfolio&task=field.add'); ?>">
                                    <?php echo JText::_('COM_JOOMPORTFOLIO_FIELD_NONE_A'); ?>
                                </a>
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
<div id="loadImg">
    <img  src="<?php echo JURI::root() . "administrator/components/com_joomportfolio/assets/images/loading81.gif"; ?>" />
</div>