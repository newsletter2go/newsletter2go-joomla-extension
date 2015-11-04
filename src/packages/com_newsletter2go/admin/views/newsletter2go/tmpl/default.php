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
        <h2><?php echo JText::_('COM_NEWSLETTER2GO_DOI_CONNECT'); ?></h2>
        <div class="n2go-container">
            <h3 class="n2go-header-connection" style="background-color: <?php echo $this->doiSuccess ? 'greenyellow!' : 'yellow'; ?>">
                <?php echo $this->doiSuccess ? JText::_('COM_NEWSLETTER2GO_DOI_SUCCESS_FRONT') . " (Host: $this->doiHost)" : JText::_('COM_NEWSLETTER2GO_DOI_INCORRECT'); ?>
            </h3>
            <input type="text" name="doiCode" placeholder="<?php echo JText::_('COM_NEWSLETTER2GO_DOICODE_PLACEHOLDER');?>" value="<?php echo $this->doiCode; ?>" style="width:300px" />
            <button onclick="Joomla.submitbutton('newsletter2go.save')" class="btn-primary"><?php echo JText::_('COM_NEWSLETTER2GO_SAVE'); ?></button>
            <br />
            <a href="https://app.newsletter2go.com/en/settings/#/form" target="_blank"><?php echo JText::_('COM_NEWSLETTER2GO_FIND_DOI_CODE'); ?></a>
        </div>
        <hr />
    </div>
    <div class="n2go-section">
        <h2><?php echo JText::_('COM_NEWSLETTER2GO_CONFIGURE_SUBSCRIPTION'); ?></h2>
        <div class="n2go-container">
            <h3><?php echo JText::_('COM_NEWSLETTER2GO_CONFIGURE_FIELDS'); ?></h3>
            <div class="alert alert-info" style="margin-bottom: 15px;">
                <?php echo JText::_('COM_NEWSLETTER2GO_ATTENTION'); ?>
            </div>
            <ul id="widgetFields">
                <li>
                    <span class="n2go-table-header" style="text-align: right;"><?php echo JText::_('COM_NEWSLETTER2GO_FIELD_NAME'); ?></span>
                    <span class="n2go-table-header" style="padding-left: 25px;"><?php echo JText::_('COM_NEWSLETTER2GO_FIELD_TITLE'); ?></span>
                </li>
                <?php
                $i = 1;
                foreach ($this->attributes as $value) {
                    ?>
                    <li class="widgetField <?php echo $value['required'] ? ' n2go-required' : ''?>" draggable="true">
                        <input type="checkbox" <?php echo $value['disabled']; ?> id="<?php echo $value['id']; ?>" name="attributes[]" title="<?php echo $value['title']; ?>"
                               class="js-n2go-widget-field" value="<?php echo $value['id']; ?>" <?php echo $value['checked']; ?> >

                        <input type="hidden" value="<?php echo $i++; ?>" name="<?php echo $value['id']; ?>Sort" >
                        <div class="n2go-editable-label">
                            <?= $value['title']; ?><?php echo $value['required'] ? JText::_('COM_NEWSLETTER2GO_REQUIRED') : ''?>
                        </div>
                        <input type="hidden" value="<?php echo $value['required']; ?>" name="<?php echo $value['id']; ?>Required"/>
                        <input type="hidden" value="<?php echo $value['title']; ?>" name="fieldTitles[<?php echo $value['id']; ?>]"/>
                        <label for="<?php echo $value['id']; ?>"><?php echo $value['label']; ?></label>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <hr />
        <div class="n2go-container">
            <h3><?php echo JText::_('COM_NEWSLETTER2GO_CONFIGURE_TEXTS'); ?></h3>

            <label for="success"><?php echo JText::_('COM_NEWSLETTER2GO_TEXTS_SUCCESS'); ?></label>
            <input type="text" name="success" id="success" value="<?php echo isset($this->texts['success']) ? $this->texts['success'] : ''; ?>" size="75" />

            <label for="failureSubsc"><?php echo JText::_('COM_NEWSLETTER2GO_TEXTS_FSUBSCRIBED'); ?></label>
            <input type="text" id="failureSubsc" name="failureSubsc" value="<?php echo isset($this->texts['failureSubsc']) ? $this->texts['failureSubsc'] : ''; ?>" size="75" />

            <label for="failureEmail"><?php echo JText::_('COM_NEWSLETTER2GO_TEXTS_FEMAIL'); ?></label>
            <input type="text" id="failureEmail" name="failureEmail" value="<?php echo isset($this->texts['failureEmail']) ? $this->texts['failureEmail'] : ''; ?>" size="75" />

            <label for="failureRequired"><?php echo JText::_('COM_NEWSLETTER2GO_TEXTS_FFIELDS'); ?></label>
            <input type="text" id="failureRequired" name="failureRequired" value="<?php echo isset($this->texts['failureRequired']) ? $this->texts['failureRequired'] : ''; ?>" size="75" />

            <label for="failureError"><?php echo JText::_('COM_NEWSLETTER2GO_TEXTS_FERROR'); ?></label>
            <input type="text" id="failureError" name="failureError" value="<?php echo isset($this->texts['failureError']) ? $this->texts['failureError'] : ''; ?>" size="75" />

            <label for="buttonText"><?php echo JText::_('COM_NEWSLETTER2GO_TEXTS_BTNTEXT'); ?></label>
            <input class="js-n2go-widget-field" type="text" id="buttonText" name="buttonText" value="<?php echo isset($this->texts['buttonText']) ? $this->texts['buttonText'] : ''; ?>" size="75" />
            </label>
        </div>
        <hr />

        <div class="n2go-container">
            <h3><?php echo JText::_('COM_NEWSLETTER2GO_CONFIGURE_COLORS'); ?></h3>
            <table style="float: left">
                <tr>
                    <th><label for="formBackgroundColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_FORM_BACKGROUND'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker" type="text" name="formBackgroundColor"
                               id="formBackgroundColor" value="<?php echo isset($this->colors['formBackgroundColor']) ? $this->colors['formBackgroundColor'] : ''; ?>" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="textColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_TEXT'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker" type="text" name="textColor" id="textColor"
                               value="<?php echo isset($this->colors['textColor']) ? $this->colors['textColor'] : ''; ?>" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="borderColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_BORDER'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker" type="text" name="borderColor" id="borderColor"
                               value="<?php echo isset($this->colors['borderColor']) ? $this->colors['borderColor'] : ''; ?>" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="backgroundColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_IBACKGROUND'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker" type="text" name="backgroundColor"
                               id="backgroundColor" value="<?php echo isset($this->colors['backgroundColor']) ? $this->colors['backgroundColor'] : ''; ?>" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="btnTextColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_BTEXT'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker" type="text" name="btnTextColor" id="btnTextColor"
                               value="<?php echo isset($this->colors['btnTextColor']) ? $this->colors['btnTextColor'] : ''; ?>" size="7" /></td>
                </tr>
                <tr>
                    <th><label for="btnBackgroundColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_BBACKGROUND'); ?></label></th>
                    <td><input class="js-n2go-widget-field color-picker" type="text" name="btnBackgroundColor"
                               id="btnBackgroundColor" value="<?php echo isset($this->colors['btnBackgroundColor']) ? $this->colors['btnBackgroundColor'] : ''; ?>" size="7" /></td>
                </tr>
            </table>
            <div id="colorPicker"></div>
        </div>
        <div id="n2goWidget">
            <h3><?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_PREVIEW_TITLE'); ?></h3>
            <input type="button" value="<?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_PREVIEW'); ?>" class="btn-primary btn-nl2go" id="btnShowPreview" />
            <input type="button" value="<?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_SOURCECODE'); ?>" class="" id="btnShowSource" />
            <div class="preview-pane">
                <iframe id="widgetPreview" style="width: 100%" src="<?php echo $this->previewUrl . urlencode($this->widget) ?>"></iframe>
                <textarea id="widgetSourceCode" name="widgetSourceCode" style="display: none;"><?php echo $this->widget ?></textarea>
                <div id="preview-loading-mask">
                    <img src="components/com_newsletter2go/views/newsletter2go/ajax-loader.gif" alt="Loading..." />
                </div>
            </div>
            <p><?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_DESCRIPTION'); ?></p>
            <button onclick="Joomla.submitbutton('newsletter2go.save')" class="btn-primary"><?php echo JText::_('COM_NEWSLETTER2GO_SAVE_FORM'); ?></button>
        </div>
    </div>
    <input type="hidden" id="n2goBaseUrl" name="n2goBaseUrl" value="<?php echo $this->previewUrl; ?>" />
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<script src="components/com_newsletter2go/views/newsletter2go/farbtastic/farbtastic.js" type="text/javascript"></script>
<script src="components/com_newsletter2go/views/newsletter2go/newsletter2go.js" type="text/javascript"></script>
