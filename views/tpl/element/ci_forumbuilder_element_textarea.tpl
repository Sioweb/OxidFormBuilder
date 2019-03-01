<div class="formbuilder-element formbuilder-element-default[{if isset($class)}] [{$class}][{/if}]">[{if isset($label)}]
    <label for="[{$id}]">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{/if}]
    <textarea[{if $required}] required[{/if}] name="[{$name}]" id="[{$id}]" [{$field->getAttributes()}]>[{$value}]</textarea>
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>