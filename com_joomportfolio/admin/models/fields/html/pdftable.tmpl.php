<?php

/**
 * JoomPortfolio component for Joomla 3
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

$input = JFactory::getApplication()->input;

$item_id = $input->get('id', 0);

JHTML::_('behavior.framework');

?>

<!--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/base/jquery-ui.css" id="theme">-->
<!-- jQuery Image Gallery styles -->
<!--<link rel="stylesheet" href="http://blueimp.github.com/jQuery-Image-Gallery/css/jquery.image-gallery.min.css">-->
<!-- CSS to style the file input field as button and adjust the jQuery UI progress bars -->
<link rel="stylesheet" href="components/com_joomportfolio/assets/css/jquery.fileupload-ui.css">
<!-- Redirect browsers with JavaScript disabled to the origin page -->
<noscript><input type="hidden" name="redirect" value="/server/index.php"></noscript>
<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
<div class="row fileupload-buttonbar">
    <div class="span7" style="float: right;">
           
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
        <button type="submit" class="btn btn-primary start">
            <i class="icon-upload icon-white"></i>
            <span>Start upload</span>
        </button>
         </div>
    <!-- The global progress information -->
    <div class="span5 fileupload-progress fade in">
        <!-- The global progress bar -->
        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0"
             aria-valuemax="100">
            <div class="bar" style="width:0%;"></div>
        </div>
        <!-- The extended global progress information -->
        <div class="progress-extended">&nbsp;</div>
    </div>
</div>
<!-- The loading indicator is shown during file processing -->
<div class="fileupload-loading"></div>
<br/><br/><br/>


<!-- The table listing the files available for upload/download -->
<table role="presentation" class="table table-striped" style="float:left;">
    <th style="line-height: 30px;"><?php echo JText::_('COM_JOOMPORTFOLIO_PDF_FILE'); ?></th>
    <th><table><th style="width: 350px; border-top: none; text-align: center;"><?php echo JText::_('COM_JOOMPORTFOLIO_ITEM_IMG_TITLE');?></th>
    <th style="border-top: none;"><?php echo JText::_('COM_JOOMPORTFOLIO_ITEM_IMG_ORDERING'); ?></th></table></th>
    <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">

    <?php
    foreach ($images as $image) {
        ?>
        <tr class="template-download-2 fade in" style="display: table-row;">

            <td class="preview">
                <?php  echo $image->title; ?>
                <input type="hidden" name="jform[pdf][img_path][]" value="<?php echo $image->full ?>">
            </td>
            <td>
                <table>
                    <tr>
                        <td class="title" style="width: 350px;"><input type="text" name="jform[pdf][img_title][]"
                                                                       value="<?php echo $image->title ?>"
                                                                       style="width: 90%; min-height: 27px; text-align: center;"
                                                                       placeholder="<?php echo JText::_('COM_JOOMPORTFOLIO_PDF_TITLE'); ?>"/>
                        </td>
                        <td class="ordering" style="width: 10px;"><input type="text" name="jform[pdf][img_ordering][]"
                                                                         value="<?php echo $image->ordering ?>"
                                                                         style="width: 90%; min-height: 27px; text-align: center;"
                                                                         placeholder="0"/></td>


                        <td class="delete-pdf">
                            <span
                                class="btn btn-danger ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary"
                                data-type="POST"
                                data-url="index.php?option=com_joomportfolio&amp;task=uploader.deletePdf&amp;id=<?php echo $image->item_id; ?>&amp;image=<?php echo $image->full ?>"
                                role="button" aria-disabled="false">
                                <span class="ui-button-icon-primary ui-icon ui-icon-trash"></span>
                                <span class="ui-button-text">
                                    <i class="icon-trash icon-white"></i>
                                    <?php echo JText::_('COM_JOOMPORTFOLIO_DELETE'); ?>
                                </span>
                            </span>
                            <input type="checkbox" id="checkbox-<?php echo $image->id; ?>" name="delete" value="1"
                                   style="display: none;"/><!--<label for="checkbox-<?php echo $image->id; ?>"
                                                                  style="margin: -23px 0px 0px 112px; position: absolute;" ></label>-->

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php
    }
    ?>


    </tbody>
</table>

<script id="template-upload-2" type="text/x-tmpl">

    {% for (var i=0, file; file=o.files[i]; i++) { %}

    <tr class="template-upload fade in">
        <td class="preview"><span class="fade in"></span></td>

        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
        <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
        <td>
            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0"
                 aria-valuemax="100" aria-valuenow="0">
                <div class="bar" style="width:0%;"></div>
            </div>
        </td>
        <td class="start">{% if (!o.options.autoUpload) { %}
            <button class="btn btn-primary">
                <i class="icon-upload icon-white"></i>
                <span>Start</span>
            </button>
            {% } %}
        </td>
        {% } else { %}
        <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>Cancel</span>
            </button>
            {% } %}
        </td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download-2" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    {% if(file.message==undefined){  %}
    <tr class="template-download-2 fade in">
        {% if (file.error) { %}
        <td></td>
        <td class="name"><span>{%=file.name%}</span></td>

        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else { %}
        <td class="preview">{%=file.title%}{% if (file.thumbnail_url) { %}
            {%=file.name%}
            <input type="hidden" name="jform[pdf][img_path][]" value="{%=file.name%}"/>
            {% } %}
        </td>

        <td>
            <table>
                <tr>
                    <td class="title" style="width: 350px;"><input type="text" name="jform[pdf][img_title][]"  style="width: 90%; min-height: 27px; text-align: center;"  placeholder="<?php echo JText::_('COM_JOOMPORTFOLIO_PDF_TITLE'); ?>" /> </td>
                    <td class="ordering" style="width: 10px;"><input type="text" name="jform[pdf][img_ordering][]" style="width: 90%; min-height: 27px; text-align: center;" placeholder="0"/></td>

                    {% } %}
                    <td class="delete-pdf  " onclick="rowDelete(this)"
                        style=" background-color:#F9F9F9; border:none; min-width:144px;">
                        <span
                            class="btn btn-danger ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button" aria-disabled="false"
                            data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"
                         {% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                        <span class="ui-button-icon-primary ui-icon ui-icon-trash"></span>
                                <span class="ui-button-text">
                                    <i class="icon-trash icon-white"></i>
                        Delete
                        </span>
                        </span>
                        <input type="checkbox" name="delete" id="checkboxa{%=file.image%}" value="1"
                               style="display:none;">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    {% }else{ alert(file.message); } %}
    {% } %}


</script>

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?php echo JURI::base() . 'components/com_joomportfolio/assets/js/blueimp/tmpl.min.js'; ?>"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?php echo JURI::base() . 'components/com_joomportfolio/assets/js/blueimp/load-image.min.js'; ?>"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script   src="<?php echo JURI::base() . 'components/com_joomportfolio/assets/js/blueimp/canvas-to-blob.min.js'; ?>"></script>

<script src="components/com_joomportfolio/assets/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="components/com_joomportfolio/assets/js/jquery.fileupload.js"></script>
<!-- The File Upload file processing plugin -->
<script src="components/com_joomportfolio/assets/js/jquery.fileupload-fp.js"></script>
<!-- The File Upload user interface plugin -->
<script src="components/com_joomportfolio/assets/js/jquery.fileupload-ui.js"></script>
<!-- The File Upload jQuery UI plugin -->
<script src="components/com_joomportfolio/assets/js/jquery.fileupload-jui.js"></script>


<script type="text/javascript">
    jQuery(document).ready(function () {

        jQuery('#pdf-form').fileupload({
            url: 'index.php?option=com_joomportfolio&task=uploader.addPdf&item_id=<?php echo $input->get('id') ?>',
            formData: {task: 'uploader.addPdf'},
            uploadTemplateId: 'template-upload-2',
            downloadTemplateId: 'template-download-2',
            previewSourceFileTypes: '/^image\/(pdf)$/'
        });

        jQuery(".delete-pdf").on('click', '.btn-danger', function (e) {
            var url = jQuery(e.target).parent('span.btn-danger').attr('data-url'),
                table_row = jQuery(e.target).parent('span.btn-danger'),
                row = jQuery(table_row.parent().parent().parent().parent().parent().parent());

            jQuery.ajax({
                url: url,
                type: "POST",
                success: function (obj) {
                    row.remove();
                }
            });

        });


    });

    function rowDelete(that) {
        var url = jQuery(that).find('span.btn-danger').attr('data-url'),
            table_row = jQuery(that).parent().parent().parent().parent().parent();
        jQuery.ajax({
            url: url,
            type: "POST",
            success: function (obj) {
                table_row.remove();


            }
        });
    }
</script>