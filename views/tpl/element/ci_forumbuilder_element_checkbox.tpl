<div class="formbuilder-element formbuilder-element-checkox[{if isset($outerclass)}] [{$outerclass}][{/if}]">
    <input type="hidden" name="[{$name}]" value="0">
    <input[{if $value}] checked[{/if}] value="1" id="[{$form.id}]_[{$id}]" [{$field->getAttributes()}]>[{if isset($label)}]
    <label for="[{$form.id}]_[{$id}]">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{/if}]
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>