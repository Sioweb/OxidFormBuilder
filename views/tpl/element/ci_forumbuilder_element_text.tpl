<div class="formbuilder-element formbuilder-element-default[{if isset($class)}] [{$class}][{/if}]">[{if isset($label)}]
    <label for="[{$id}]">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{/if}]
    <input[{if $required}] required[{/if}] type="text" name="[{$name}]" value="[{$value}]" id="[{$id}]" [{$field->getAttributes()}]>
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>