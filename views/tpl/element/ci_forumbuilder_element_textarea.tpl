<div class="formbuilder-element formbuilder-element-default[{if isset($outerclass)}] [{$outerclass}][{/if}]">[{if isset($label)}]
    <label for="[{$form.id}]_[{$id}]">[{$label}][{if $required}]<sup>*</sup>[{/if}]</label>[{else}]<span class="nolabel">&nbsp;</span>[{/if}]
    <textarea id="[{if !isset($forceId) }][{$form.id}]_[{/if}][{$id}]" [{$field->getAttributes()}]>[{$value}]</textarea>
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>