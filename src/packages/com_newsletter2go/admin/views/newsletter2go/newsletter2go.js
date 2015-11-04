function n2goPreviewRendered() {
    document.getElementById('preview-loading-mask').style.display = 'none';
}

window.onload = function () {
    var dragSrcEl = null,
            farb = jQuery.farbtastic('#colorPicker'),
            elements = document.getElementsByClassName('js-n2go-widget-field'),
            previewUrl = document.getElementById('n2goBaseUrl').value,
            requiredLabel = document.getElementById('hiddenRequiredLabel').value,
            i,
            renderHTML = function () {
                var widget = document.getElementById('widgetSourceCode'),
                        view = document.getElementById('widgetPreview');
                document.getElementById('preview-loading-mask').style.display = 'block';
                widget.style.display = 'none';
                view.src = previewUrl + encodeURIComponent(widget.value);
                view.style.display = 'block';

                document.getElementById('btnShowPreview').className = 'btn-primary btn-nl2go';
                document.getElementById('btnShowSource').className = '';
            };

    function buildWidgetForm(sourceCode) {
        if (!sourceCode) {
            var chbx = document.getElementsByName('attributes[]'),
                    fields = [], i, elem,
                    texts, styles, inputStyle;

            for (i = 0; i < chbx.length; i++) {
                if (chbx[i].checked === true) {
                    elem = [];
                    elem['sort'] = document.getElementsByName(chbx[i].value + 'Sort')[0].value;
                    elem['required'] = document.getElementsByName(chbx[i].value + 'Required')[0].value ? ' required' : '';
                    elem['name'] = chbx[i].title;
                    elem['id'] = chbx[i].value;

                    fields.push(elem);
                }
            }

            texts = [];
            texts['button'] = document.getElementsByName('buttonText')[0].value;

            styles = [];
            styles['textColor'] = document.getElementsByName('textColor')[0].value;
            styles['borderColor'] = document.getElementsByName('borderColor')[0].value;
            styles['backgroundColor'] = document.getElementsByName('backgroundColor')[0].value;
            styles['btnTextColor'] = document.getElementsByName('btnTextColor')[0].value;
            styles['btnBackgroundColor'] = document.getElementsByName('btnBackgroundColor')[0].value;
            styles['formBackgroundColor'] = document.getElementsByName('formBackgroundColor')[0].value;

            fields.sort(function (a, b) {
                return a['sort'] - b['sort'];
            });

            inputStyle = 'style="';
            inputStyle += styles['formBackgroundColor'] ? 'background-color:' + styles['formBackgroundColor'] + '; ' : '';
            inputStyle += styles['textColor'] ? 'color:' + styles['textColor'] + '; ' : '';
            inputStyle += '" ';

            sourceCode = '<div id="n2goResponseArea" ' + inputStyle + '>';
            sourceCode += '\n  <form method="post" id="n2goForm">';

            if (styles['borderColor'] || styles['backgroundColor'] || styles['textColor']) {
                inputStyle = 'style="';
                inputStyle += styles['borderColor'] ? 'border-color:' + styles['borderColor'] + '; ' : '';
                inputStyle += styles['backgroundColor'] ? 'background-color:' + styles['backgroundColor'] + '; ' : '';
                inputStyle += styles['textColor'] ? 'color:' + styles['textColor'] + '; ' : '';
                inputStyle += '" ';
            }

            for (i = 0; i < fields.length; i++) {
                if (fields[i]['name'] === 'Gender') {
                    sourceCode += '\n    ' + fields[i]['name'] + '<br />\n    ' + '<select ' + inputStyle + 'name="' + fields[i]['id'] + '" ' + fields[i]['required'] + '>';
                    sourceCode += '\n      <option value=" "></option>';
                    sourceCode += '\n      <option value="m">Male</option>';
                    sourceCode += '\n      <option value="f">Female</option>';
                    sourceCode += '\n    </select><br>';
                } else {
                    sourceCode += '\n    ' + fields[i]['name'] + '<br />\n    ' + '<input ' + inputStyle + 'type="text" name="' + fields[i]['id'] + '"' +  fields[i]['required'] + ' /><br />';
                }
            }

            sourceCode += '\n    <br />\n    <div class="message"></div>';
            sourceCode += '\n    <input name="action" type="hidden" value="n2go_subscribe" />';
            sourceCode += '\n    <button type="button" ';
            if (styles['btnTextColor'] || styles['btnBackgroundColor']) {
                sourceCode += 'style="background-image: none; ';
                sourceCode += styles['btnTextColor'] ? 'color:' + styles['btnTextColor'] + ';' : '';
                sourceCode += styles['btnBackgroundColor'] ? 'background-color:' + styles['btnBackgroundColor'] + ';' : '';
                sourceCode += '"';
            }

            sourceCode += ' id="n2goButton" onClick="n2goAjaxFormSubmit();" >' + texts['button'] + '</button>\n  </form>\n</div>';
            document.getElementById('widgetSourceCode').innerHTML = sourceCode;
            document.getElementById('widgetSourceCode').value = sourceCode;
        }

        renderHTML();
    }

    function extractValues(elem) {
        return {
            id: elem.children[0].id,
            class: elem.className,
            title: elem.children[0].title,
            value: elem.children[0].value,
            checked: elem.children[0].checked,
            disabled: elem.children[0].disabled,
            displayLabel: elem.children[2].innerHTML,
            required: elem.children[3].value,
            label: elem.children[5].innerHTML
        };
    }

    function importValues(elem, values) {
        elem.className = values.class;
        elem.children[0].id = values.id;
        elem.children[0].title = values.title;
        elem.children[0].value = values.value;
        elem.children[0].checked = values.checked;
        elem.children[0].disabled = values.disabled;
        elem.children[1].name = values.value + 'Sort';
        elem.children[2].innerHTML = values.displayLabel;
        elem.children[3].value = values.required;
        elem.children[3].name = values.value + 'Required';
        elem.children[4].name = 'fieldTitles[' + values.value + ']';
        elem.children[4].value = values.title;
        elem.children[5].htmlFor = values.id;
        elem.children[5].innerHTML = values.label;

    }

    function handleDragStart(e) {
        dragSrcEl = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('Text', JSON.stringify(extractValues(this)));
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';

        return false;
    }

    function handleDragEnter(e) {
        e.preventDefault();
        this.classList.add('over');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        this.classList.remove('over');
    }

    function handleDrop(e) {
        e.stopPropagation();
        e.preventDefault();

        if (dragSrcEl != this) {
            var a = JSON.parse(e.dataTransfer.getData('Text'));
            var b = extractValues(this);
            importValues(dragSrcEl, b);
            importValues(this, a);
        }

        return false;
    }

    function handleDragEnd() {
        [].forEach.call(document.querySelectorAll('#widgetFields .widgetField'), function (field) {
            field.classList.remove('over');
        });

        buildWidgetForm();
    }

    function transformToEditBox(e) {
        var me = this,
            textField = document.createElement('input'),
            oldText = me.innerHTML.replace(' (required)', '').trim();

        textField.value = oldText;
        textField.type = 'text';
        textField.addEventListener('blur', function(){
            var val = this.value;

            this.parentElement.draggable = true;
            val = val ? val : oldText;
            if (oldText === val) {
                this.parentNode.replaceChild(me, this);
                return true;
            }

            this.parentElement.children[0].title = val;
            this.parentElement.children[4].value = val;
            me.innerHTML = me.innerHTML.replace(oldText, val);

            this.parentNode.replaceChild(me, this);
            buildWidgetForm();
        }, false);

        me.parentNode.replaceChild(textField, me);
        textField.parentElement.draggable = false;
        textField.focus();
    }

    [].forEach.call(document.querySelectorAll('#widgetFields .widgetField'), function (field) {
        field.addEventListener('dragstart', handleDragStart, false);
        field.addEventListener('dragenter', handleDragEnter, false);
        field.addEventListener('dragover', handleDragOver, false);
        field.addEventListener('dragleave', handleDragLeave, false);
        field.addEventListener('drop', handleDrop, false);
        field.addEventListener('dragend', handleDragEnd, false);
        field.children[2].addEventListener('click', transformToEditBox, false);
    });

    buildWidgetForm(true);
    
    document.getElementById('btnShowSource').onclick = function () {
        var view = document.getElementById('widgetSourceCode');
        view.style.display = 'block';
        document.getElementById('widgetPreview').style.display = 'none';
        this.className = 'btn-primary btn-nl2go';
        document.getElementById('btnShowPreview').className = '';
    };

    document.getElementById('btnShowPreview').onclick = function () {
        renderHTML();
    };

    function hookClickHandler(checkbox) {
        checkbox.onclick = function (e) {
            var parentChildren = this.parentElement.children;

            if (!this.checked) {
                if (parentChildren[3].value) {
                    e.preventDefault();
                    this.checked = true;
                    this.parentNode.className = 'widgetField';
                    parentChildren[3].value = '';
                    parentChildren[2].innerHTML = parentChildren[2].innerHTML.replace(requiredLabel, '');
                    buildWidgetForm();
                    return false;
                }
            } else {
                this.parentNode.className = 'widgetField n2go-required';
                parentChildren[3].value = 'true';
                parentChildren[2].innerHTML += requiredLabel;
            }
        };
    }

    for (i = 0; i < elements.length; i++) {
        if (elements[i].type === 'checkbox') {
            hookClickHandler(elements[i]);
        }
        elements[i].onchange = function () {
            buildWidgetForm();
        };
    }

    jQuery('.color-picker').focus(function () {
        var input = this;

        // reset to start position before linking to current input
        farb.linkTo(function () {
        }).setColor('#000');
        farb.linkTo(function (color) {
            input.style.backgroundColor = color;
            input.style.color = farb.RGBToHSL(farb.unpack(color))[2] > 0.5 ? '#000' : '#fff';
            input.value = color;
        }).setColor(input.value);
    }).blur(function () {
        farb.linkTo(function () {
        }).setColor('#000');
        if (!this.value) {
            this.style.backgroundColor = '';
            this.style.color = '';
        }
        buildWidgetForm();
    });
};
