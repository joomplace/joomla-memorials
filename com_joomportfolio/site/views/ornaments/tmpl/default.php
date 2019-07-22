<?php
/**
 * JoomPortfolio component for Joomla 3.x
 * @package JoomPortfolio
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// no direct access

defined('_JEXEC') or die('Unauthorized Access');
?>
<script type="text/javascript" src="<?php echo JUri::root().'media/jui/js/jquery.min.js';?>"></script>

<script type="text/javascript">

    jQuery(document).ready(function(){

        jQuery('.image-container').click(function(e){

            var input = window.parent.document.getElementById('candle-image-containter');

            var inputVal = window.parent.document.getElementById('condolence-image-id');

            //input.value = '<?php echo JText::_('APP_EMEM_IMAGES_CHOOSEN');?>';


           inputVal.value =jQuery(e.target).attr('data-img-id');
            jQuery('#ornament-containter').val("<?php echo JText::_('COM_JOOMPORTFOLIO__IMAGES_CHOOSEN');?>");
            window.parent.SqueezeBox.close()

        });

    });

</script>

<div id="condolence-candle-list">

    <div class="choose-condolence-header">

       <h2><?php echo JText::_('COM_JOOMPORTFOLIO_SELECT_ORNAMENT_IMG');?></h2>

    </div>

    <?php

    foreach ($this->images as $image) {

        ?>

        <div class="image-container" id="im<?php echo $image->id;?>">

            <img src="<?php echo JUri::root()."images/joomportfolio/condolences/$image->full";?>" data-img-id="<?php echo $image->id;?>">

        </div>



    <?php

    } ?>

</div>

