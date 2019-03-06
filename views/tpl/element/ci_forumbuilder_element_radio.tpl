<div class="formbuilder-element formbuilder-element-radio[{if isset($outerclass)}] [{$outerclass}][{/if}]">
    <input type="hidden" name="[{$name}]" value="0">
    [{if isset($label)}]<label for="[{$form.id}]_[{$id}]_0">[{$label}]</label>[{/if}]
    <div class="formbuilder-element-radio-container">
        [{foreach from=$value item=optionSet key=optionIndex}]
        <div class="formbuilder-element-radio-element">
            <input[{if isset($optionSet.active)}] checked[{/if}] value="[{$optionSet.key}]" id="[{$form.id}]_[{$id}]_[{$optionIndex}]" [{$field->getAttributes()}]>
            <label for="[{$form.id}]_[{$id}]_[{$optionIndex}]">[{if $required}]<sup>*</sup>[{/if}][{$optionSet.value}]</label>
        </div>
        [{/foreach}]
    </div>
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>