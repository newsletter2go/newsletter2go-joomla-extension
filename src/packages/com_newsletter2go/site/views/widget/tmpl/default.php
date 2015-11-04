<?php
defined('_JEXEC') or die;

echo '<div id="n2goWidgetPreview" class="moduletable">' . $this->widget . '</div>';
?>

<script>
    jQuery(document).ready(function () {
        jQuery('body').html(jQuery('#n2goWidgetPreview'));
        window.parent.n2goPreviewRendered();
    });
</script>

