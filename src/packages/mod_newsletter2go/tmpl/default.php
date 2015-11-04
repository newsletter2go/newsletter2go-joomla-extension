<?php

defined('_JEXEC') or die;

echo $widget; 
?>
<script>
function n2goAjaxFormSubmit() {
    jQuery.ajax({
        url: 'index.php?option=com_newsletter2go&task=ajaxSubscribe&format=ajax',
        method: 'POST',
        data: jQuery("#n2goForm").serialize(),
        success: function (response) {
            var data = JSON.parse(response);
            if (data.success) {
                jQuery("#n2goResponseArea").html(data.message);
            } else {
                jQuery("#n2goResponseArea").find('.message').text(data.message);
            }
        }
    });
}
</script>