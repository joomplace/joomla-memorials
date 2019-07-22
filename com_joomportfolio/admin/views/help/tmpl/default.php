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
?>
<?php echo $this->loadTemplate('menu'); ?>
<form action="" method="post" name="adminForm" id="custom-form" class="form-validate">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->leftmenu;?>
    </div>
</form>
<table class="admin">
    <tr>
        <td valign="top" width="100%">
            <div class="helptable">
                <div class="button-chlog">
                    <div class="blank">
                        <a class="modal" rel="{handler: 'iframe'}"
                           href="index.php?option=com_joomportfolio&amp;task=history&amp;tmpl=component">
                            <?php  echo JText::_('COM_JOOMPORTFOLIO') . ' ' . JoomPortfolioHelper::getVersion() . " version history";?>
                        </a>
                    </div>
                </div>
                <div class="clr"><!----></div>
                <div style="text-align:left; padding:5px; font-family: verdana, arial, sans-serif; font-size: 9pt;">
                    <div style="text-align:left; padding:5px; font-family: verdana, arial, sans-serif; font-size: 9pt;">
                        <div style="padding-left:10px ">
                            <h4><b>1) Installation</b></h4>

                            <div style="padding-left:10px ">
                                <p>Navigate to <b>Installers>Components</b>. Browse the component's installation package
                                    file in the Upload Package File section and press Upload File&Install. Click
                                    Continue to finish the installation.
                                    Navigate to <b>Installers>Modules</b>. Browse the component's modules package file
                                    in the Upload Package File section and press Upload File&Install. Click Continue to
                                    finish the installation.
                                </p>

                            </div>
                        </div>

                        <div style="padding-left:10px ">
                            <h4><b>2) Settings</b></h4>

                            <div style="padding-left:10px ">
                                <p>Go to <b>Components>JoomPortfolio>Settings</b>.
                                    This page will allow you to adjust the configuration of the front-end of the
                                    component.
                                    You can change the following settings on the page:
                                <ul>

                                    <li> The name of the component which will be shown on the front-end</li>
                                    <li> The number of items shown on the page</li>
                                    <li> To show or not to show the description of sections and categories</li>
                                    <li> Intro text</li>
                                    <li> Module parameters</li>

                                </ul>
                                After you've configured all the settings press the <i>Save</i> icon in the top right
                                corner of the form to save the settings or <i>Cancel</i> to discard the changes.
                                </p>
                            </div>
                        </div>

                        <div style="padding-left:10px ">

                            <h4><b>3) Management</b></h4>

                            <div style="padding-left:10px ">
                                <p>
                                    Once you configured the settings you may start managing categories, items and custom
                                    fields.
                                    To pass to the Categories page you can either go to
                                    Components>JoomPortfolio>Categories or navigate to this section using the left page
                                    menu.
                                    In addition there are quick transition links on the top right side of the page.
                                </p>
                            </div>
                        </div>

                        <div style="padding-left: 10px;">
                            <h4><b>4) Categories</b></h4>

                            <div style="padding-left: 10px;">
                                <p>
                                    This page represents the list of the created categories.

                                    TO CREATE A NEW CATEGORY:</p>

                                <ul>
                                    <li> Go to <b>Components>JoomPortfolio>Categories</b>.
                                    </li>
                                    <li> Press the <b>New</b> button in the top right corner of the page.
                                    </li>
                                    <li> Enter a name for the newly created category in the Category name field, choose
                                        Parent from the list, enter a description for the category, mark if it should be
                                        published or no.
                                    </li>
                                    <li> Press <b>Save</b> to save the category and return to the page with the list of
                                        Categories or <b>Apply</b> to apply the changes and stay on the same page; press
                                        <b>Cancel</b> to discard changes.
                                    </li>

                                </ul>
                            </div>
                        </div>

                        <div style="padding-left: 10px;">
                            <h4><b>5) Items</b></h4>

                            <div style="padding-left: 10px;">
                                <p>
                                    This page represents the list of the created items.
                                    Use standard Joomla buttons in the top right corner of the page to manage items.

                                    TO CREATE A NEW ITEM:</p>

                                <ul>
                                    <li> Go to <b>Components>JoomPortfolio>Items</b>.
                                    </li>
                                    <li> Press the <b>New</b> button in the top right corner of the page.
                                    </li>
                                    <li> Enter a name for the newly created item in the Title name field, choose
                                        Category(you can choose several), mark if it should be published or no.
                                    </li>
                                    <li> Go to tabs below. There you can set title alias of the item, enter a
                                        description, upload images and set up unlimited images for the item, set custom
                                        fields on the last tab.
                                    </li>
                                    <li> Press <b>Save</b> to save the item and return to the page with the list of
                                        Items or <b>Apply</b> to apply the changes and stay on the same page; press <b>Cancel</b>
                                        to discard changes.
                                    </li>

                                </ul>
                            </div>
                        </div>

                        <div style="padding-left: 10px;">
                            <h4><b>6) Custom fields</b></h4>

                            <div style="padding-left: 10px;">
                                <p>
                                    This page represents the list of the created custom fields.
                                    Use standard Joomla buttons in the top right corner of the page to manage custom
                                    fields.

                                    TO CREATE A NEW CUSTOM FIELD:</p>

                                <ul>
                                    <li> Go to <b>Components>JoomPortfolio>Custom Fields</b>.
                                    </li>
                                    <li> Press the <b>New</b> button in the top right corner of the page.
                                    </li>
                                    <li> Enter a name for the newly created custom field in the name field, mark if it
                                        should be published or no.
                                    </li>
                                    <li> Press <b>Save</b> to save the item and return to the page with the list of
                                        Items or <b>Apply</b> to apply the changes and stay on the same page - then
                                        you'll be able to use these custom fields when creating or editing your items;
                                        press <b>Cancel</b> to discard changes.
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>
