<div class="formbuilder-element formbuilder-element-checkbox[{if isset($outerclass)}] [{$outerclass}][{/if}]">
    [{ if $value|is_array}]
    <input type="hidden" name="[{$nameRaw}]" value="0">
    [{if isset($label)}]<label for="[{$form.id}]_[{$id}]_0">[{$label}]</label>[{else}]<span class="nolabel">&nbsp;</span>[{/if}]
    <div class="formbuilder-element-checkbox-container">
        [{foreach from=$value item=optionSet key=optionIndex}]
        <div class="formbuilder-element-checkbox-element">
            <input[{if isset($optionSet.active)}] checked[{/if}] value="[{$optionSet.key}]" id="[{$form.id}]_[{$id}]_[{$optionIndex}]" [{$field->getAttributes()}]>
            <label for="[{$form.id}]_[{$id}]_[{$optionIndex}]">[{if $required}]<sup>*</sup>[{/if}][{$optionSet.value}]</label>
        </div>
        [{/foreach}]
    </div>
    [{else}]
    <input type="hidden" name="[{$name}]" value="0">
    <input[{if $value}] checked[{/if}] value="1" id="[{$form.id}]_[{$id}]" [{$field->getAttributes()}]>[{if isset($label)}]
    <label for="[{$form.id}]_[{$id}]">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{/if}]
    [{/if}]
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>