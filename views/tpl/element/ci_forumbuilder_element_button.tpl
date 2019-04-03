<div class="formbuilder-element formbuilder-element-button[{if isset($outerclass)}] [{$outerclass}][{/if}]">
    [{if $label}]<label>[{/if}]
    <input [{$field->getAttributes()}]>
    [{if $label}][{$label}]</label>[{/if}]
    [{if isset($help)}]<p class="formbuilder-element-help">[{$help}]</p>[{/if}]
</div>