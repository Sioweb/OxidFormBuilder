<div class="formbuilder-element formbuilder-element-checkox[{if isset($class)}] [{$class}][{/if}]">
    <input type="hidden" name="[{$name}]" value="0">
    <input[{if $required}] required[{/if}][{if $value}] checked[{/if}] type="checkbox" name="[{$name}]" value="1" id="[{$id}]" [{$field->getAttributes()}]>[{if isset($label)}]
    <label for="[{$id}]">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{/if}]
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>