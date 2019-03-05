<div class="formbuilder-element formbuilder-element-optionswidget[{if isset($outerclass)}] [{$outerclass}][{/if}]">[{if isset($label)}]
    <label for="[{$form.id}]_[{$id}]_0_key">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{/if}]
    
    [{foreach from=$value item=option key=optionIndex}]
    <div class="formbuilder-element-optionswidget-input">
        <label for="[{$form.id}]_[{$id}]_[{$optionIndex}]_key">
            <span>Key/Name</span>
            <input type="text" id="[{$form.id}]_[{$id}]_[{$optionIndex}]_key" name="[{$name}][[{$optionIndex}]][key]" value="[{$option.key}]">
        </label>
        <label for="[{$form.id}]_[{$id}]_[{$optionIndex}]_value">
            <span>Wert</span>
            <input type="text" id="[{$form.id}]_[{$id}]_[{$optionIndex}]_value" name="[{$name}][[{$optionIndex}]][value]" value="[{$option.value}]">
        </label>
        <ul class="formbuilder-element-optionswidget-controlls">
            <li data-formbuilder-options-add>+</li>
            <li data-formbuilder-options-remove>-</li>
        </ul>
    </div>
    [{/foreach}]
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>