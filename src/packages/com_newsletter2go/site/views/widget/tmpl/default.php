<?php

/**
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

echo '<div id="n2goWidgetPreview" class="moduletable">' . $this->widget . '</div>';
?>

<script>
    jQuery(document).ready(function () {
        jQuery('body').html(jQuery('#n2goWidgetPreview'));
        window.parent.n2goPreviewRendered();
    });
</script>

