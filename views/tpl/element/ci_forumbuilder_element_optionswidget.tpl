<div class="formbuilder-element formbuilder-element-optionswidget[{if isset($outerclass)}] [{$outerclass}][{/if}]">[{if isset($label)}]
    <label for="[{$form.id}]_[{$id}]_key">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{/if}]
    <div class="formbuilder-element-optionswidget-input">
        <label for="[{$form.id}]_[{$id}]_key"> 
            <span>Key/Name</span>
            <input type="text" id="[{$form.id}]_[{$id}]_key" [{$name}][0][key] value="[{$value}]">
        </label>
        <label for="[{$form.id}]_[{$id}]_value"> 
            <span>Wert</span>
            <input type="text" id="[{$form.id}]_[{$id}]_value" [{$name}][0][value] value="[{$value}]">
        </label>
        <ul class="formbuilder-element-optionswidget-controlls">
            <li>+</li>
            <li>-</li>
        </ul>
    </div>

    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>