<?php

/**
* JoomPortfolio component for Joomla 2.5
* @package JoomPortfolio
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

$input =JFactory::getApplication()->input;

$item_id = $input->get('id', 0);

JHTML::_('behavior.framework');

?>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/base/jquery-ui.css" id="theme">
<!-- jQuery Image Gallery styles -->
<link rel="stylesheet" href="http://blueimp.github.com/jQuery-Image-Gallery/css/jquery.image-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the jQuery UI progress bars -->
<link rel="stylesheet" href="components/com_joomportfolio/assets/css/jquery.fileupload-ui.css">
    <!-- Redirect browsers with JavaScript disabled to the origin page -->
    <noscript><input type="hidden" name="redirect" value="/server/index.php"></noscript>
    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="row fileupload-buttonbar" >
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
           <!-- <button type="reset" class="btn btn-warning cancel">
                <i class="icon-ban-circle icon-white"></i>
                <span>Cancel upload</span>
            </button>
           <button type="button" class="btn btn-danger delete" >
                <i class="icon-trash icon-white"></i>
                <span>Delete</span>
            </button>
            <input type="checkbox" id="checkbox" class="toggle" style="display: none;"><label for="checkbox" style="min-width: 90px; float:right;"><?php echo JText::_('COM_JOOMPORTFOLIO_CHECK_ALL') ?></label>--
        </div>
        <!-- The global progress information -->
        <div class="span5 fileupload-progress fade in">
            <!-- The global progress bar -->
            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="bar" style="width:0%;"></div>
            </div>
            <!-- The extended global progress information -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>
    <!-- The loading indicator is shown during file processing -->
    <div class="fileupload-loading"></div>
    <br/><br/><br/>
    
    <script>
		function unValueAll()
		{
			var radios = document.querySelectorAll('[id^="is_default_"]');
			for (var key in radios) {
				var val = radios[key];
				val.value = 'val_0';
			} 
		}

		function defaultImage(image_id, item_id)
		{ 
			var url = '<?php echo JURI::base(); ?>index.php?option=com_joomportfolio&task=item.defaultSwitch';
			var data = 'image_id='+image_id+'&item_id='+item_id;
			var request = new Request({
				url: url,
				method: 'post',
				data: data,
				onSuccess: function(){
					var pics = document.querySelectorAll('[class="defaults"]');
					for (var key in pics) {
						var val = pics[key];
						val.src ='<?php echo JURI::root().'administrator/components/com_joomportfolio/assets/images/disabled.png'; ?>';
					}
					document.getElementById('img_'+image_id).setAttribute('src', '<?php echo JURI::root().'administrator/components/com_joomportfolio/assets/images/featured.png'; ?>');
				}
			}).send();
		}
    </script>
    
    <!-- The table listing the files available for upload/download -->
    <table role="presentation" class="table table-striped" style="float:left;"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
       <th style="text-align: center; line-height: 30px;"><?php echo JText::_('COM_JOOMPORTFOLIO_ITEM_IMG_PREVIEW'); ?></th>
       <th><table><th style="width: 350px; border-top: none; text-align: center;"><?php echo JText::_('COM_JOOMPORTFOLIO_ITEM_IMG_TITLE');?></th>
       <th style="width: 350px; border-top: none; text-align: center;"><?php echo JText::_('COM_JOOMPORTFOLIO_ITEM_IMG_COPYRIGHT'); ?></th>
       <th style="width: 350px; border-top: none; text-align: center;"><?php echo JText::_('COM_JOOMPORTFOLIO_ITEM_IMG_DESCRIPTION');?></th>
       <th style="border-top: none;"><?php echo JText::_('COM_JOOMPORTFOLIO_ITEM_IMG_ORDERING'); ?></th></table></th>
    <?php
    foreach($images as $image) { ?>
    <tr class="template-download-1 fade in" style="display: table-row;">

            <td class="preview">
                <a href="<?php echo JURI::root().'images/joomportfolio/'.$item_id.'/original/'.$image->full ?>" title="<?php echo $image->title ?>" rel="gallery" download="<?php echo $image->full ?>">
                	<img src="index.php?option=com_joomportfolio&amp;task=uploader.imageThumb&amp;id=<?php echo $item_id ?>&amp;image=<?php echo $image->thumb ?>"/>
                        
                </a>
                <input type="hidden" name="jform[images][img_path][]" value="<?php echo $image->full ?>">
            </td>
            <td>
                <table>
                    <tr>
                        <td class="title" style="width: 350px;"><input type="text" name="jform[images][img_title][]" value="<?php echo $image->title ?>" style="width: 90%; min-height: 27px; text-align: center;" placeholder="<?php echo JText::_('COM_JOOMPORTFOLIO_IMAGE_TITLE'); ?>"/></td>
                        <td class="copyright" style="width: 350px;"><input type="text" name="jform[images][img_copyright][]" value="<?php echo $image->copyright ?>" style="width: 90%; min-height: 27px; text-align: center;" placeholder="<?php echo JText::_('COM_JOOMPORTFOLIO_IMAGE_COPYRIGHT'); ?>"/></td>
                        <td class="description" style="width: 350px;"><input type="text" name="jform[images][img_description][]"  value="<?php echo $image->description ?>" style="width: 90%; min-height: 27px; text-align: center;" placeholder="<?php echo JText::_('COM_JOOMPORTFOLIO_IMAGE_DESCRIPTION'); ?>"/></td>
                        <td class="ordering" style="width: 10px;"><input type="text" name="jform[images][img_ordering][]"  value="<?php echo $image->ordering ?>" style="width: 90%; min-height: 27px; text-align: center;" placeholder="0"/></td>
                        <td class="isdefault" colspan="2">
	                        <a href="javascript:void(0);" onclick="defaultImage(<?php echo $image->id.','.$image->item_id; ?>);">
	                        	<img class="defaults" id="img_<?php echo $image->id; ?>" alt="Make default" src="<?php echo JURI::root(); ?>administrator/components/com_joomportfolio/assets/images/<?php echo $image->is_default?'featured':'disabled' ?>.png" title="<?php echo JText::_('COM_JOOMPORTFOLIO_DEFAULT_TIP'); ?>"/>
	                        </a>
                        </td>

                        <td class="delete" style="/*position: absolute; right: 90px;*/ background-color:#F9F9F9; border:none; min-width:144px;">
                            <button class="btn btn-danger ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" data-type="POST" data-url="index.php?option=com_joomportfolio&amp;task=uploader.deleteImage&amp;id=<?php echo $image->item_id; ?>&amp;image=<?php echo $image->full ?>" role="button" aria-disabled="false">
                                <span class="ui-button-icon-primary ui-icon ui-icon-trash"></span>
                                <span class="ui-button-text">
                                    <i class="icon-trash icon-white"></i>
                                    <span><?php echo JText::_('COM_JOOMPORTFOLIO_DELETE'); ?></span>
                                </span>
                            </button>
                            <input type="checkbox" id="checkbox-<?php echo $image->id; ?>" name="delete" value="1" style="display: none;"/><!--<label for="checkbox-<?php echo $image->id; ?>" style="margin: -23px 0px 0px 112px; position: absolute;" ></label>-->
                            <!--  <button class="btn btn-danger ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" data-type="POST" data-url="index.php?option=com_joomportfolio&amp;task=uploader.defaultImage&amp;id=<?php echo $image->item_id; ?>&amp;image=<?php echo $image->full ?>" role="button" aria-disabled="false">
                                <span class="ui-button-icon-primary ui-icon ui-icon-plusthick"></span>
                                    <span class="ui-button-text">
                                        <i class="icon-trash icon-white"></i>
                                        <span><?php echo JText::_('COM_JOOMPORTFOLIO_DEFAULT'); ?></span>
                                    </span>
                            </button> -->
                        </td>
                    </tr>
                </table>
            </td>
    </tr>
    <?php
    }
     ?>


    </tbody></table>


