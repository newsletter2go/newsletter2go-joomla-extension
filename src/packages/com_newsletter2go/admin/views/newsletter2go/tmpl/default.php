<?php
defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php?option=com_newsletter2go'); ?>" method="post" name="adminForm" id="adminForm">
<input type="hidden" id="hiddenRequiredLabel" value="<?php echo JText::_('COM_NEWSLETTER2GO_REQUIRED'); ?>" />
<div class="n2go-section">
    <div class="n2go-block50 main-block">
        <div class="panel">
            <div class="panel-heading text-center">
                <h3><?php echo JText::_('COM_NEWSLETTER2GO_JOOMLA_PLUGIN'); ?></h3>
            </div>
            <div class="panel-body">
                <div class="n2go-row">
                    <div class="n2go-login"><span><?php echo JText::_('COM_NEWSLETTER2GO_CONNECT'); ?></span></div>
                    <div class="n2go-block">
                        <?php if ($this->forms === false){ ?>
                        <div class="n2go-btn">
                            <input type="hidden" name="apiKey" placeholder="<?php echo JText::_('COM_NEWSLETTER2GO_APIKEY_PLACEHOLDER');?>" value="<?php echo $this->apiKey; ?>" style="width:300px" readonly>
                            <a href="<?php echo $this->apiKeyConnectUrl; ?>" target="_blank" style="padding:5px"><span class="fa fa-plug"></span> <span>Login or Create Account</span></a>
                        </div>
                        <?php } else { ?>
                        <span class="n2go-label-success"> <span class="fa fa-check margin-right-5"></span>
							<span>Successfully connected</span></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="n2go-row">
                    <div class="n2go-block50">
                        <span><?php echo JText::_('COM_NEWSLETTER2GO_CHOOSE_THE_CONNECTED_FORM'); ?></span>
                    </div>
                    <div class="btn-group">
                        <select id="formUniqueCode" class="n2go-select" name="formUniqueCode">
                            <?php if (!empty($this->forms)){ ?>
                                <?php foreach ($this->forms as $form) { ?>
                                    <option value="<?php echo $form['hash']; ?>" <?php if ($form['hash'] == $this->formUniqueCode) { echo "selected"; }?>><?php echo $form['name']; ?></option>
                                <?php } ?>
                            <?php } else { ?>
                                <option value=""></option>
                            <?php } ?>
                         </select>
                    </div>
                    </div>
                </div>
                <div class="n2go-row">
                    <div class="n2go-block50"><span><?php echo JText::_('COM_NEWSLETTER2GO_CONFIGURE_YOUR_WIDGET'); ?></span></div>
                    <div class="n2go-block25">
                        <label for="formBackgroundColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_FORM_BACKGROUND'); ?></label>
                        <div class="n2go-cp input-group">
                            <span class="n2go-input-group-addon">#</span><input name="form.background-color" type="text" placeholder="" value="FFFFFF" class="n2go-colorField form-control n2go-text-right">
                            <div class="input-group-btn">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                        <label for="labelColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_LABEL'); ?></label>
                        <div class="n2go-cp input-group">
                            <span class="n2go-input-group-addon">#</span><input name="label.color" type="text" placeholder="" value="222222" class="n2go-colorField form-control n2go-text-right">
                            <div class="input-group-btn">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                        <label for="textColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_TEXT'); ?></label>
                        <div class="n2go-cp input-group">
                            <span class="n2go-input-group-addon">#</span><input name="input.color" type="text" placeholder="" value="222222" class="n2go-colorField form-control n2go-text-right">
                            <div class="input-group-btn">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                        <label for="borderColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_BORDER'); ?></label>
                        <div class="n2go-cp input-group">
                            <span class="n2go-input-group-addon">#</span><input name="input.border-color" type="text" placeholder="" value="CCCCCC" class="n2go-colorField form-control n2go-text-right">
                            <div class="input-group-btn">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                        <label for="backgroundColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_IBACKGROUND'); ?></label>
                        <div class="n2go-cp input-group">
                            <span class="n2go-input-group-addon">#</span><input name="input.background-color" type="text" placeholder="" value="FFFFFF" class="n2go-colorField form-control n2go-text-right">
                            <div class="input-group-btn">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                        <label for="btnTextColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_BTEXT'); ?></label>
                        <div class="n2go-cp input-group">
                            <span class="n2go-input-group-addon">#</span><input type="text" name="button.color" placeholder="" value="FFFFFF" class="n2go-colorField form-control n2go-text-right">
                            <div class="input-group-btn">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                        <label for="btnBackgroundColor"><?php echo JText::_('COM_NEWSLETTER2GO_COLORS_BBACKGROUND'); ?></label>
                        <div class="n2go-cp input-group">
                            <span class="n2go-input-group-addon">#</span><input type="text" name="button.background-color" placeholder="" value="00BAFF" class="n2go-colorField form-control n2go-text-right">
                            <div class="input-group-btn">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="n2go-block50 main-block">
        <div class="panel">
            <div class="panel-heading text-center">
                <h3><?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_PREVIEW_TITLE'); ?></h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#preview" data-toggle="tab" aria-expanded="true"><?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_PREVIEW'); ?></a></li>
                    <li class=""><a href="#source" data-toggle="tab" aria-expanded="false"><?php echo JText::_('COM_NEWSLETTER2GO_WIDGET_SOURCE'); ?></a></li>
                </ul>
                <!-- Tab panes-->
                <div class="tab-content">
                    <div id="preview" role="tabpanel" class="tab-pane active">
                        <?php if(!isset($this->_errors['formId']) === true){ ?>
                            <div id="widgetPreview"><script id="n2g_script"></script></div>
<!--                            <textarea id="widgetSourceCode" name="widgetSourceCode">--><?php //echo $this->widget; ?><!--</textarea>-->
                        <?php } else { ?>
                            <h3 class="n2go-error">
                            <?php foreach($this->_errors as $errorMessage){
                                    echo $errorMessage . '<br/>';
                               } ?></h3>
                        <?php } ?>
                    </div>
                    <div id="source" role="tabpanel" class="tab-pane">
                        <textarea id="widgetStyleConfig" name="widgetStyleConfig"><?php echo $this->configFormStyles; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</div>
</form>
<script src="components/com_newsletter2go/views/newsletter2go/bootstrap-colorpicker-master/bootstrap-colorpicker.min.js" type="text/javascript"></script>
<script src="components/com_newsletter2go/views/newsletter2go/newsletter2go_default.js" type="text/javascript"></script>
<script src="components/com_newsletter2go/views/newsletter2go/newsletter2go.js" type="text/javascript"></script>