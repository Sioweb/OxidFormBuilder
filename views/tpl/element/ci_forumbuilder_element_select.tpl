<div class="formbuilder-element formbuilder-element-select[{if isset($outerclass)}] [{$outerclass}][{/if}]">[{if isset($label)}]
    <label for="[{$form.id}]_[{$id}]">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{/if}]
    <select id="[{$form.id}]_[{$id}]" [{$field->getAttributes()}]>
        [{foreach from=$options item=optionOutput key=optionValue}]
        <option value="[{if $optionValue != '_blank'}][{$optionValue}][{/if}]"[{if $optionValue == $value}] selected[{/if}]>[{$optionOutput}]</option>
        [{/foreach}]
    </select>
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>