<?php

/**
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

?>
<script id="<?= $uniqueId ? $uniqueId : "n2g_script" ?>">
        window.addEventListener('load', function () {
            !function (e, t, n, c, r, a, i) {
                e.Newsletter2GoTrackingObject = r, e[r] = e[r] || function () {
                        (e[r].q = e[r].q || []).push(arguments)
                    }, e[r].l = 1 * new Date, a = t.createElement(n), i = t.getElementsByTagName(n)[0], a.async = 1, a.src = c, i.parentNode.insertBefore(a, i)
            }(window, document, "script", "//static.newsletter2go.com/utils.js", "n2g");
            n2g('create', '<?=$formUniqueCode?>');
            n2g('<?= $n2gParams ?>', <?= $widgetConfig ?> <?= $uniqueId ? ',"'.$uniqueId.'"' : "" ?>);
        });
</script>