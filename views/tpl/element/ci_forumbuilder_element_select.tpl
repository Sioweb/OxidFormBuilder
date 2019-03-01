<div class="formbuilder-element formbuilder-element-select[{if isset($class)}] [{$class}][{/if}]">[{if isset($label)}]
    <label for="[{$id}]">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{/if}]
    <select[{if $required}] required[{/if}] name="[{$name}]" id="[{$id}]" [{$field->getAttributes()}]>
        [{foreach from=$options item=optionOutput key=optionValue}]
        <option value="[{if $optionValue != '_blank'}][{$optionValue}][{/if}]"[{if $optionValue == $value}] selected[{/if}]>[{$optionOutput}]</option>
        [{/foreach}]
    </select>
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>