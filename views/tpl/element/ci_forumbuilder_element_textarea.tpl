<div class="formbuilder-element formbuilder-element-default[{if isset($class)}] [{$class}][{/if}]">[{if isset($label)}]
    <label for="[{$form.id}]_[{$id}]">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{/if}]
    <textarea id="[{$form.id}]_[{$id}]" [{$field->getAttributes()}]>[{$value}]</textarea>
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>