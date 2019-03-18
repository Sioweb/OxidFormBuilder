<div class="formbuilder-element formbuilder-element-select[{if isset($outerclass)}] [{$outerclass}][{/if}]">[{if isset($label)}]
    <label for="[{$form.id}]_[{$id}]">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{else}]<span class="nolabel">&nbsp;</span>[{/if}]
    <select id="[{$form.id}]_[{$id}]" [{$field->getAttributes()}]>
        [{foreach from=$value item=optionSet key=optionIndex}]
        <option value="[{if $optionSet.key != '_blank'}][{$optionSet.key}][{/if}]"[{if isset($optionSet.active)}] selected[{/if}]>[{$optionSet.value}]</option>
        [{/foreach}]
    </select>
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>