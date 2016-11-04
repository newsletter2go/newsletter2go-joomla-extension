window.addEventListener('load', function () {
    var formUniqueCode = document.getElementById('formUniqueCode').value.trim();
       
 

    var widgetStyleConfig = document.getElementById('widgetStyleConfig'),
        input,
        timer = 0,
        n2gSetUp = function  () {
            if (widgetStyleConfig.textContent === null || widgetStyleConfig.textContent.trim() === "") {
                widgetStyleConfig.textContent = JSON.stringify(n2gConfig, null, 2);
            } else {
                n2gConfig = JSON.parse(widgetStyleConfig.textContent);

            }

            [].forEach.call(document.getElementsByClassName('n2go-colorField'), function (element) {
                var field = element.name.split('.');
                var style = getStyle(field[1], n2gConfig[field[0]]['style']);

                if (style !== '') {
                    jQuery(element.parentElement).colorpicker({ color: style, format: 'hex' });
                } else  {
                    jQuery(element.parentElement).colorpicker({ color: element.value, format: 'hex' });
                }
            });

            n2g('create', formUniqueCode);

            if (jQuery('#widgetPreviewSubscribe').length > 0) {
                n2g('subscribe:createForm', n2gConfig, 'n2g_script_subscribe');
            }
            if (jQuery('#widgetPreviewUnsubscribe').length > 0) {
                n2g('unsubscribe:createForm', n2gConfig, 'n2g_script_unsubscribe');
            }

        };

    function getStyle (field, str) {
        var styleArray = str.split(';');

        for (var i=0; i < styleArray.length; i++){
            var styleField = styleArray[i].split(':');
            if (styleField[0].trim() == field) {
                return styleField[1].trim();
            }
        }
        return '';
    }

    function updateConfig (element) {
        widgetStyleConfig.textContent = '';
        var formPropertyArray = element.name.split('.'),
            property = formPropertyArray[0],
            attribute = 'style',
            cssProperty = formPropertyArray[1],
            cssValue = element.value;

        var styleProperties;
        if (n2gConfig[property][attribute] == '') {
            styleProperties = cssProperty + ':' + cssValue;
        } else {
            styleProperties = updateString(n2gConfig[property][attribute], cssProperty, cssValue);
        }

        n2gConfig[property][attribute] = styleProperties;
        widgetStyleConfig.textContent = JSON.stringify(n2gConfig, null, 2);
    }

    function updateForm () {
        clearTimeout(timer);
        if (jQuery('#widgetPreviewSubscribe').length > 0) {
            timer = setTimeout(function () {
                jQuery('#widgetPreviewSubscribe form').remove();
                n2g('subscribe:createForm', n2gConfig, 'n2g_script_subscribe');
            }, 100);
        }
        if (jQuery('#widgetPreviewUnsubscribe').length > 0) {
            timer = setTimeout(function () {
                jQuery('#widgetPreviewUnsubscribe form').remove();
                n2g('unsubscribe:createForm', n2gConfig, 'n2g_script_unsubscribe');
            }, 100);
        }

    }

    function updateString (string, cssProperty, cssValue) {
        if (string.slice(-1) === ';') {
            string = string.substring(0, string.length - 1);
        }
        var stylePropertiesArray = string.split(';'),
            found = false;

        for (var i = 0; i < stylePropertiesArray.length; i++) {
            var trimmedAttr = stylePropertiesArray[i].trim();
            var styleProperty = trimmedAttr.split(':');
            if (styleProperty[0] == cssProperty) {
                stylePropertiesArray[i] = cssProperty + ':' + cssValue;
                found = true;
                break;
            }
        }
        if (!found) {
            stylePropertiesArray[i] = cssProperty + ':' + cssValue;
        }
        return stylePropertiesArray.join(';') + ';';
    }

    if (formUniqueCode !== '') {
        n2gSetUp();

        jQuery('.n2go-colorField').val(function(index, value) {
            return value.replace('#', '');
        });

        jQuery('.n2go-cp').colorpicker().on('changeColor', function(ev){
            input = this.children[1];
            updateConfig(input);
            updateForm();
        });
		
    }

    document.getElementById('resetStyles').addEventListener("click", function (e){
        e.preventDefault();
        var defaultConfig = JSON.stringify(n2gConfigConst, null, 2);
        jQuery.ajax({
            type: "POST",
            url: "index.php?option=com_newsletter2go&task=newsletter2go.resetFormStyles",
            data: {style: defaultConfig},
            success: function (data) {
                location.reload();
            }

        });
    });
 

});