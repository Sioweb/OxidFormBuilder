<div class="formbuilder-element formbuilder-element-radio[{if isset($outerclass)}] [{$outerclass}][{/if}]">
    <input type="hidden" name="[{$name}]" value="0">
    [{if isset($label)}]<label for="[{$form.id}]_[{$id}]_0">[{$label}]</label>[{else}]<span class="nolabel">&nbsp;</span>[{/if}]
    <div class="formbuilder-element-radio-container">
        [{foreach from=$value item=optionSet key=optionIndex}]
        <div class="formbuilder-element-radio-element">
            <input[{if isset($optionSet.active)}] checked[{/if}] value="[{$optionSet.key}]" id="[{$form.id}]_[{$id}]_[{$optionIndex}]" [{$field->getAttributes()}]>
            <label for="[{$form.id}]_[{$id}]_[{$optionIndex}]">[{if $required}]<sup>*</sup>[{/if}]
            [{ if is_array($optionSet.value) }]
            [{foreach from=$optionSet.value key=classname item=arrlabel}]
            <span class="formbuilder-arrlabel-[{$classname}]"[{if $classname|strpos:"help" !== false}]title="[{$arrlabel|replace:"\"":"'"}]"[{/if}]>[{$arrlabel}]</span>
            [{/foreach}]
            [{else}][{$optionSet.value}][{/if}]</label>
        </div>
        [{/foreach}]
    </div>
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>