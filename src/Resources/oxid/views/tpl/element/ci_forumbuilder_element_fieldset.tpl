<fieldset id="formbuilder-fieldset-[{$form.id}]_[{$id}]" class="formbuilder-fieldset[{if isset($class)}] [{$class}][{/if}]" [{$fieldset->getAttributes()}]>
    [{if isset($legend)}]<legend>[{$legend}]</legend>[{/if}]
    [{foreach from=$fields item=field}]
    [{$field->raw}]
    [{/foreach}]
</fieldset>