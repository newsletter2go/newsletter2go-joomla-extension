<?php
defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php?option=com_newsletter2go'); ?>" method="post" name="adminForm" id="adminForm">
    <input type="hidden" id="hiddenRequiredLabel" value="<?php echo JText::_('COM_NEWSLETTER2GO_REQUIRED'); ?>" />
    <div class="n2go-section">
        <h2><?php echo JText::_('COM_NEWSLETTER2GO_CONNECT'); ?></h2>
        <div class="n2go-container">
            <h3 class="n2go-header-connection" style="background-color: <?php echo $this->groups['success'] ? 'greenyellow!' : 'yellow'; ?>">
                <?php echo $this->groups['success'] ? JText::_('COM_NEWSLETTER2GO_CONNECTED') : JText::_('COM_NEWSLETTER2GO_NOTCONNECTED'); ?>
            </h3>
            <input type="text" name="apiKey" placeholder="<?php echo JText::_('COM_NEWSLETTER2GO_APIKEY_PLACEHOLDER');?>" value="<?php echo $this->apiKey; ?>" style="width:300px" />
            <button onclick="Joomla.submitbutton('newsletter2go.save')" class="btn-primary"><?php echo JText::_('COM_NEWSLETTER2GO_SAVE'); ?></button>
            <br />
            <a href="https://app.newsletter2go.com/en/settings/#/api" target="_blank"><?php echo JText::_('COM_NEWSLETTER2GO_FIND_API_KEY'); ?></a>
        </div>
        <hr />
    </div>
    <div class="n2go-section">
        <h2><?php echo JText::_('COM_NEWSLETTER2GO_FORM_CODE'); ?></h2>
        <div class="n2go-container">
            <input type="text" id="formUniqueCode" name="formUniqueCode" placeholder="<?php echo JText::_('COM_NEWSLETTER2GO_FORMID_PLACEHOLDER');?>" value="<?php echo $this->formUniqueCode; ?>" style="width:300px" />
            <button onclick="Joomla.submitbutton('newsletter2go.save')" class="btn-primary"><?php echo JText::_('COM_NEWSLETTER2GO_SAVE'); ?></button>
            <br />
            <a href="https://ui.newsletter2go.com" target="_blank"><?php echo JText::_('COM_NEWSLETTER2GO_FIND_FORM_CODE'); ?></a>
        </div>
        <hr />
    </div>

    <div class="n2go-section">
        <div class="n2go-container">
            <h3><?php echo JText::_('COM_NEWSLETTER2GO_CONFIGURE_COLORS'); ?></h3>
            <table style="float: left">
                <tr>
                    <th><label for="formBackgroundColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_FORM_BACKGROUND'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker nl2g-fields" type="text" name="form.background-color" id="formBackgroundColor" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="labelColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_LABEL'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker nl2g-fields" type="text" name="label.color" id="labelColor" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="textColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_TEXT'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker nl2g-fields" type="text" name="input.color" id="textColor" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="borderColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_BORDER'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker nl2g-fields" type="text" name="input.border-color" id="borderColor" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="backgroundColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_IBACKGROUND'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker nl2g-fields" type="text" name="input.background-color" id="backgroundColor" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="btnTextColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_BTEXT'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker nl2g-fields" type="text" name="button.color" id="btnTextColor" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="btnBackgroundColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_BBACKGROUND'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker nl2g-fields" type="text" name="button.background-color" id="btnBackgroundColor" size="7" /></td>
                </tr>
            </table>
            <div id="colorPicker"></div>
        </div>
        <div id="n2goWidget">
            <h3><?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_PREVIEW_TITLE'); ?></h3>
            <div id="n2gButtons">
            <input type="button" value="<?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_PREVIEW'); ?>" id="btnShowPreview" class="btn-primary btn-nl2go" />
            <input type="button" value="<?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_CONFIG'); ?>" id="btnShowConfig" />
            <input type="button" value="<?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_SOURCE'); ?>" id="btnShowSource" />
            </div>
            <div class="preview-pane" id="preview-form-panel">
                <?php
                if(!isset($this->_errors['formId']) === true){ ?>
                    <div id="widgetPreview"><script id="n2g_script"></script></div>
                    <textarea id="widgetSourceCode" name="widgetSourceCode"><?php echo $this->widget; ?></textarea>
                    <textarea id="widgetStyleConfig" name="widgetStyleConfig"><?php echo $this->configFormStyles; ?></textarea>
                <?php } else { ?>
                    <h3 class="n2go-error">
                        <?php
                        foreach($this->_errors as $errorMessage){
                            echo $errorMessage . '<br/>';
                        }
                        ?>
                    </h3>
                <?php } ?>
            </div>
            <p><?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_DESCRIPTION'); ?></p>
            <button onclick="Joomla.submitbutton('newsletter2go.save')" class="btn-primary"><?php echo JText::_('COM_NEWSLETTER2GO_SAVE_FORM'); ?></button>
        </div>
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<script src="components/com_newsletter2go/views/newsletter2go/farbtastic/farbtastic.js" type="text/javascript"></script>
<script src="components/com_newsletter2go/views/newsletter2go/newsletter2go_default.js" type="text/javascript"></script>
<script src="components/com_newsletter2go/views/newsletter2go/newsletter2go.js" type="text/javascript"></script>