<script id="template-upload-1" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}

    <tr class="template-upload fade in">
        <td class="preview"><span class="fade in"></span></td>

        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
        <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
        <td>
            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
        </td>
        <td class="start">{% if (!o.options.autoUpload) { %}
            <button class="btn btn-primary">
                <i class="icon-upload icon-white"></i>
                <span>Start</span>
            </button>
            {% } %}</td>
        {% } else { %}
        <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>Cancel</span>
            </button>
            {% } %}</td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download-1" type="text/x-tmpl">

    {%  for (var i=0, file; file=o.files[i]; i++) { %}
    {% if(file.message==undefined){  %}
    <tr class="template-download-1 fade in">
        {% if (file.error) { %}
        <td></td>
        <td class="name"><span>{%=file.name%}</span></td>

        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else { %}
        <td class="preview">{% if (file.thumbnail_url) { %}
            <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            <input type="hidden" name="jform[images][img_path][]" value="{%=file.name%}" />
            {% } %}</td>
<!--        <td class="name">-->
<!--            <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>-->
<!--        </td>-->
        <td>
            <table>
                <tr>
                    <td class="title" style="width: 350px;"><input type="text" name="jform[images][img_title][]" style="width: 90%; min-height: 27px; text-align: center;" placeholder="<?php echo JText::_('COM_JOOMPORTFOLIO_IMAGE_TITLE'); ?>" /></td>
                    <td class="copyright" style="width: 350px;"><input type="text" name="jform[images][img_copyright][]" style="width: 90%; min-height: 27px; text-align: center;" placeholder="<?php echo JText::_('COM_JOOMPORTFOLIO_IMAGE_COPYRIGHT'); ?>" /></td>
                    <td class="description" style="width: 350px;"><input type="text" name="jform[images][img_description][]" style="width: 90%; min-height: 27px; text-align: center;" placeholder="<?php echo JText::_('COM_JOOMPORTFOLIO_IMAGE_DESCRIPTION'); ?>" /></td>
                    <td class="ordering" style="width: 10px;"><input type="text" name="jform[images][img_ordering][]" style="width: 90%; min-height: 27px; text-align: center;" placeholder="0" /></td>
            <!--        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td> -->
					<td class="isdefault" colspan="2">
	                	<a href="javascript:void(0);" onclick="defaultImage('{%=file.image%}',{%=file.item_id%});">
	                        <img class="defaults" id="img_{%=file.image%}" alt="Make default" src="<?php echo JURI::root(); ?>administrator/components/com_joomportfolio/assets/images/<?php echo !@$image->is_default?'disabled':'featured'; ?>.png" title="<?php echo JText::_('COM_JOOMPORTFOLIO_DEFAULT_TIP'); ?>"/>
	                    </a>
                    </td>
                    {% } %}
                    <td class="delete" style=" background-color:#F9F9F9; border:none; min-width:144px;">
                        <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                        <i class="icon-trash icon-white"></i>
                        <span>Delete</span>
                        </button>
                        <input type="checkbox" name="delete" id="checkboxa{%=file.image%}" value="1" style="display:none"><!--<label for="checkboxa{%=file.image%}" style="margin: -23px 0px 0px 112px; position: absolute;"><?php JText::_('COM_JOOMPORTFOLIO_CHECK_ALL') ?></label>-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    {% }else{ alert(file.message); } %}
    {% } %}



</script>
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?php echo JURI::base().'components/com_joomportfolio/assets/js/blueimp/tmpl.min.js'; ?>"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?php echo JURI::base().'components/com_joomportfolio/assets/js/blueimp/load-image.min.js'; ?>"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?php echo JURI::base().'components/com_joomportfolio/assets/js/blueimp/canvas-to-blob.min.js'; ?>"></script>
<!-- jQuery Image Gallery -->
<!--<script src="http://blueimp.github.com/jQuery-Image-Gallery/js/jquery.image-gallery.min.js"></script>-->
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
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
        jQuery('#img-form').fileupload({
            url: 'index.php?option=com_joomportfolio&task=uploader.addImage&item_id=<?php echo $input->get('id') ?>',
            formData: {task: 'uploader.addImage'},
            uploadTemplateId: 'template-upload-1',
            downloadTemplateId: 'template-download-1',
            previewSourceFileTypes: '/^image\/(gif|jpeg|png|mp3)$/'
        });


    });
</script>
    </div>